@extends('layouts.app')

@section('title', 'Manajemen Tagihan & Invoice — Total Billing')
@section('page_title', 'Manajemen Tagihan & Invoice')
@section('page_subtitle', 'Master Data Invoicing & Override Status Pelunasan')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- Hero Banner --}}
    <div class="m3-hero-card">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="badge-m3 badge-m3-neutral" style="margin-bottom: 8px;">
                    <i class="fas fa-receipt me-1"></i> MASTER INVOICING
                </span>
                <h2 style="font-family: var(--font-display); font-size: 1.45rem; font-weight: 700; color: var(--on-surface); margin: 4px 0 0 0;">
                    Daftar Tagihan Mahasiswa (UKT & Wisuda)
                </h2>
                <p style="margin: 4px 0 0 0; color: var(--on-surface-variant); font-size: 0.875rem;">
                    Kelola dan pantau seluruh tagihan pembayaran. Anda dapat memodifikasi status pembayaran secara manual jika diperlukan.
                </p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('billing.simulator') }}" class="btn-m3 btn-m3-primary">
                    <i class="fas fa-building-columns"></i>
                    <span>Simulator Bank</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Filter & Search Form --}}
    <div class="m3-card" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('billing.invoices') }}" style="display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end;">
            {{-- Filter Status --}}
            <div style="flex: 1; min-width: 160px;">
                <label style="display: block; font-size: 0.78rem; font-weight: 600; color: var(--on-surface-variant); margin-bottom: 4px;">Status Bayar:</label>
                <select name="status" class="input-m3" onchange="this.form.submit()">
                    <option value="ALL" {{ $filterStatus === 'ALL' ? 'selected' : '' }}>Semua Status</option>
                    <option value="PAID" {{ $filterStatus === 'PAID' ? 'selected' : '' }}>LUNAS (PAID)</option>
                    <option value="UNPAID" {{ $filterStatus === 'UNPAID' ? 'selected' : '' }}>BELUM LUNAS (UNPAID)</option>
                </select>
            </div>

            {{-- Filter Jenis Tagihan --}}
            <div style="flex: 1; min-width: 160px;">
                <label style="display: block; font-size: 0.78rem; font-weight: 600; color: var(--on-surface-variant); margin-bottom: 4px;">Jenis Tagihan:</label>
                <select name="jenis" class="input-m3" onchange="this.form.submit()">
                    <option value="ALL" {{ $filterJenis === 'ALL' ? 'selected' : '' }}>Semua Jenis</option>
                    <option value="UKT" {{ $filterJenis === 'UKT' ? 'selected' : '' }}>UKT Semester</option>
                    <option value="WISUDA" {{ $filterJenis === 'WISUDA' ? 'selected' : '' }}>Pendaftaran Wisuda</option>
                </select>
            </div>

            {{-- Keyword Search --}}
            <div style="flex: 2; min-width: 220px;">
                <label style="display: block; font-size: 0.78rem; font-weight: 600; color: var(--on-surface-variant); margin-bottom: 4px;">Pencarian:</label>
                <input type="text" name="q" value="{{ $search }}" class="input-m3" placeholder="Cari No. Invoice, VA, atau NIM...">
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn-m3 btn-m3-tonal" style="height: 42px; padding: 0 16px;">
                    <i class="fas fa-magnifying-glass"></i>
                    <span>Filter</span>
                </button>
                @if($filterStatus !== 'ALL' || $filterJenis !== 'ALL' || !empty($search))
                    <a href="{{ route('billing.invoices') }}" class="btn-m3 btn-m3-outlined" style="height: 42px; padding: 0 12px;" title="Reset Filter">
                        <i class="fas fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Invoices Table Card --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px; background: var(--surface-container-lowest); border: 1px solid var(--outline-variant) !important;">
        <div class="card-body p-0">
            <div class="table-responsive mb-0" style="border: none; border-radius: 0;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 80px;">Jenis</th>
                            <th>Nomor Invoice</th>
                            <th>Mahasiswa</th>
                            <th>Nomor Virtual Account</th>
                            <th>Nominal Tagihan</th>
                            <th>Waktu & Kanal Bayar</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Kelola Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tagihanList as $tagihan)
                            <tr>
                                <td class="ps-4">
                                    <span class="badge-m3 {{ $tagihan->jenis_tagihan === 'WISUDA' ? 'badge-m3-primary' : 'badge-m3-neutral' }}" style="font-weight: 700; font-size: 0.7rem;">
                                        {{ $tagihan->jenis_tagihan }}
                                    </span>
                                </td>
                                <td>
                                    <strong style="font-family: var(--font-mono); color: var(--on-surface); font-size: 0.85rem;">{{ $tagihan->nomor_invoice }}</strong>
                                    <div style="font-size: 0.7rem; color: var(--on-surface-variant);">
                                        Terbit: {{ $tagihan->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--on-surface); font-size: 0.875rem;">
                                        {{ $tagihan->mahasiswa->nama ?? 'Mahasiswa' }}
                                    </div>
                                    <div style="font-family: var(--font-mono); font-size: 0.75rem; color: var(--on-surface-variant);">
                                        NIM: {{ $tagihan->nim }}
                                    </div>
                                </td>
                                <td>
                                    <span style="font-family: var(--font-mono); font-weight: 700; color: var(--primary); font-size: 0.9rem;">
                                        {{ $tagihan->nomor_va }}
                                    </span>
                                </td>
                                <td>
                                    <strong style="color: var(--on-surface); font-size: 0.9rem;">
                                        Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    @if($tagihan->tanggal_bayar)
                                        <div style="font-size: 0.8rem; color: var(--on-surface);">
                                            {{ $tagihan->tanggal_bayar->format('d/m/Y H:i') }}
                                        </div>
                                        <small style="color: var(--on-surface-variant); font-size: 0.7rem;">
                                            {{ $tagihan->channel_bayar ?? 'Virtual Account' }}
                                        </small>
                                    @else
                                        <span style="color: var(--outline-variant); font-size: 0.8rem;">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($tagihan->status_pembayaran === 'PAID')
                                        <span class="badge-m3 badge-m3-success" style="font-size: 0.75rem; font-weight: 700;">
                                            <i class="fas fa-circle-check me-1"></i> PAID
                                        </span>
                                    @else
                                        <span class="badge-m3 badge-m3-error" style="font-size: 0.75rem; font-weight: 700;">
                                            <i class="fas fa-clock me-1"></i> UNPAID
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('billing.update_status', $tagihan->id) }}" method="POST" style="margin: 0; display: inline-block;">
                                        @csrf
                                        @if($tagihan->status_pembayaran === 'PAID')
                                            <input type="hidden" name="status_pembayaran" value="UNPAID">
                                            <button type="submit" class="btn-m3 btn-m3-outlined" style="height: 30px; padding: 0 10px; font-size: 0.72rem; border-color: var(--error); color: var(--error);" onclick="return confirm('Batalkan pelunasan tagihan ini?')">
                                                <i class="fas fa-rotate-left"></i>
                                                <span>Set UNPAID</span>
                                            </button>
                                        @else
                                            <input type="hidden" name="status_pembayaran" value="PAID">
                                            <input type="hidden" name="channel_bayar" value="ADMIN_MANUAL_SET">
                                            <button type="submit" class="btn-m3 btn-m3-tonal" style="height: 30px; padding: 0 10px; font-size: 0.72rem;">
                                                <i class="fas fa-check"></i>
                                                <span>Set LUNAS</span>
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4" style="color: var(--on-surface-variant);">
                                    Tidak ada tagihan yang sesuai dengan filter pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($tagihanList->hasPages())
            <div class="px-4 py-3" style="border-top: 1px solid var(--outline-variant); display: flex; justify-content: center;">
                {{ $tagihanList->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
