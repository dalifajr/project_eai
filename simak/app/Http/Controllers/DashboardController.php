<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Krs;
use App\Models\PendaftaranWisuda;
use App\Services\BillingIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected BillingIntegrationService $billingService;

    public function __construct(BillingIntegrationService $billingService)
    {
        $this->billingService = $billingService;
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $totalMahasiswa = Mahasiswa::count();
            $totalMataKuliah = MataKuliah::count();
            $totalKrs = Krs::count();
            $totalWisuda = PendaftaranWisuda::count();
            $allMahasiswa = Mahasiswa::with('user', 'pendaftaranWisuda', 'krsList')->get();

            return view('dashboard.admin', compact('totalMahasiswa', 'totalMataKuliah', 'totalKrs', 'totalWisuda', 'allMahasiswa'));
        }

        // Mahasiswa Dashboard
        $mahasiswa = Mahasiswa::where('nim', $user->nim)->firstOrFail();
        $krsAktif = Krs::where('nim', $mahasiswa->nim)
            ->where('semester', $mahasiswa->semester_sekarang)
            ->with('details.mataKuliah')
            ->first();

        $wisuda = PendaftaranWisuda::where('nim', $mahasiswa->nim)->latest()->first();

        // Cek status UKT via EAI
        $uktStatus = $this->billingService->getStatusUkt($mahasiswa->nim, $mahasiswa->semester_sekarang);

        return view('dashboard.mahasiswa', compact('mahasiswa', 'krsAktif', 'wisuda', 'uktStatus'));
    }
}
