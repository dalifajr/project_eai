@extends('layouts.app')

@section('title', 'Data Mahasiswa & Pengujian EAI')
@section('page_title', 'Data Mahasiswa & Uji EAI')
@section('page_subtitle', 'Kelola Mahasiswa & Quick-Switch Akun untuk Pengujian')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- Header Card --}}
    <div class="m3-hero-card">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="badge-m3 badge-m3-primary" style="margin-bottom: 8px;">
                    <i class="fas fa-users-gear me-1"></i> PANEL ADMINISTRATOR AKADEMIK
                </span>
                <h2 style="font-family: var(--font-display); font-size: 1.45rem; font-weight: 700; color: var(--on-surface); margin: 4px 0 0 0;">
                    Daftar Mahasiswa & Pengujian Integrasi EAI
                </h2>
                <p style="margin: 4px 0 0 0; color: var(--on-surface-variant); font-size: 0.875rem;">
                    Gunakan tombol <strong>Login Sebagai Mahasiswa</strong> untuk langsung berpindah akun tanpa memasukkan password saat menguji simulasi KRS, UKT, dan Wisuda.
                </p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="btn-m3 btn-m3-tonal">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Overview</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Search & Stats Bar --}}
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span class="badge-m3 badge-m3-info" style="font-size: 0.825rem;">
                Total: <strong>{{ $allMahasiswa->count() }}</strong> Mahasiswa Terdaftar
            </span>
        </div>
        <div style="width: 100%; max-width: 320px;">
            <input type="text" id="studentSearchInput" class="input-m3" placeholder="Cari nama atau NIM mahasiswa..." onkeyup="filterStudents()">
        </div>
    </div>

    {{-- Students Table Card --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px; background: var(--surface-container-lowest); border: 1px solid var(--outline-variant) !important;">
        <div class="card-body p-0">
            <div class="table-responsive mb-0" style="border: none; border-radius: 0;">
                <table class="table table-hover align-middle mb-0" id="studentsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Semester</th>
                            <th>IPS / Beban</th>
                            <th>Status Prasyarat</th>
                            <th>Wisuda</th>
                            <th class="text-end pe-4">Aksi Uji EAI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allMahasiswa as $index => $mhs)
                            <tr>
                                <td class="ps-4" style="font-family: var(--font-mono); color: var(--on-surface-variant); font-size: 0.825rem;">
                                    {{ $index + 1 }}
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 34px; height: 34px; border-radius: var(--radius-sm); background: var(--primary-container); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0;">
                                            {{ strtoupper(substr($mhs->nama, 0, 1)) }}
                                        </div>
                                        <div style="display: flex; flex-direction: column; line-height: 1.2;">
                                            <strong style="color: var(--on-surface); font-size: 0.9rem;">{{ $mhs->nama }}</strong>
                                            <span style="font-family: var(--font-mono); font-size: 0.775rem; color: var(--on-surface-variant);">
                                                NIM: {{ $mhs->nim }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 0.85rem; color: var(--on-surface);">{{ $mhs->program_studi }}</div>
                                    <small style="color: var(--on-surface-variant);">Angkatan {{ $mhs->angkatan }}</small>
                                </td>
                                <td>
                                    <span class="badge-m3 badge-m3-neutral" style="font-weight: 700;">
                                        Sem {{ $mhs->semester_sekarang }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.85rem; font-weight: 600; color: var(--primary);">
                                        IPS: {{ number_format($mhs->ips_lalu, 2) }}
                                    </div>
                                    <small style="color: var(--on-surface-variant);">Maks {{ $mhs->batas_sks }} SKS</small>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        <span style="font-size: 0.75rem;">
                                            Skripsi: <strong>{{ $mhs->status_skripsi }}</strong>
                                        </span>
                                        <span style="font-size: 0.72rem; color: var(--on-surface-variant);">
                                            Lab: {!! $mhs->bebas_lab ? '<span class="text-success fw-bold">Bebas</span>' : '<span class="text-danger">Belum</span>' !!} &bull; 
                                            Pus: {!! $mhs->bebas_perpustakaan ? '<span class="text-success fw-bold">Bebas</span>' : '<span class="text-danger">Belum</span>' !!}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if($mhs->pendaftaranWisuda)
                                        @if($mhs->pendaftaranWisuda->status_pembayaran_wisuda === 'PAID')
                                            <span class="badge-m3 badge-m3-success" style="font-size: 0.75rem;">PAID</span>
                                        @else
                                            <span class="badge-m3 badge-m3-warning" style="font-size: 0.75rem;">UNPAID</span>
                                        @endif
                                    @else
                                        <span style="font-size: 0.75rem; color: var(--outline-variant);">-</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($mhs->user)
                                        <a href="{{ route('quick.login', $mhs->user->username) }}" class="btn-m3 btn-m3-tonal" style="font-size: 0.78rem; height: 32px; padding: 0 12px;" title="Login sebagai {{ $mhs->nama }}">
                                            <i class="fas fa-right-to-bracket"></i>
                                            <span>Login As</span>
                                        </a>
                                    @else
                                        <span style="font-size: 0.75rem; color: var(--outline-variant);">No User</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4" style="color: var(--on-surface-variant);">
                                    Tidak ada data mahasiswa ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function filterStudents() {
        const input = document.getElementById('studentSearchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('studentsTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const tdText = tr[i].textContent || tr[i].innerText;
            if (tdText.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>
@endpush
@endsection
