@extends('layouts.app')

@section('title', 'Pendaftaran Wisuda Sarjana')

@section('content')
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 22px; font-weight: 700;">Pendaftaran Wisuda Periode 2026/2027</h2>
    <p style="color: var(--text-muted); font-size: 14px;">Integrasi Verifikasi Akademik SIMAK & Total Billing System</p>
</div>

<!-- STEP 1: VALIDASI PERSYARATAN AKADEMIK & SKRIPSI -->
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">1. Verifikasi Kelulusan Akademik & Bebas Puspa/Lab</h3>
            <p class="card-subtitle">Pemeriksaan integritas berkas akademik internal SIMAK</p>
        </div>
        @if($syaratAkademikLengkap)
            <span class="badge badge-success">SYARAT AKADEMIK TERPENUHI</span>
        @else
            <span class="badge badge-danger">PERSYARATAN BELUM LENGKAP</span>
        @endif
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
        <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px;">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Ujian Skripsi / Tugas Akhir</div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <strong>{{ strtoupper($mahasiswa->status_skripsi) }}</strong>
                @if($mahasiswa->status_skripsi === 'lulus')
                    <span class="badge badge-success">LULUS</span>
                @else
                    <span class="badge badge-danger">BELUM</span>
                @endif
            </div>
        </div>

        <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px;">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Bebas Laboratorium</div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <strong>{{ $mahasiswa->bebas_lab ? 'Terverifikasi' : 'Belum Diverifikasi' }}</strong>
                @if($mahasiswa->bebas_lab)
                    <span class="badge badge-success">BEBAS</span>
                @else
                    <span class="badge badge-danger">BELUM</span>
                @endif
            </div>
        </div>

        <div style="border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 14px;">
            <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Bebas Perpustakaan Pusat</div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <strong>{{ $mahasiswa->bebas_perpustakaan ? 'Terverifikasi' : 'Belum Diverifikasi' }}</strong>
                @if($mahasiswa->bebas_perpustakaan)
                    <span class="badge badge-success">BEBAS</span>
                @else
                    <span class="badge badge-danger">BELUM</span>
                @endif
            </div>
        </div>
    </div>

    @if(!$syaratAkademikLengkap)
        <div class="alert alert-danger" style="margin-top: 18px; margin-bottom: 0;">
            <strong>Pendaftaran Wisuda Ditolak: Persyaratan Akademik Belum Lengkap!</strong>
            <ul style="margin-left: 20px; margin-top: 6px; font-size: 13px;">
                @foreach($kekuranganSyarat as $syarat)
                    <li>{{ $syarat }}</li>
                @endforeach
            </ul>
            <p style="font-size: 12px; margin-top: 8px; color: var(--text-muted);">
                Pastikan Anda telah dinyatakan Lulus Sidang Skripsi oleh dewan penguji dan menuntaskan bebas pinjam pustaka/alat sebelum mendaftar wisuda.
            </p>
        </div>
    @endif
</div>

@if($syaratAkademikLengkap)
    <!-- STEP 2: CEK BEBAS TUNGGAKAN UKT & DENDA (EAI API CALL) -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">2. Cek Tunggakan Biaya Kuliah & Denda (Total Billing System)</h3>
                <p class="card-subtitle">Validasi riwayat tagihan seluruh semester via REST API EAI</p>
            </div>
            @if($tunggakan['has_debt'])
                <span class="badge badge-danger">TERDETEKSI TUNGGAKAN</span>
            @else
                <span class="badge badge-success">BEBAS TUNGGAKAN (CLEARED)</span>
            @endif
        </div>

        @if($tunggakan['has_debt'])
            <div class="alert alert-warning" style="margin-bottom: 0;">
                <strong>Perhatian: Ditemukan Tunggakan yang Belum Dilunasi!</strong>
                <p style="font-size: 13px; margin-top: 4px;">
                    Sesuai regulasi kampus, mahasiswa yang memiliki tunggakan UKT atau denda dilarang mengajukan paket wisuda sampai seluruh kewajiban dilunasi.
                </p>

                <div class="table-responsive" style="margin-top: 14px; background: #ffffff; border-radius: var(--radius-md);">
                    <table>
                        <thead>
                            <tr>
                                <th>Invoice</th>
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
                                <td>{{ $row['deskripsi'] }}</td>
                                <td>{{ $row['semester'] ?? '-' }}</td>
                                <td><strong>Rp {{ number_format($row['nominal'], 0, ',', '.') }}</strong></td>
                                <td><span style="font-family: monospace; font-weight: 700;">{{ $row['nomor_va'] }}</span></td>
                            </tr>
                            @endforeach
                            <tr style="font-weight: 700; background: #fef3c7;">
                                <td colspan="3" style="text-align: right;">Total Tanggungan Wajib Dilunasi:</td>
                                <td colspan="2">Rp {{ number_format($tunggakan['total_tunggakan'], 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 14px; display: flex; gap: 10px;">
                    <a href="http://localhost:8001" target="_blank" class="btn btn-primary" style="background:#0284c7; font-size:13px;">
                        Buka Billing Simulator untuk Melunasi Tagihan Denda Ini &rarr;
                    </a>
                    <button onclick="location.reload()" class="btn btn-outline" style="font-size:13px;">
                        Refresh Status Tunggakan
                    </button>
                </div>
            </div>
        @else
            <div class="alert alert-success" style="margin-bottom: 0;">
                <strong>Verifikasi Bebas Tunggakan Berhasil.</strong>
                <span style="font-size: 13px; margin-left: 8px;">
                    Mahasiswa terkonfirmasi bersih dari tunggakan UKT dan denda. Akses tagihan paket wisuda dibuka.
                </span>
            </div>
        @endif
    </div>

    <!-- STEP 3: TAGIHAN PAKET WISUDA & NOMOR VA (EAI SYNC & WEBHOOK) -->
    @if(!$tunggakan['has_debt'] && $pendaftaran)
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">3. Tagihan Paket Wisuda & Nomor Virtual Account (VA)</h3>
                    <p class="card-subtitle">Penerbitan kode bayar wisuda dari Total Billing System</p>
                </div>
                @if($pendaftaran->status_pembayaran === 'paid')
                    <span class="badge badge-success">PEMBAYARAN LUNAS</span>
                @else
                    <span class="badge badge-danger">MENUNGGU PEMBAYARAN</span>
                @endif
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px;">
                    <span style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Nomor Virtual Account (VA):</span>
                    <div style="font-size: 26px; font-weight: 700; color: var(--primary); letter-spacing: 0.05em; margin: 6px 0;">
                        {{ $pendaftaran->nomor_va ?? $tagihanWisuda['nomor_va'] }}
                    </div>
                    <div style="font-size: 13px; color: var(--text-muted);">
                        Bank Mitra: Bank Kampus EAI / Bank BNI / Mandiri / BCA
                    </div>
                </div>

                <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 18px;">
                    <span style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Total Nominal Tagihan Wisuda:</span>
                    <div style="font-size: 26px; font-weight: 700; color: #0f172a; margin: 6px 0;">
                        Rp {{ number_format($pendaftaran->nominal_wisuda, 0, ',', '.') }}
                    </div>
                    <div style="font-size: 13px; color: var(--text-muted);">
                        Paket Termasuk: Toga Wisuda, Gordon, Ijazah Asli, Transkrip & 2 Undangan
                    </div>
                </div>
            </div>

            @if($pendaftaran->status_pembayaran !== 'paid')
                <div class="alert alert-warning" style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong>Menunggu Pembayaran Virtual Account Wisuda</strong>
                        <p style="font-size: 13px; margin-top: 2px;">
                            Gunakan tombol simulator di Total Billing System untuk melakukan pelunasan instan. Setelah dibayar, webhook asinkron akan otomatis mengupdate status di SIMAK.
                        </p>
                    </div>
                    <a href="http://localhost:8001" target="_blank" class="btn btn-primary" style="background:#0284c7; font-size:13px; white-space:nowrap;">
                        Bayar VA via Billing Simulator &rarr;
                    </a>
                </div>
            @else
                <div class="alert alert-success">
                    <strong>Pelunasan Wisuda Berhasil Diterima melalui Webhook Callback EAI!</strong>
                    <p style="font-size: 13px; margin-top: 2px;">
                        Transaksi pembayaran telah terkonfirmasi. Silakan lengkapi formulir ukuran toga dan undangan di bawah ini.
                    </p>
                </div>
            @endif
        </div>

        <!-- STEP 4: FORM DATA LANJUTAN (TERBUKA HANYA SETELAH STATUS PAID) -->
        @if($pendaftaran->status_pembayaran === 'paid')
            <div class="card" style="border-top: 4px solid var(--success);">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">4. Formulir Kelengkapan Perlengkapan Wisuda & Undangan</h3>
                        <p class="card-subtitle">Pengisian ukuran jubah toga dan konfirmasi alokasi kursi undangan</p>
                    </div>
                    @if($pendaftaran->status_pendaftaran === 'berkas_lengkap')
                        <span class="badge badge-success">BERKAS LENGKAP</span>
                    @else
                        <span class="badge badge-warning">FORM PERLU DIISI</span>
                    @endif
                </div>

                <form action="{{ route('wisuda.data_lanjutan') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
                                Pilih Ukuran Baju Toga Wisuda:
                            </label>
                            <select name="ukuran_toga" required style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 14px;">
                                <option value="">-- Pilih Ukuran --</option>
                                <option value="S" {{ $pendaftaran->ukuran_toga === 'S' ? 'selected' : '' }}>Ukuran S (Panjang 105 cm, Lebar Dada 50 cm)</option>
                                <option value="M" {{ $pendaftaran->ukuran_toga === 'M' ? 'selected' : '' }}>Ukuran M (Panjang 110 cm, Lebar Dada 54 cm)</option>
                                <option value="L" {{ $pendaftaran->ukuran_toga === 'L' ? 'selected' : '' }}>Ukuran L (Panjang 115 cm, Lebar Dada 58 cm)</option>
                                <option value="XL" {{ $pendaftaran->ukuran_toga === 'XL' ? 'selected' : '' }}>Ukuran XL (Panjang 120 cm, Lebar Dada 62 cm)</option>
                                <option value="XXL" {{ $pendaftaran->ukuran_toga === 'XXL' ? 'selected' : '' }}>Ukuran XXL (Panjang 125 cm, Lebar Dada 66 cm)</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
                                Jumlah Undangan Keluarga (Maksimal 4 Orang):
                            </label>
                            <input type="number" name="jumlah_undangan" min="1" max="4" value="{{ $pendaftaran->jumlah_undangan ?? 2 }}" required
                                   style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 14px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Catatan Khusus (Opsional):</label>
                        <input type="text" name="catatan" value="{{ $pendaftaran->catatan }}" placeholder="Contoh: Pendamping menggunakan kursi roda / vegetarian"
                               style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 14px;">
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid var(--border-color);">
                        <button type="submit" class="btn btn-primary">
                            Simpan Formulir Perlengkapan Wisuda
                        </button>

                        @if($pendaftaran->status_pendaftaran === 'berkas_lengkap' || $pendaftaran->ukuran_toga)
                            <a href="{{ route('wisuda.cetak') }}" target="_blank" class="btn btn-outline" style="background:#15803d; color:#fff; border-color:#15803d;">
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
