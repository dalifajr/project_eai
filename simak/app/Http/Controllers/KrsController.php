<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Krs;
use App\Models\KrsDetail;
use App\Services\BillingIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KrsController extends Controller
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

        // 1. Validasi Status Akademik Mahasiswa
        if ($mahasiswa->status_akademik !== 'aktif') {
            return view('krs.index', [
                'mahasiswa' => $mahasiswa,
                'akademikAktif' => false,
                'pesanAkademik' => "Status akademik Anda saat ini adalah: " . strtoupper($mahasiswa->status_akademik) . ". Silakan hubungi bagian administrasi akademik untuk aktivasi status.",
                'uktStatus' => null,
                'mataKuliah' => collect(),
                'krsAktif' => null,
            ]);
        }

        // 2. Call API Cek Status Pembayaran UKT Semester Berjalan ke Total Billing System
        $uktStatus = $this->billingService->getStatusUkt($mahasiswa->nim, $mahasiswa->semester_sekarang);

        // 3. Ambil data KRS aktif jika sudah pernah diisi
        $krsAktif = Krs::where('nim', $mahasiswa->nim)
            ->where('semester', $mahasiswa->semester_sekarang)
            ->with('details.mataKuliah')
            ->first();

        // 4. Daftar tawaran mata kuliah semester berjalan
        $mataKuliah = MataKuliah::where('semester', $mahasiswa->semester_sekarang)->get();

        return view('krs.index', [
            'mahasiswa' => $mahasiswa,
            'akademikAktif' => true,
            'pesanAkademik' => null,
            'uktStatus' => $uktStatus,
            'mataKuliah' => $mataKuliah,
            'krsAktif' => $krsAktif,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('nim', $user->nim)->firstOrFail();

        // Validasi keamanan: status akademik dan UKT
        if ($mahasiswa->status_akademik !== 'aktif') {
            return back()->withErrors(['krs' => 'Status akademik Anda tidak aktif. Pengisian KRS dibatalkan.']);
        }

        $uktStatus = $this->billingService->getStatusUkt($mahasiswa->nim, $mahasiswa->semester_sekarang);
        if (!$uktStatus['is_paid']) {
            return back()->withErrors(['krs' => 'Pembayaran UKT belum dilunasi. Pengisian KRS terkunci.']);
        }

        $selectedKodeMk = $request->input('mata_kuliah', []);
        if (empty($selectedKodeMk)) {
            return back()->withErrors(['mata_kuliah' => 'Pilihlah minimal 1 mata kuliah yang akan diambil.']);
        }

        $selectedCourses = MataKuliah::whereIn('kode_mk', $selectedKodeMk)->get();

        // 1. Validasi Batas Maksimal SKS
        $totalSks = $selectedCourses->sum('sks');
        if ($totalSks > $mahasiswa->batas_sks) {
            return back()->withErrors([
                'sks' => "Jumlah SKS yang dipilih ({$totalSks} SKS) melebihi batas maksimal yang diizinkan ({$mahasiswa->batas_sks} SKS).",
            ])->withInput();
        }

        // 2. Validasi Bentrok Jadwal Kuliah
        $bentrokList = [];
        $count = $selectedCourses->count();
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $mk1 = $selectedCourses[$i];
                $mk2 = $selectedCourses[$j];

                if ($mk1->hari === $mk2->hari) {
                    $start1 = strtotime($mk1->jam_mulai);
                    $end1 = strtotime($mk1->jam_selesai);
                    $start2 = strtotime($mk2->jam_mulai);
                    $end2 = strtotime($mk2->jam_selesai);

                    // Overlap check
                    if ($start1 < $end2 && $start2 < $end1) {
                        $bentrokList[] = "Bentrok jadwal pada hari {$mk1->hari}: [{$mk1->kode_mk}] {$mk1->nama_mk} ({$mk1->jam_mulai} - {$mk1->jam_selesai}) bertabrakan dengan [{$mk2->kode_mk}] {$mk2->nama_mk} ({$mk2->jam_mulai} - {$mk2->jam_selesai}).";
                    }
                }
            }
        }

        if (!empty($bentrokList)) {
            return back()->withErrors([
                'bentrok' => implode('<br>', $bentrokList),
            ])->withInput();
        }

        // 3. Simpan KRS ke Basis Data
        DB::transaction(function () use ($mahasiswa, $totalSks, $selectedCourses) {
            $krs = Krs::updateOrCreate(
                [
                    'nim' => $mahasiswa->nim,
                    'semester' => $mahasiswa->semester_sekarang,
                ],
                [
                    'tahun_akademik' => '2026/2027',
                    'total_sks' => $totalSks,
                    'status_krs' => 'disetujui',
                ]
            );

            // Hapus detail lama dan masukkan yang baru
            $krs->details()->delete();
            foreach ($selectedCourses as $mk) {
                KrsDetail::create([
                    'krs_id' => $krs->id,
                    'kode_mk' => $mk->kode_mk,
                ]);
            }
        });

        return redirect()->route('krs.index')->with('success', "Rencana Studi berhasil disimpan dengan total {$totalSks} SKS.");
    }

    public function cetak()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('nim', $user->nim)->firstOrFail();
        $krs = Krs::where('nim', $mahasiswa->nim)
            ->where('semester', $mahasiswa->semester_sekarang)
            ->with('details.mataKuliah')
            ->firstOrFail();

        return view('krs.cetak', compact('mahasiswa', 'krs'));
    }
}
