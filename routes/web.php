<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HiradcController;
use App\Http\Controllers\LiveAuditController;
use App\Http\Controllers\ProgramKerjaController;
use App\Http\Controllers\TemuanController;

/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::controller(LoginController::class)->group(function () {

        Route::get('/login', 'showLoginForm')
            ->name('login');

        Route::post('/login', 'login')
            ->name('login.post');
    });
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [LogoutController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | HIRADC
    |--------------------------------------------------------------------------
    */

    Route::prefix('hiradc')->name('hiradc.')->group(function () {

        Route::resource('/', HiradcController::class)
            ->parameters(['' => 'hiradc'])
            ->except(['edit', 'update']);

        Route::post('{hiradc}/validate-v1', [HiradcController::class, 'validateV1'])
            ->name('validate-v1');

        Route::post('{hiradc}/validate-v2', [HiradcController::class, 'validateV2'])
            ->name('validate-v2');
    });

    /*
    |--------------------------------------------------------------------------
    | Program Kerja
    |--------------------------------------------------------------------------
    */

    Route::prefix('program-kerja')->name('program-kerja.')->group(function () {

        Route::resource('/', ProgramKerjaController::class)
            ->parameters(['' => 'programKerja']);

        Route::post('{programKerja}/upload-bukti', [ProgramKerjaController::class, 'uploadBukti'])
            ->name('upload-bukti');

        Route::post('{programKerja}/close', [ProgramKerjaController::class, 'close'])
            ->name('close');
    });

    /*
    |--------------------------------------------------------------------------
    | Live Audit
    |--------------------------------------------------------------------------
    */

    Route::prefix('live-audit')->name('live-audit.')->group(function () {

        Route::resource('/', LiveAuditController::class)
            ->parameters(['' => 'liveAudit'])
            ->except(['edit', 'update']);

        Route::post('{liveAudit}/validate-v1', [LiveAuditController::class, 'validateV1'])
            ->name('validate-v1');

        Route::post('{liveAudit}/validate-v2', [LiveAuditController::class, 'validateV2'])
            ->name('validate-v2');

        Route::get('{liveAudit}/export-pdf', [LiveAuditController::class, 'exportPdf'])
            ->name('export-pdf');
    });

    /*
    |--------------------------------------------------------------------------
    | Temuan UA/UC
    |--------------------------------------------------------------------------
    */

    Route::resource('temuan', TemuanController::class)
        ->except(['edit', 'update']);

    Route::post(
        'temuan/{temuan}/complete-draft',
        [TemuanController::class, 'completeDraft']
    )->name('temuan.complete-draft');

    Route::post(
        'temuan/{temuan}/validate-v1',
        [TemuanController::class, 'validateV1']
    )->name('temuan.validate-v1');

    Route::post(
        'temuan/{temuan}/validate-v2',
        [TemuanController::class, 'validateV2']
    )->name('temuan.validate-v2');

    Route::post(
        'temuan/{temuan}/upload-bukti',
        [TemuanController::class, 'uploadBukti']
    )->name('temuan.upload-bukti');

    Route::post(
        'temuan/{temuan}/close',
        [TemuanController::class, 'close']
    )->name('temuan.close');

    /*
    |--------------------------------------------------------------------------
    | AI Classification
    |--------------------------------------------------------------------------
    */

    Route::post('temuan/classify-ai', function (Request $request) {

        $request->validate([
            'judul_temuan' => 'required|string',
        ]);

        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'API key tidak tersedia',
            ], 500);
        }

        try {

            $prompt = "
            Klasifikasikan temuan K3 berikut ke dalam salah satu kategori:
            - unsafe_action
            - unsafe_condition
            - near_miss
            - positive

            Berikan confidence score antara 0 sampai 1.

            Jawab HANYA dalam format JSON tanpa markdown:

            {
                \"kategori\": \"...\",
                \"confidence\": 0.0
            }

            Temuan:
            {$request->judul_temuan}
            ";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
                ->timeout(10)
                ->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}",
                    [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => $prompt,
                                    ],
                                ],
                            ],
                        ],
                        'generationConfig' => [
                            'temperature' => 0.1,
                            'maxOutputTokens' => 100,
                        ],
                    ]
                );

            if (!$response->successful()) {
                return response()->json([
                    'error' => 'AI tidak tersedia',
                ], 500);
            }

            $text = $response->json(
                'candidates.0.content.parts.0.text'
            );

            if (empty($text)) {
                return response()->json([
                    'error' => 'Response AI kosong',
                ], 500);
            }

            $text = preg_replace('/```json|```/', '', $text);
            $text = trim($text);

            $parsed = json_decode($text, true);

            if (
                !is_array($parsed) ||
                !isset($parsed['kategori']) ||
                !isset($parsed['confidence'])
            ) {
                return response()->json([
                    'error' => 'Format AI tidak valid',
                ], 500);
            }

            return response()->json($parsed);
        } catch (\Exception $e) {

            Log::error(
                'Gemini classify-ai error: ' . $e->getMessage()
            );

            return response()->json([
                'error' => 'AI tidak tersedia',
            ], 500);
        }
    })->name('temuan.classify-ai');
});
