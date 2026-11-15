@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')
@section('page_title', 'Dashboard Akademik')
@section('page_subtitle', 'Selamat datang di Portal SIMAK Universe')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- Welcome Hero Greeting --}}
    <div class="m3-hero-card">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="max-width: 680px;">
                <span class="badge-m3 badge-m3-neutral" style="margin-bottom: 8px;">
                    <i class="fas fa-university me-1"></i> TAHUN AKADEMIK 2026/2027 GANJIL
                </span>
                <h1 style="font-family: var(--font-display); font-size: 1.55rem; font-weight: 700; margin: 4px 0 8px 0; color: var(--on-surface);">
                    Halo, {{ $mahasiswa->nama }}! 👋
                </h1>
                <p style="margin: 0; color: var(--on-surface-variant); font-size: 0.9rem; line-height: 1.5;">
                    {{ $mahasiswa->program_studi }} — {{ $mahasiswa->fakultas }}. Status Akademik saat ini adalah
                    <strong style="color: var(--primary);">{{ strtoupper($mahasiswa->status_akademik) }}</strong>.
                </p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('profil.index') }}" class="btn-m3 btn-m3-tonal">
                    <i class="fas fa-id-card"></i>
                    <span>Profil Lengkap</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Academic KPI Counters --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        {{-- NIM & Semester --}}
        <div class="m3-card" style="padding: 18px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 600; color: var(--on-surface-variant); text-transform: uppercase;">Identitas / NIM</span>
                    <div style="font-family: var(--font-mono); font-size: 1.25rem; font-weight: 700; color: var(--on-surface); margin-top: 4px;">
                        {{ $mahasiswa->nim }}
                    </div>
                </div>
                <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--secondary-container); color: var(--on-secondary-container); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fas fa-fingerprint"></i>
                </div>
            </div>
            <div style="margin-top: 10px; font-size: 0.8rem; color: var(--on-surface-variant);">
                Semester Aktif: <strong>Semester {{ $mahasiswa->semester_sekarang }}</strong>
            </div>
        </div>

        {{-- IPS Terakhir --}}
        <div class="m3-card" style="padding: 18px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 600; color: var(--on-surface-variant); text-transform: uppercase;">IPS Terakhir</span>
                    <div style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; color: var(--primary); margin-top: 4px;">
                        {{ number_format($mahasiswa->ips_lalu, 2) }}
                    </div>
                </div>
                <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--primary-container); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div style="margin-top: 10px; font-size: 0.8rem; color: var(--on-surface-variant);">
                Batas Beban SKS: <strong>{{ $mahasiswa->batas_sks }} SKS</strong>
            </div>
        </div>

        {{-- Status UKT Card --}}
        <div class="m3-card" style="padding: 18px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 600; color: var(--on-surface-variant); text-transform: uppercase;">Status UKT (EAI)</span>
                    <div style="margin-top: 6px;">
                        @if(($uktStatus['status_pembayaran'] ?? 'UNPAID') === 'PAID')
                            <span class="badge-m3 badge-m3-success" style="font-size: 0.85rem; padding: 4px 12px;">
                                <i class="fas fa-circle-check me-1"></i> LUNAS
                            </span>
                        @else
                            <span class="badge-m3 badge-m3-error" style="font-size: 0.85rem; padding: 4px 12px;">
                                <i class="fas fa-circle-exclamation me-1"></i> BELUM LUNAS
                            </span>
                        @endif
                    </div>
                </div>
                <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--tertiary-container); color: var(--on-tertiary-container); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
            <div style="margin-top: 10px; font-size: 0.8rem; color: var(--on-surface-variant);">
                Rp {{ number_format($uktStatus['jumlah_tagihan'] ?? 5000000, 0, ',', '.') }}
            </div>
        </div>

        {{-- Status KRS Card --}}
        <div class="m3-card" style="padding: 18px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 600; color: var(--on-surface-variant); text-transform: uppercase;">Status KRS</span>
                    <div style="margin-top: 6px;">
                        @if($krsAktif)
                            <span class="badge-m3 badge-m3-info" style="font-size: 0.85rem; padding: 4px 12px;">
                                <i class="fas fa-check me-1"></i> {{ $krsAktif->total_sks }} SKS DIAMBIL
                            </span>
                        @else
                            <span class="badge-m3 badge-m3-warning" style="font-size: 0.85rem; padding: 4px 12px;">
                                <i class="fas fa-clock me-1"></i> BELUM MENGISI
                            </span>
                        @endif
                    </div>
                </div>
                <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--secondary-container); color: var(--on-secondary-container); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fas fa-book-open"></i>
                </div>
            </div>
            <div style="margin-top: 10px; font-size: 0.8rem; color: var(--on-surface-variant);">
                Semester {{ $mahasiswa->semester_sekarang }}
            </div>
        </div>
    </div>

    {{-- Feature Summary Cards (Divided into Clean Panels) --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 20px;">
        
        {{-- Panel 1: Status Keuangan & UKT Quick Access --}}
        <div class="m3-card" style="display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: var(--radius-sm); background: var(--primary-container); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: var(--on-surface);">Keuangan & UKT</h3>
                        <small style="color: var(--on-surface-variant);">Integrasi Realtime EAI Billing</small>
                    </div>
                </div>
                <a href="{{ route('keuangan.index') }}" class="btn-m3 btn-m3-tonal" style="font-size: 0.78rem; height: 34px; padding: 0 12px;">
                    <span>Buka Menu</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div style="background: var(--surface-container); border-radius: var(--radius-md); padding: 16px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 0.85rem; color: var(--on-surface-variant);">Nomor Virtual Account:</span>
                    <span style="font-family: var(--font-mono); font-weight: 700; color: var(--on-surface);">{{ $uktStatus['nomor_va'] ?? '88019'.$mahasiswa->nim }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 0.85rem; color: var(--on-surface-variant);">Nominal Tagihan:</span>
                    <strong style="color: var(--on-surface);">Rp {{ number_format($uktStatus['jumlah_tagihan'] ?? 5000000, 0, ',', '.') }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.85rem; color: var(--on-surface-variant);">Status Validasi:</span>
                    @if(($uktStatus['status_pembayaran'] ?? 'UNPAID') === 'PAID')
                        <span class="badge-m3 badge-m3-success"><i class="fas fa-check-circle me-1"></i> LUNAS</span>
                    @else
                        <span class="badge-m3 badge-m3-error"><i class="fas fa-times-circle me-1"></i> MENUNGGU PEMBAYARAN</span>
                    @endif
                </div>
            </div>

            <div style="margin-top: auto;">
                @if(($uktStatus['status_pembayaran'] ?? 'UNPAID') !== 'PAID')
                    <p style="font-size: 0.8rem; color: var(--on-surface-variant); margin-bottom: 12px;">
                        <i class="fas fa-circle-info text-warning me-1"></i> Bayar tagihan via Simulator di Total Billing System (Port 8001) untuk membuka hak pengisian KRS.
                    </p>
                @endif
                <a href="{{ route('keuangan.index') }}" class="btn-m3 btn-m3-primary" style="width: 100%;">
                    <i class="fas fa-credit-card"></i>
                    <span>Rincian Pembayaran & Bukti</span>
                </a>
            </div>
        </div>

        {{-- Panel 2: Pengisian KRS Quick Access --}}
        <div class="m3-card" style="display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: var(--radius-sm); background: var(--secondary-container); color: var(--on-secondary-container); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: var(--on-surface);">Rencana Studi (KRS)</h3>
                        <small style="color: var(--on-surface-variant);">Semester {{ $mahasiswa->semester_sekarang }}</small>
                    </div>
                </div>
                <a href="{{ route('krs.index') }}" class="btn-m3 btn-m3-tonal" style="font-size: 0.78rem; height: 34px; padding: 0 12px;">
                    <span>Buka Menu</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div style="background: var(--surface-container); border-radius: var(--radius-md); padding: 16px; margin-bottom: 16px;">
                @if($krsAktif && $krsAktif->details->count() > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 0.85rem; color: var(--on-surface-variant);">Mata Kuliah Terpilih:</span>
                        <strong style="color: var(--on-surface);">{{ $krsAktif->details->count() }} Mata Kuliah</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 0.85rem; color: var(--on-surface-variant);">Total Beban SKS:</span>
                        <span class="badge-m3 badge-m3-info">{{ $krsAktif->total_sks }} / {{ $mahasiswa->batas_sks }} SKS</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; color: var(--on-surface-variant);">Status Pengajuan:</span>
                        <span class="badge-m3 badge-m3-success"><i class="fas fa-check me-1"></i> Disetujui Dosen PA</span>
                    </div>
                @else
                    <div style="text-align: center; padding: 12px 0;">
                        <i class="fas fa-folder-open" style="font-size: 2rem; color: var(--outline-variant); margin-bottom: 8px;"></i>
                        <p style="font-size: 0.85rem; color: var(--on-surface-variant); margin: 0;">
                            Belum ada rencana studi tersimpan untuk semester ini.
                        </p>
                    </div>
                @endif
            </div>

            <div style="margin-top: auto; display: flex; gap: 10px;">
                <a href="{{ route('krs.index') }}" class="btn-m3 btn-m3-primary" style="flex: 1;">
                    <i class="fas fa-pen-to-square"></i>
                    <span>{{ $krsAktif ? 'Kelola KRS' : 'Isi KRS Sekarang' }}</span>
                </a>
                @if($krsAktif)
                    <a href="{{ route('krs.cetak') }}" target="_blank" class="btn-m3 btn-m3-tonal" title="Cetak KRS PDF">
                        <i class="fas fa-print"></i>
                    </a>
                @endif
            </div>
        </div>

        {{-- Panel 3: Pendaftaran Wisuda Quick Access --}}
        <div class="m3-card" style="display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: var(--radius-sm); background: var(--tertiary-container); color: var(--on-tertiary-container); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: var(--on-surface);">Pendaftaran Wisuda</h3>
                        <small style="color: var(--on-surface-variant);">Kelulusan & Webhook EAI</small>
                    </div>
                </div>
                <a href="{{ route('wisuda.index') }}" class="btn-m3 btn-m3-tonal" style="font-size: 0.78rem; height: 34px; padding: 0 12px;">
                    <span>Buka Menu</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div style="background: var(--surface-container); border-radius: var(--radius-md); padding: 16px; margin-bottom: 16px;">
                @if($wisuda)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 0.85rem; color: var(--on-surface-variant);">No. Pendaftaran:</span>
                        <span style="font-family: var(--font-mono); font-weight: 600; color: var(--on-surface);">{{ $wisuda->nomor_pendaftaran }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 0.85rem; color: var(--on-surface-variant);">Status Pendaftaran:</span>
                        <span class="badge-m3 badge-m3-primary">{{ strtoupper($wisuda->status_pendaftaran) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.85rem; color: var(--on-surface-variant);">Status UKT Wisuda:</span>
                        @if($wisuda->status_pembayaran_wisuda === 'PAID')
                            <span class="badge-m3 badge-m3-success"><i class="fas fa-check-circle me-1"></i> PAID</span>
                        @else
                            <span class="badge-m3 badge-m3-warning"><i class="fas fa-clock me-1"></i> UNPAID</span>
                        @endif
                    </div>
                @else
                    <div style="text-align: center; padding: 12px 0;">
                        <i class="fas fa-user-graduate" style="font-size: 2rem; color: var(--outline-variant); margin-bottom: 8px;"></i>
                        <p style="font-size: 0.85rem; color: var(--on-surface-variant); margin: 0;">
                            Pendaftaran Wisuda dibuka untuk mahasiswa semester akhir.
                        </p>
                    </div>
                @endif
            </div>

            <div style="margin-top: auto; display: flex; gap: 10px;">
                <a href="{{ route('wisuda.index') }}" class="btn-m3 btn-m3-primary" style="flex: 1;">
                    <i class="fas fa-user-graduate"></i>
                    <span>{{ $wisuda ? 'Lihat Status Wisuda' : 'Daftar Wisuda' }}</span>
                </a>
                @if($wisuda && $wisuda->status_pembayaran_wisuda === 'PAID')
                    <a href="{{ route('wisuda.cetak') }}" target="_blank" class="btn-m3 btn-m3-tonal" title="Cetak Bukti Wisuda">
                        <i class="fas fa-print"></i>
                    </a>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
