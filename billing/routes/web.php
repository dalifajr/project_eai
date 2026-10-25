<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillingWebController;
use App\Http\Controllers\AuthController;

// Autentikasi Staf Keuangan
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Dashboard & Operations
Route::middleware('auth')->group(function () {
    Route::get('/', [BillingWebController::class, 'index'])->name('billing.dashboard');
    Route::post('/simulasi-bayar', [BillingWebController::class, 'prosesSimulasiBayar'])->name('billing.simulasi_bayar');
    Route::post('/tagihan/{id}/update-status', [BillingWebController::class, 'updateStatusPelunasan'])->name('billing.update_status');
});
