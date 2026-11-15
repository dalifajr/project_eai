@extends('layouts.app')

@section('title', 'Status Keuangan & UKT')
@section('page_title', 'Status Keuangan & UKT')
@section('page_subtitle', 'Monitoring Tagihan & Virtual Account via EAI Billing')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- EAI Status Banner --}}
    <div class="m3-hero-card" style="background: linear-gradient(135deg, var(--surface-container-high) 0%, var(--surface-container-lowest) 100%);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <span class="badge-m3 badge-m3-success" style="font-size: 0.72rem;">
                        <i class="fas fa-tower-broadcast me-1"></i> EAI REST API ACTIVE
                    </span>
                    <span style="font-size: 0.8rem; color: var(--on-surface-variant);">Sinkronisasi Otomatis dengan Total Billing System</span>
                </div>
                <h2 style="font-family: var(--font-display); font-size: 1.45rem; font-weight: 700; color: var(--on-surface); margin: 0;">
                    Tagihan Biaya Pendidikan Mahasiswa
                </h2>
                <p style="margin: 4px 0 0 0; color: var(--on-surface-variant); font-size: 0.875rem;">
                    NIM: <strong>{{ $mahasiswa->nim }}</strong> &bull; {{ $mahasiswa->nama }} &bull; Semester {{ $mahasiswa->semester_sekarang }}
                </p>
            </div>
            <div>
                <a href="{{ route('keuangan.index') }}" class="btn-m3 btn-m3-tonal" title="Perbarui Status Pembayaran EAI">
                    <i class="fas fa-arrows-rotate"></i>
                    <span>Cek Status Terkini</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Main VA Billing Display Card --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 20px;">
        
        {{-- Card 1: Virtual Account & Status Pelunasan --}}
        <div class="m3-card" style="display: flex; flex-direction: column; border-top: 4px solid var(--primary);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--on-surface-variant); letter-spacing: 0.05em;">
                        TAGIHAN UKT SEMESTER {{ $mahasiswa->semester_sekarang }}
                    </span>
                    <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--on-surface); margin: 4px 0 0 0;">
                        Rp {{ number_format($uktStatus['jumlah_tagihan'] ?? 5000000, 0, ',', '.') }}
                    </h3>
                </div>
                <div>
                    @if(($uktStatus['status_pembayaran'] ?? 'UNPAID') === 'PAID')
                        <span class="badge-m3 badge-m3-success" style="font-size: 0.85rem; padding: 6px 14px;">
                            <i class="fas fa-circle-check me-1"></i> SUDAH LUNAS
                        </span>
                    @else
                        <span class="badge-m3 badge-m3-error" style="font-size: 0.85rem; padding: 6px 14px;">
                            <i class="fas fa-clock me-1"></i> BELUM LUNAS
                        </span>
                    @endif
                </div>
            </div>

            {{-- VA Box with Copy Button --}}
            <div style="background: var(--surface-container); border-radius: var(--radius-md); padding: 18px; margin-bottom: 20px; border: 1px solid var(--outline-variant);">
                <div style="font-size: 0.75rem; color: var(--on-surface-variant); text-transform: uppercase; font-weight: 600; margin-bottom: 6px;">
                    Nomor Virtual Account (Semua Bank / ATM / M-Banking):
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                    <span id="vaNumberDisplay" style="font-family: var(--font-mono); font-size: 1.4rem; font-weight: 700; color: var(--primary); letter-spacing: 0.05em;">
                        {{ $uktStatus['nomor_va'] ?? '88019'.$mahasiswa->nim }}
                    </span>
                    <button type="button" class="btn-m3 btn-m3-tonal" onclick="copyVA()" style="height: 36px; padding: 0 12px; font-size: 0.8rem;" title="Salin Nomor VA">
                        <i class="fas fa-copy"></i>
                        <span id="copyBtnText">Salin</span>
                    </button>
                </div>
                <div style="margin-top: 10px; font-size: 0.78rem; color: var(--on-surface-variant); display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-shield-halved text-success"></i>
                    <span>Nama Akun VA: <strong>SIMAK-{{ strtoupper(strtok($mahasiswa->nama, " ")) }}</strong></span>
                </div>
            </div>

            {{-- Status Alert Notice --}}
            @if(($uktStatus['status_pembayaran'] ?? 'UNPAID') === 'PAID')
                <div class="m3-alert m3-alert-success" style="margin-bottom: 0;">
                    <i class="fas fa-circle-check" style="font-size: 1.2rem;"></i>
                    <div style="font-size: 0.875rem;">
                        <strong>Pembayaran UKT Terverifikasi!</strong> Hak akses pengisian Kartu Rencana Studi (KRS) untuk Semester {{ $mahasiswa->semester_sekarang }} telah dibuka.
                    </div>
                </div>
                <div style="margin-top: 16px;">
                    <a href="{{ route('krs.index') }}" class="btn-m3 btn-m3-primary" style="width: 100%;">
                        <i class="fas fa-file-signature"></i>
                        <span>Lanjut ke Pengisian KRS</span>
                    </a>
                </div>
            @else
                <div class="m3-alert m3-alert-warning" style="margin-bottom: 0;">
                    <i class="fas fa-triangle-exclamation" style="font-size: 1.2rem;"></i>
                    <div style="font-size: 0.875rem;">
                        <strong>UKT Belum Lunas:</strong> Pengisian KRS dikunci oleh sistem EAI sampai tagihan UKT dinyatakan LUNAS (PAID) oleh Total Billing System.
                    </div>
                </div>
            @endif
        </div>

        {{-- Card 2: Rincian Tagihan & Integrasi Gateway Bank --}}
        <div class="m3-card" style="display: flex; flex-direction: column;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--on-surface); margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-receipt text-primary"></i>
                <span>Rincian Biaya & Panduan Simulasi</span>
            </h3>

            <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm); font-size: 0.875rem;">
                    <span style="color: var(--on-surface-variant);">SPP Pokok / UKT Semester {{ $mahasiswa->semester_sekarang }}</span>
                    <span style="font-weight: 600; color: var(--on-surface);">Rp 4.000.000</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm); font-size: 0.875rem;">
                    <span style="color: var(--on-surface-variant);">Biaya Praktikum & Laboratorium</span>
                    <span style="font-weight: 600; color: var(--on-surface);">Rp 750.000</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 14px; background: var(--surface-container); border-radius: var(--radius-sm); font-size: 0.875rem;">
                    <span style="color: var(--on-surface-variant);">Pemeliharaan Sistem Informasi & IT</span>
                    <span style="font-weight: 600; color: var(--on-surface);">Rp 250.000</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 12px 14px; background: var(--primary-container); border-radius: var(--radius-sm); font-size: 0.95rem; font-weight: 700; color: var(--on-primary-container);">
                    <span>Total Tagihan UKT</span>
                    <span>Rp {{ number_format($uktStatus['jumlah_tagihan'] ?? 5000000, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- EAI Simulation Gateway Box --}}
            <div style="background: var(--surface-container-high); border-radius: var(--radius-md); padding: 16px; border: 1px dashed var(--outline-variant); margin-top: auto;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                    <i class="fas fa-laptop-code text-primary"></i>
                    <strong style="font-size: 0.875rem; color: var(--on-surface);">Pengujian Aliran EAI (Bank Simulator):</strong>
                </div>
                <p style="font-size: 0.8rem; color: var(--on-surface-variant); margin: 0 0 10px 0; line-height: 1.5;">
                    Untuk menguji pelunasan tagihan ini dalam simulasi integrasi enterprise, buka <strong>Total Billing System (Port 8001)</strong> pada menu <em>Simulator Gateway Bank</em>, lalu masukkan nomor VA di atas.
                </p>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="http://localhost:8001" target="_blank" class="btn-m3 btn-m3-tonal" style="font-size: 0.8rem; height: 36px;">
                        <i class="fas fa-up-right-from-square"></i>
                        <span>Buka Total Billing (Port 8001)</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    function copyVA() {
        const vaText = document.getElementById('vaNumberDisplay').innerText.trim();
        navigator.clipboard.writeText(vaText).then(() => {
            const btnText = document.getElementById('copyBtnText');
            btnText.innerText = 'Tersalin!';
            setTimeout(() => {
                btnText.innerText = 'Salin';
            }, 2000);
        });
    }
</script>
@endpush
@endsection
