@extends('layouts.app')

@section('title', 'Pengisian Kartu Rencana Studi (KRS)')
@section('page_title', 'Pengisian KRS')
@section('page_subtitle', 'Penyusunan Rencana Studi Semester ' . $mahasiswa->semester_sekarang)

@section('content')
<!-- Page Header Info -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <span class="m3-badge-pill primary" style="margin-bottom: 6px;">PORTAL RENCANA STUDI</span>
        <h1 style="font-size: 26px; font-weight: 700; color: var(--on-surface); letter-spacing: -0.02em;">
            Pengisian Kartu Rencana Studi (KRS)
        </h1>
        <p style="font-size: 13.5px; color: var(--on-surface-variant); margin-top: 4px;">
            Semester {{ $mahasiswa->semester_sekarang }} &bull; Batas Beban: <strong>{{ $mahasiswa->batas_sks }} SKS</strong> (IPS Lalu: {{ $mahasiswa->ips_lalu }})
        </p>
    </div>
    <div>
        @if($akademikAktif && $uktStatus['is_paid'])
            <span class="m3-badge-pill success" style="padding: 8px 16px; font-size: 12.5px;">Akses KRS: TERBUKA</span>
        @else
            <span class="m3-badge-pill danger" style="padding: 8px 16px; font-size: 12.5px;">Akses KRS: TERKUNCI</span>
        @endif
    </div>
</div>

<!-- 1. Validasi Status Akademik Mahasiswa -->
@if(!$akademikAktif)
    <div class="m3-card" style="border-left: 4px solid var(--error); background: var(--error-container); color: var(--on-error-container);">
        <div style="display: flex; gap: 16px; align-items: flex-start;">
            <div style="font-size: 28px;">🚫</div>
            <div>
                <h3 style="font-size: 16px; font-weight: 700; color: var(--on-error-container);">Akses Pengisian KRS Ditolak</h3>
                <p style="font-size: 13.5px; margin-top: 4px;">{{ $pesanAkademik }}</p>
            </div>
        </div>
    </div>
@else
    <!-- 2. Validasi Status Pembayaran UKT via EAI Total Billing System -->
    @if(!$uktStatus['is_paid'])
        <!-- FORM KRS TERKUNCI KARENA UKT BELUM LUNAS -->
        <div class="m3-card" style="border-left: 4px solid var(--warning); background: var(--warning-container); color: var(--on-warning-container);">
            <div style="display: flex; gap: 20px; align-items: flex-start;">
                <div style="width: 48px; height: 48px; border-radius: var(--radius-sm); background: var(--warning); color: var(--on-warning); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                    🔒
                </div>
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <span class="m3-badge-pill warning">INTEGRASI EAI TERTUTUP</span>
                        <span class="m3-badge-pill danger">STATUS: UNPAID</span>
                    </div>

                    <h3 style="font-size: 18px; font-weight: 700; color: var(--on-warning-container); letter-spacing: -0.01em;">
                        Formulir KRS Terkunci: Tagihan UKT Semester {{ $mahasiswa->semester_sekarang }} Belum Dilunasi
                    </h3>
                    <p style="font-size: 13.5px; opacity: 0.9; margin-top: 4px; line-height: 1.6;">
                        Berdasarkan verifikasi sistem penagihan terpusat (Total Billing System), Anda belum melakukan pelunasan UKT semester ini. Pengisian KRS ditutup secara otomatis oleh EAI sampai pembayaran diverifikasi lunas.
                    </p>

                    @if(isset($uktStatus['data']['nomor_va']))
                    <div style="background: var(--surface-container-lowest); border: 1px solid var(--outline-variant); border-radius: var(--radius-sm); padding: 18px; margin: 18px 0; max-width: 520px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 11px; font-weight: 600; color: var(--on-surface-variant); text-transform: uppercase;">Nomor Virtual Account Pembayaran:</span>
                            <span class="m3-badge-pill primary">Bank Mitra Kampus</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                            <span style="font-family: var(--font-mono); font-size: 22px; font-weight: 700; color: var(--primary); letter-spacing: 0.04em;">
                                {{ $uktStatus['data']['nomor_va'] }}
                            </span>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $uktStatus['data']['nomor_va'] }}'); alert('Nomor VA berhasil disalin!');" class="btn-modern secondary sm">
                                Salin VA
                            </button>
                        </div>
                        <div style="margin-top: 12px; font-size: 13px; color: var(--on-surface); display: flex; justify-content: space-between; border-top: 1px dashed var(--outline-variant); padding-top: 8px;">
                            <span>Nominal Kewajiban:</span>
                            <strong style="font-size: 15px;">Rp {{ number_format($uktStatus['data']['nominal'] ?? 0, 0, ',', '.') }}</strong>
                        </div>
                        <div style="margin-top: 4px; font-size: 11.5px; color: var(--on-surface-variant); display: flex; justify-content: space-between;">
                            <span>Batas Waktu Pelunasan:</span>
                            <span>{{ $uktStatus['data']['jatuh_tempo'] ?? '-' }}</span>
                        </div>
                    </div>
                    @endif

                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <a href="http://localhost:8001" target="_blank" class="btn-modern primary" style="font-size: 13px;">
                            Buka Billing Simulator untuk Simulasi Bayar UKT &rarr;
                        </a>
                        <button onclick="location.reload()" class="btn-modern secondary sm" style="font-size: 13px;">
                            Periksa Ulang Status Pembayaran (Refresh)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- KONDISI UKT SUDAH LUNAS: FORM KRS TERBUKA -->
        <div class="m3-card" style="background: var(--tertiary-container); color: var(--on-tertiary-container); padding: 18px 22px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="width: 32px; height: 32px; border-radius: 50%; background: var(--tertiary); color: var(--on-tertiary); display: inline-flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700;">✓</span>
                <div>
                    <strong style="font-size: 14.5px;">Verifikasi Pembayaran UKT: Lunas (Tersinkronisasi)</strong>
                    <div style="font-size: 12.5px; opacity: 0.9; margin-top: 2px;">
                        Invoice: <code style="background: rgba(0,0,0,0.06); padding: 2px 6px; border-radius: 4px;">{{ $uktStatus['data']['nomor_invoice'] ?? '-' }}</code> &bull; Nominal: <strong>Rp {{ number_format($uktStatus['data']['nominal'] ?? 0, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
            <span class="m3-badge-pill success">PORTAL DIBUKA</span>
        </div>

        @if($krsAktif)
            <!-- Tampilan Kartu Rencana Studi Tersimpan -->
            <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 16px; background: var(--surface-container-lowest); border: 1px solid var(--outline-variant) !important;">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3" style="border-bottom: 1px solid var(--outline-variant) !important;">
                    <div>
                        <h3 class="mb-0 fw-bold" style="font-size: 1.05rem; color: var(--on-surface);">Kartu Rencana Studi (KRS) Tersimpan</h3>
                        <small class="text-muted" style="color: var(--on-surface-variant) !important;">Telah disetujui Dosen Pembimbing Akademik</small>
                    </div>
                    <div>
                        <a href="{{ route('krs.cetak') }}" target="_blank" class="btn-m3 btn-m3-tonal" style="font-size: 0.8rem; height: 34px; padding: 0 14px;">
                            <i class="fas fa-print me-1"></i>
                            <span>Cetak Draft KRS</span>
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive mb-0" style="border: none; border-radius: 0;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4" style="width: 50px; text-align: center;">No</th>
                                    <th>Kode MK</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th>Bobot</th>
                                    <th>Jadwal Kuliah</th>
                                    <th class="pe-4">Ruangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach($krsAktif->details as $item)
                                <tr>
                                    <td class="ps-4" style="text-align: center; color: var(--on-surface-variant); font-size: 0.85rem;">{{ $no++ }}</td>
                                    <td><code style="background: var(--surface-container-high); padding: 2px 6px; border-radius: 4px; font-family: var(--font-mono);">{{ $item->mataKuliah->kode_mk }}</code></td>
                                    <td><strong style="color: var(--on-surface);">{{ $item->mataKuliah->nama_mk }}</strong></td>
                                    <td><span class="badge-m3 badge-m3-neutral">{{ $item->mataKuliah->sks }} SKS</span></td>
                                    <td>{{ $item->mataKuliah->hari }}, {{ substr($item->mataKuliah->jam_mulai, 0, 5) }} - {{ substr($item->mataKuliah->jam_selesai, 0, 5) }}</td>
                                    <td class="pe-4">{{ $item->mataKuliah->ruangan }}</td>
                                </tr>
                                @endforeach
                                <tr style="background: var(--surface-container-low); font-weight: 700;">
                                    <td colspan="3" class="ps-4" style="text-align: right; padding-right: 20px;">Total Beban SKS Terambil:</td>
                                    <td colspan="3" class="pe-4">
                                        <span class="badge-m3 badge-m3-success" style="font-size: 0.8rem;">{{ $krsAktif->total_sks }} SKS</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Pemilihan Mata Kuliah Tawaran -->
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px; background: var(--surface-container-lowest); border: 1px solid var(--outline-variant) !important;">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3" style="border-bottom: 1px solid var(--outline-variant) !important;">
                <div>
                    <h3 class="mb-0 fw-bold" style="font-size: 1.05rem; color: var(--on-surface);">{{ $krsAktif ? 'Perbarui Pilihan Mata Kuliah' : 'Pilih Mata Kuliah Tawaran' }}</h3>
                    <small class="text-muted" style="color: var(--on-surface-variant) !important;">
                        Batas Beban SKS Anda: <strong>{{ $mahasiswa->batas_sks }} SKS</strong> (Berdasarkan evaluasi akademik semester lalu)
                    </small>
                </div>

                <!-- Live Dynamic SKS Counter Badge -->
                <div id="sks-counter-badge" class="badge-m3 badge-m3-neutral" style="font-size: 0.85rem; padding: 6px 14px;">
                    SKS Dipilih: <strong id="selected-sks" style="margin-left: 4px; font-family: var(--font-mono);">0</strong> / {{ $mahasiswa->batas_sks }}
                </div>
            </div>

            <form action="{{ route('krs.store') }}" method="POST" id="form-krs">
                @csrf
                <div class="card-body p-0">
                    <div class="table-responsive mb-0" style="border: none; border-radius: 0;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4" style="width: 50px; text-align: center;">Pilih</th>
                                    <th>Kode</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th>SKS</th>
                                    <th>Hari & Jam Kuliah</th>
                                    <th>Ruang</th>
                                    <th class="pe-4">Keterangan Jadwal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $selectedIds = $krsAktif ? $krsAktif->details->pluck('kode_mk')->toArray() : [];
                                @endphp
                                @foreach($mataKuliah as $mk)
                                <tr class="mk-row">
                                    <td class="ps-4" style="text-align: center;">
                                        <input type="checkbox" name="mata_kuliah[]" value="{{ $mk->kode_mk }}"
                                               data-sks="{{ $mk->sks }}"
                                               data-hari="{{ $mk->hari }}"
                                               data-mulai="{{ $mk->jam_mulai }}"
                                               data-selesai="{{ $mk->jam_selesai }}"
                                               class="mk-checkbox"
                                               style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--primary);"
                                               {{ in_array($mk->kode_mk, $selectedIds) ? 'checked' : '' }}>
                                    </td>
                                    <td><code style="background: var(--surface-container-high); padding: 2px 6px; border-radius: 4px; font-family: var(--font-mono);">{{ $mk->kode_mk }}</code></td>
                                    <td><strong style="color: var(--on-surface);">{{ $mk->nama_mk }}</strong></td>
                                    <td><span class="badge-m3 badge-m3-neutral">{{ $mk->sks }} SKS</span></td>
                                    <td>
                                        <strong>{{ $mk->hari }}</strong>, {{ substr($mk->jam_mulai, 0, 5) }} - {{ substr($mk->jam_selesai, 0, 5) }}
                                    </td>
                                    <td>{{ $mk->ruangan }}</td>
                                    <td class="pe-4">
                                        @if($mk->kode_mk === 'TIF701' || $mk->kode_mk === 'TIF702')
                                            <span class="badge-m3 badge-m3-warning" style="font-size: 0.72rem;">Senin 08:00 (Uji Bentrok)</span>
                                        @else
                                            <span style="font-size: 0.8rem; color: var(--on-surface-variant);">Tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="px-4 py-3" style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--outline-variant); flex-wrap: wrap; gap: 12px; background: var(--surface-container-lowest);">
                    <div style="font-size: 0.825rem; color: var(--on-surface-variant);">
                        Sistem EAI secara otomatis memvalidasi integritas jadwal dan batas kuota SKS.
                    </div>
                    <button type="submit" class="btn-m3 btn-m3-primary" id="btn-submit-krs">
                        <span>Simpan Rencana Studi (KRS)</span>
                        <i class="fas fa-arrow-right ms-1"></i>
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
                    if (tr) tr.style.backgroundColor = 'var(--secondary-container)';
                } else {
                    if (tr) tr.style.backgroundColor = '';
                }
            });

            if (selectedSksEl) {
                selectedSksEl.textContent = total;
            }

            if (badgeEl) {
                if (total > maxSks) {
                    badgeEl.className = 'm3-badge-pill danger';
                } else if (total > 0) {
                    badgeEl.className = 'm3-badge-pill success';
                } else {
                    badgeEl.className = 'm3-badge-pill neutral';
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
