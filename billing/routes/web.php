<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillingWebController;

Route::get('/', [BillingWebController::class, 'index'])->name('billing.dashboard');
Route::post('/simulasi-bayar', [BillingWebController::class, 'prosesSimulasiBayar'])->name('billing.simulasi_bayar');
