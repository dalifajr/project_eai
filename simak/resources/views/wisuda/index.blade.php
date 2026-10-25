@extends('layouts.app')

@section('title', 'Pendaftaran Wisuda Sarjana')

@section('content')
<!-- Page Header Info -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
    <div>
        <span class="badge-pill brand" style="margin-bottom: 6px;">PORTAL KELULUSAN & ALUMNI</span>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--text-heading); letter-spacing: -0.02em;">
            Pendaftaran Wisuda Periode 2026/2027
        </h1>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">
            Alur Validasi Multidisiplin: Verifikasi Akademik Internal SIMAK &bull; Integrasi Finansial Total Billing System
        </p>
    </div>
</div>

<!-- Modern 4-Step Process Timeline Bar -->
<div class="card-modern" style="padding: 18px 24px; margin-bottom: 24px;">
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; position: relative;">
        <!-- Step 1 -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $syaratAkademikLengkap ? '#10b981' : '#ef4444' }}; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                {{ $syaratAkademikLengkap ? '✓' : '1' }}
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 700; color: var(--text-heading);">Tahap 1: Akademik</div>
                <div style="font-size: 11px; color: var(--text-muted);">{{ $syaratAkademikLengkap ? 'Lolos Sidang' : 'Belum Lengkap' }}</div>
            </div>
        </div>

        <!-- Step 2 -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ !$syaratAkademikLengkap ? '#cbd5e1' : (!empty($tunggakan['has_debt']) ? '#ef4444' : '#10b981') }}; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                {{ !$syaratAkademikLengkap ? '2' : (!empty($tunggakan['has_debt']) ? '!' : '✓') }}
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 700; color: var(--text-heading);">Tahap 2: Bebas Tunggakan</div>
                <div style="font-size: 11px; color: var(--text-muted);">{{ !$syaratAkademikLengkap ? 'Menunggu Tahap 1' : (!empty($tunggakan['has_debt']) ? 'Ada Denda' : 'Bebas Tunggakan') }}</div>
            </div>
        </div>

        <!-- Step 3 -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ (!$syaratAkademikLengkap || !empty($tunggakan['has_debt'])) ? '#cbd5e1' : (($pendaftaran && $pendaftaran->status_pembayaran === 'paid') ? '#10b981' : '#f59e0b') }}; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                {{ ($pendaftaran && $pendaftaran->status_pembayaran === 'paid') ? '✓' : '3' }}
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 700; color: var(--text-heading);">Tahap 3: Biaya Wisuda</div>
                <div style="font-size: 11px; color: var(--text-muted);">{{ ($pendaftaran && $pendaftaran->status_pembayaran === 'paid') ? 'Lunas Webhook' : 'Menunggu Bayar' }}</div>
            </div>
        </div>

        <!-- Step 4 -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ ($pendaftaran && $pendaftaran->status_pembayaran === 'paid' && $pendaftaran->ukuran_toga) ? '#10b981' : '#cbd5e1' }}; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px;">
                {{ ($pendaftaran && $pendaftaran->status_pembayaran === 'paid' && $pendaftaran->ukuran_toga) ? '✓' : '4' }}
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 700; color: var(--text-heading);">Tahap 4: Toga & Undangan</div>
                <div style="font-size: 11px; color: var(--text-muted);">{{ ($pendaftaran && $pendaftaran->ukuran_toga) ? 'Berkas Final' : 'Belum Diisi' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- STEP 1: VALIDASI PERSYARATAN AKADEMIK & SKRIPSI -->
<div class="card-modern">
    <div class="card-header-modern">
        <div>
            <h2 class="card-title-modern">1. Verifikasi Kelulusan Akademik & Bebas Puspa/Lab</h2>
            <p class="card-subtitle-modern">Pemeriksaan integritas berkas akademik internal SIMAK</p>
        </div>
        @if($syaratAkademikLengkap)
            <span class="badge-pill success">SYARAT AKADEMIK TERPENUHI</span>
        @else
            <span class="badge-pill danger">PERSYARATAN BELUM LENGKAP</span>
        @endif
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
        <div style="border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 14px; background: var(--bg-subtle);">
            <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Ujian Sidang Skripsi</div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                <strong style="color: var(--text-heading);">{{ strtoupper($mahasiswa->status_skripsi) }}</strong>
                @if($mahasiswa->status_skripsi === 'lulus')
                    <span class="badge-pill success">LULUS</span>
                @else
                    <span class="badge-pill danger">BELUM LULUS</span>
                @endif
            </div>
        </div>

        <div style="border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 14px; background: var(--bg-subtle);">
            <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Bebas Laboratorium</div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                <strong style="color: var(--text-heading);">{{ $mahasiswa->bebas_lab ? 'Terverifikasi' : 'Belum Selesai' }}</strong>
                @if($mahasiswa->bebas_lab)
                    <span class="badge-pill success">BEBAS</span>
                @else
                    <span class="badge-pill danger">BELUM</span>
                @endif
            </div>
        </div>

        <div style="border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 14px; background: var(--bg-subtle);">
            <div style="font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Bebas Perpustakaan</div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                <strong style="color: var(--text-heading);">{{ $mahasiswa->bebas_perpustakaan ? 'Terverifikasi' : 'Belum Selesai' }}</strong>
                @if($mahasiswa->bebas_perpustakaan)
                    <span class="badge-pill success">BEBAS</span>
                @else
                    <span class="badge-pill danger">BELUM</span>
                @endif
            </div>
        </div>
    </div>

    @if(!$syaratAkademikLengkap)
        <div class="banner-alert danger" style="margin-top: 18px; margin-bottom: 0;">
            <span style="font-size: 18px;">✕</span>
            <div>
                <strong>Pendaftaran Wisuda Ditolak: Persyaratan Akademik Belum Lengkap</strong>
                <ul style="margin-left: 20px; margin-top: 6px; font-size: 13px;">
                    @foreach($kekuranganSyarat as $syarat)
                        <li>{{ $syarat }}</li>
                    @endforeach
                </ul>
                <p style="font-size: 12px; margin-top: 6px; color: #991b1b;">
                    Pastikan Anda telah dinyatakan Lulus Sidang Skripsi dan menuntaskan bebas pinjam pustaka/alat sebelum melanjutkan ke tahap pembayaran.
                </p>
            </div>
        </div>
    @endif
</div>

@if($syaratAkademikLengkap)
    <!-- STEP 2: CEK BEBAS TUNGGAKAN UKT & DENDA (EAI API CALL) -->
    <div class="card-modern">
        <div class="card-header-modern">
            <div>
                <h2 class="card-title-modern">2. Cek Bebas Tunggakan Biaya Kuliah & Denda (EAI REST API)</h2>
                <p class="card-subtitle-modern">Validasi riwayat tagihan seluruh semester ke Total Billing System</p>
            </div>
            @if($tunggakan['has_debt'])
                <span class="badge-pill danger">TERDETEKSI TUNGGAKAN</span>
            @else
                <span class="badge-pill success">BEBAS TUNGGAKAN (CLEARED)</span>
            @endif
        </div>

        @if($tunggakan['has_debt'])
            <div class="banner-alert warning" style="margin-bottom: 0;">
                <span style="font-size: 18px;">⚠️</span>
                <div style="flex: 1;">
                    <strong>Perhatian: Ditemukan Tunggakan yang Belum Dilunasi!</strong>
                    <p style="font-size: 13px; margin-top: 4px;">
                        Sesuai regulasi kampus, mahasiswa yang memiliki tunggakan UKT atau denda dilarang mendaftar paket wisuda sampai seluruh kewajiban dilunasi.
                    </p>

                    <div class="table-container-modern" style="margin-top: 14px;">
                        <table class="table-modern">
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
                                    <td><code>{{ $row['invoice_id'] }}</code></td>
                                    <td><strong>{{ $row['deskripsi'] }}</strong></td>
                                    <td>{{ $row['semester'] ?? '-' }}</td>
                                    <td><strong>Rp {{ number_format($row['nominal'], 0, ',', '.') }}</strong></td>
                                    <td><span style="font-family: var(--font-mono); font-weight: 700; color: var(--brand-primary);">{{ $row['nomor_va'] }}</span></td>
                                </tr>
                                @endforeach
                                <tr style="font-weight: 700; background: #fef3c7;">
                                    <td colspan="3" style="text-align: right; padding-right: 16px;">Total Kewajiban Wajib Dilunasi:</td>
                                    <td colspan="2"><span style="font-size: 14px; color: #b45309;">Rp {{ number_format($tunggakan['total_tunggakan'], 0, ',', '.') }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 14px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <a href="http://localhost:8001" target="_blank" class="btn-modern primary" style="background:#0f766e; font-size:13px;">
                            Buka Billing Simulator untuk Melunasi Tagihan Denda Ini &rarr;
                        </a>
                        <button onclick="location.reload()" class="btn-modern secondary sm" style="font-size:12px;">
                            Refresh Status Tunggakan
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="banner-alert success" style="margin-bottom: 0;">
                <span style="font-size: 18px;">✓</span>
                <div>
                    <strong>Verifikasi Bebas Tunggakan Berhasil</strong>
                    <div style="font-size: 12px; color: #065f46; margin-top: 2px;">
                        Mahasiswa terkonfirmasi bersih dari segala tunggakan UKT dan denda akademik. Akses penerbitan tagihan wisuda telah dibuka.
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- STEP 3: TAGIHAN PAKET WISUDA & NOMOR VA (EAI SYNC & WEBHOOK) -->
    @if(!$tunggakan['has_debt'] && $pendaftaran)
        <div class="card-modern">
            <div class="card-header-modern">
                <div>
                    <h2 class="card-title-modern">3. Tagihan Paket Wisuda & Nomor Virtual Account (VA)</h2>
                    <p class="card-subtitle-modern">Penerbitan kode bayar wisuda resmi dari Total Billing System</p>
                </div>
                @if($pendaftaran->status_pembayaran === 'paid')
                    <span class="badge-pill success">PEMBAYARAN LUNAS</span>
                @else
                    <span class="badge-pill warning">MENUNGGU PEMBAYARAN</span>
                @endif
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div style="background: var(--bg-subtle); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 18px;">
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Nomor Virtual Account Wisuda:</span>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin: 8px 0;">
                        <span style="font-family: var(--font-mono); font-size: 24px; font-weight: 800; color: var(--brand-primary); letter-spacing: 0.04em;">
                            {{ $pendaftaran->nomor_va ?? $tagihanWisuda['nomor_va'] }}
                        </span>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $pendaftaran->nomor_va ?? $tagihanWisuda['nomor_va'] }}'); alert('Nomor VA Wisuda berhasil disalin!');" class="btn-modern secondary sm">
                            Salin VA
                        </button>
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted);">
                        Bank Kampus EAI &bull; BNI &bull; Mandiri &bull; BCA
                    </div>
                </div>

                <div style="background: var(--bg-subtle); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 18px;">
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Total Nominal Tagihan Wisuda:</span>
                    <div style="font-size: 24px; font-weight: 800; color: var(--text-heading); margin: 8px 0;">
                        Rp {{ number_format($pendaftaran->nominal_wisuda, 0, ',', '.') }}
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted);">
                        Paket Lengkap: Toga, Gordon, Ijazah Asli, Transkrip & 2 Undangan Keluarga
                    </div>
                </div>
            </div>

            @if($pendaftaran->status_pembayaran !== 'paid')
                <div class="banner-alert warning" style="justify-content: space-between; align-items: center;">
                    <div>
                        <strong>Menunggu Pembayaran Tagihan Paket Wisuda</strong>
                        <p style="font-size: 12px; margin-top: 2px;">
                            Silakan buka simulator pembayaran di Total Billing System untuk simulasi pelunasan instan. Setelah lunas, webhook callback akan seketika membuka Tahap 4.
                        </p>
                    </div>
                    <a href="http://localhost:8001" target="_blank" class="btn-modern primary" style="background:#0f766e; font-size:13px; white-space:nowrap;">
                        Bayar VA via Billing Simulator &rarr;
                    </a>
                </div>
            @else
                <div class="banner-alert success">
                    <span style="font-size: 18px;">✓</span>
                    <div>
                        <strong>Pelunasan Wisuda Berhasil Diterima melalui Webhook Callback EAI</strong>
                        <p style="font-size: 12px; margin-top: 2px;">
                            Transaksi pembayaran telah terkonfirmasi secara realtime. Silakan lengkapi ukuran baju toga dan konfirmasi alokasi undangan di formulir Tahap 4 berikut.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- STEP 4: FORM DATA LANJUTAN (TERBUKA HANYA SETELAH STATUS PAID) -->
        @if($pendaftaran->status_pembayaran === 'paid')
            <div class="card-modern" style="border-top: 4px solid #10b981;">
                <div class="card-header-modern">
                    <div>
                        <h2 class="card-title-modern">4. Formulir Perlengkapan Wisuda & Alokasi Kursi Undangan</h2>
                        <p class="card-subtitle-modern">Pengisian ukuran jubah toga dan alokasi kursi undangan wisudawan</p>
                    </div>
                    @if($pendaftaran->status_pendaftaran === 'berkas_lengkap')
                        <span class="badge-pill success">BERKAS LENGKAP</span>
                    @else
                        <span class="badge-pill warning">FORM PERLU DISIMPAN</span>
                    @endif
                </div>

                <form action="{{ route('wisuda.data_lanjutan') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-heading); margin-bottom: 6px;">
                                UKURAN JUBAH TOGA WISUDA
                            </label>
                            <select name="ukuran_toga" required style="width: 100%; height: 42px; padding: 0 14px; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); font-size: 13px; font-family: var(--font-sans); background: #ffffff;">
                                <option value="">-- Pilih Ukuran Jubah --</option>
                                <option value="S" {{ $pendaftaran->ukuran_toga === 'S' ? 'selected' : '' }}>Ukuran S (Panjang 105 cm, Dada 50 cm)</option>
                                <option value="M" {{ $pendaftaran->ukuran_toga === 'M' ? 'selected' : '' }}>Ukuran M (Panjang 110 cm, Dada 54 cm)</option>
                                <option value="L" {{ $pendaftaran->ukuran_toga === 'L' ? 'selected' : '' }}>Ukuran L (Panjang 115 cm, Dada 58 cm)</option>
                                <option value="XL" {{ $pendaftaran->ukuran_toga === 'XL' ? 'selected' : '' }}>Ukuran XL (Panjang 120 cm, Dada 62 cm)</option>
                                <option value="XXL" {{ $pendaftaran->ukuran_toga === 'XXL' ? 'selected' : '' }}>Ukuran XXL (Panjang 125 cm, Dada 66 cm)</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-heading); margin-bottom: 6px;">
                                JUMLAH KURSI UNDANGAN KELUARGA (MAKS 4 ORANG)
                            </label>
                            <input type="number" name="jumlah_undangan" min="1" max="4" value="{{ $pendaftaran->jumlah_undangan ?? 2 }}" required
                                   style="width: 100%; height: 42px; padding: 0 14px; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); font-size: 13px; font-family: var(--font-sans); background: #ffffff;">
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-heading); margin-bottom: 6px;">
                            CATATAN KHUSUS / KEBUTUHAN PENDAMPING (OPSIONAL)
                        </label>
                        <input type="text" name="catatan" value="{{ $pendaftaran->catatan }}" placeholder="Contoh: Pendamping memerlukan akses kursi roda"
                               style="width: 100%; height: 42px; padding: 0 14px; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); font-size: 13px; font-family: var(--font-sans); background: #ffffff;">
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid var(--border-subtle);">
                        <button type="submit" class="btn-modern primary">
                            Simpan Formulir Perlengkapan &rarr;
                        </button>

                        @if($pendaftaran->status_pendaftaran === 'berkas_lengkap' || $pendaftaran->ukuran_toga)
                            <a href="{{ route('wisuda.cetak') }}" target="_blank" class="btn-modern secondary" style="border-color: #10b981; color: #047857;">
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
