@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
<!-- Header Page Info -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <span class="m3-badge-pill neutral" style="margin-bottom: 6px;">KONTROL SISTEM &bull; EAI HUB</span>
        <h1 style="font-size: 26px; font-weight: 700; color: var(--on-surface); letter-spacing: -0.02em;">
            Dashboard Administrator SIMAK
        </h1>
        <p style="font-size: 13.5px; color: var(--on-surface-variant); margin-top: 4px;">
            Pemantauan agregat mahasiswa, validasi integrasi EAI, dan kontrol pendaftaran
        </p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="http://localhost:8001" target="_blank" class="btn-modern primary" style="font-size: 13px;">
            Buka Total Billing Console (Port 8001) &rarr;
        </a>
    </div>
</div>

<!-- Modern Stat Counters Grid (20px Squircle M3 Cards) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="m3-card" style="margin-bottom: 0; padding: 22px 24px; border-radius: var(--radius-lg);">
        <span style="font-size: 11.5px; font-weight: 600; color: var(--on-surface-variant); text-transform: uppercase; letter-spacing: 0.05em;">Total Mahasiswa</span>
        <div style="font-size: 32px; font-weight: 700; color: var(--primary); margin-top: 6px; letter-spacing: -0.02em; font-family: var(--font-display);">
            {{ $totalMahasiswa }}
        </div>
        <span style="font-size: 12px; color: var(--on-surface-variant);">Terdaftar aktif</span>
    </div>

    <div class="m3-card" style="margin-bottom: 0; padding: 22px 24px; border-radius: var(--radius-lg);">
        <span style="font-size: 11.5px; font-weight: 600; color: var(--on-surface-variant); text-transform: uppercase; letter-spacing: 0.05em;">Mata Kuliah Ditawarkan</span>
        <div style="font-size: 32px; font-weight: 700; color: var(--info); margin-top: 6px; letter-spacing: -0.02em; font-family: var(--font-display);">
            {{ $totalMataKuliah }}
        </div>
        <span style="font-size: 12px; color: var(--on-surface-variant);">Semester ganjil 2026</span>
    </div>

    <div class="m3-card" style="margin-bottom: 0; padding: 22px 24px; border-radius: var(--radius-lg);">
        <span style="font-size: 11.5px; font-weight: 600; color: var(--on-surface-variant); text-transform: uppercase; letter-spacing: 0.05em;">KRS Disetujui</span>
        <div style="font-size: 32px; font-weight: 700; color: var(--tertiary); margin-top: 6px; letter-spacing: -0.02em; font-family: var(--font-display);">
            {{ $totalKrs }}
        </div>
        <span style="font-size: 12px; color: var(--on-surface-variant);">Tervalidasi UKT Lunas</span>
    </div>

    <div class="m3-card" style="margin-bottom: 0; padding: 22px 24px; border-radius: var(--radius-lg);">
        <span style="font-size: 11.5px; font-weight: 600; color: var(--on-surface-variant); text-transform: uppercase; letter-spacing: 0.05em;">Pendaftar Wisuda</span>
        <div style="font-size: 32px; font-weight: 700; color: var(--secondary); margin-top: 6px; letter-spacing: -0.02em; font-family: var(--font-display);">
            {{ $totalWisuda }}
        </div>
        <span style="font-size: 12px; color: var(--on-surface-variant);">Tercatat di sistem</span>
    </div>
</div>

<!-- Modern Table Card: Rekapitulasi Mahasiswa & Pengujian EAI -->
<div class="m3-card" style="padding: 0; overflow: hidden;">
    <div style="padding: 22px 24px; border-bottom: 1px solid var(--outline-variant);">
        <h2 class="m3-card-title" style="font-size: 17px;">Rekapitulasi Mahasiswa & Skenario Pengujian EAI</h2>
        <p class="m3-card-subtitle">Klik tombol login instan pada salah satu mahasiswa untuk menguji kondisi integrasi secara langsung</p>
    </div>

    <div class="m3-table-container" style="border: none; border-radius: 0; margin-bottom: 0;">
        <table class="m3-table">
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
                    <td><code style="background: var(--surface-container-high); border-radius: 4px; padding: 2px 6px;">{{ $mhs->nim }}</code></td>
                    <td>
                        <strong style="color: var(--on-surface);">{{ $mhs->nama }}</strong>
                    </td>
                    <td>{{ $mhs->program_studi }} (Smt {{ $mhs->semester_sekarang }})</td>
                    <td>
                        <span class="m3-badge-pill {{ $mhs->status_akademik === 'aktif' ? 'success' : 'danger' }}">
                            {{ strtoupper($mhs->status_akademik) }}
                        </span>
                    </td>
                    <td>
                        <span class="m3-badge-pill {{ $mhs->status_skripsi === 'lulus' ? 'success' : 'warning' }}">
                            {{ strtoupper($mhs->status_skripsi) }}
                        </span>
                    </td>
                    <td>
                        @if($mhs->bebas_lab && $mhs->bebas_perpustakaan)
                            <span class="m3-badge-pill success">Lengkap</span>
                        @else
                            <span class="m3-badge-pill neutral">Belum Lengkap</span>
                        @endif
                    </td>
                    <td>
                        @if($mhs->pendaftaranWisuda)
                            <span class="m3-badge-pill {{ $mhs->pendaftaranWisuda->status_pembayaran === 'paid' ? 'success' : 'warning' }}">
                                {{ strtoupper($mhs->pendaftaranWisuda->status_pendaftaran) }} ({{ strtoupper($mhs->pendaftaranWisuda->status_pembayaran) }})
                            </span>
                        @else
                            <span style="color: var(--on-surface-variant); font-size: 12px;">Belum Mendaftar</span>
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
