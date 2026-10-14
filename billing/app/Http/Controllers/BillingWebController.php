<?php

namespace App\Http\Controllers;

use App\Models\TagihanPembayaran;
use App\Models\MahasiswaRef;
use App\Models\LogWebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class BillingWebController extends Controller
{
    public function index(Request $request)
    {
        $filterStatus = $request->query('status', 'ALL');
        $filterJenis = $request->query('jenis', 'ALL');

        $query = TagihanPembayaran::with('mahasiswa')->orderBy('created_at', 'desc');

        if ($filterStatus !== 'ALL') {
            $query->where('status_pembayaran', $filterStatus);
        }

        if ($filterJenis !== 'ALL') {
            $query->where('jenis_tagihan', $filterJenis);
        }

        $tagihanList = $query->paginate(15);

        // Statistik
        $totalNominalPaid = TagihanPembayaran::where('status_pembayaran', 'PAID')->sum('jumlah_tagihan');
        $totalNominalUnpaid = TagihanPembayaran::where('status_pembayaran', 'UNPAID')->sum('jumlah_tagihan');
        $totalTagihanCount = TagihanPembayaran::count();
        $unpaidCount = TagihanPembayaran::where('status_pembayaran', 'UNPAID')->count();

        // Tagihan yang siap disimulasikan pembayarannya
        $unpaidInvoices = TagihanPembayaran::with('mahasiswa')
            ->where('status_pembayaran', 'UNPAID')
            ->get();

        // Log Webhook 10 Terakhir
        $webhookLogs = LogWebhookEvent::orderBy('created_at', 'desc')->take(10)->get();

        return view('dashboard.index', compact(
            'tagihanList',
            'totalNominalPaid',
            'totalNominalUnpaid',
            'totalTagihanCount',
            'unpaidCount',
            'unpaidInvoices',
            'webhookLogs',
            'filterStatus',
            'filterJenis'
        ));
    }

    public function prosesSimulasiBayar(Request $request)
    {
        $validated = $request->validate([
            'nomor_va' => 'required|string',
            'channel_bayar' => 'required|string',
        ]);

        $tagihan = TagihanPembayaran::where('nomor_va', $validated['nomor_va'])->first();

        if (!$tagihan) {
            return back()->withErrors(['nomor_va' => 'Nomor Virtual Account tidak ditemukan dalam basis data billing.']);
        }

        if ($tagihan->status_pembayaran === 'PAID') {
            return back()->with('info', "Tagihan {$tagihan->nomor_invoice} sudah lunas sebelumnya.");
        }

        // Update tagihan ke PAID
        $tagihan->update([
            'status_pembayaran' => 'PAID',
            'tanggal_bayar' => Carbon::now(),
            'channel_bayar' => $validated['channel_bayar'],
        ]);

        $pesan = "Sukses! Tagihan [{$tagihan->jenis_tagihan}] {$tagihan->nomor_invoice} berhasil dibayar via {$validated['channel_bayar']}.";

        // Jika tagihan adalah WISUDA, tembakkan Webhook ke SIMAK
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

                LogWebhookEvent::create([
                    'event_name' => 'payment.success',
                    'target_url' => $webhookUrl,
                    'payload' => $payloadJson,
                    'response_code' => $response->status(),
                    'response_body' => substr($response->body(), 0, 500),
                    'is_success' => $response->successful(),
                ]);

                if ($response->successful()) {
                    $pesan .= " Webhook callback terkirim ke SIMAK (Status HTTP {$response->status()}). Form toga di SIMAK otomatis dibuka!";
                } else {
                    $pesan .= " Tagihan lunas, namun webhook SIMAK merespons dengan status {$response->status()}.";
                }
            } catch (\Exception $e) {
                LogWebhookEvent::create([
                    'event_name' => 'payment.success',
                    'target_url' => $webhookUrl,
                    'payload' => $payloadJson,
                    'response_code' => 500,
                    'response_body' => $e->getMessage(),
                    'is_success' => false,
                ]);
                $pesan .= " Tagihan lunas, namun pengiriman webhook ke SIMAK gagal dihubungi (pastikan SIMAK berjalan di port 8000).";
            }
        }

        return redirect()->route('billing.dashboard')->with('success', $pesan);
    }
}
