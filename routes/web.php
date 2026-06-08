<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HiradcController;
use App\Http\Controllers\ProgramKerjaController;
use App\Http\Controllers\LiveAuditController;
use App\Http\Controllers\TemuanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Master\ChecklistItemController;
use App\Http\Controllers\Master\UserController;
use Illuminate\Support\Facades\Http;

// Redirect root ke login
Route::get('/', fn() => redirect()->route('login'));

// ================================================================
// AUTH ROUTES
// ================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.post');
});

// ================================================================
// PROTECTED ROUTES
// ================================================================
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])
        ->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile
    Route::get('profile', [ProfileController::class, 'index'])
        ->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update-password');

    // HIRADC
    Route::resource('hiradc', HiradcController::class)
        ->except(['edit', 'update']);

    // Aktivitas
    Route::post(
        'hiradc/{hiradc}/aktivitas',
        [HiradcController::class, 'storeAktivitas']
    )
        ->name('hiradc.aktivitas.store');
    Route::delete(
        'hiradc/{hiradc}/aktivitas/{aktivitas}',
        [HiradcController::class, 'destroyAktivitas']
    )
        ->name('hiradc.aktivitas.destroy');

    // Aspek Bahaya
    Route::post(
        'hiradc/aktivitas/{aktivitas}/aspek-bahaya',
        [HiradcController::class, 'storeAspekBahaya']
    )
        ->name('hiradc.aspek-bahaya.store');
    Route::delete(
        'hiradc/aspek-bahaya/{aspek}',
        [HiradcController::class, 'destroyAspekBahaya']
    )
        ->name('hiradc.aspek-bahaya.destroy');
    Route::post(
        'hiradc/aspek-bahaya/{aspek}/update-risiko-akhir',
        [HiradcController::class, 'updateLevelRisikoAkhir']
    )
        ->name('hiradc.aspek-bahaya.update-risiko-akhir');
    Route::post(
        'hiradc/{hiradc}/validate-v1',
        [HiradcController::class, 'validateV1']
    )
        ->name('hiradc.validate-v1');
    Route::post(
        'hiradc/{hiradc}/validate-v2',
        [HiradcController::class, 'validateV2']
    )
        ->name('hiradc.validate-v2');

    // Program Kerja
    Route::resource('program-kerja', ProgramKerjaController::class);
    Route::post(
        'program-kerja/{programKerja}/upload-bukti',
        [ProgramKerjaController::class, 'uploadBukti']
    )
        ->name('program-kerja.upload-bukti');
    Route::post(
        'program-kerja/{programKerja}/close',
        [ProgramKerjaController::class, 'close']
    )
        ->name('program-kerja.close');

    // Live Audit
    Route::resource('live-audit', LiveAuditController::class)
        ->except(['edit', 'update']);
    Route::post(
        'live-audit/{liveAudit}/validate-v1',
        [LiveAuditController::class, 'validateV1']
    )
        ->name('live-audit.validate-v1');
    Route::post(
        'live-audit/{liveAudit}/validate-v2',
        [LiveAuditController::class, 'validateV2']
    )
        ->name('live-audit.validate-v2');
    Route::get(
        'live-audit/{liveAudit}/export-pdf',
        [LiveAuditController::class, 'exportPdf']
    )
        ->name('live-audit.export-pdf');

    // Temuan UA/UC
    Route::post('temuan/classify-ai', function (\Illuminate\Http\Request $request) {
        $request->validate(['judul_temuan' => 'required|string']);
        $apiKey = config('services.gemini.key');
        if (empty($apiKey)) {
            return response()->json(['error' => 'API key tidak tersedia'], 500);
        }
        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(10)
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                    'contents' => [[
                        'parts' => [[
                            'text' => "Klasifikasikan temuan K3 berikut ke dalam salah satu kategori: unsafe_action, unsafe_condition, near_miss, atau positive. Berikan confidence score antara 0 sampai 1. Jawab HANYA dalam format JSON tanpa markdown: {\"kategori\": \"...\", \"confidence\": 0.0}. Temuan: \"{$request->judul_temuan}\"",
                        ]],
                    ]],
                    'generationConfig' => [
                        'temperature'     => 0.1,
                        'maxOutputTokens' => 100,
                    ],
                ]);
            if (!$response->successful()) {
                return response()->json(['error' => 'AI tidak tersedia'], 500);
            }
            $text   = $response->json('candidates.0.content.parts.0.text');
            $text   = preg_replace('/```json|```/', '', $text);
            $parsed = json_decode(trim($text), true);
            if (!is_array($parsed) || !isset($parsed['kategori'])) {
                return response()->json(['error' => 'Format AI tidak valid'], 500);
            }
            return response()->json($parsed);
        } catch (\Exception $e) {
            return response()->json(['error' => 'AI tidak tersedia'], 500);
        }
    })->name('temuan.classify-ai');

    Route::resource('temuan', TemuanController::class)
        ->except(['edit', 'update']);
    Route::post(
        'temuan/{temuan}/complete-draft',
        [TemuanController::class, 'completeDraft']
    )
        ->name('temuan.complete-draft');
    Route::post(
        'temuan/{temuan}/validate-v1',
        [TemuanController::class, 'validateV1']
    )
        ->name('temuan.validate-v1');
    Route::post(
        'temuan/{temuan}/validate-v2',
        [TemuanController::class, 'validateV2']
    )
        ->name('temuan.validate-v2');
    Route::post(
        'temuan/{temuan}/upload-bukti',
        [TemuanController::class, 'uploadBukti']
    )
        ->name('temuan.upload-bukti');
    Route::post(
        'temuan/{temuan}/close',
        [TemuanController::class, 'close']
    )
        ->name('temuan.close');

    // Master Data
    Route::prefix('master')->name('master.')->group(function () {
        // Checklist Items
        Route::resource('checklist-items', ChecklistItemController::class);
        Route::post(
            'checklist-items/{id}/restore',
            [ChecklistItemController::class, 'restore']
        )
            ->name('checklist-items.restore');

        // Users
        Route::resource('users', UserController::class)
            ->except(['show']);
    });
});
