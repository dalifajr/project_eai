<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TagihanPembayaran;
use App\Models\MahasiswaRef;
use App\Models\LogWebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class BillingApiController extends Controller
{
    /**
     * Endpoint 1: Cek Status Pembayaran UKT (NIM & Semester)
     * Digunakan SIMAK saat validasi pengisian KRS
     */
    public function statusUkt(Request $request, string $nim)
    {
        $semester = $request->query('semester', '7');

        $tagihan = TagihanPembayaran::where('nim', $nim)
            ->where('jenis_tagihan', 'UKT')
            ->where('semester', $semester)
            ->first();

        if (!$tagihan) {
            return response()->json([
                'status' => 'not_found',
                'code' => 404,
                'message' => "Data tagihan UKT semester {$semester} untuk NIM {$nim} tidak ditemukan.",
            ], 404);
        }

        if ($tagihan->status_pembayaran === 'PAID') {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'data' => [
                    'nim' => $tagihan->nim,
                    'semester' => $tagihan->semester,
                    'status_ukt' => 'PAID',
                    'nomor_invoice' => $tagihan->nomor_invoice,
                    'nominal' => (float) $tagihan->jumlah_tagihan,
                    'tanggal_bayar' => $tagihan->tanggal_bayar ? $tagihan->tanggal_bayar->format('Y-m-d H:i:s') : null,
                    'channel_bayar' => $tagihan->channel_bayar,
                ],
            ], 200);
        }

        // UNPAID
        return response()->json([
            'status' => 'unpaid',
            'code' => 402,
            'message' => "UKT semester {$semester} belum dilunasi.",
            'data' => [
                'nim' => $tagihan->nim,
                'semester' => $tagihan->semester,
                'status_ukt' => 'UNPAID',
                'nomor_invoice' => $tagihan->nomor_invoice,
                'nominal' => (float) $tagihan->jumlah_tagihan,
                'nomor_va' => $tagihan->nomor_va,
                'jatuh_tempo' => $tagihan->tanggal_jatuh_tempo ? $tagihan->tanggal_jatuh_tempo->format('Y-m-d H:i:s') : null,
            ],
        ], 402);
    }

    /**
     * Endpoint 2: Cek Tunggakan UKT dan Denda Mahasiswa
     * Digunakan SIMAK sebelum mengizinkan pendaftaran wisuda
     */
    public function cekTunggakan(Request $request, string $nim)
    {
        // Cari semua tagihan selain WISUDA yang statusnya masih UNPAID
        $tunggakan = TagihanPembayaran::where('nim', $nim)
            ->where('jenis_tagihan', '!=', 'WISUDA')
            ->where('status_pembayaran', 'UNPAID')
            ->get();

        if ($tunggakan->isNotEmpty()) {
            $totalTunggakan = $tunggakan->sum('jumlah_tagihan');
            $detail = $tunggakan->map(function ($item) {
                return [
                    'invoice_id' => $item->nomor_invoice,
                    'jenis' => $item->jenis_tagihan,
                    'deskripsi' => $item->deskripsi,
                    'semester' => $item->semester,
                    'nominal' => (float) $item->jumlah_tagihan,
                    'nomor_va' => $item->nomor_va,
                ];
            });

            return response()->json([
                'status' => 'has_debt',
                'code' => 200,
                'has_debt' => true,
                'total_tunggakan' => (float) $totalTunggakan,
                'message' => 'Mahasiswa masih memiliki tanggungan tagihan atau denda yang belum dilunasi.',
                'detail' => $detail,
            ], 200);
        }

        return response()->json([
            'status' => 'cleared',
            'code' => 200,
            'has_debt' => false,
            'total_tunggakan' => 0,
            'message' => 'Mahasiswa bersih dari seluruh kewajiban biaya UKT dan denda.',
            'detail' => [],
        ], 200);
    }

    /**
     * Endpoint 3: Request Tagihan Paket Wisuda & Nomor VA
     * Digunakan SIMAK untuk membuat invoice wisuda
     */
    public function requestVaWisuda(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string',
            'nama' => 'nullable|string',
            'prodi' => 'nullable|string',
        ]);

        $nim = $validated['nim'];

        // Pastikan mahasiswa_ref terdaftar
        $mhs = MahasiswaRef::firstOrCreate(
            ['nim' => $nim],
            [
                'nama' => $validated['nama'] ?? 'Mahasiswa Wisuda',
                'prodi' => $validated['prodi'] ?? 'Teknik Informatika',
                'angkatan' => substr($nim, 0, 2) ? '20' . substr($nim, 0, 2) : '2020',
            ]
        );

        // Cek apakah invoice wisuda sudah pernah dibuat
        $tagihanWisuda = TagihanPembayaran::where('nim', $nim)
            ->where('jenis_tagihan', 'WISUDA')
            ->first();

        if ($tagihanWisuda) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Tagihan wisuda sudah aktif.',
                'data' => [
                    'invoice_wisuda_id' => $tagihanWisuda->nomor_invoice,
                    'nomor_va' => $tagihanWisuda->nomor_va,
                    'nominal' => (float) $tagihanWisuda->jumlah_tagihan,
                    'status_pembayaran' => $tagihanWisuda->status_pembayaran,
                    'jatuh_tempo' => $tagihanWisuda->tanggal_jatuh_tempo->format('Y-m-d H:i:s'),
                ],
            ], 200);
        }

        // Generate invoice baru
        $invoiceNumber = 'INV-WSD-' . date('Y') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $nomorVa = '9901' . $nim; // Prefix 9901 untuk Wisuda
        $nominal = 1750000;

        $tagihanWisuda = TagihanPembayaran::create([
            'nomor_invoice' => $invoiceNumber,
            'nim' => $nim,
            'jenis_tagihan' => 'WISUDA',
            'deskripsi' => 'Biaya Paket Wisuda Sarjana (Toga, Ijazah, Buku Wisuda, Undangan)',
            'semester' => '8',
            'jumlah_tagihan' => $nominal,
            'nomor_va' => $nomorVa,
            'status_pembayaran' => 'UNPAID',
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(30),
        ]);

        return response()->json([
            'status' => 'created',
            'code' => 201,
            'message' => 'Invoice tagihan paket wisuda berhasil diterbitkan.',
            'data' => [
                'invoice_wisuda_id' => $tagihanWisuda->nomor_invoice,
                'nomor_va' => $tagihanWisuda->nomor_va,
                'nominal' => (float) $tagihanWisuda->jumlah_tagihan,
                'status_pembayaran' => 'UNPAID',
                'jatuh_tempo' => $tagihanWisuda->tanggal_jatuh_tempo->format('Y-m-d H:i:s'),
            ],
        ], 201);
    }

    /**
     * Endpoint 4: Simulator Pembayaran & Webhook Dispatcher
     * Memverifikasi pembayaran dan menembakkan callback ke SIMAK
     */
    public function bayarTagihanSimulasi(Request $request)
    {
        $validated = $request->validate([
            'nomor_va' => 'required|string',
            'channel_bayar' => 'nullable|string',
        ]);

        $tagihan = TagihanPembayaran::where('nomor_va', $validated['nomor_va'])->first();

        if (!$tagihan) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Nomor Virtual Account tidak valid atau tidak ditemukan.',
            ], 404);
        }

        if ($tagihan->status_pembayaran === 'PAID') {
            return response()->json([
                'status' => 'info',
                'code' => 200,
                'message' => 'Tagihan dengan nomor VA ini sudah lunas sebelumnya.',
                'data' => $tagihan,
            ], 200);
        }

        // Tandai lunas
        $tagihan->update([
            'status_pembayaran' => 'PAID',
            'tanggal_bayar' => Carbon::now(),
            'channel_bayar' => $validated['channel_bayar'] ?? 'M-BANKING SIMULATOR (EAI)',
        ]);

        $webhookSent = false;
        $webhookStatus = null;

        // Jika tagihan adalah WISUDA, tembakkan Webhook Callback ke SIMAK
        if ($tagihan->jenis_tagihan === 'WISUDA') {
            $webhookUrl = env('SIMAK_WEBHOOK_URL', 'http://localhost:8000/api/v1/webhook/pembayaran-wisuda');
            $secret = env('EAI_WEBHOOK_SECRET', 'WEBHOOK-HMAC-SECRET-8877');

            $payload = [
                'event' => 'payment.success',
                'timestamp' => Carbon::now()->toIso8601String(),
                'data' => [
                    'nim' => $tagihan->nim,
                    'invoice_id' => $tagihan->nomor_invoice,
                    'nomor_va' => $tagihan->nomor_va,
                    'jenis_tagihan' => $tagihan->jenis_tagihan,
                    'nominal_dibayar' => (float) $tagihan->jumlah_tagihan,
                    'status' => 'PAID',
                    'waktu_transaksi' => $tagihan->tanggal_bayar->format('Y-m-d H:i:s'),
                    'channel_bayar' => $tagihan->channel_bayar,
                ],
            ];

            $payloadJson = json_encode($payload);
            $signature = hash_hmac('sha256', $payloadJson, $secret);

            try {
                $response = Http::withHeaders([
                    'X-Billing-Signature' => $signature,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->timeout(5)->post($webhookUrl, $payload);

                $webhookSent = true;
                $webhookStatus = $response->status();

                LogWebhookEvent::create([
                    'event_name' => 'payment.success',
                    'target_url' => $webhookUrl,
                    'payload' => $payloadJson,
                    'response_code' => $response->status(),
                    'response_body' => substr($response->body(), 0, 500),
                    'is_success' => $response->successful(),
                ]);
            } catch (\Exception $e) {
                LogWebhookEvent::create([
                    'event_name' => 'payment.success',
                    'target_url' => $webhookUrl,
                    'payload' => $payloadJson,
                    'response_code' => 500,
                    'response_body' => $e->getMessage(),
                    'is_success' => false,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Pembayaran berhasil diverifikasi.',
            'webhook_dispatched' => $webhookSent,
            'webhook_response_code' => $webhookStatus,
            'data' => $tagihan,
        ], 200);
    }
}
