@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Sisi Kiri: Profil Mahasiswa & Status EAI -->
    <div>
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Profil Akademik Mahasiswa</h2>
                    <p class="card-subtitle">Data induk terdaftar pada pangkalan data kampus</p>
                </div>
                <span class="badge {{ $mahasiswa->status_akademik === 'aktif' ? 'badge-success' : 'badge-danger' }}">
                    Status: {{ strtoupper($mahasiswa->status_akademik) }}
                </span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 14px;">
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 12px;">NIM</span>
                    <strong>{{ $mahasiswa->nim }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 12px;">Nama Lengkap</span>
                    <strong>{{ $mahasiswa->nama }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 12px;">Program Studi</span>
                    <span>{{ $mahasiswa->program_studi }}</span>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 12px;">Fakultas / Angkatan</span>
                    <span>{{ $mahasiswa->fakultas }} ({{ $mahasiswa->angkatan }})</span>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 12px;">Semester Berjalan</span>
                    <strong>Semester {{ $mahasiswa->semester_sekarang }}</strong>
                </div>
                <div>
                    <span style="color: var(--text-muted); display: block; font-size: 12px;">Batas Maksimal SKS</span>
                    <strong>{{ $mahasiswa->batas_sks }} SKS (IPS Lalu: {{ $mahasiswa->ips_lalu }})</strong>
                </div>
            </div>
        </div>

        <!-- Card Integrasi Keuangan Realtime (EAI Service Status) -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Status Sinkronisasi Keuangan (EAI)</h2>
                    <p class="card-subtitle">Terhubung langsung ke Total Billing System via REST API</p>
                </div>
                @if($uktStatus['is_paid'])
                    <span class="badge badge-success">UKT LUNAS</span>
                @else
                    <span class="badge badge-danger">UKT BELUM LUNAS</span>
                @endif
            </div>

            @if($uktStatus['is_paid'])
                <div class="alert alert-success" style="margin-bottom: 0;">
                    <strong>Pembayaran UKT Semester {{ $mahasiswa->semester_sekarang }} Terverifikasi.</strong>
                    <p style="font-size: 13px; margin-top: 4px;">
                        Nomor Invoice: <code>{{ $uktStatus['data']['nomor_invoice'] ?? '-' }}</code> |
                        Nominal: <strong>Rp {{ number_format($uktStatus['data']['nominal'] ?? 0, 0, ',', '.') }}</strong> |
                        Waktu Bayar: {{ $uktStatus['data']['tanggal_bayar'] ?? '-' }}
                    </p>
                    <div style="margin-top: 14px;">
                        <a href="{{ route('krs.index') }}" class="btn btn-primary" style="font-size: 13px; min-height: 38px; padding: 6px 16px;">
                            Buka Menu Pengisian KRS &rarr;
                        </a>
                    </div>
                </div>
            @else
                <div class="alert alert-warning" style="margin-bottom: 0;">
                    <strong>Perhatian: Pembayaran UKT Semester {{ $mahasiswa->semester_sekarang }} Belum Dilunasi!</strong>
                    <p style="font-size: 13px; margin-top: 4px;">
                        Berdasarkan validasi EAI dengan Total Billing System, tagihan UKT Anda belum terbayar. Pengisian KRS ditutup sampai pembayaran diverifikasi.
                    </p>
                    @if(isset($uktStatus['data']['nomor_va']))
                    <div style="background: #ffffff; padding: 12px; border-radius: 6px; margin: 12px 0; border: 1px dashed var(--warning-border);">
                        <div style="font-size: 12px; color: var(--text-muted);">Kode Virtual Account Pembayaran UKT:</div>
                        <div style="font-size: 18px; font-weight: 700; color: #b45309; letter-spacing: 0.05em;">
                            {{ $uktStatus['data']['nomor_va'] }}
                        </div>
                        <div style="font-size: 13px; margin-top: 4px;">
                            Nominal: <strong>Rp {{ number_format($uktStatus['data']['nominal'] ?? 0, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @endif
                    <div style="display: flex; gap: 8px; margin-top: 12px;">
                        <a href="{{ route('krs.index') }}" class="btn btn-outline" style="font-size: 13px; min-height: 38px; padding: 6px 14px;">
                            Cek Rincian Tagihan di Menu KRS
                        </a>
                        <a href="http://localhost:8001" target="_blank" class="btn btn-primary" style="font-size: 13px; min-height: 38px; padding: 6px 14px; background:#0284c7;">
                            Buka Billing Simulator untuk Bayar &rarr;
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Sisi Kanan: Status KRS & Wisuda -->
    <div>
        <!-- Card Ringkasan KRS -->
        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 15px; font-weight: 700;">Status KRS Semester Ini</h3>
                @if($krsAktif)
                    <span class="badge badge-success">DISETUJUI</span>
                @else
                    <span class="badge badge-secondary">BELUM ISI</span>
                @endif
            </div>

            @if($krsAktif)
                <p style="font-size: 13px; margin-bottom: 8px;">
                    Total SKS Terambil: <strong>{{ $krsAktif->total_sks }} SKS</strong>
                </p>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 14px;">
                    Mata Kuliah: {{ $krsAktif->details->count() }} mata kuliah terdaftar.
                </p>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('krs.index') }}" class="btn btn-outline" style="flex: 1; font-size: 13px; min-height: 38px;">Lihat KRS</a>
                    <a href="{{ route('krs.cetak') }}" target="_blank" class="btn btn-primary" style="font-size: 13px; min-height: 38px;">Cetak Draft</a>
                </div>
            @else
                <div style="padding: 16px 0; text-align: center; color: var(--text-muted);">
                    <p style="font-size: 13px;">Belum ada Kartu Rencana Studi tersimpan untuk semester ini.</p>
                    <a href="{{ route('krs.index') }}" class="btn btn-outline" style="margin-top: 12px; font-size: 13px; min-height: 38px;">
                        Akses Pengisian KRS
                    </a>
                </div>
            @endif
        </div>

        <!-- Card Persiapan Wisuda -->
        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 15px; font-weight: 700;">Status Wisuda</h3>
                @if($mahasiswa->status_skripsi === 'lulus')
                    <span class="badge badge-success">SKRIPSI LULUS</span>
                @else
                    <span class="badge badge-secondary">{{ strtoupper($mahasiswa->status_skripsi) }}</span>
                @endif
            </div>

            <div style="font-size: 13px; margin-bottom: 14px;">
                <div style="margin-bottom: 6px; display: flex; justify-content: space-between;">
                    <span>Ujian Skripsi:</span>
                    <strong>{{ strtoupper($mahasiswa->status_skripsi) }}</strong>
                </div>
                <div style="margin-bottom: 6px; display: flex; justify-content: space-between;">
                    <span>Bebas Lab:</span>
                    <span>{!! $mahasiswa->bebas_lab ? '<span style="color:var(--success)">Lengkap</span>' : '<span style="color:var(--danger)">Belum</span>' !!}</span>
                </div>
                <div style="margin-bottom: 6px; display: flex; justify-content: space-between;">
                    <span>Bebas Pustaka:</span>
                    <span>{!! $mahasiswa->bebas_perpustakaan ? '<span style="color:var(--success)">Lengkap</span>' : '<span style="color:var(--danger)">Belum</span>' !!}</span>
                </div>
            </div>

            <a href="{{ route('wisuda.index') }}" class="btn btn-outline" style="width: 100%; font-size: 13px; min-height: 38px;">
                Akses Pendaftaran Wisuda &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
