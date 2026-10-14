<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pendaftaran Wisuda - {{ $mahasiswa->nim }} - {{ $mahasiswa->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #111;
            padding: 30px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .header p {
            font-size: 12px;
            margin: 0;
            color: #444;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-table td {
            padding: 6px 0;
            vertical-align: top;
        }
        .card-box {
            border: 1px solid #333;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 20px;
            background: #fafafa;
        }
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .sig-block {
            text-align: center;
            width: 220px;
        }
        .sig-line {
            margin-top: 70px;
            border-top: 1px solid #000;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 18px; font-size: 13px; font-weight: bold; cursor: pointer; background: #1e3a8a; color: #fff; border: none; border-radius: 4px;">
            Cetak Bukti Pendaftaran Wisuda
        </button>
    </div>

    <div class="header">
        <h1>BUKTI TANDA TERIMA PENDAFTARAN WISUDA SARJANA</h1>
        <p>KEMENTERIAN PENDIDIKAN TINGGI, RISET, DAN TEKNOLOGI</p>
        <p>UNIVERSITAS TEKNOLOGI INFORMASI NUSANTARA</p>
        <p>TAHUN AKADEMIK 2026/2027</p>
    </div>

    <div class="card-box">
        <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 6px;">
            DATA CALON WISUDAWAN
        </h3>
        <table class="meta-table" style="margin-bottom: 0;">
            <tr>
                <td style="width: 25%;"><strong>Nomor Pendaftaran</strong></td>
                <td style="width: 75%;">: WSD-2026-{{ str_pad($pendaftaran->id, 4, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td><strong>NIM</strong></td>
                <td>: {{ $mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td><strong>Nama Lengkap</strong></td>
                <td>: {{ $mahasiswa->nama }}</td>
            </tr>
            <tr>
                <td><strong>Program Studi / Fakultas</strong></td>
                <td>: {{ $mahasiswa->program_studi }} / {{ $mahasiswa->fakultas }}</td>
            </tr>
            <tr>
                <td><strong>Status Kelulusan Skripsi</strong></td>
                <td>: LULUS SIDANG TUGAS AKHIR</td>
            </tr>
            <tr>
                <td><strong>Verifikasi Bebas Lab / Pustaka</strong></td>
                <td>: TERVERIFIKASI LENGKAP</td>
            </tr>
        </table>
    </div>

    <div class="card-box">
        <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 6px;">
            STATUS TRANSAKSI & PERLENGKAPAN TOGA (EAI VERIFIED)
        </h3>
        <table class="meta-table" style="margin-bottom: 0;">
            <tr>
                <td style="width: 25%;"><strong>Invoice Paket Wisuda</strong></td>
                <td style="width: 75%;">: {{ $pendaftaran->invoice_wisuda_id }} (Rp {{ number_format($pendaftaran->nominal_wisuda, 0, ',', '.') }})</td>
            </tr>
            <tr>
                <td><strong>Nomor Virtual Account</strong></td>
                <td>: {{ $pendaftaran->nomor_va }}</td>
            </tr>
            <tr>
                <td><strong>Status Pembayaran</strong></td>
                <td>: <strong style="color: green;">LUNAS (PAID)</strong></td>
            </tr>
            <tr>
                <td><strong>Ukuran Jubah Toga</strong></td>
                <td>: <strong>{{ $pendaftaran->ukuran_toga ?? 'Belum ditentukan' }}</strong></td>
            </tr>
            <tr>
                <td><strong>Alokasi Undangan Pendamping</strong></td>
                <td>: {{ $pendaftaran->jumlah_undangan }} Kursi VIP</td>
            </tr>
            <tr>
                <td><strong>Catatan Pendaftaran</strong></td>
                <td>: {{ $pendaftaran->catatan ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <p style="font-size: 12px; color: #555; margin-top: 15px;">
        * Harap membawa lembar bukti pendaftaran ini pada saat pengambilan toga dan gladi resik wisuda di Gedung Rektorat.
    </p>

    <div class="signatures">
        <div class="sig-block">
            <p>Panitia Pelaksana Wisuda,</p>
            <p>Biro Administrasi Akademik (BAA)</p>
            <div class="sig-line"></div>
            <p>Dr. Ir. Hendra Gunawan, M.Kom.<br>NIP. 19750812 200003 1 001</p>
        </div>
        <div class="sig-block">
            <p>Kota Kampus, {{ date('d F Y') }}</p>
            <p>Calon Wisudawan</p>
            <div class="sig-line"></div>
            <p><strong>{{ $mahasiswa->nama }}</strong><br>NIM. {{ $mahasiswa->nim }}</p>
        </div>
    </div>
</body>
</html>
