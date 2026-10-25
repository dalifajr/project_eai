@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
<!-- Header Page Info -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
    <div>
        <span class="badge-pill neutral" style="margin-bottom: 6px;">KONTROL SISTEM</span>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--text-heading); letter-spacing: -0.02em;">
            Dashboard Administrator SIMAK
        </h1>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">
            Pemantauan agregat mahasiswa, validasi integrasi EAI, dan kontrol pendaftaran
        </p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="http://localhost:8001" target="_blank" class="btn-modern primary" style="background:#0f766e; font-size:12px;">
            Buka Total Billing Console (Port 8001) &rarr;
        </a>
    </div>
</div>

<!-- Modern Stat Counters Grid -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
    <div class="card-modern" style="margin-bottom: 0; padding: 18px 20px;">
        <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Total Mahasiswa</span>
        <div style="font-size: 28px; font-weight: 800; color: var(--brand-primary); margin-top: 6px; letter-spacing: -0.02em;">
            {{ $totalMahasiswa }}
        </div>
        <span style="font-size: 12px; color: var(--text-muted);">Terdaftar aktif</span>
    </div>

    <div class="card-modern" style="margin-bottom: 0; padding: 18px 20px;">
        <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Mata Kuliah Ditawarkan</span>
        <div style="font-size: 28px; font-weight: 800; color: #0284c7; margin-top: 6px; letter-spacing: -0.02em;">
            {{ $totalMataKuliah }}
        </div>
        <span style="font-size: 12px; color: var(--text-muted);">Semester ganjil 2026</span>
    </div>

    <div class="card-modern" style="margin-bottom: 0; padding: 18px 20px;">
        <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">KRS Disetujui</span>
        <div style="font-size: 28px; font-weight: 800; color: #10b981; margin-top: 6px; letter-spacing: -0.02em;">
            {{ $totalKrs }}
        </div>
        <span style="font-size: 12px; color: var(--text-muted);">Tervalidasi UKT Lunas</span>
    </div>

    <div class="card-modern" style="margin-bottom: 0; padding: 18px 20px;">
        <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Pendaftar Wisuda</span>
        <div style="font-size: 28px; font-weight: 800; color: #8b5cf6; margin-top: 6px; letter-spacing: -0.02em;">
            {{ $totalWisuda }}
        </div>
        <span style="font-size: 12px; color: var(--text-muted);">Tercatat di sistem</span>
    </div>
</div>

<!-- Modern Table Card: Rekapitulasi Mahasiswa & Pengujian EAI -->
<div class="card-modern">
    <div class="card-header-modern">
        <div>
            <h2 class="card-title-modern">Rekapitulasi Mahasiswa & Skenario Pengujian EAI</h2>
            <p class="card-subtitle-modern">Klik tombol login instan pada salah satu mahasiswa untuk menguji kondisi integrasi secara langsung</p>
        </div>
    </div>

    <div class="table-container-modern">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Prodi & Semester</th>
                    <th>Status Akademik</th>
                    <th>Status Skripsi</th>
                    <th>Bebas Lab / Pustaka</th>
                    <th>Status Wisuda</th>
                    <th style="text-align: right;">Aksi Pengujian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allMahasiswa as $mhs)
                <tr>
                    <td><code>{{ $mhs->nim }}</code></td>
                    <td>
                        <strong style="color: var(--text-heading);">{{ $mhs->nama }}</strong>
                    </td>
                    <td>{{ $mhs->program_studi }} (Smt {{ $mhs->semester_sekarang }})</td>
                    <td>
                        <span class="badge-pill {{ $mhs->status_akademik === 'aktif' ? 'success' : 'danger' }}">
                            {{ strtoupper($mhs->status_akademik) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-pill {{ $mhs->status_skripsi === 'lulus' ? 'success' : 'warning' }}">
                            {{ strtoupper($mhs->status_skripsi) }}
                        </span>
                    </td>
                    <td>
                        @if($mhs->bebas_lab && $mhs->bebas_perpustakaan)
                            <span class="badge-pill success">Lengkap</span>
                        @else
                            <span class="badge-pill neutral">Belum Lengkap</span>
                        @endif
                    </td>
                    <td>
                        @if($mhs->pendaftaranWisuda)
                            <span class="badge-pill {{ $mhs->pendaftaranWisuda->status_pembayaran === 'paid' ? 'success' : 'warning' }}">
                                {{ strtoupper($mhs->pendaftaranWisuda->status_pendaftaran) }} ({{ strtoupper($mhs->pendaftaranWisuda->status_pembayaran) }})
                            </span>
                        @else
                            <span style="color: var(--text-muted); font-size: 12px;">Belum Mendaftar</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('quick.login', $mhs->nim) }}" class="btn-modern secondary sm">
                            Login Sebagai Mhs &rarr;
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
