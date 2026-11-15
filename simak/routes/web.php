<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KrsController;
use App\Http\Controllers\WisudaController;

// Authentication & Quick Switcher
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/quick-login/{username}', [AuthController::class, 'quickLogin'])->name('quick.login');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Mahasiswa Dedicated Menus
    Route::get('/profil', [DashboardController::class, 'profil'])->name('profil.index');
    Route::get('/keuangan', [DashboardController::class, 'keuangan'])->name('keuangan.index');

    // Pengisian KRS
    Route::get('/krs', [KrsController::class, 'index'])->name('krs.index');
    Route::post('/krs', [KrsController::class, 'store'])->name('krs.store');
    Route::get('/krs/cetak', [KrsController::class, 'cetak'])->name('krs.cetak');

    // Pendaftaran Wisuda
    Route::get('/wisuda', [WisudaController::class, 'index'])->name('wisuda.index');
    Route::post('/wisuda/data-lanjutan', [WisudaController::class, 'storeFormLanjutan'])->name('wisuda.data_lanjutan');
    Route::get('/wisuda/cetak', [WisudaController::class, 'cetak'])->name('wisuda.cetak');

    // Admin Dedicated Menus
    Route::get('/admin/mahasiswa', [DashboardController::class, 'adminMahasiswa'])->name('admin.mahasiswa');
    Route::get('/admin/matakuliah', [DashboardController::class, 'adminMataKuliah'])->name('admin.matakuliah');
});
