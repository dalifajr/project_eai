<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>KRS - {{ $mahasiswa->nim }} - {{ $mahasiswa->nama }}</title>
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
            padding: 4px 0;
            vertical-align: top;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .data-table th, .data-table td {
            border: 1px solid #333;
            padding: 8px 10px;
            text-align: left;
        }
        .data-table th {
            background-color: #f2f2f2;
            font-weight: 700;
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
            Cetak Dokumen KRS
        </button>
    </div>

    <div class="header">
        <h1>KARTU RENCANA STUDI (KRS)</h1>
        <p>KEMENTERIAN PENDIDIKAN TINGGI, RISET, DAN TEKNOLOGI</p>
        <p>UNIVERSITAS TEKNOLOGI INFORMASI NUSANTARA</p>
        <p>Tahun Akademik {{ $krs->tahun_akademik }} - Semester {{ $krs->semester }}</p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 15%;"><strong>NIM</strong></td>
            <td style="width: 35%;">: {{ $mahasiswa->nim }}</td>
            <td style="width: 15%;"><strong>Fakultas</strong></td>
            <td style="width: 35%;">: {{ $mahasiswa->fakultas }}</td>
        </tr>
        <tr>
            <td><strong>Nama Mahasiswa</strong></td>
            <td>: {{ $mahasiswa->nama }}</td>
            <td><strong>Program Studi</strong></td>
            <td>: {{ $mahasiswa->program_studi }}</td>
        </tr>
        <tr>
            <td><strong>Semester</strong></td>
            <td>: Semester {{ $krs->semester }}</td>
            <td><strong>Batas Beban SKS</strong></td>
            <td>: {{ $mahasiswa->batas_sks }} SKS</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">No</th>
                <th style="width: 90px;">Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th style="width: 50px; text-align: center;">SKS</th>
                <th style="width: 180px;">Jadwal Perkuliahan</th>
                <th style="width: 80px;">Ruangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($krs->details as $item)
            <tr>
                <td style="text-align: center;">{{ $no++ }}</td>
                <td style="font-family: 'Google Sans Mono', monospace;">{{ $item->mataKuliah->kode_mk }}</td>
                <td><strong>{{ $item->mataKuliah->nama_mk }}</strong></td>
                <td style="text-align: center;">{{ $item->mataKuliah->sks }}</td>
                <td>{{ $item->mataKuliah->hari }}, {{ substr($item->mataKuliah->jam_mulai, 0, 5) }} - {{ substr($item->mataKuliah->jam_selesai, 0, 5) }}</td>
                <td>{{ $item->mataKuliah->ruangan }}</td>
            </tr>
            @endforeach
            <tr style="font-weight: bold; background-color: #fafafa;">
                <td colspan="3" style="text-align: right; padding-right: 12px;">Total SKS yang Diambil:</td>
                <td style="text-align: center;">{{ $krs->total_sks }} SKS</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <div class="signatures">
        <div class="sig-block">
            <p>Mahasiswa Pemohon,</p>
            <div class="sig-line"></div>
            <p style="margin-top: 6px;"><strong>{{ $mahasiswa->nama }}</strong></p>
            <p style="font-size: 11px;">NIM. {{ $mahasiswa->nim }}</p>
        </div>

        <div class="sig-block">
            <p>Dosen Pembimbing Akademik,</p>
            <div class="sig-line"></div>
            <p style="margin-top: 6px;"><strong>Dr. Ir. Hendra Wijaya, M.Kom</strong></p>
            <p style="font-size: 11px;">NIP. 198504122010121003</p>
        </div>
    </div>
</body>
</html>
