<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\PendaftaranWisuda;
use App\Services\BillingIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WisudaController extends Controller
{
    protected BillingIntegrationService $billingService;

    public function __construct(BillingIntegrationService $billingService)
    {
        $this->billingService = $billingService;
    }

    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'mahasiswa') {
            return redirect()->route('dashboard');
        }

        $mahasiswa = Mahasiswa::where('nim', $user->nim)->firstOrFail();

        // 1. Validasi Kelulusan Akademik & Bebas Puspa/Lab
        $syaratAkademikLengkap = (
            $mahasiswa->status_skripsi === 'lulus' &&
            $mahasiswa->bebas_lab &&
            $mahasiswa->bebas_perpustakaan
        );

        if (!$syaratAkademikLengkap) {
            $kekuranganSyarat = [];
            if ($mahasiswa->status_skripsi !== 'lulus') {
                $kekuranganSyarat[] = "Ujian Skripsi / Sidang Tugas Akhir belum berstatus LULUS (status saat ini: " . strtoupper($mahasiswa->status_skripsi) . ").";
            }
            if (!$mahasiswa->bebas_lab) {
                $kekuranganSyarat[] = "Belum memperoleh surat bebas laboratorium komputer / sains.";
            }
            if (!$mahasiswa->bebas_perpustakaan) {
                $kekuranganSyarat[] = "Belum menyelesaikan bebas tanggungan pustaka di perpustakaan pusat.";
            }

            return view('wisuda.index', [
                'mahasiswa' => $mahasiswa,
                'syaratAkademikLengkap' => false,
                'kekuranganSyarat' => $kekuranganSyarat,
                'tunggakan' => [
                    'has_debt' => false,
                    'total_tunggakan' => 0,
                    'detail' => [],
                ],
                'pendaftaran' => null,
                'tagihanWisuda' => null,
            ]);
        }

        // 2. Cek Tunggakan UKT dan Denda ke Total Billing System via EAI API
        $tunggakan = $this->billingService->cekTunggakan($mahasiswa->nim);

        if ($tunggakan['has_debt']) {
            return view('wisuda.index', [
                'mahasiswa' => $mahasiswa,
                'syaratAkademikLengkap' => true,
                'kekuranganSyarat' => [],
                'tunggakan' => $tunggakan,
                'pendaftaran' => null,
                'tagihanWisuda' => null,
            ]);
        }

        // 3. Request Tagihan Paket Wisuda & Nomor VA ke Total Billing System
        $responseVa = $this->billingService->requestVaWisuda(
            $mahasiswa->nim,
            $mahasiswa->nama,
            $mahasiswa->program_studi
        );

        $tagihanWisuda = $responseVa['success'] ? $responseVa['data'] : null;

        // Ambil data pendaftaran wisuda lokal
        $pendaftaran = PendaftaranWisuda::firstOrCreate(
            ['nim' => $mahasiswa->nim],
            [
                'tanggal_daftar' => now()->toDateString(),
                'invoice_wisuda_id' => $tagihanWisuda['invoice_wisuda_id'] ?? null,
                'nomor_va' => $tagihanWisuda['nomor_va'] ?? null,
                'nominal_wisuda' => $tagihanWisuda['nominal'] ?? 1750000,
                'status_pembayaran' => ($tagihanWisuda['status_pembayaran'] ?? 'UNPAID') === 'PAID' ? 'paid' : 'unpaid',
                'status_pendaftaran' => 'menunggu_pembayaran',
            ]
        );

        // Jika billing sudah berstatus PAID tetapi lokal masih unpaid, update
        if (isset($tagihanWisuda['status_pembayaran']) && $tagihanWisuda['status_pembayaran'] === 'PAID' && $pendaftaran->status_pembayaran !== 'paid') {
            $pendaftaran->update(['status_pembayaran' => 'paid']);
        }

        return view('wisuda.index', [
            'mahasiswa' => $mahasiswa,
            'syaratAkademikLengkap' => true,
            'kekuranganSyarat' => [],
            'tunggakan' => $tunggakan,
            'pendaftaran' => $pendaftaran,
            'tagihanWisuda' => $tagihanWisuda,
        ]);
    }

    public function storeFormLanjutan(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('nim', $user->nim)->firstOrFail();
        $pendaftaran = PendaftaranWisuda::where('nim', $mahasiswa->nim)->firstOrFail();

        if ($pendaftaran->status_pembayaran !== 'paid') {
            return back()->withErrors(['wisuda' => 'Pembayaran paket wisuda belum terverifikasi. Pengisian data lanjutan belum diizinkan.']);
        }

        $validated = $request->validate([
            'ukuran_toga' => 'required|in:S,M,L,XL,XXL',
            'jumlah_undangan' => 'required|integer|min:1|max:4',
            'catatan' => 'nullable|string|max:255',
        ]);

        $pendaftaran->update([
            'ukuran_toga' => $validated['ukuran_toga'],
            'jumlah_undangan' => $validated['jumlah_undangan'],
            'catatan' => $validated['catatan'] ?? null,
            'status_pendaftaran' => 'berkas_lengkap',
        ]);

        return redirect()->route('wisuda.index')->with('success', 'Data perlengkapan toga dan undangan wisuda berhasil disimpan.');
    }

    public function cetak()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('nim', $user->nim)->firstOrFail();
        $pendaftaran = PendaftaranWisuda::where('nim', $mahasiswa->nim)->firstOrFail();

        if ($pendaftaran->status_pembayaran !== 'paid') {
            return redirect()->route('wisuda.index')->withErrors(['wisuda' => 'Belum dapat mencetak bukti wisuda karena tagihan belum lunas.']);
        }

        return view('wisuda.cetak', compact('mahasiswa', 'pendaftaran'));
    }
}
