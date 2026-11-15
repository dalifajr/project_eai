@extends('layouts.app')

@section('title', 'Profil Akademik Mahasiswa')
@section('page_title', 'Profil Mahasiswa')
@section('page_subtitle', 'Data Induk & Riwayat Akademik Mahasiswa')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- Profile Hero Card --}}
    <div class="m3-card" style="padding: 28px;">
        <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap;">
            <div style="width: 84px; height: 84px; border-radius: var(--radius-xl); background: var(--primary-container); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 2.2rem; font-weight: 700; flex-shrink: 0; border: 2px solid var(--outline-variant);">
                {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
            </div>
            <div style="flex: 1; min-width: 240px;">
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <h2 style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; color: var(--on-surface); margin: 0;">
                        {{ $mahasiswa->nama }}
                    </h2>
                    <span class="badge-m3 badge-m3-success" style="font-size: 0.8rem;">
                        <i class="fas fa-circle-check me-1"></i> {{ strtoupper($mahasiswa->status_akademik) }}
                    </span>
                </div>
                <div style="margin-top: 6px; color: var(--on-surface-variant); font-size: 0.95rem;">
                    NIM: <strong style="font-family: var(--font-mono); color: var(--on-surface);">{{ $mahasiswa->nim }}</strong>
                    &bull; {{ $mahasiswa->program_studi }} ({{ $mahasiswa->fakultas }})
                </div>
                <div style="margin-top: 8px; font-size: 0.825rem; color: var(--on-surface-variant);">
                    Angkatan: <strong>{{ $mahasiswa->angkatan }}</strong> &bull; Semester Aktif: <strong>Semester {{ $mahasiswa->semester_sekarang }}</strong>
                </div>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="btn-m3 btn-m3-tonal">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Detail Grid: 2 Columns --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 20px;">
        
        {{-- Section 1: Data Identitas Akademik --}}
        <div class="m3-card">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--on-surface); margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-id-card-clip text-primary"></i>
                <span>Data Induk Mahasiswa</span>
            </h3>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                    <span style="font-size: 0.875rem; color: var(--on-surface-variant);">Nomor Induk Mahasiswa (NIM)</span>
                    <span style="font-family: var(--font-mono); font-weight: 700; color: var(--on-surface);">{{ $mahasiswa->nim }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                    <span style="font-size: 0.875rem; color: var(--on-surface-variant);">Nama Lengkap</span>
                    <strong style="color: var(--on-surface);">{{ $mahasiswa->nama }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                    <span style="font-size: 0.875rem; color: var(--on-surface-variant);">Program Studi</span>
                    <strong style="color: var(--on-surface);">{{ $mahasiswa->program_studi }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                    <span style="font-size: 0.875rem; color: var(--on-surface-variant);">Fakultas</span>
                    <span style="color: var(--on-surface);">{{ $mahasiswa->fakultas }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                    <span style="font-size: 0.875rem; color: var(--on-surface-variant);">Tahun Masuk / Angkatan</span>
                    <span style="color: var(--on-surface);">{{ $mahasiswa->angkatan }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                    <span style="font-size: 0.875rem; color: var(--on-surface-variant);">Dosen Pembimbing Akademik</span>
                    <span style="font-weight: 600; color: var(--primary);">Dr. Ir. Hendra Gunawan, M.T.</span>
                </div>
            </div>
        </div>

        {{-- Section 2: Capaian & Prasyarat Kelulusan --}}
        <div class="m3-card">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--on-surface); margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-award text-primary"></i>
                <span>Capaian Studi & Prasyarat</span>
            </h3>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                    <span style="font-size: 0.875rem; color: var(--on-surface-variant);">Indeks Prestasi Semester (IPS Lalu)</span>
                    <span style="font-family: var(--font-display); font-weight: 700; color: var(--primary); font-size: 1.1rem;">
                        {{ number_format($mahasiswa->ips_lalu, 2) }}
                    </span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                    <span style="font-size: 0.875rem; color: var(--on-surface-variant);">Batas Maksimum Beban SKS</span>
                    <span class="badge-m3 badge-m3-info" style="font-weight: 700;">{{ $mahasiswa->batas_sks }} SKS</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm); align-items: center;">
                    <span style="font-size: 0.875rem; color: var(--on-surface-variant);">Status Ujian Skripsi / TA</span>
                    @if($mahasiswa->status_skripsi === 'LULUS')
                        <span class="badge-m3 badge-m3-success"><i class="fas fa-check me-1"></i> LULUS</span>
                    @else
                        <span class="badge-m3 badge-m3-warning"><i class="fas fa-clock me-1"></i> {{ strtoupper($mahasiswa->status_skripsi) }}</span>
                    @endif
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm); align-items: center;">
                    <span style="font-size: 0.875rem; color: var(--on-surface-variant);">Bebas Tanggungan Laboratorium</span>
                    @if($mahasiswa->bebas_lab)
                        <span class="badge-m3 badge-m3-success"><i class="fas fa-check me-1"></i> BEBAS</span>
                    @else
                        <span class="badge-m3 badge-m3-error"><i class="fas fa-times me-1"></i> BELUM BEBAS</span>
                    @endif
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm); align-items: center;">
                    <span style="font-size: 0.875rem; color: var(--on-surface-variant);">Bebas Tanggungan Perpustakaan</span>
                    @if($mahasiswa->bebas_perpustakaan)
                        <span class="badge-m3 badge-m3-success"><i class="fas fa-check me-1"></i> BEBAS</span>
                    @else
                        <span class="badge-m3 badge-m3-error"><i class="fas fa-times me-1"></i> BELUM BEBAS</span>
                    @endif
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <a href="{{ route('keuangan.index') }}" class="btn-m3 btn-m3-primary" style="flex: 1;">
                    <i class="fas fa-wallet"></i>
                    <span>Cek Status Keuangan</span>
                </a>
                <a href="{{ route('krs.index') }}" class="btn-m3 btn-m3-tonal" style="flex: 1;">
                    <i class="fas fa-file-signature"></i>
                    <span>Pengisian KRS</span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
