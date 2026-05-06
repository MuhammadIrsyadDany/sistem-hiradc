<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgramKerjaController;
use App\Http\Controllers\HiradcController;
use App\Http\Controllers\LiveAuditController;

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
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login')->name('login.post');
    });
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])
        ->name('logout');

    // Dashboard
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
});
