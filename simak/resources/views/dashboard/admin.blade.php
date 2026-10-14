@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="margin-bottom: 0; padding: 18px;">
        <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">TOTAL MAHASISWA</span>
        <div style="font-size: 26px; font-weight: 700; margin-top: 4px; color: var(--primary);">{{ $totalMahasiswa }}</div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 18px;">
        <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">MATA KULIAH AKTIF</span>
        <div style="font-size: 26px; font-weight: 700; margin-top: 4px; color: #0284c7;">{{ $totalMataKuliah }}</div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 18px;">
        <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">KRS TERSIMPAN</span>
        <div style="font-size: 26px; font-weight: 700; margin-top: 4px; color: #16a34a;">{{ $totalKrs }}</div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 18px;">
        <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">PENDAFTAR WISUDA</span>
        <div style="font-size: 26px; font-weight: 700; margin-top: 4px; color: #9333ea;">{{ $totalWisuda }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Rekapitulasi Mahasiswa & Skenario Pengujian EAI</h2>
            <p class="card-subtitle">Daftar mahasiswa yang disiapkan untuk menguji skenario integrasi dengan Total Billing System</p>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Prodi / Smt</th>
                    <th>Status Akademik</th>
                    <th>Status Skripsi</th>
                    <th>Syarat Lab & Puspa</th>
                    <th>Pendaftaran Wisuda</th>
                    <th>Aksi Pengujian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allMahasiswa as $mhs)
                <tr>
                    <td><strong>{{ $mhs->nim }}</strong></td>
                    <td>{{ $mhs->nama }}</td>
                    <td>{{ $mhs->program_studi }} (Smt {{ $mhs->semester_sekarang }})</td>
                    <td>
                        <span class="badge {{ $mhs->status_akademik === 'aktif' ? 'badge-success' : 'badge-danger' }}">
                            {{ strtoupper($mhs->status_akademik) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $mhs->status_skripsi === 'lulus' ? 'badge-success' : 'badge-warning' }}">
                            {{ strtoupper($mhs->status_skripsi) }}
                        </span>
                    </td>
                    <td>
                        @if($mhs->bebas_lab && $mhs->bebas_perpustakaan)
                            <span class="badge badge-success">LENGKAP</span>
                        @else
                            <span class="badge badge-secondary">BELUM LENGKAP</span>
                        @endif
                    </td>
                    <td>
                        @if($mhs->pendaftaranWisuda)
                            <span class="badge {{ $mhs->pendaftaranWisuda->status_pembayaran === 'paid' ? 'badge-success' : 'badge-warning' }}">
                                {{ strtoupper($mhs->pendaftaranWisuda->status_pendaftaran) }} ({{ strtoupper($mhs->pendaftaranWisuda->status_pembayaran) }})
                            </span>
                        @else
                            <span style="color: var(--text-muted); font-size: 12px;">Belum Mendaftar</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('quick.login', $mhs->nim) }}" class="btn btn-outline" style="font-size: 11px; min-height: 32px; padding: 4px 10px;">
                            Login Sebagai Mahasiswa &rarr;
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
