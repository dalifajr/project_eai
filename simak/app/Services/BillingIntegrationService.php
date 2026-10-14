<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BillingIntegrationService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('BILLING_API_URL', 'http://localhost:8001/api/v1/billing'), '/');
        $this->apiKey = env('BILLING_API_KEY', 'EAI-SECRET-TOKEN-2026-X99');
    }

    /**
     * Memeriksa status pembayaran UKT mahasiswa untuk semester tertentu.
     * Mengembalikan array response standar EAI.
     */
    public function getStatusUkt(string $nim, string $semester = '7'): array
    {
        $endpoint = "{$this->baseUrl}/status-ukt/{$nim}?semester={$semester}";

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(5)->get($endpoint);

            if ($response->status() === 200) {
                return [
                    'is_paid' => true,
                    'status' => 'PAID',
                    'data' => $response->json('data'),
                    'message' => 'UKT telah lunas.',
                ];
            }

            if ($response->status() === 402) {
                return [
                    'is_paid' => false,
                    'status' => 'UNPAID',
                    'data' => $response->json('data'),
                    'message' => 'UKT belum dilunasi.',
                ];
            }

            return [
                'is_paid' => false,
                'status' => 'NOT_FOUND',
                'data' => null,
                'message' => $response->json('message') ?? 'Data tagihan tidak ditemukan.',
            ];
        } catch (\Exception $e) {
            Log::error("EAI Error [BillingIntegrationService::getStatusUkt]: " . $e->getMessage());
            return [
                'is_paid' => false,
                'status' => 'SERVICE_UNAVAILABLE',
                'data' => null,
                'message' => 'Layanan Total Billing System sedang tidak dapat dijangkau (Connection Timeout).',
            ];
        }
    }

    /**
     * Memeriksa riwayat tunggakan UKT dan denda mahasiswa sebelum pendaftaran wisuda.
     */
    public function cekTunggakan(string $nim): array
    {
        $endpoint = "{$this->baseUrl}/cek-tunggakan/{$nim}";

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(5)->get($endpoint);

            if ($response->successful()) {
                $body = $response->json();
                return [
                    'success' => true,
                    'has_debt' => $body['has_debt'] ?? false,
                    'total_tunggakan' => $body['total_tunggakan'] ?? 0,
                    'detail' => $body['detail'] ?? [],
                    'message' => $body['message'] ?? '',
                ];
            }

            return [
                'success' => false,
                'has_debt' => true,
                'total_tunggakan' => 0,
                'detail' => [],
                'message' => 'Gagal memvalidasi status keuangan mahasiswa ke Total Billing System.',
            ];
        } catch (\Exception $e) {
            Log::error("EAI Error [BillingIntegrationService::cekTunggakan]: " . $e->getMessage());
            return [
                'success' => false,
                'has_debt' => true,
                'total_tunggakan' => 0,
                'detail' => [],
                'message' => 'Layanan Total Billing System tidak merespons.',
            ];
        }
    }

    /**
     * Meminta penerbitan invoice paket wisuda & kode Virtual Account ke Total Billing System.
     */
    public function requestVaWisuda(string $nim, string $nama = '', string $prodi = ''): array
    {
        $endpoint = "{$this->baseUrl}/request-va-wisuda";

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(5)->post($endpoint, [
                'nim' => $nim,
                'nama' => $nama,
                'prodi' => $prodi,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json('data'),
                    'message' => $response->json('message') ?? 'Tagihan wisuda berhasil diterbitkan.',
                ];
            }

            return [
                'success' => false,
                'data' => null,
                'message' => $response->json('message') ?? 'Gagal membuat tagihan wisuda.',
            ];
        } catch (\Exception $e) {
            Log::error("EAI Error [BillingIntegrationService::requestVaWisuda]: " . $e->getMessage());
            return [
                'success' => false,
                'data' => null,
                'message' => 'Layanan Total Billing System tidak dapat dihubungi.',
            ];
        }
    }
}
