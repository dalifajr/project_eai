<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranWisuda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Menerima Webhook Callback Pembayaran Wisuda dari Total Billing System
     */
    public function handlePembayaranWisuda(Request $request)
    {
        $rawPayload = $request->getContent();
        $receivedSignature = $request->header('X-Billing-Signature');
        $secret = env('EAI_WEBHOOK_SECRET', 'WEBHOOK-HMAC-SECRET-8877');

        // Validasi Signature HMAC
        $expectedSignature = hash_hmac('sha256', $rawPayload, $secret);
        if (!$receivedSignature || !hash_equals($expectedSignature, $receivedSignature)) {
            Log::warning("EAI Webhook Warning: Invalid HMAC signature from {$request->ip()}");
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid webhook signature.',
            ], 403);
        }

        $payload = $request->json()->all();
        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? [];

        Log::info("EAI Webhook Received: Event [{$event}]", $data);

        if ($event === 'payment.success') {
            $nim = $data['nim'] ?? null;
            $invoiceId = $data['invoice_id'] ?? null;

            if ($nim) {
                // Update atau buat pendaftaran wisuda
                $wisuda = PendaftaranWisuda::where('nim', $nim)->latest()->first();

                if ($wisuda) {
                    $wisuda->update([
                        'status_pembayaran' => 'paid',
                        'invoice_wisuda_id' => $invoiceId ?? $wisuda->invoice_wisuda_id,
                        'nomor_va' => $data['nomor_va'] ?? $wisuda->nomor_va,
                        'nominal_wisuda' => $data['nominal_dibayar'] ?? $wisuda->nominal_wisuda,
                    ]);
                } else {
                    PendaftaranWisuda::create([
                        'nim' => $nim,
                        'tanggal_daftar' => now()->toDateString(),
                        'invoice_wisuda_id' => $invoiceId,
                        'nomor_va' => $data['nomor_va'] ?? null,
                        'nominal_wisuda' => $data['nominal_dibayar'] ?? 1750000,
                        'status_pembayaran' => 'paid',
                        'status_pendaftaran' => 'menunggu_pembayaran',
                    ]);
                }
            }

            return response()->json([
                'status' => 'acknowledged',
                'code' => 200,
                'message' => 'Payment webhook processed successfully.',
            ], 200);
        }

        if ($event === 'payment.revoked') {
            $nim = $data['nim'] ?? null;
            if ($nim) {
                $wisuda = PendaftaranWisuda::where('nim', $nim)->latest()->first();
                if ($wisuda) {
                    $wisuda->update([
                        'status_pembayaran' => 'unpaid',
                        'status_pendaftaran' => 'menunggu_pembayaran',
                    ]);
                }
            }

            return response()->json([
                'status' => 'acknowledged',
                'code' => 200,
                'message' => 'Payment revoke webhook processed successfully.',
            ], 200);
        }

        return response()->json([
            'status' => 'ignored',
            'code' => 200,
            'message' => 'Unhandled event type.',
        ], 200);
    }
}
