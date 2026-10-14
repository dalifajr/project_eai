<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WebhookController;

Route::prefix('v1')->group(function () {
    Route::post('/webhook/pembayaran-wisuda', [WebhookController::class, 'handlePembayaranWisuda']);
});
