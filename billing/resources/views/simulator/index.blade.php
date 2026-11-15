@extends('layouts.app')

@section('title', 'Simulator Gateway Bank & VA — Total Billing')
@section('page_title', 'Simulator Gateway Bank')
@section('page_subtitle', 'Simulasi Pelunasan Virtual Account & Trigger Webhook EAI')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- Hero Banner --}}
    <div class="m3-hero-card" style="background: linear-gradient(135deg, var(--surface-container-high) 0%, var(--surface-container-lowest) 100%);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div style="max-width: 720px;">
                <span class="badge-m3 badge-m3-success" style="margin-bottom: 8px;">
                    <i class="fas fa-building-columns me-1"></i> SIMULATOR INTEGRASI GATEWAY
                </span>
                <h1 style="font-family: var(--font-display); font-size: 1.55rem; font-weight: 700; color: var(--on-surface); margin: 4px 0 8px 0;">
                    Simulasi Pelunasan Tagihan (Bank & Switching)
                </h1>
                <p style="margin: 0; color: var(--on-surface-variant); font-size: 0.9rem; line-height: 1.5;">
                    Uji coba aliran enterprise: Masukkan nomor Virtual Account (VA) untuk melunasi tagihan. Sistem akan otomatis memancarkan <strong>Webhook Event</strong> ke SIMAK jika tagihan adalah Wisuda, atau memperbarui status query REST API jika tagihan adalah UKT.
                </p>
            </div>
            <div>
                <a href="{{ route('billing.dashboard') }}" class="btn-m3 btn-m3-tonal">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Overview</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Simulator Form & Explanation Grid --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 20px;">
        
        {{-- Card 1: Form Simulator Konsol --}}
        <div class="m3-card" style="border-top: 4px solid var(--primary);">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--primary-container); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fas fa-terminal"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--on-surface); margin: 0;">Konsol Transaksi Bank</h3>
                    <small style="color: var(--on-surface-variant);">Pilih Tagihan atau Masukkan VA Secara Manual</small>
                </div>
            </div>

            <form action="{{ route('billing.simulasi_bayar') }}" method="POST">
                @csrf

                {{-- Pilih Tagihan Cepat --}}
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: var(--on-surface); margin-bottom: 6px;">
                        Pilih Tagihan Belum Lunas (UNPAID):
                    </label>
                    <select id="quickInvoiceSelect" class="input-m3" onchange="applySelectedInvoice(this)">
                        <option value="">-- Pilih Tagihan Mahasiswa --</option>
                        @foreach($unpaidInvoices as $inv)
                            <option value="{{ $inv->nomor_va }}" data-invoice="{{ $inv->nomor_invoice }}" data-nominal="{{ $inv->jumlah_tagihan }}" data-mhs="{{ $inv->mahasiswa->nama ?? $inv->nim }}" data-jenis="{{ $inv->jenis_tagihan }}">
                                [{{ $inv->jenis_tagihan }}] {{ $inv->nomor_invoice }} - {{ $inv->mahasiswa->nama ?? $inv->nim }} (Rp {{ number_format($inv->jumlah_tagihan, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Input Nomor VA --}}
                <div style="margin-bottom: 16px;">
                    <label for="nomor_va" style="display: block; font-size: 0.8125rem; font-weight: 600; color: var(--on-surface); margin-bottom: 6px;">
                        Nomor Virtual Account (VA): <span style="color: var(--error);">*</span>
                    </label>
                    <input type="text" name="nomor_va" id="nomor_va" class="input-m3" placeholder="Contoh: 880192021001" required style="font-family: var(--font-mono); font-size: 1.05rem; font-weight: 600; letter-spacing: 0.05em;">
                </div>

                {{-- Channel Pembayaran --}}
                <div style="margin-bottom: 24px;">
                    <label for="channel_bayar" style="display: block; font-size: 0.8125rem; font-weight: 600; color: var(--on-surface); margin-bottom: 6px;">
                        Kanal Pembayaran / Switching Bank:
                    </label>
                    <select name="channel_bayar" id="channel_bayar" class="input-m3">
                        <option value="MANDIRI_VIRTUAL_ACCOUNT">Bank Mandiri (Livin' by Mandiri VA)</option>
                        <option value="BCA_VIRTUAL_ACCOUNT">Bank Central Asia (BCA Mobile / KlikBCA)</option>
                        <option value="BNI_VIRTUAL_ACCOUNT">Bank Negara Indonesia (BNI Mobile Banking)</option>
                        <option value="BRI_BRIVA">Bank Rakyat Indonesia (BRIVA)</option>
                        <option value="PERMATA_VA">Bank Permata (PermataNet)</option>
                        <option value="QRIS_DINAMIS">QRIS Standar Bank Indonesia</option>
                        <option value="TELLER_KAMPUS">Teller Kasir Keuangan Kampus</option>
                    </select>
                </div>

                <button type="submit" class="btn-m3 btn-m3-primary" style="width: 100%; height: 46px; font-size: 0.95rem;">
                    <i class="fas fa-check-double me-1"></i>
                    <span>Eksekusi Pelunasan & Trigger EAI</span>
                </button>
            </form>
        </div>

        {{-- Card 2: Penjelasan Aliran Arsitektur EAI --}}
        <div class="m3-card" style="display: flex; flex-direction: column;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--secondary-container); color: var(--on-secondary-container); display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fas fa-diagram-project"></i>
                </div>
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--on-surface); margin: 0;">Mekanisme Integrasi EAI</h3>
                    <small style="color: var(--on-surface-variant);">Dua Pola Integrasi: Webhook & REST API</small>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 14px; font-size: 0.875rem; color: var(--on-surface-variant); line-height: 1.5;">
                <div style="padding: 12px 14px; background: var(--surface-container); border-radius: var(--radius-sm); border-left: 3px solid var(--primary);">
                    <strong style="color: var(--on-surface); display: block; margin-bottom: 4px;">1. Pola Webhook Asinkron (Tagihan Wisuda):</strong>
                    Ketika tagihan wisuda berhasil dibayar, controller Billing secara otomatis memancarkan event <code>payment.success</code> dengan payload JSON terenkripsi HMAC SHA-256 langsung ke endpoint SIMAK (<code>:8000/api/v1/webhook/pembayaran-wisuda</code>).
                </div>

                <div style="padding: 12px 14px; background: var(--surface-container); border-radius: var(--radius-sm); border-left: 3px solid var(--secondary);">
                    <strong style="color: var(--on-surface); display: block; margin-bottom: 4px;">2. Pola Sinkron REST API (Tagihan UKT Mahasiswa):</strong>
                    Ketika tagihan UKT dilunasi, perubahan status tersimpan di database Billing. Saat mahasiswa membuka menu KRS di SIMAK, SIMAK melakukan handshake REST API via HTTP GET ke <code>:8001/api/v1/billing/status-ukt</code> untuk memverifikasi hak akses KRS secara realtime.
                </div>
            </div>

            <div style="margin-top: auto; padding-top: 16px;">
                <a href="{{ route('billing.webhook_logs') }}" class="btn-m3 btn-m3-tonal" style="width: 100%;">
                    <i class="fas fa-tower-broadcast"></i>
                    <span>Pantau di Audit Log Webhook</span>
                </a>
            </div>
        </div>

    </div>

    {{-- Daftar Tagihan Belum Lunas (Quick Selection Table) --}}
    <div class="m3-card" style="padding: 0; overflow: hidden;">
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--outline-variant); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--on-surface); margin: 0;">
                    Daftar Tagihan Menunggu Pelunasan (UNPAID)
                </h3>
                <small style="color: var(--on-surface-variant);">Pilih salah satu baris untuk langsung mengisi form simulator di atas</small>
            </div>
            <span class="badge-m3 badge-m3-warning" style="font-weight: 700;">
                {{ $unpaidInvoices->count() }} Tagihan Tersedia
            </span>
        </div>

        <div class="table-container-modern">
            <table class="table-m3">
                <thead>
                    <tr>
                        <th>Jenis</th>
                        <th>Nomor Invoice</th>
                        <th>Mahasiswa</th>
                        <th>Nomor Virtual Account</th>
                        <th>Nominal Tagihan</th>
                        <th style="text-align: right;">Aksi Cepat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unpaidInvoices as $inv)
                        <tr>
                            <td>
                                <span class="badge-m3 {{ $inv->jenis_tagihan === 'WISUDA' ? 'badge-m3-primary' : 'badge-m3-neutral' }}" style="font-weight: 700; font-size: 0.72rem;">
                                    {{ $inv->jenis_tagihan }}
                                </span>
                            </td>
                            <td>
                                <strong style="font-family: var(--font-mono); color: var(--on-surface);">{{ $inv->nomor_invoice }}</strong>
                            </td>
                            <td>
                                <div style="color: var(--on-surface); font-weight: 600;">{{ $inv->mahasiswa->nama ?? 'Mahasiswa' }}</div>
                                <small style="font-family: var(--font-mono); color: var(--on-surface-variant);">NIM: {{ $inv->nim }}</small>
                            </td>
                            <td>
                                <span style="font-family: var(--font-mono); font-weight: 700; color: var(--primary); font-size: 0.95rem;">
                                    {{ $inv->nomor_va }}
                                </span>
                            </td>
                            <td>
                                <strong style="color: var(--on-surface);">Rp {{ number_format($inv->jumlah_tagihan, 0, ',', '.') }}</strong>
                            </td>
                            <td style="text-align: right;">
                                <button type="button" class="btn-m3 btn-m3-tonal" style="height: 32px; padding: 0 12px; font-size: 0.78rem;" onclick="setVA('{{ $inv->nomor_va }}')">
                                    <i class="fas fa-hand-pointer"></i>
                                    <span>Pilih VA Ini</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 32px; color: var(--on-surface-variant);">
                                Semua tagihan saat ini telah berstatus LUNAS (PAID).
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function applySelectedInvoice(selectEl) {
        const val = selectEl.value;
        if (val) {
            document.getElementById('nomor_va').value = val;
        }
    }

    function setVA(vaNumber) {
        document.getElementById('nomor_va').value = vaNumber;
        window.scrollTo({ top: 120, behavior: 'smooth' });
    }
</script>
@endpush
@endsection
