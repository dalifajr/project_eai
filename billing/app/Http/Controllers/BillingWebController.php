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
            $webhookResult = $this->dispatchWisudaWebhook($tagihan, 'payment.success');
            if ($webhookResult['success']) {
                $pesan .= " Webhook callback terkirim ke SIMAK (Status HTTP {$webhookResult['status']}).";
            } else {
                $pesan .= " Namun webhook ke SIMAK gagal: {$webhookResult['message']}.";
            }
        }

        return redirect()->route('billing.dashboard')->with('success', $pesan);
    }

    /**
     * Memodifikasi Status Pelunasan oleh Admin (PAID / UNPAID)
     */
    public function updateStatusPelunasan(Request $request, $id)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'required|in:PAID,UNPAID',
            'channel_bayar' => 'nullable|string|max:100',
        ]);

        $tagihan = TagihanPembayaran::findOrFail($id);
        $statusBaru = $validated['status_pembayaran'];
        $statusBaruKanal = $validated['channel_bayar'] ?? 'UPDATE MANUAL ADMIN';

        if ($statusBaru === 'PAID') {
            $tagihan->update([
                'status_pembayaran' => 'PAID',
                'tanggal_bayar' => Carbon::now(),
                'channel_bayar' => $statusBaruKanal,
            ]);

            $pesan = "Status tagihan {$tagihan->nomor_invoice} berhasil diubah menjadi LUNAS (PAID).";

            if ($tagihan->jenis_tagihan === 'WISUDA') {
                $webhook = $this->dispatchWisudaWebhook($tagihan, 'payment.success');
                if ($webhook['success']) {
                    $pesan .= " Webhook pelunasan terkirim ke SIMAK.";
                }
            }
        } else {
            // Ubah ke UNPAID
            $tagihan->update([
                'status_pembayaran' => 'UNPAID',
                'tanggal_bayar' => null,
                'channel_bayar' => null,
            ]);

            $pesan = "Status tagihan {$tagihan->nomor_invoice} berhasil diubah menjadi BELUM LUNAS (UNPAID).";

            if ($tagihan->jenis_tagihan === 'WISUDA') {
                $webhook = $this->dispatchWisudaWebhook($tagihan, 'payment.revoked');
                if ($webhook['success']) {
                    $pesan .= " Webhook pembatalan lunas terkirim ke SIMAK.";
                }
            }
        }

        return redirect()->route('billing.dashboard')->with('success', $pesan);
    }

    /**
     * Helper untuk memancarkan Webhook Event Wisuda ke SIMAK
     */
    private function dispatchWisudaWebhook(TagihanPembayaran $tagihan, string $event = 'payment.success'): array
    {
        $webhookUrl = env('SIMAK_WEBHOOK_URL', 'http://localhost:8000/api/v1/webhook/pembayaran-wisuda');
        $secret = env('EAI_WEBHOOK_SECRET', 'WEBHOOK-HMAC-SECRET-8877');

        $payload = [
            'event' => $event,
            'timestamp' => Carbon::now()->toIso8601String(),
            'data' => [
                'nim' => $tagihan->nim,
                'invoice_id' => $tagihan->nomor_invoice,
                'nomor_va' => $tagihan->nomor_va,
                'jenis_tagihan' => $tagihan->jenis_tagihan,
                'nominal_dibayar' => (float) $tagihan->jumlah_tagihan,
                'status' => $tagihan->status_pembayaran,
                'waktu_transaksi' => $tagihan->tanggal_bayar ? $tagihan->tanggal_bayar->format('Y-m-d H:i:s') : null,
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
                'event_name' => $event,
                'target_url' => $webhookUrl,
                'payload' => $payloadJson,
                'response_code' => $response->status(),
                'response_body' => substr($response->body(), 0, 500),
                'is_success' => $response->successful(),
            ]);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'message' => 'Dispatched',
            ];
        } catch (\Exception $e) {
            LogWebhookEvent::create([
                'event_name' => $event,
                'target_url' => $webhookUrl,
                'payload' => $payloadJson,
                'response_code' => 500,
                'response_body' => $e->getMessage(),
                'is_success' => false,
            ]);

            return [
                'success' => false,
                'status' => 500,
                'message' => $e->getMessage(),
            ];
        }
    }
}
