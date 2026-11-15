@extends('layouts.app')

@section('title', 'Pendaftaran Wisuda Sarjana')
@section('page_title', 'Pendaftaran Wisuda')
@section('page_subtitle', 'Verifikasi Prasyarat Akademik & Integrasi Pembayaran EAI')

@section('content')
<!-- Page Header Info -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <span class="m3-badge-pill primary" style="margin-bottom: 6px;">PORTAL KELULUSAN & ALUMNI</span>
        <h1 style="font-size: 26px; font-weight: 700; color: var(--on-surface); letter-spacing: -0.02em;">
            Pendaftaran Wisuda Periode 2026/2027
        </h1>
        <p style="font-size: 13.5px; color: var(--on-surface-variant); margin-top: 4px;">
            Alur Validasi Multidisiplin: Verifikasi Akademik Internal SIMAK &bull; Integrasi Finansial Total Billing System
        </p>
    </div>
</div>

<!-- Modern 4-Step Process Timeline Bar (20px M3 Card) -->
<div class="m3-card" style="padding: 20px 26px; margin-bottom: 24px; border-radius: var(--radius-lg);">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
        <!-- Step 1 -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ $syaratAkademikLengkap ? 'var(--tertiary)' : 'var(--error)' }}; color: var(--on-primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                {{ $syaratAkademikLengkap ? '✓' : '1' }}
            </div>
            <div>
                <div style="font-size: 12.5px; font-weight: 700; color: var(--on-surface);">Tahap 1: Akademik</div>
                <div style="font-size: 11.5px; color: var(--on-surface-variant);">{{ $syaratAkademikLengkap ? 'Lolos Sidang' : 'Belum Lengkap' }}</div>
            </div>
        </div>

        <!-- Step 2 -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ !$syaratAkademikLengkap ? 'var(--surface-container-highest)' : (!empty($tunggakan['has_debt']) ? 'var(--error)' : 'var(--tertiary)') }}; color: {{ !$syaratAkademikLengkap ? 'var(--on-surface-variant)' : '#fff' }}; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                {{ !$syaratAkademikLengkap ? '2' : (!empty($tunggakan['has_debt']) ? '!' : '✓') }}
            </div>
            <div>
                <div style="font-size: 12.5px; font-weight: 700; color: var(--on-surface);">Tahap 2: Bebas Tunggakan</div>
                <div style="font-size: 11.5px; color: var(--on-surface-variant);">{{ !$syaratAkademikLengkap ? 'Menunggu Tahap 1' : (!empty($tunggakan['has_debt']) ? 'Ada Denda' : 'Bebas Tunggakan') }}</div>
            </div>
        </div>

        <!-- Step 3 -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ (!$syaratAkademikLengkap || !empty($tunggakan['has_debt'])) ? 'var(--surface-container-highest)' : (($pendaftaran && $pendaftaran->status_pembayaran === 'paid') ? 'var(--tertiary)' : 'var(--warning)') }}; color: {{ (!$syaratAkademikLengkap || !empty($tunggakan['has_debt'])) ? 'var(--on-surface-variant)' : '#fff' }}; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                {{ ($pendaftaran && $pendaftaran->status_pembayaran === 'paid') ? '✓' : '3' }}
            </div>
            <div>
                <div style="font-size: 12.5px; font-weight: 700; color: var(--on-surface);">Tahap 3: Biaya Wisuda</div>
                <div style="font-size: 11.5px; color: var(--on-surface-variant);">{{ ($pendaftaran && $pendaftaran->status_pembayaran === 'paid') ? 'Lunas Webhook' : 'Menunggu Bayar' }}</div>
            </div>
        </div>

        <!-- Step 4 -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 36px; height: 36px; border-radius: 50%; background: {{ ($pendaftaran && $pendaftaran->status_pembayaran === 'paid' && $pendaftaran->ukuran_toga) ? 'var(--tertiary)' : 'var(--surface-container-highest)' }}; color: {{ ($pendaftaran && $pendaftaran->status_pembayaran === 'paid' && $pendaftaran->ukuran_toga) ? '#fff' : 'var(--on-surface-variant)' }}; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                {{ ($pendaftaran && $pendaftaran->status_pembayaran === 'paid' && $pendaftaran->ukuran_toga) ? '✓' : '4' }}
            </div>
            <div>
                <div style="font-size: 12.5px; font-weight: 700; color: var(--on-surface);">Tahap 4: Toga & Undangan</div>
                <div style="font-size: 11.5px; color: var(--on-surface-variant);">{{ ($pendaftaran && $pendaftaran->ukuran_toga) ? 'Berkas Final' : 'Belum Diisi' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- STEP 1: VALIDASI PERSYARATAN AKADEMIK & SKRIPSI -->
<div class="m3-card">
    <div class="m3-card-header">
        <div>
            <h2 class="m3-card-title">1. Verifikasi Kelulusan Akademik & Bebas Puspa/Lab</h2>
            <p class="m3-card-subtitle">Pemeriksaan integritas berkas akademik internal SIMAK</p>
        </div>
        @if($syaratAkademikLengkap)
            <span class="m3-badge-pill success">SYARAT AKADEMIK TERPENUHI</span>
        @else
            <span class="m3-badge-pill danger">PERSYARATAN BELUM LENGKAP</span>
        @endif
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
        <div class="m3-inner-block">
            <div style="font-size: 11px; color: var(--on-surface-variant); font-weight: 600; text-transform: uppercase;">Ujian Sidang Skripsi</div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                <strong style="color: var(--on-surface);">{{ strtoupper($mahasiswa->status_skripsi) }}</strong>
                @if($mahasiswa->status_skripsi === 'lulus')
                    <span class="m3-badge-pill success">LULUS</span>
                @else
                    <span class="m3-badge-pill danger">BELUM LULUS</span>
                @endif
            </div>
        </div>

        <div class="m3-inner-block">
            <div style="font-size: 11px; color: var(--on-surface-variant); font-weight: 600; text-transform: uppercase;">Bebas Laboratorium</div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                <strong style="color: var(--on-surface);">{{ $mahasiswa->bebas_lab ? 'Terverifikasi' : 'Belum Selesai' }}</strong>
                @if($mahasiswa->bebas_lab)
                    <span class="m3-badge-pill success">BEBAS</span>
                @else
                    <span class="m3-badge-pill danger">BELUM</span>
                @endif
            </div>
        </div>

        <div class="m3-inner-block">
            <div style="font-size: 11px; color: var(--on-surface-variant); font-weight: 600; text-transform: uppercase;">Bebas Perpustakaan</div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                <strong style="color: var(--on-surface);">{{ $mahasiswa->bebas_perpustakaan ? 'Terverifikasi' : 'Belum Selesai' }}</strong>
                @if($mahasiswa->bebas_perpustakaan)
                    <span class="m3-badge-pill success">BEBAS</span>
                @else
                    <span class="m3-badge-pill danger">BELUM</span>
                @endif
            </div>
        </div>
    </div>

    @if(!$syaratAkademikLengkap)
        <div class="m3-card" style="background: var(--error-container); color: var(--on-error-container); margin-top: 18px; margin-bottom: 0; padding: 16px 20px;">
            <div style="display: flex; gap: 12px; align-items: flex-start;">
                <span style="font-size: 18px;">✕</span>
                <div>
                    <strong>Pendaftaran Wisuda Ditolak: Persyaratan Akademik Belum Lengkap</strong>
                    <ul style="margin-left: 20px; margin-top: 6px; font-size: 13px;">
                        @foreach($kekuranganSyarat as $syarat)
                            <li>{{ $syarat }}</li>
                        @endforeach
                    </ul>
                    <p style="font-size: 12px; margin-top: 6px; opacity: 0.9;">
                        Pastikan Anda telah dinyatakan Lulus Sidang Skripsi dan menuntaskan bebas pinjam pustaka/alat sebelum melanjutkan ke tahap pembayaran.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

@if($syaratAkademikLengkap)
    <!-- STEP 2: CEK BEBAS TUNGGAKAN UKT & DENDA (EAI API CALL) -->
    <div class="m3-card">
        <div class="m3-card-header">
            <div>
                <h2 class="m3-card-title">2. Cek Bebas Tunggakan Biaya Kuliah & Denda (EAI REST API)</h2>
                <p class="m3-card-subtitle">Validasi riwayat tagihan seluruh semester ke Total Billing System</p>
            </div>
            @if($tunggakan['has_debt'])
                <span class="m3-badge-pill danger">TERDETEKSI TUNGGAKAN</span>
            @else
                <span class="m3-badge-pill success">BEBAS TUNGGAKAN (CLEARED)</span>
            @endif
        </div>

        @if($tunggakan['has_debt'])
            <div class="m3-card" style="background: var(--warning-container); color: var(--on-warning-container); margin-bottom: 0; padding: 20px;">
                <div style="display: flex; gap: 14px; align-items: flex-start;">
                    <span style="font-size: 22px;">⚠️</span>
                    <div style="flex: 1;">
                        <strong style="font-size: 15px;">Perhatian: Ditemukan Tunggakan yang Belum Dilunasi!</strong>
                        <p style="font-size: 13.5px; margin-top: 4px; opacity: 0.9;">
                            Sesuai regulasi kampus, mahasiswa yang memiliki tunggakan UKT atau denda dilarang mendaftar paket wisuda sampai seluruh kewajiban dilunasi.
                        </p>

                        <div class="m3-table-container" style="margin-top: 16px; background: var(--surface-container-lowest);">
                            <table class="m3-table">
                                <thead>
                                    <tr>
                                        <th>Invoice ID</th>
                                        <th>Keterangan Tagihan</th>
                                        <th>Semester</th>
                                        <th>Nominal</th>
                                        <th>Nomor VA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tunggakan['detail'] as $row)
                                    <tr>
                                        <td><code style="background: var(--surface-container-high); padding: 2px 6px; border-radius: 4px;">{{ $row['invoice_id'] }}</code></td>
                                        <td><strong style="color: var(--on-surface);">{{ $row['deskripsi'] }}</strong></td>
                                        <td>{{ $row['semester'] ?? '-' }}</td>
                                        <td><strong>Rp {{ number_format($row['nominal'], 0, ',', '.') }}</strong></td>
                                        <td><span style="font-family: var(--font-mono); font-weight: 700; color: var(--primary);">{{ $row['nomor_va'] }}</span></td>
                                    </tr>
                                    @endforeach
                                    <tr style="font-weight: 700; background: var(--surface-container-high);">
                                        <td colspan="3" style="text-align: right; padding-right: 20px;">Total Kewajiban Wajib Dilunasi:</td>
                                        <td colspan="2"><span style="font-size: 14px; color: var(--primary);">Rp {{ number_format($tunggakan['total_tunggakan'], 0, ',', '.') }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div style="margin-top: 16px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            <a href="http://localhost:8001" target="_blank" class="btn-modern primary" style="font-size: 13px;">
                                Buka Billing Simulator untuk Melunasi Tagihan Denda Ini &rarr;
                            </a>
                            <button onclick="location.reload()" class="btn-modern secondary sm" style="font-size: 12.5px;">
                                Refresh Status Tunggakan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="m3-card" style="background: var(--tertiary-container); color: var(--on-tertiary-container); margin-bottom: 0; padding: 16px 20px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 20px;">✓</span>
                    <div>
                        <strong>Verifikasi Bebas Tunggakan Berhasil</strong>
                        <div style="font-size: 12.5px; opacity: 0.9; margin-top: 2px;">
                            Mahasiswa terkonfirmasi bersih dari segala tunggakan UKT dan denda akademik. Akses penerbitan tagihan wisuda telah dibuka.
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- STEP 3: TAGIHAN PAKET WISUDA & NOMOR VA (EAI SYNC & WEBHOOK) -->
    @if(!$tunggakan['has_debt'] && $pendaftaran)
        <div class="m3-card">
            <div class="m3-card-header">
                <div>
                    <h2 class="m3-card-title">3. Tagihan Paket Wisuda & Nomor Virtual Account (VA)</h2>
                    <p class="m3-card-subtitle">Penerbitan kode bayar wisuda resmi dari Total Billing System</p>
                </div>
                @if($pendaftaran->status_pembayaran === 'paid')
                    <span class="m3-badge-pill success">PEMBAYARAN LUNAS</span>
                @else
                    <span class="m3-badge-pill warning">MENUNGGU PEMBAYARAN</span>
                @endif
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 20px;">
                <div class="m3-inner-block" style="padding: 18px 20px;">
                    <span style="font-size: 11px; color: var(--on-surface-variant); font-weight: 600; text-transform: uppercase;">Nomor Virtual Account Wisuda:</span>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin: 8px 0;">
                        <span style="font-family: var(--font-mono); font-size: 24px; font-weight: 700; color: var(--primary); letter-spacing: 0.04em;">
                            {{ $pendaftaran->nomor_va ?? $tagihanWisuda['nomor_va'] }}
                        </span>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $pendaftaran->nomor_va ?? $tagihanWisuda['nomor_va'] }}'); alert('Nomor VA Wisuda berhasil disalin!');" class="btn-modern secondary sm">
                            Salin VA
                        </button>
                    </div>
                    <div style="font-size: 12px; color: var(--on-surface-variant);">
                        Bank Kampus EAI &bull; BNI &bull; Mandiri &bull; BCA
                    </div>
                </div>

                <div class="m3-inner-block" style="padding: 18px 20px;">
                    <span style="font-size: 11px; color: var(--on-surface-variant); font-weight: 600; text-transform: uppercase;">Total Nominal Tagihan Wisuda:</span>
                    <div style="font-size: 24px; font-weight: 700; color: var(--on-surface); margin: 8px 0; font-family: var(--font-display);">
                        Rp {{ number_format($pendaftaran->nominal_wisuda, 0, ',', '.') }}
                    </div>
                    <div style="font-size: 12px; color: var(--on-surface-variant);">
                        Paket Lengkap: Toga, Gordon, Ijazah Asli, Transkrip & 2 Undangan Keluarga
                    </div>
                </div>
            </div>

            @if($pendaftaran->status_pembayaran !== 'paid')
                <div class="m3-card" style="background: var(--warning-container); color: var(--on-warning-container); padding: 18px 22px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 14px; margin-bottom: 0;">
                    <div>
                        <strong>Menunggu Pembayaran Tagihan Paket Wisuda</strong>
                        <p style="font-size: 12.5px; opacity: 0.9; margin-top: 2px;">
                            Silakan buka simulator pembayaran di Total Billing System untuk simulasi pelunasan instan. Setelah lunas, webhook callback akan seketika membuka Tahap 4.
                        </p>
                    </div>
                    <a href="http://localhost:8001" target="_blank" class="btn-modern primary" style="font-size: 13px; white-space: nowrap;">
                        Bayar VA via Billing Simulator &rarr;
                    </a>
                </div>
            @else
                <div class="m3-card" style="background: var(--tertiary-container); color: var(--on-tertiary-container); padding: 18px 22px; display: flex; align-items: center; gap: 12px; margin-bottom: 0;">
                    <span style="font-size: 22px;">✓</span>
                    <div>
                        <strong>Pelunasan Wisuda Berhasil Diterima melalui Webhook Callback EAI</strong>
                        <p style="font-size: 12.5px; opacity: 0.9; margin-top: 2px;">
                            Transaksi pembayaran telah terkonfirmasi secara realtime. Silakan lengkapi ukuran baju toga dan konfirmasi alokasi undangan di formulir Tahap 4 berikut.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- STEP 4: FORM DATA LANJUTAN (TERBUKA HANYA SETELAH STATUS PAID) -->
        @if($pendaftaran->status_pembayaran === 'paid')
            <div class="m3-card" style="border-top: 4px solid var(--tertiary);">
                <div class="m3-card-header">
                    <div>
                        <h2 class="m3-card-title">4. Formulir Perlengkapan Wisuda & Alokasi Kursi Undangan</h2>
                        <p class="m3-card-subtitle">Pengisian ukuran jubah toga dan alokasi kursi undangan wisudawan</p>
                    </div>
                    @if($pendaftaran->status_pendaftaran === 'berkas_lengkap')
                        <span class="m3-badge-pill success">BERKAS LENGKAP</span>
                    @else
                        <span class="m3-badge-pill warning">FORM PERLU DISIMPAN</span>
                    @endif
                </div>

                <form action="{{ route('wisuda.data_lanjutan') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 18px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--on-surface); margin-bottom: 8px;">
                                UKURAN JUBAH TOGA WISUDA
                            </label>
                            <select name="ukuran_toga" required class="m3-select">
                                <option value="">-- Pilih Ukuran Jubah --</option>
                                <option value="S" {{ $pendaftaran->ukuran_toga === 'S' ? 'selected' : '' }}>Ukuran S (Panjang 105 cm, Dada 50 cm)</option>
                                <option value="M" {{ $pendaftaran->ukuran_toga === 'M' ? 'selected' : '' }}>Ukuran M (Panjang 110 cm, Dada 54 cm)</option>
                                <option value="L" {{ $pendaftaran->ukuran_toga === 'L' ? 'selected' : '' }}>Ukuran L (Panjang 115 cm, Dada 58 cm)</option>
                                <option value="XL" {{ $pendaftaran->ukuran_toga === 'XL' ? 'selected' : '' }}>Ukuran XL (Panjang 120 cm, Dada 62 cm)</option>
                                <option value="XXL" {{ $pendaftaran->ukuran_toga === 'XXL' ? 'selected' : '' }}>Ukuran XXL (Panjang 125 cm, Dada 66 cm)</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 600; color: var(--on-surface); margin-bottom: 8px;">
                                JUMLAH KURSI UNDANGAN KELUARGA (MAKS 4 ORANG)
                            </label>
                            <input type="number" name="jumlah_undangan" min="1" max="4" value="{{ $pendaftaran->jumlah_undangan ?? 2 }}" required class="m3-input">
                        </div>
                    </div>

                    <div style="margin-bottom: 22px;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--on-surface); margin-bottom: 8px;">
                            CATATAN KHUSUS / KEBUTUHAN PENDAMPING (OPSIONAL)
                        </label>
                        <input type="text" name="catatan" value="{{ $pendaftaran->catatan }}" placeholder="Contoh: Pendamping memerlukan akses kursi roda" class="m3-input">
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 18px; border-top: 1px solid var(--outline-variant); flex-wrap: wrap; gap: 12px;">
                        <button type="submit" class="btn-modern primary">
                            Simpan Formulir Perlengkapan &rarr;
                        </button>

                        @if($pendaftaran->status_pendaftaran === 'berkas_lengkap' || $pendaftaran->ukuran_toga)
                            <a href="{{ route('wisuda.cetak') }}" target="_blank" class="btn-modern secondary" style="border-color: var(--tertiary); color: var(--tertiary);">
                                Cetak Kartu Bukti Wisuda & Undangan &rarr;
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        @endif
    @endif
@endif
@endsection
