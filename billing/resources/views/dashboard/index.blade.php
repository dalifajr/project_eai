@extends('layouts.app')

@section('title', 'Overview Finansial — Total Billing System')
@section('page_title', 'Overview Finansial')
@section('page_subtitle', 'Monitoring Pembayaran & Invoicing Gateway Enterprise')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- Hero Greeting --}}
    <div class="m3-hero-card" style="background: linear-gradient(135deg, var(--surface-container-high) 0%, var(--surface-container-lowest) 100%);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="max-width: 680px;">
                <span class="badge-m3 badge-m3-primary" style="margin-bottom: 8px;">
                    <i class="fas fa-landmark me-1"></i> ENTERPRISE PAYMENT GATEWAY
                </span>
                <h1 style="font-family: var(--font-display); font-size: 1.55rem; font-weight: 700; color: var(--on-surface); margin: 4px 0 8px 0;">
                    Ringkasan Transaksi & Gateway Finansial
                </h1>
                <p style="margin: 0; color: var(--on-surface-variant); font-size: 0.9rem; line-height: 1.5;">
                    Sistem melayani sinkronisasi status pelunasan UKT dan dispatch webhook pendaftaran wisuda ke <strong>SIMAK Universe (Port 8000)</strong> secara realtime.
                </p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('billing.simulator') }}" class="btn-m3 btn-m3-primary">
                    <i class="fas fa-building-columns"></i>
                    <span>Buka Simulator Bank</span>
                </a>
                <a href="{{ route('billing.invoices') }}" class="btn-m3 btn-m3-tonal">
                    <i class="fas fa-receipt"></i>
                    <span>Semua Tagihan</span>
                </a>
            </div>
        </div>
    </div>

    {{-- 4 KPI Metric Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 16px;">
        {{-- Total Penerimaan (Paid) --}}
        <div class="m3-card" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--on-surface-variant); text-transform: uppercase;">Total Penerimaan (Paid)</span>
                    <div style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; color: #146c2e; margin-top: 4px;">
                        Rp {{ number_format($totalNominalPaid, 0, ',', '.') }}
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: var(--radius-sm); background: var(--tertiary-container); color: var(--on-tertiary-container); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fas fa-vault"></i>
                </div>
            </div>
            <div style="margin-top: 12px; font-size: 0.8rem; color: var(--on-surface-variant);">
                <strong>{{ $paidCount }}</strong> tagihan lunas terverifikasi
            </div>
        </div>

        {{-- Tagihan Tertunda (Unpaid) --}}
        <div class="m3-card" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--on-surface-variant); text-transform: uppercase;">Tagihan Tertunda (Unpaid)</span>
                    <div style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; color: var(--error); margin-top: 4px;">
                        Rp {{ number_format($totalNominalUnpaid, 0, ',', '.') }}
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: var(--radius-sm); background: var(--error-container); color: var(--on-error-container); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div style="margin-top: 12px; font-size: 0.8rem; color: var(--on-surface-variant);">
                <strong>{{ $unpaidCount }}</strong> tagihan menanti pelunasan
            </div>
        </div>

        {{-- Total Tagihan Terbit --}}
        <div class="m3-card" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--on-surface-variant); text-transform: uppercase;">Total Tagihan Terbit</span>
                    <div style="font-family: var(--font-display); font-size: 1.8rem; font-weight: 700; color: var(--primary); margin-top: 4px;">
                        {{ $totalTagihanCount }}
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: var(--radius-sm); background: var(--primary-container); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>
            <div style="margin-top: 12px; font-size: 0.8rem; color: var(--on-surface-variant);">
                UKT Semester & Wisuda terdaftar
            </div>
        </div>

        {{-- Rasio Pelunasan --}}
        @php
            $ratio = $totalTagihanCount > 0 ? round(($paidCount / $totalTagihanCount) * 100, 1) : 0;
        @endphp
        <div class="m3-card" style="padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--on-surface-variant); text-transform: uppercase;">Rasio Pelunasan</span>
                    <div style="font-family: var(--font-display); font-size: 1.8rem; font-weight: 700; color: var(--secondary); margin-top: 4px;">
                        {{ $ratio }}%
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: var(--radius-sm); background: var(--secondary-container); color: var(--on-secondary-container); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
            <div style="margin-top: 12px; font-size: 0.8rem; color: var(--on-surface-variant);">
                Tingkat kepatuhan pembayaran mahasiswa
            </div>
        </div>
    </div>

    {{-- Two Columns: Recent Invoices & Recent Webhook Logs --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
        
        {{-- Left: 5 Tagihan Terakhir --}}
        <div class="m3-card" style="display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: var(--on-surface); display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-receipt text-primary"></i>
                    <span>Tagihan Terbaru</span>
                </h3>
                <a href="{{ route('billing.invoices') }}" class="btn-m3 btn-m3-tonal" style="font-size: 0.78rem; height: 32px; padding: 0 10px;">
                    <span>Lihat Semua</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                @forelse($recentInvoices as $inv)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong style="font-size: 0.875rem; color: var(--on-surface);">{{ $inv->nomor_invoice }}</strong>
                                <span class="badge-m3 {{ $inv->jenis_tagihan === 'WISUDA' ? 'badge-m3-primary' : 'badge-m3-neutral' }}" style="font-size: 0.65rem;">
                                    {{ $inv->jenis_tagihan }}
                                </span>
                            </div>
                            <div style="font-size: 0.775rem; color: var(--on-surface-variant); margin-top: 2px;">
                                NIM: {{ $inv->nim }} &bull; {{ $inv->mahasiswa->nama ?? 'Mahasiswa' }}
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; font-size: 0.875rem; color: var(--on-surface);">
                                Rp {{ number_format($inv->jumlah_tagihan, 0, ',', '.') }}
                            </div>
                            <div style="margin-top: 2px;">
                                @if($inv->status_pembayaran === 'PAID')
                                    <span class="badge-m3 badge-m3-success" style="font-size: 0.7rem;">PAID</span>
                                @else
                                    <span class="badge-m3 badge-m3-error" style="font-size: 0.7rem;">UNPAID</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="font-size: 0.85rem; color: var(--on-surface-variant); text-align: center; padding: 20px 0;">
                        Belum ada tagihan tersimpan.
                    </p>
                @endforelse
            </div>

            <div style="margin-top: 16px;">
                <a href="{{ route('billing.invoices') }}" class="btn-m3 btn-m3-primary" style="width: 100%;">
                    <i class="fas fa-table-list"></i>
                    <span>Buka Manajemen Tagihan & Invoice</span>
                </a>
            </div>
        </div>

        {{-- Right: 5 Log Webhook Terakhir --}}
        <div class="m3-card" style="display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: var(--on-surface); display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-tower-broadcast text-primary"></i>
                    <span>Aktivitas Webhook Terkini</span>
                </h3>
                <a href="{{ route('billing.webhook_logs') }}" class="btn-m3 btn-m3-tonal" style="font-size: 0.78rem; height: 32px; padding: 0 10px;">
                    <span>Semua Log</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                @forelse($recentWebhooks as $log)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; background: var(--surface-container); border-radius: var(--radius-sm);">
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <strong style="font-family: var(--font-mono); font-size: 0.825rem; color: var(--on-surface);">{{ $log->event_name }}</strong>
                                <span class="badge-m3 {{ $log->is_success ? 'badge-m3-success' : 'badge-m3-error' }}" style="font-size: 0.65rem;">
                                    HTTP {{ $log->response_code }}
                                </span>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--on-surface-variant); margin-top: 2px;">
                                {{ $log->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div>
                            <span class="badge-m3 {{ $log->is_success ? 'badge-m3-success' : 'badge-m3-error' }}" style="font-size: 0.75rem;">
                                {{ $log->is_success ? 'SUCCESS' : 'FAILED' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p style="font-size: 0.85rem; color: var(--on-surface-variant); text-align: center; padding: 20px 0;">
                        Belum ada transmisi webhook tercatat.
                    </p>
                @endforelse
            </div>

            <div style="margin-top: 16px;">
                <a href="{{ route('billing.webhook_logs') }}" class="btn-m3 btn-m3-tonal" style="width: 100%;">
                    <i class="fas fa-list-check"></i>
                    <span>Buka Seluruh Audit Log Webhook</span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
