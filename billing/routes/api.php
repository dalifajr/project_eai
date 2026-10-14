<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BillingApiController;
use App\Http\Middleware\VerifyBillingApiKey;

Route::prefix('v1/billing')->group(function () {
    // Protected EAI API Endpoints (called by SIMAK)
    Route::middleware([VerifyBillingApiKey::class])->group(function () {
        Route::get('/status-ukt/{nim}', [BillingApiController::class, 'statusUkt']);
        Route::get('/cek-tunggakan/{nim}', [BillingApiController::class, 'cekTunggakan']);
        Route::post('/request-va-wisuda', [BillingApiController::class, 'requestVaWisuda']);
    });

    // Public / Simulator Payment Endpoint
    Route::post('/simulasi-bayar', [BillingApiController::class, 'bayarTagihanSimulasi']);
});
