@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<!-- M3 Hero Greeting Banner (28px Squircle Radius) -->
<div class="m3-hero-card" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <span class="m3-badge-pill" style="background: rgba(255,255,255,0.25); color: currentColor; margin-bottom: 8px;">
            PORTAL MAHASISWA &bull; EAI LIVE
        </span>
        <h1 style="font-size: 26px; font-weight: 700; color: inherit; letter-spacing: -0.02em;">
            Selamat Datang, {{ $mahasiswa->nama }}
        </h1>
        <p style="font-size: 13.5px; opacity: 0.9; margin-top: 4px;">
            Semester {{ $mahasiswa->semester_sekarang }} &bull; Tahun Akademik 2026/2027 &bull; Terintegrasi Realtime ke Total Billing System
        </p>
    </div>
    <div>
        <span class="m3-badge-pill {{ $mahasiswa->status_akademik === 'aktif' ? 'success' : 'danger' }}" style="padding: 8px 18px; font-size: 12.5px;">
            Status: {{ strtoupper($mahasiswa->status_akademik) }}
        </span>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1.6fr 1.1fr; gap: 24px; align-items: start;">
    <!-- Sisi Kiri: Profil & Status Integrasi Keuangan EAI -->
    <div>
        <!-- Modern Student Profile Card (16px Squircle dengan 12px Inner Blocks) -->
        <div class="m3-card">
            <div class="m3-card-header">
                <div>
                    <h2 class="m3-card-title">Identitas & Status Akademik</h2>
                    <p class="m3-card-subtitle">Informasi pangkalan data pendidikan tinggi terverifikasi</p>
                </div>
                <span class="m3-badge-pill neutral" style="font-family: var(--font-mono);">NIM: {{ $mahasiswa->nim }}</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px;">
                <div class="m3-inner-block">
                    <span style="font-size: 11px; color: var(--on-surface-variant); font-weight: 600; text-transform: uppercase;">Program Studi</span>
                    <div style="font-size: 14.5px; font-weight: 700; color: var(--on-surface); margin-top: 3px;">{{ $mahasiswa->program_studi }}</div>
                </div>

                <div class="m3-inner-block">
                    <span style="font-size: 11px; color: var(--on-surface-variant); font-weight: 600; text-transform: uppercase;">Fakultas & Angkatan</span>
                    <div style="font-size: 14.5px; font-weight: 700; color: var(--on-surface); margin-top: 3px;">{{ $mahasiswa->fakultas }} ({{ $mahasiswa->angkatan }})</div>
                </div>

                <div class="m3-inner-block">
                    <span style="font-size: 11px; color: var(--on-surface-variant); font-weight: 600; text-transform: uppercase;">Semester Berjalan</span>
                    <div style="font-size: 14.5px; font-weight: 700; color: var(--on-surface); margin-top: 3px;">Semester {{ $mahasiswa->semester_sekarang }}</div>
                </div>

                <div class="m3-inner-block">
                    <span style="font-size: 11px; color: var(--on-surface-variant); font-weight: 600; text-transform: uppercase;">Batas Beban SKS</span>
                    <div style="font-size: 14.5px; font-weight: 700; color: var(--primary); margin-top: 3px;">{{ $mahasiswa->batas_sks }} SKS (IPS: {{ $mahasiswa->ips_lalu }})</div>
                </div>
            </div>
        </div>

        <!-- EAI Realtime Financial Integration Card -->
        <div class="m3-card" style="border: 1px solid {{ $uktStatus['is_paid'] ? 'var(--tertiary)' : 'var(--warning)' }};">
            <div class="m3-card-header" style="border-bottom-color: var(--outline-variant);">
                <div>
                    <span class="m3-badge-pill primary" style="font-size: 10px; margin-bottom: 4px;">EAI LIVE VALIDATION</span>
                    <h2 class="m3-card-title">Status Tagihan UKT (Total Billing System)</h2>
                    <p class="m3-card-subtitle">Sinkronisasi status tagihan kuliah via REST API EAI</p>
                </div>
                @if($uktStatus['is_paid'])
                    <span class="m3-badge-pill success">UKT SUDAH LUNAS</span>
                @else
                    <span class="m3-badge-pill danger">UKT BELUM LUNAS</span>
                @endif
            </div>

            @if($uktStatus['is_paid'])
                <!-- KONDISI UKT LUNAS: M3 Tertiary Container -->
                <div style="background: var(--tertiary-container); border: 1px solid transparent; border-radius: var(--radius-sm); padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--tertiary); color: var(--on-tertiary); display: inline-flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700;">✓</span>
                        <strong style="font-size: 15px; color: var(--on-tertiary-container);">Pembayaran UKT Semester {{ $mahasiswa->semester_sekarang }} Terverifikasi Sah</strong>
                    </div>
                    <p style="font-size: 13.5px; color: var(--on-tertiary-container); opacity: 0.9; margin-left: 40px;">
                        Data pembayaran telah tersinkronisasi dari Total Billing System. Hak akses pengisian Kartu Rencana Studi (KRS) telah aktif.
                    </p>

                    <div style="margin: 14px 0 0 40px; font-size: 12.5px; color: var(--on-tertiary-container); display: flex; flex-wrap: wrap; gap: 16px;">
                        <span>Invoice: <code style="background: rgba(0,0,0,0.06); border-radius: 4px; padding: 2px 6px;">{{ $uktStatus['data']['nomor_invoice'] ?? '-' }}</code></span>
                        <span>Nominal: <strong>Rp {{ number_format($uktStatus['data']['nominal'] ?? 0, 0, ',', '.') }}</strong></span>
                        <span>Waktu Bayar: <strong>{{ $uktStatus['data']['tanggal_bayar'] ?? '-' }}</strong></span>
                    </div>

                    <div style="margin-top: 18px; margin-left: 40px;">
                        <a href="{{ route('krs.index') }}" class="btn-modern primary" style="font-size: 13px;">
                            Buka Form Pengisian KRS &rarr;
                        </a>
                    </div>
                </div>
            @else
                <!-- KONDISI UKT BELUM LUNAS: M3 Warning Container -->
                <div style="background: var(--warning-container); border: 1px solid transparent; border-radius: var(--radius-sm); padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--warning); color: var(--on-warning); display: inline-flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700;">!</span>
                        <strong style="font-size: 15px; color: var(--on-warning-container);">Akses KRS Terkunci: Tagihan UKT Belum Dilunasi</strong>
                    </div>
                    <p style="font-size: 13.5px; color: var(--on-warning-container); opacity: 0.9; margin-left: 40px;">
                        Validasi EAI mendeteksi tagihan UKT Semester {{ $mahasiswa->semester_sekarang }} belum dibayar. Sesuai aturan akademik, pengisian KRS tertutup sampai ada verifikasi pelunasan dari Total Billing System.
                    </p>

                    @if(isset($uktStatus['data']['nomor_va']))
                    <div style="margin: 16px 0 0 40px; background: var(--surface-container-lowest); border: 1px solid var(--outline-variant); border-radius: var(--radius-sm); padding: 18px; max-width: 480px;">
                        <span style="font-size: 11px; font-weight: 600; color: var(--on-surface-variant); text-transform: uppercase;">Nomor Virtual Account Pembayaran:</span>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 6px;">
                            <span id="va-number-text" style="font-family: var(--font-mono); font-size: 22px; font-weight: 700; color: var(--primary); letter-spacing: 0.04em;">
                                {{ $uktStatus['data']['nomor_va'] }}
                            </span>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $uktStatus['data']['nomor_va'] }}'); alert('Nomor VA berhasil disalin!');" class="btn-modern secondary sm" style="font-size: 12px;">
                                Salin VA
                            </button>
                        </div>
                        <div style="margin-top: 10px; font-size: 13px; color: var(--on-surface); display: flex; justify-content: space-between; border-top: 1px dashed var(--outline-variant); padding-top: 8px;">
                            <span>Nominal Tagihan:</span>
                            <strong style="font-size: 14px;">Rp {{ number_format($uktStatus['data']['nominal'] ?? 0, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @endif

                    <div style="margin-top: 18px; margin-left: 40px; display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="http://localhost:8001" target="_blank" class="btn-modern primary" style="font-size: 13px;">
                            Buka Billing Simulator untuk Bayar &rarr;
                        </a>
                        <button onclick="location.reload()" class="btn-modern secondary sm" style="font-size: 12px;">
                            Refresh Status Pembayaran
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Sisi Kanan: Status KRS & Wisuda Widgets -->
    <div>
        <!-- KRS Status Widget (16px Card) -->
        <div class="m3-card">
            <div class="m3-card-header">
                <div>
                    <h3 class="m3-card-title">Status KRS Semester Ini</h3>
                    <p class="m3-card-subtitle">Kartu Rencana Studi Semester {{ $mahasiswa->semester_sekarang }}</p>
                </div>
                @if($krsAktif)
                    <span class="m3-badge-pill success">DISETUJUI</span>
                @else
                    <span class="m3-badge-pill neutral">BELUM MENGISI</span>
                @endif
            </div>

            @if($krsAktif)
                <div class="m3-inner-block" style="margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="font-size: 12px; color: var(--on-surface-variant);">Beban SKS Diambil:</span>
                        <strong style="font-size: 15px; color: var(--primary);">{{ $krsAktif->total_sks }} / {{ $mahasiswa->batas_sks }} SKS</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: var(--on-surface-variant);">
                        <span>Jumlah Mata Kuliah:</span>
                        <strong>{{ $krsAktif->details->count() }} MK</strong>
                    </div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('krs.index') }}" class="btn-modern secondary" style="flex: 1; font-size: 12.5px;">Lihat Rincian KRS</a>
                    <a href="{{ route('krs.cetak') }}" target="_blank" class="btn-modern primary" style="font-size: 12.5px;">Cetak Draft</a>
                </div>
            @else
                <div style="text-align: center; padding: 18px 0; color: var(--on-surface-variant);">
                    <p style="font-size: 13px;">Belum ada Kartu Rencana Studi tersimpan untuk semester ini.</p>
                    <a href="{{ route('krs.index') }}" class="btn-modern secondary" style="margin-top: 14px; font-size: 12.5px;">
                        Menuju Menu KRS &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- Wisuda Pipeline Widget (16px Card) -->
        <div class="m3-card">
            <div class="m3-card-header">
                <div>
                    <h3 class="m3-card-title">Pemeriksaan Wisuda</h3>
                    <p class="m3-card-subtitle">Kesiapan kelulusan & bebas tanggungan</p>
                </div>
                @if($mahasiswa->status_skripsi === 'lulus')
                    <span class="m3-badge-pill success">SKRIPSI LULUS</span>
                @else
                    <span class="m3-badge-pill neutral">{{ strtoupper($mahasiswa->status_skripsi) }}</span>
                @endif
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px; margin-bottom: 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: var(--surface-container-low); border-radius: var(--radius-sm); border: 1px solid var(--outline-variant);">
                    <span style="color: var(--on-surface-variant);">Sidang Skripsi:</span>
                    <strong>{{ strtoupper($mahasiswa->status_skripsi) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: var(--surface-container-low); border-radius: var(--radius-sm); border: 1px solid var(--outline-variant);">
                    <span style="color: var(--on-surface-variant);">Bebas Lab:</span>
                    <span>{!! $mahasiswa->bebas_lab ? '<span class="m3-badge-pill success">Bebas</span>' : '<span class="m3-badge-pill danger">Belum</span>' !!}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: var(--surface-container-low); border-radius: var(--radius-sm); border: 1px solid var(--outline-variant);">
                    <span style="color: var(--on-surface-variant);">Bebas Pustaka:</span>
                    <span>{!! $mahasiswa->bebas_perpustakaan ? '<span class="m3-badge-pill success">Bebas</span>' : '<span class="m3-badge-pill danger">Belum</span>' !!}</span>
                </div>
            </div>

            <a href="{{ route('wisuda.index') }}" class="btn-modern secondary" style="width: 100%; font-size: 12.5px; justify-content: center;">
                Pendaftaran & Cek Wisuda &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
