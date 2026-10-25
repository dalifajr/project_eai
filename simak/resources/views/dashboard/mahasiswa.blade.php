@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<!-- Header Page Info -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
    <div>
        <span class="badge-pill brand" style="margin-bottom: 6px;">PORTAL MAHASISWA</span>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--text-heading); letter-spacing: -0.02em;">
            Selamat Datang, {{ $mahasiswa->nama }}
        </h1>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">
            Semester {{ $mahasiswa->semester_sekarang }} &bull; Tahun Akademik 2026/2027 &bull; Terhubung ke Total Billing System
        </p>
    </div>
    <div>
        <span class="badge-pill {{ $mahasiswa->status_akademik === 'aktif' ? 'success' : 'danger' }}" style="padding: 6px 14px; font-size: 12px;">
            Status Akademik: {{ strtoupper($mahasiswa->status_akademik) }}
        </span>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1.6fr 1.1fr; gap: 24px; align-items: start;">
    <!-- Sisi Kiri: Profil & Status Integrasi Keuangan EAI -->
    <div>
        <!-- Modern Student Profile Card -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div>
                    <h2 class="card-title-modern">Identitas & Status Akademik</h2>
                    <p class="card-subtitle-modern">Informasi pangkalan data pendidikan tinggi terverifikasi</p>
                </div>
                <span class="badge-pill neutral">NIM: {{ $mahasiswa->nim }}</span>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                <div style="background: var(--bg-subtle); padding: 12px 14px; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Program Studi</span>
                    <div style="font-size: 14px; font-weight: 700; color: var(--text-heading); margin-top: 2px;">{{ $mahasiswa->program_studi }}</div>
                </div>

                <div style="background: var(--bg-subtle); padding: 12px 14px; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Fakultas & Angkatan</span>
                    <div style="font-size: 14px; font-weight: 700; color: var(--text-heading); margin-top: 2px;">{{ $mahasiswa->fakultas }} ({{ $mahasiswa->angkatan }})</div>
                </div>

                <div style="background: var(--bg-subtle); padding: 12px 14px; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Semester Berjalan</span>
                    <div style="font-size: 14px; font-weight: 700; color: var(--text-heading); margin-top: 2px;">Semester {{ $mahasiswa->semester_sekarang }}</div>
                </div>

                <div style="background: var(--bg-subtle); padding: 12px 14px; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Batas Beban SKS</span>
                    <div style="font-size: 14px; font-weight: 700; color: var(--brand-primary); margin-top: 2px;">{{ $mahasiswa->batas_sks }} SKS (IPS: {{ $mahasiswa->ips_lalu }})</div>
                </div>
            </div>
        </div>

        <!-- EAI Realtime Financial Integration Card -->
        <div class="card-modern" style="border: 1px solid {{ $uktStatus['is_paid'] ? '#a7f3d0' : '#fde68a' }}; background: {{ $uktStatus['is_paid'] ? '#ffffff' : '#fffdfa' }};">
            <div class="card-header-modern" style="border-color: {{ $uktStatus['is_paid'] ? '#d1fae5' : '#fef3c7' }};">
                <div>
                    <span class="badge-pill brand" style="font-size: 10px; margin-bottom: 4px;">EAI LIVE VALIDATION</span>
                    <h2 class="card-title-modern">Status Tagihan UKT (Total Billing System)</h2>
                    <p class="card-subtitle-modern">Sinkronisasi status tagihan kuliah via REST API EAI</p>
                </div>
                @if($uktStatus['is_paid'])
                    <span class="badge-pill success">UKT SUDAH LUNAS</span>
                @else
                    <span class="badge-pill danger">UKT BELUM LUNAS</span>
                @endif
            </div>

            @if($uktStatus['is_paid'])
                <!-- KONDISI UKT LUNAS -->
                <div style="background: var(--status-success-bg); border: 1px solid var(--status-success-border); border-radius: var(--radius-md); padding: 18px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <span style="width: 24px; height: 24px; border-radius: 50%; background: #10b981; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700;">✓</span>
                        <strong style="font-size: 15px; color: var(--status-success-text);">Pembayaran UKT Semester {{ $mahasiswa->semester_sekarang }} Terverifikasi Sah</strong>
                    </div>
                    <p style="font-size: 13px; color: #065f46; margin-left: 34px;">
                        Data pembayaran telah tersinkronisasi dari Total Billing System. Hak akses pengisian Kartu Rencana Studi (KRS) telah aktif.
                    </p>

                    <div style="margin: 14px 0 0 34px; font-size: 12px; color: #047857; display: flex; flex-wrap: wrap; gap: 16px;">
                        <span>Invoice: <code>{{ $uktStatus['data']['nomor_invoice'] ?? '-' }}</code></span>
                        <span>Nominal: <strong>Rp {{ number_format($uktStatus['data']['nominal'] ?? 0, 0, ',', '.') }}</strong></span>
                        <span>Waktu Bayar: <strong>{{ $uktStatus['data']['tanggal_bayar'] ?? '-' }}</strong></span>
                    </div>

                    <div style="margin-top: 16px; margin-left: 34px;">
                        <a href="{{ route('krs.index') }}" class="btn-modern primary" style="font-size: 13px;">
                            Buka Form Pengisian KRS &rarr;
                        </a>
                    </div>
                </div>
            @else
                <!-- KONDISI UKT BELUM LUNAS -->
                <div style="background: var(--status-warning-bg); border: 1px solid var(--status-warning-border); border-radius: var(--radius-md); padding: 18px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <span style="font-size: 18px;">🔒</span>
                        <strong style="font-size: 15px; color: var(--status-warning-text);">Akses KRS Terkunci: Tagihan UKT Belum Dilunasi</strong>
                    </div>
                    <p style="font-size: 13px; color: #78350f; margin-left: 28px;">
                        Validasi EAI mendeteksi tagihan UKT Semester {{ $mahasiswa->semester_sekarang }} belum dibayar. Sesuai aturan akademik, pengisian KRS tertutup sampai ada verifikasi pelunasan dari Total Billing System.
                    </p>

                    @if(isset($uktStatus['data']['nomor_va']))
                    <div style="margin: 14px 0 0 28px; background: #ffffff; border: 1px solid #fde68a; border-radius: var(--radius-md); padding: 16px; max-width: 460px;">
                        <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Nomor Virtual Account Pembayaran:</span>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 6px;">
                            <span id="va-number-text" style="font-family: var(--font-mono); font-size: 20px; font-weight: 800; color: var(--brand-primary); letter-spacing: 0.04em;">
                                {{ $uktStatus['data']['nomor_va'] }}
                            </span>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $uktStatus['data']['nomor_va'] }}'); alert('Nomor VA berhasil disalin!');" class="btn-modern secondary sm" style="font-size: 11px;">
                                Salin VA
                            </button>
                        </div>
                        <div style="margin-top: 10px; font-size: 13px; color: var(--text-heading); display: flex; justify-content: space-between; border-top: 1px dashed #e2e8f0; padding-top: 8px;">
                            <span>Nominal Tagihan:</span>
                            <strong>Rp {{ number_format($uktStatus['data']['nominal'] ?? 0, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @endif

                    <div style="margin-top: 16px; margin-left: 28px; display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="http://localhost:8001" target="_blank" class="btn-modern primary" style="font-size: 13px; background: #0f766e;">
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
        <!-- KRS Status Widget -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div>
                    <h3 class="card-title-modern">Status KRS Semester Ini</h3>
                    <p class="card-subtitle-modern">Kartu Rencana Studi Semester {{ $mahasiswa->semester_sekarang }}</p>
                </div>
                @if($krsAktif)
                    <span class="badge-pill success">DISETUJUI</span>
                @else
                    <span class="badge-pill neutral">BELUM MENGISI</span>
                @endif
            </div>

            @if($krsAktif)
                <div style="background: var(--bg-subtle); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--border-subtle); margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="font-size: 12px; color: var(--text-muted);">Beban SKS Diambil:</span>
                        <strong style="font-size: 15px; color: var(--brand-primary);">{{ $krsAktif->total_sks }} / {{ $mahasiswa->batas_sks }} SKS</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: var(--text-muted);">
                        <span>Jumlah Mata Kuliah:</span>
                        <strong>{{ $krsAktif->details->count() }} MK</strong>
                    </div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('krs.index') }}" class="btn-modern secondary" style="flex: 1; font-size: 12px;">Lihat Rincian KRS</a>
                    <a href="{{ route('krs.cetak') }}" target="_blank" class="btn-modern primary" style="font-size: 12px;">Cetak Draft</a>
                </div>
            @else
                <div style="text-align: center; padding: 16px 0; color: var(--text-muted);">
                    <p style="font-size: 13px;">Belum ada Kartu Rencana Studi tersimpan untuk semester ini.</p>
                    <a href="{{ route('krs.index') }}" class="btn-modern secondary" style="margin-top: 12px; font-size: 12px;">
                        Menuju Menu KRS &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- Wisuda Pipeline Widget -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div>
                    <h3 class="card-title-modern">Pemeriksaan Wisuda</h3>
                    <p class="card-subtitle-modern">Kesiapan kelulusan & bebas tanggungan</p>
                </div>
                @if($mahasiswa->status_skripsi === 'lulus')
                    <span class="badge-pill success">SKRIPSI LULUS</span>
                @else
                    <span class="badge-pill neutral">{{ strtoupper($mahasiswa->status_skripsi) }}</span>
                @endif
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; background: var(--bg-subtle); border-radius: var(--radius-sm);">
                    <span style="color: var(--text-muted);">Sidang Skripsi:</span>
                    <strong>{{ strtoupper($mahasiswa->status_skripsi) }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; background: var(--bg-subtle); border-radius: var(--radius-sm);">
                    <span style="color: var(--text-muted);">Bebas Lab:</span>
                    <span>{!! $mahasiswa->bebas_lab ? '<span class="badge-pill success">Bebas</span>' : '<span class="badge-pill danger">Belum</span>' !!}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; background: var(--bg-subtle); border-radius: var(--radius-sm);">
                    <span style="color: var(--text-muted);">Bebas Pustaka:</span>
                    <span>{!! $mahasiswa->bebas_perpustakaan ? '<span class="badge-pill success">Bebas</span>' : '<span class="badge-pill danger">Belum</span>' !!}</span>
                </div>
            </div>

            <a href="{{ route('wisuda.index') }}" class="btn-modern secondary" style="width: 100%; font-size: 12px;">
                Pendaftaran & Cek Wisuda &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
