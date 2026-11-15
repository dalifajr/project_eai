<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pendaftaran Wisuda - {{ $mahasiswa->nim }} - {{ $mahasiswa->nama }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;700&family=Google+Sans+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Google Sans', -apple-system, BlinkMacSystemFont, Arial, sans-serif;
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
            font-weight: 700;
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
            border-radius: 8px;
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
        <button onclick="window.print()" style="padding: 10px 22px; font-size: 13px; font-weight: 700; cursor: pointer; background: #65558f; color: #fff; border: none; border-radius: 9999px; font-family: 'Google Sans', sans-serif;">
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
        <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 6px; font-weight: 700;">
            DATA CALON WISUDAWAN
        </h3>
        <table class="meta-table" style="margin-bottom: 0;">
            <tr>
                <td style="width: 25%;"><strong>Nomor Pendaftaran</strong></td>
                <td style="width: 75%;">: <span style="font-family: 'Google Sans Mono', monospace;">WSD-2026-{{ str_pad($pendaftaran->id, 4, '0', STR_PAD_LEFT) }}</span></td>
            </tr>
            <tr>
                <td><strong>NIM</strong></td>
                <td>: <span style="font-family: 'Google Sans Mono', monospace;">{{ $mahasiswa->nim }}</span></td>
            </tr>
            <tr>
                <td><strong>Nama Lengkap</strong></td>
                <td>: <strong>{{ $mahasiswa->nama }}</strong></td>
            </tr>
            <tr>
                <td><strong>Program Studi / Fakultas</strong></td>
                <td>: {{ $mahasiswa->program_studi }} / {{ $mahasiswa->fakultas }}</td>
            </tr>
            <tr>
                <td><strong>Status Kelulusan Skripsi</strong></td>
                <td>: LULUS YUDISIUM</td>
            </tr>
        </table>
    </div>

    <div class="card-box">
        <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 6px; font-weight: 700;">
            STATUS INTEGRASI EAI TOTAL BILLING SYSTEM
        </h3>
        <table class="meta-table" style="margin-bottom: 0;">
            <tr>
                <td style="width: 25%;"><strong>Nomor Virtual Account</strong></td>
                <td style="width: 75%;">: <strong style="font-family: 'Google Sans Mono', monospace;">{{ $pendaftaran->nomor_va }}</strong></td>
            </tr>
            <tr>
                <td><strong>Status Pembayaran</strong></td>
                <td>: <strong style="color: #146c2e;">LUNAS (TERVERIFIKASI REALTIME WEBHOOK)</strong></td>
            </tr>
            <tr>
                <td><strong>Nominal Pembayaran</strong></td>
                <td>: Rp {{ number_format($pendaftaran->nominal_wisuda, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Waktu Verifikasi Lunas</strong></td>
                <td>: {{ $pendaftaran->updated_at }}</td>
            </tr>
        </table>
    </div>

    <div class="card-box">
        <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 14px; border-bottom: 1px solid #ddd; padding-bottom: 6px; font-weight: 700;">
            RINCIAN PERLENGKAPAN & ALOKASI UNDANGAN
        </h3>
        <table class="meta-table" style="margin-bottom: 0;">
            <tr>
                <td style="width: 25%;"><strong>Ukuran Baju Jubah Toga</strong></td>
                <td style="width: 75%;">: <strong>Ukuran {{ $pendaftaran->ukuran_toga }}</strong></td>
            </tr>
            <tr>
                <td><strong>Alokasi Kursi Undangan</strong></td>
                <td>: {{ $pendaftaran->jumlah_undangan }} Orang (2 VIP + {{ max(0, $pendaftaran->jumlah_undangan - 2) }} Reguler)</td>
            </tr>
            <tr>
                <td><strong>Catatan Fasilitas Khusus</strong></td>
                <td>: {{ $pendaftaran->catatan ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="signatures">
        <div class="sig-block">
            <p>Calon Wisudawan,</p>
            <div class="sig-line"></div>
            <p style="margin-top: 6px;"><strong>{{ $mahasiswa->nama }}</strong></p>
            <p style="font-size: 11px;">NIM. {{ $mahasiswa->nim }}</p>
        </div>

        <div class="sig-block">
            <p>Panitia Wisuda Universitas,</p>
            <div class="sig-line"></div>
            <p style="margin-top: 6px;"><strong>Biro Administrasi Akademik</strong></p>
            <p style="font-size: 11px;">SIMAK EAI System Verified</p>
        </div>
    </div>
</body>
</html>
