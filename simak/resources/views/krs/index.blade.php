@extends('layouts.app')

@section('title', 'Pengisian Kartu Rencana Studi (KRS)')

@section('content')
<!-- Page Header Info -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
    <div>
        <span class="badge-pill brand" style="margin-bottom: 6px;">PORTAL RENCANA STUDI</span>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--text-heading); letter-spacing: -0.02em;">
            Pengisian Kartu Rencana Studi (KRS)
        </h1>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">
            Semester {{ $mahasiswa->semester_sekarang }} &bull; Batas Beban: <strong>{{ $mahasiswa->batas_sks }} SKS</strong> (IPS Lalu: {{ $mahasiswa->ips_lalu }})
        </p>
    </div>
    <div>
        @if($akademikAktif && $uktStatus['is_paid'])
            <span class="badge-pill success" style="padding: 6px 14px; font-size: 12px;">Akses KRS: TERBUKA</span>
        @else
            <span class="badge-pill danger" style="padding: 6px 14px; font-size: 12px;">Akses KRS: TERKUNCI</span>
        @endif
    </div>
</div>

<!-- 1. Validasi Status Akademik Mahasiswa -->
@if(!$akademikAktif)
    <div class="card-modern" style="border-left: 4px solid #ef4444; background: #fff5f5;">
        <div style="display: flex; gap: 16px; align-items: flex-start;">
            <div style="font-size: 28px;">🚫</div>
            <div>
                <h3 style="font-size: 16px; font-weight: 800; color: var(--status-danger-text);">Akses Pengisian KRS Ditolak</h3>
                <p style="font-size: 13px; color: #991b1b; margin-top: 4px;">{{ $pesanAkademik }}</p>
            </div>
        </div>
    </div>
@else
    <!-- 2. Validasi Status Pembayaran UKT via EAI Total Billing System -->
    @if(!$uktStatus['is_paid'])
        <!-- FORM KRS TERKUNCI KARENA UKT BELUM LUNAS -->
        <div class="card-modern" style="border-left: 4px solid #f59e0b; background: #fffdf9;">
            <div style="display: flex; gap: 20px; align-items: flex-start;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                    🔒
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                        <span class="badge-pill warning">INTEGRASI EAI TERTUTUP</span>
                        <span class="badge-pill danger">STATUS: UNPAID</span>
                    </div>

                    <h3 style="font-size: 18px; font-weight: 800; color: #92400e; letter-spacing: -0.01em;">
                        Formulir KRS Terkunci: Tagihan UKT Semester {{ $mahasiswa->semester_sekarang }} Belum Dilunasi
                    </h3>
                    <p style="font-size: 13px; color: #78350f; margin-top: 4px; line-height: 1.6;">
                        Berdasarkan verifikasi sistem penagihan terpusat (Total Billing System), Anda belum melakukan pelunasan UKT semester ini. Pengisian KRS ditutup secara otomatis oleh EAI sampai pembayaran diverifikasi lunas.
                    </p>

                    @if(isset($uktStatus['data']['nomor_va']))
                    <div style="background: #ffffff; border: 1px solid #fde68a; border-radius: var(--radius-md); padding: 18px; margin: 18px 0; max-width: 520px; box-shadow: var(--shadow-xs);">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Nomor Virtual Account Pembayaran:</span>
                            <span class="badge-pill brand">Bank Mitra Kampus</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                            <span style="font-family: var(--font-mono); font-size: 22px; font-weight: 800; color: var(--brand-primary); letter-spacing: 0.04em;">
                                {{ $uktStatus['data']['nomor_va'] }}
                            </span>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $uktStatus['data']['nomor_va'] }}'); alert('Nomor VA berhasil disalin!');" class="btn-modern secondary sm">
                                Salin VA
                            </button>
                        </div>
                        <div style="margin-top: 12px; font-size: 13px; color: var(--text-heading); display: flex; justify-content: space-between; border-top: 1px dashed #e2e8f0; padding-top: 8px;">
                            <span>Nominal Kewajiban:</span>
                            <strong style="font-size: 15px; color: #0f172a;">Rp {{ number_format($uktStatus['data']['nominal'] ?? 0, 0, ',', '.') }}</strong>
                        </div>
                        <div style="margin-top: 4px; font-size: 11px; color: var(--text-muted); display: flex; justify-content: space-between;">
                            <span>Batas Waktu Pelunasan:</span>
                            <span>{{ $uktStatus['data']['jatuh_tempo'] ?? '-' }}</span>
                        </div>
                    </div>
                    @endif

                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <a href="http://localhost:8001" target="_blank" class="btn-modern primary" style="background:#0f766e; font-size:13px;">
                            Buka Billing Simulator untuk Simulasi Bayar UKT &rarr;
                        </a>
                        <button onclick="location.reload()" class="btn-modern secondary sm" style="font-size:13px;">
                            Periksa Ulang Status Pembayaran (Refresh)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- KONDISI UKT SUDAH LUNAS: FORM KRS TERBUKA -->
        <div class="banner-alert success" style="justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 18px;">✓</span>
                <div>
                    <strong>Verifikasi Pembayaran UKT: Lunas (Tersinkronisasi)</strong>
                    <div style="font-size: 12px; color: #065f46; margin-top: 2px;">
                        Invoice: <code>{{ $uktStatus['data']['nomor_invoice'] ?? '-' }}</code> &bull; Nominal: <strong>Rp {{ number_format($uktStatus['data']['nominal'] ?? 0, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
            <span class="badge-pill success">PORTAL DIBUKA</span>
        </div>

        @if($krsAktif)
            <!-- Tampilan Kartu Rencana Studi Tersimpan -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <div>
                        <h2 class="card-title-modern">Kartu Rencana Studi (KRS) Tersimpan</h2>
                        <p class="card-subtitle-modern">Telah disetujui Dosen Pembimbing Akademik</p>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('krs.cetak') }}" target="_blank" class="btn-modern secondary sm">
                            Cetak Draft KRS &rarr;
                        </a>
                    </div>
                </div>

                <div class="table-container-modern">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;">No</th>
                                <th>Kode MK</th>
                                <th>Nama Mata Kuliah</th>
                                <th>Bobot</th>
                                <th>Jadwal Kuliah</th>
                                <th>Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach($krsAktif->details as $item)
                            <tr>
                                <td style="text-align: center; color: var(--text-muted);">{{ $no++ }}</td>
                                <td><code>{{ $item->mataKuliah->kode_mk }}</code></td>
                                <td><strong style="color: var(--text-heading);">{{ $item->mataKuliah->nama_mk }}</strong></td>
                                <td><span class="badge-pill neutral">{{ $item->mataKuliah->sks }} SKS</span></td>
                                <td>{{ $item->mataKuliah->hari }}, {{ substr($item->mataKuliah->jam_mulai, 0, 5) }} - {{ substr($item->mataKuliah->jam_selesai, 0, 5) }}</td>
                                <td>{{ $item->mataKuliah->ruangan }}</td>
                            </tr>
                            @endforeach
                            <tr style="background-color: var(--bg-subtle); font-weight: 700;">
                                <td colspan="3" style="text-align: right; padding-right: 16px;">Total Beban SKS Terambil:</td>
                                <td colspan="3">
                                    <span class="badge-pill success" style="font-size: 13px;">{{ $krsAktif->total_sks }} SKS</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Form Pemilihan Mata Kuliah Tawaran -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div>
                    <h2 class="card-title-modern">{{ $krsAktif ? 'Perbarui Pilihan Mata Kuliah' : 'Pilih Mata Kuliah Tawaran' }}</h2>
                    <p class="card-subtitle-modern">
                        Batas Beban SKS Anda: <strong>{{ $mahasiswa->batas_sks }} SKS</strong> (Berdasarkan evaluasi akademik semester lalu)
                    </p>
                </div>

                <!-- Live Dynamic SKS Counter Badge -->
                <div id="sks-counter-badge" class="badge-pill neutral" style="font-size: 13px; padding: 6px 16px;">
                    SKS Dipilih: <strong id="selected-sks" style="margin-left: 4px; font-family: var(--font-mono);">0</strong> / {{ $mahasiswa->batas_sks }}
                </div>
            </div>

            <form action="{{ route('krs.store') }}" method="POST" id="form-krs">
                @csrf
                <div class="table-container-modern">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">Pilih</th>
                                <th>Kode</th>
                                <th>Nama Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Hari & Jam Kuliah</th>
                                <th>Ruang</th>
                                <th>Keterangan Jadwal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $selectedIds = $krsAktif ? $krsAktif->details->pluck('kode_mk')->toArray() : [];
                            @endphp
                            @foreach($mataKuliah as $mk)
                            <tr class="mk-row">
                                <td style="text-align: center;">
                                    <input type="checkbox" name="mata_kuliah[]" value="{{ $mk->kode_mk }}"
                                           data-sks="{{ $mk->sks }}"
                                           data-hari="{{ $mk->hari }}"
                                           data-mulai="{{ $mk->jam_mulai }}"
                                           data-selesai="{{ $mk->jam_selesai }}"
                                           class="mk-checkbox"
                                           style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--brand-primary);"
                                           {{ in_array($mk->kode_mk, $selectedIds) ? 'checked' : '' }}>
                                </td>
                                <td><code>{{ $mk->kode_mk }}</code></td>
                                <td><strong style="color: var(--text-heading);">{{ $mk->nama_mk }}</strong></td>
                                <td><span class="badge-pill neutral">{{ $mk->sks }} SKS</span></td>
                                <td>
                                    <strong>{{ $mk->hari }}</strong>, {{ substr($mk->jam_mulai, 0, 5) }} - {{ substr($mk->jam_selesai, 0, 5) }}
                                </td>
                                <td>{{ $mk->ruangan }}</td>
                                <td>
                                    @if($mk->kode_mk === 'TIF701' || $mk->kode_mk === 'TIF702')
                                        <span class="badge-pill warning" style="font-size: 10px;">Senin 08:00 (Uji Bentrok)</span>
                                    @else
                                        <span style="font-size: 12px; color: var(--text-muted);">Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid var(--border-subtle);">
                    <div style="font-size: 12px; color: var(--text-muted);">
                        Sistem EAI secara otomatis memvalidasi integritas jadwal dan batas kuota SKS.
                    </div>
                    <button type="submit" class="btn-modern primary" id="btn-submit-krs">
                        Simpan Rencana Studi (KRS) &rarr;
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
                const tr = cb.closest('tr');
                if (cb.checked) {
                    total += parseInt(cb.getAttribute('data-sks') || 0);
                    if (tr) tr.style.backgroundColor = '#f0fdf4';
                } else {
                    if (tr) tr.style.backgroundColor = '';
                }
            });

            if (selectedSksEl) {
                selectedSksEl.textContent = total;
            }

            if (badgeEl) {
                if (total > maxSks) {
                    badgeEl.className = 'badge-pill danger';
                } else if (total > 0) {
                    badgeEl.className = 'badge-pill success';
                } else {
                    badgeEl.className = 'badge-pill neutral';
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
