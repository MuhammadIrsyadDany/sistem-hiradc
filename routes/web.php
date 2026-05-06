<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgramKerjaController;
use App\Http\Controllers\HiradcController;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes (hanya untuk guest/belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.post');
});

// Protected Routes (harus login)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])
        ->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // HIRADC
    Route::resource('hiradc', HiradcController::class)
        ->except(['edit', 'update']);
    Route::post('hiradc/{hiradc}/validate-v1', [HiradcController::class, 'validateV1'])
        ->name('hiradc.validate-v1');
    Route::post('hiradc/{hiradc}/validate-v2', [HiradcController::class, 'validateV2'])
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
});
