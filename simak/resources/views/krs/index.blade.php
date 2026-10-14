@extends('layouts.app')

@section('title', 'Pengisian Kartu Rencana Studi (KRS)')

@section('content')
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 22px; font-weight: 700;">Pengisian Kartu Rencana Studi (KRS)</h2>
    <p style="color: var(--text-muted); font-size: 14px;">Semester {{ $mahasiswa->semester_sekarang }} Tahun Akademik 2026/2027</p>
</div>

<!-- 1. Validasi Status Akademik Mahasiswa -->
@if(!$akademikAktif)
    <div class="card" style="border-left: 4px solid var(--danger);">
        <div style="display: flex; gap: 16px; align-items: flex-start;">
            <div style="font-size: 28px;">🚫</div>
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--danger);">Akses Pengisian KRS Ditolak</h3>
                <p style="font-size: 14px; margin-top: 4px;">{{ $pesanAkademik }}</p>
            </div>
        </div>
    </div>
@else
    <!-- 2. Validasi Status Pembayaran UKT via EAI Total Billing System -->
    @if(!$uktStatus['is_paid'])
        <!-- FORM KRS TERKUNCI (UKT BELUM LUNAS) -->
        <div class="card" style="border-left: 4px solid #b45309; background-color: #fffbeb;">
            <div style="display: flex; gap: 16px; align-items: flex-start;">
                <div style="font-size: 32px;">🔒</div>
                <div style="flex: 1;">
                    <span class="badge badge-warning" style="margin-bottom: 8px;">INTEGRASI EAI: FORM KRS TERKUNCI</span>
                    <h3 style="font-size: 18px; font-weight: 700; color: #b45309;">
                        KRS Terkunci: Tagihan UKT Semester {{ $mahasiswa->semester_sekarang }} Belum Dilunasi
                    </h3>
                    <p style="font-size: 14px; color: #78350f; margin-top: 6px;">
                        Berdasarkan verifikasi sistem penagihan terpusat (Total Billing System), Anda belum melakukan pelunasan UKT semester ini. Pengisian KRS hanya dapat dibuka setelah pembayaran terverifikasi lunas.
                    </p>

                    @if(isset($uktStatus['data']['nomor_va']))
                    <div style="background: #ffffff; border: 1px solid #fde68a; border-radius: var(--radius-md); padding: 16px; margin: 16px 0; max-width: 520px;">
                        <div style="font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Rincian Virtual Account UKT:</div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                            <span style="font-size: 20px; font-weight: 700; color: #1e3a8a; letter-spacing: 0.05em;">
                                {{ $uktStatus['data']['nomor_va'] }}
                            </span>
                            <span class="badge badge-danger">STATUS: UNPAID</span>
                        </div>
                        <div style="margin-top: 8px; font-size: 13px; color: var(--text-main); display: flex; justify-content: space-between;">
                            <span>Nominal Tagihan:</span>
                            <strong>Rp {{ number_format($uktStatus['data']['nominal'] ?? 0, 0, ',', '.') }}</strong>
                        </div>
                        <div style="margin-top: 4px; font-size: 12px; color: var(--text-muted); display: flex; justify-content: space-between;">
                            <span>Batas Jatuh Tempo:</span>
                            <span>{{ $uktStatus['data']['jatuh_tempo'] ?? '-' }}</span>
                        </div>
                    </div>
                    @endif

                    <div style="display: flex; gap: 12px; align-items: center; margin-top: 14px;">
                        <a href="http://localhost:8001" target="_blank" class="btn btn-primary" style="background:#0284c7; font-size:13px;">
                            Buka Billing Simulator untuk Simulasi Bayar UKT &rarr;
                        </a>
                        <button onclick="location.reload()" class="btn btn-outline" style="font-size:13px;">
                            Cek Ulang Status Pembayaran (Refresh)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- UKT SUDAH LUNAS: FORM KRS TERBUKA -->
        <div class="alert alert-success" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong>Verifikasi Pembayaran UKT: LUNAS</strong>
                <span style="font-size: 13px; margin-left: 8px; color: var(--text-muted);">
                    (Invoice: {{ $uktStatus['data']['nomor_invoice'] ?? '-' }} | Total: Rp {{ number_format($uktStatus['data']['nominal'] ?? 0, 0, ',', '.') }})
                </span>
            </div>
            <span class="badge badge-success">FORM KRS DIBUKA</span>
        </div>

        @if($krsAktif)
            <!-- Tampilan KRS yang sudah tersimpan -->
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Kartu Rencana Studi Tersimpan</h3>
                        <p class="card-subtitle">Status: Disetujui Dosen Pembimbing Akademik</p>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('krs.cetak') }}" target="_blank" class="btn btn-primary" style="font-size: 13px; min-height: 38px;">
                            Cetak Draft KRS
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Hari / Waktu</th>
                                <th>Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($krsAktif->details as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td><strong>{{ $item->mataKuliah->kode_mk }}</strong></td>
                                <td>{{ $item->mataKuliah->nama_mk }}</td>
                                <td><span class="badge badge-secondary">{{ $item->mataKuliah->sks }} SKS</span></td>
                                <td>{{ $item->mataKuliah->hari }}, {{ substr($item->mataKuliah->jam_mulai, 0, 5) }} - {{ substr($item->mataKuliah->jam_selesai, 0, 5) }}</td>
                                <td>{{ $item->mataKuliah->ruangan }}</td>
                            </tr>
                            @endforeach
                            <tr style="background-color: #f8fafc; font-weight: 700;">
                                <td colspan="3" style="text-align: right;">Total Beban SKS Terambil:</td>
                                <td colspan="3"><span class="badge badge-success" style="font-size: 13px;">{{ $krsAktif->total_sks }} SKS</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Form Pemilihan Mata Kuliah Tawaran -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">{{ $krsAktif ? 'Perbarui Pilihan Mata Kuliah' : 'Pilih Mata Kuliah Tawaran' }}</h3>
                    <p class="card-subtitle">
                        Batas Beban SKS Anda: <strong>{{ $mahasiswa->batas_sks }} SKS</strong> (Berdasarkan IPS semester lalu: {{ $mahasiswa->ips_lalu }})
                    </p>
                </div>
                <div id="sks-counter-badge" class="badge badge-secondary" style="font-size: 14px; padding: 6px 14px;">
                    SKS Dipilih: <strong id="selected-sks" style="margin-left: 4px;">0</strong> / {{ $mahasiswa->batas_sks }}
                </div>
            </div>

            <form action="{{ route('krs.store') }}" method="POST" id="form-krs">
                @csrf
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;">Pilih</th>
                                <th>Kode</th>
                                <th>Nama Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Hari & Jadwal</th>
                                <th>Ruangan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $selectedIds = $krsAktif ? $krsAktif->details->pluck('kode_mk')->toArray() : [];
                            @endphp
                            @foreach($mataKuliah as $mk)
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" name="mata_kuliah[]" value="{{ $mk->kode_mk }}"
                                           data-sks="{{ $mk->sks }}"
                                           data-hari="{{ $mk->hari }}"
                                           data-mulai="{{ $mk->jam_mulai }}"
                                           data-selesai="{{ $mk->jam_selesai }}"
                                           class="mk-checkbox"
                                           style="width: 18px; height: 18px; cursor: pointer;"
                                           {{ in_array($mk->kode_mk, $selectedIds) ? 'checked' : '' }}>
                                </td>
                                <td><strong>{{ $mk->kode_mk }}</strong></td>
                                <td>{{ $mk->nama_mk }}</td>
                                <td><span class="badge badge-secondary">{{ $mk->sks }} SKS</span></td>
                                <td>
                                    <strong>{{ $mk->hari }}</strong>, {{ substr($mk->jam_mulai, 0, 5) }} - {{ substr($mk->jam_selesai, 0, 5) }}
                                </td>
                                <td>{{ $mk->ruangan }}</td>
                                <td>
                                    @if($mk->kode_mk === 'TIF701' || $mk->kode_mk === 'TIF702')
                                        <span style="font-size: 11px; color: var(--warning); font-weight: 600;">Jadwal Senin 08:00 (Uji Bentrok)</span>
                                    @else
                                        <span style="font-size: 12px; color: var(--text-muted);">Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid var(--border-color);">
                    <div style="font-size: 13px; color: var(--text-muted);">
                        Pastikan tidak memilih jadwal yang saling bertabrakan dan tidak melebihi {{ $mahasiswa->batas_sks }} SKS.
                    </div>
                    <button type="submit" class="btn btn-primary" id="btn-submit-krs">
                        Simpan Kartu Rencana Studi (KRS)
                    </button>
                </div>
            </form>
        </div>
    @endif
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.mk-checkbox');
        const selectedSksEl = document.getElementById('selected-sks');
        const badgeEl = document.getElementById('sks-counter-badge');
        const maxSks = {{ $mahasiswa->batas_sks }};

        function calculateSks() {
            let total = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseInt(cb.getAttribute('data-sks') || 0);
                }
            });

            if (selectedSksEl) {
                selectedSksEl.textContent = total;
            }

            if (badgeEl) {
                if (total > maxSks) {
                    badgeEl.className = 'badge badge-danger';
                } else if (total > 0) {
                    badgeEl.className = 'badge badge-success';
                } else {
                    badgeEl.className = 'badge badge-secondary';
                }
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', calculateSks);
        });

        calculateSks();
    });
</script>
@endsection
