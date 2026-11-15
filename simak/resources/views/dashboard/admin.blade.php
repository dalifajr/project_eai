@extends('layouts.app')

@section('title', 'Admin Dashboard Overview')
@section('page_title', 'Dashboard Overview')
@section('page_subtitle', 'Monitoring Sistem Informasi Akademik & EAI Node')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- Admin Hero Greeting --}}
    <div class="m3-hero-card">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="badge-m3 badge-m3-primary" style="margin-bottom: 8px;">
                    <i class="fas fa-shield-halved me-1"></i> SIMAK CORE CONTROLLER
                </span>
                <h1 style="font-family: var(--font-display); font-size: 1.55rem; font-weight: 700; color: var(--on-surface); margin: 4px 0 8px 0;">
                    Panel Kendali Administrator
                </h1>
                <p style="margin: 0; color: var(--on-surface-variant); font-size: 0.9rem; line-height: 1.5;">
                    Sistem siap melayani transaksi akademik dan sinkronisasi realtime dengan <strong>Total Billing System (Port 8001)</strong>.
                </p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('admin.mahasiswa') }}" class="btn-m3 btn-m3-primary">
                    <i class="fas fa-users-gear"></i>
                    <span>Kelola Mahasiswa</span>
                </a>
                <a href="{{ route('admin.matakuliah') }}" class="btn-m3 btn-m3-tonal">
                    <i class="fas fa-book-bookmark"></i>
                    <span>Katalog MK</span>
                </a>
            </div>
        </div>
    </div>

    {{-- 4 Metric Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
        <div class="m3-card" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--on-surface-variant); text-transform: uppercase;">Total Mahasiswa</span>
                    <div style="font-family: var(--font-display); font-size: 2rem; font-weight: 700; color: var(--primary); margin-top: 4px;">
                        {{ $totalMahasiswa }}
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: var(--radius-sm); background: var(--primary-container); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
            <div style="margin-top: 12px; font-size: 0.8rem; color: var(--on-surface-variant);">
                Mahasiswa aktif terdaftar di sistem
            </div>
        </div>

        <div class="m3-card" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--on-surface-variant); text-transform: uppercase;">Mata Kuliah</span>
                    <div style="font-family: var(--font-display); font-size: 2rem; font-weight: 700; color: var(--secondary); margin-top: 4px;">
                        {{ $totalMataKuliah }}
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: var(--radius-sm); background: var(--secondary-container); color: var(--on-secondary-container); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fas fa-book-bookmark"></i>
                </div>
            </div>
            <div style="margin-top: 12px; font-size: 0.8rem; color: var(--on-surface-variant);">
                Ditawarkan semester ini
            </div>
        </div>

        <div class="m3-card" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--on-surface-variant); text-transform: uppercase;">Pengajuan KRS</span>
                    <div style="font-family: var(--font-display); font-size: 2rem; font-weight: 700; color: var(--tertiary); margin-top: 4px;">
                        {{ $totalKrs }}
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: var(--radius-sm); background: var(--tertiary-container); color: var(--on-tertiary-container); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fas fa-file-signature"></i>
                </div>
            </div>
            <div style="margin-top: 12px; font-size: 0.8rem; color: var(--on-surface-variant);">
                Rencana studi tersimpan
            </div>
        </div>

        <div class="m3-card" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--on-surface-variant); text-transform: uppercase;">Pendaftar Wisuda</span>
                    <div style="font-family: var(--font-display); font-size: 2rem; font-weight: 700; color: var(--info); margin-top: 4px;">
                        {{ $totalWisuda }}
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: var(--radius-sm); background: var(--info-container); color: var(--on-info-container); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fas fa-award"></i>
                </div>
            </div>
            <div style="margin-top: 12px; font-size: 0.8rem; color: var(--on-surface-variant);">
                Calon wisudawan terdata
            </div>
        </div>
    </div>

    {{-- Dedicated Panels Grid --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 20px;">
        
        {{-- Panel 1: Pengujian Cepat & Akses Mahasiswa --}}
        <div class="m3-card" style="display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: var(--on-surface); display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-users-gear text-primary"></i>
                    <span>Pengujian Akses Mahasiswa (Quick Switch)</span>
                </h3>
                <a href="{{ route('admin.mahasiswa') }}" class="btn-m3 btn-m3-tonal" style="font-size: 0.78rem; height: 32px; padding: 0 10px;">
                    <span>Lihat Semua</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <p style="font-size: 0.85rem; color: var(--on-surface-variant); margin: 0 0 16px 0;">
                Klik tombol <em>Login As</em> di samping nama mahasiswa untuk mensimulasikan portal dari perspektif mahasiswa yang bersangkutan:
            </p>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($allMahasiswa->take(4) as $mhs)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                        <div>
                            <strong style="color: var(--on-surface); font-size: 0.875rem;">{{ $mhs->nama }}</strong>
                            <div style="font-family: var(--font-mono); font-size: 0.75rem; color: var(--on-surface-variant);">
                                NIM: {{ $mhs->nim }} &bull; Sem {{ $mhs->semester_sekarang }}
                            </div>
                        </div>
                        @if($mhs->user)
                            <a href="{{ route('quick.login', $mhs->user->username) }}" class="btn-m3 btn-m3-tonal" style="font-size: 0.75rem; height: 30px; padding: 0 10px;">
                                <i class="fas fa-right-to-bracket"></i>
                                <span>Login As</span>
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 16px;">
                <a href="{{ route('admin.mahasiswa') }}" class="btn-m3 btn-m3-primary" style="width: 100%;">
                    <i class="fas fa-table-list"></i>
                    <span>Buka Halaman Data Mahasiswa & Uji EAI</span>
                </a>
            </div>
        </div>

        {{-- Panel 2: Status Arsitektur Integrasi EAI --}}
        <div class="m3-card" style="display: flex; flex-direction: column;">
            <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0 0 16px 0; color: var(--on-surface); display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-network-wired text-primary"></i>
                <span>Arsitektur & Health-Check Node EAI</span>
            </h3>

            <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                    <div>
                        <strong style="font-size: 0.875rem; color: var(--on-surface);">SIMAK Universe (Core)</strong>
                        <div style="font-size: 0.75rem; color: var(--on-surface-variant);">Academic System & Identity Provider</div>
                    </div>
                    <span class="badge-m3 badge-m3-success">PORT 8000</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                    <div>
                        <strong style="font-size: 0.875rem; color: var(--on-surface);">Total Billing System</strong>
                        <div style="font-size: 0.75rem; color: var(--on-surface-variant);">Payment Gateway, Invoicing & Webhook Issuer</div>
                    </div>
                    <span class="badge-m3 badge-m3-info">PORT 8001</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                    <div>
                        <strong style="font-size: 0.875rem; color: var(--on-surface);">Protokol Integrasi EAI</strong>
                        <div style="font-size: 0.75rem; color: var(--on-surface-variant);">REST API Sync & Webhook HMAC SHA-256</div>
                    </div>
                    <span class="badge-m3 badge-m3-neutral">HTTP / JSON</span>
                </div>
            </div>

            <div style="background: var(--surface-container-high); border-radius: var(--radius-md); padding: 14px; margin-top: auto; border: 1px dashed var(--outline-variant);">
                <div style="font-size: 0.8rem; color: var(--on-surface-variant); display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-circle-check text-success"></i>
                    <span>Koneksi loopback terkonfigurasi. Endpoint webhook aktif pada: <code>/api/v1/webhook/pembayaran-wisuda</code></span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
