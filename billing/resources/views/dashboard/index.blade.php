@extends('layouts.app')

@section('title', 'Financial & Billing Overview')

@section('content')
<!-- Header Page Info -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <span class="m3-badge-pill success" style="margin-bottom: 6px;">FINANCIAL CORE & GATEWAY</span>
        <h1 style="font-size: 26px; font-weight: 700; color: var(--on-surface); letter-spacing: -0.02em;">
            Dashboard Manajemen Billing & Keuangan
        </h1>
        <p style="font-size: 13.5px; color: var(--on-surface-variant); margin-top: 4px;">
            Pusat Rekonsiliasi Tagihan Mahasiswa, Simulator Gateway Bank, dan Webhook Dispatcher ke SIMAK
        </p>
    </div>
    <div style="display: flex; gap: 8px;">
        <button onclick="location.reload()" class="btn-fintech secondary sm">
            Refresh Data
        </button>
    </div>
</div>

<!-- Modern Metric KPI Cards (20px Squircle M3 Cards) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="m3-card" style="margin-bottom: 0; padding: 22px 24px; border-radius: var(--radius-lg);">
        <span style="font-size: 11.5px; font-weight: 600; color: var(--on-surface-variant); text-transform: uppercase; letter-spacing: 0.05em;">Total Tagihan Terbit</span>
        <div style="font-size: 32px; font-weight: 700; color: var(--on-surface); margin-top: 6px; letter-spacing: -0.02em; font-family: var(--font-display);">
            {{ $totalTagihanCount }} <span style="font-size: 14px; font-weight: 500; color: var(--on-surface-variant);">Invoice</span>
        </div>
        <span style="font-size: 12px; color: var(--on-surface-variant);">Akumulasi seluruh semester</span>
    </div>

    <div class="m3-card" style="margin-bottom: 0; padding: 22px 24px; border-radius: var(--radius-lg); border-top: 3px solid var(--success);">
        <span style="font-size: 11.5px; font-weight: 600; color: var(--success); text-transform: uppercase; letter-spacing: 0.05em;">Penerimaan Lunas (PAID)</span>
        <div style="font-size: 26px; font-weight: 700; color: var(--success); margin-top: 6px; letter-spacing: -0.01em; font-family: var(--font-display);">
            Rp {{ number_format($totalNominalPaid, 0, ',', '.') }}
        </div>
        <span style="font-size: 12px; color: var(--success);">Dana terverifikasi masuk</span>
    </div>

    <div class="m3-card" style="margin-bottom: 0; padding: 22px 24px; border-radius: var(--radius-lg); border-top: 3px solid var(--warning);">
        <span style="font-size: 11.5px; font-weight: 600; color: var(--warning); text-transform: uppercase; letter-spacing: 0.05em;">Piutang Berjalan (UNPAID)</span>
        <div style="font-size: 26px; font-weight: 700; color: var(--warning); margin-top: 6px; letter-spacing: -0.01em; font-family: var(--font-display);">
            Rp {{ number_format($totalNominalUnpaid, 0, ',', '.') }}
        </div>
        <span style="font-size: 12px; color: var(--warning);">Menunggu penyelesaian</span>
    </div>

    <div class="m3-card" style="margin-bottom: 0; padding: 22px 24px; border-radius: var(--radius-lg); border-top: 3px solid var(--error);">
        <span style="font-size: 11.5px; font-weight: 600; color: var(--error); text-transform: uppercase; letter-spacing: 0.05em;">Invoice Menunggu Bayar</span>
        <div style="font-size: 32px; font-weight: 700; color: var(--error); margin-top: 6px; letter-spacing: -0.02em; font-family: var(--font-display);">
            {{ $unpaidCount }} <span style="font-size: 14px; font-weight: 500; color: var(--error);">Invoice</span>
        </div>
        <span style="font-size: 12px; color: var(--on-surface-variant);">Belum diverifikasi lunas</span>
    </div>
</div>

<!-- INTERACTIVE PAYMENT SIMULATOR CARD (20px Squircle M3 Feature Card) -->
<div class="m3-card" style="border: 2px solid var(--primary); background: var(--surface-container-low); border-radius: var(--radius-lg);">
    <div class="m3-card-header" style="border-bottom-color: var(--outline-variant);">
        <div>
            <span class="m3-badge-pill success" style="margin-bottom: 4px; font-size: 10px;">EAI LIVE DEMO CONSOLE</span>
            <h2 class="m3-card-title" style="color: var(--primary); font-size: 18px;">
                Simulator Pembayaran Bank & Webhook Dispatcher
            </h2>
            <p class="m3-card-subtitle">
                Gunakan simulator ini untuk melunasi tagihan Virtual Account dan menguji pengiriman Webhook Asinkron ke SIMAK (Port 8000)
            </p>
        </div>
    </div>

    <form action="{{ route('billing.simulasi_bayar') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; align-items: flex-end;">
            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--on-surface); margin-bottom: 6px;">
                    PILIH TAGIHAN MAHASISWA BELUM LUNAS (UNPAID)
                </label>
                <select id="select-unpaid" onchange="autoFillVa(this)" style="width: 100%; height: 46px; padding: 0 14px; border: 1px solid var(--outline-variant); border-radius: var(--radius-sm); font-size: 13.5px; font-family: var(--font-sans); background: var(--surface-container-lowest); color: var(--on-surface); outline: none;">
                    <option value="">-- Pilih Tagihan untuk Disimulasikan --</option>
                    @foreach($unpaidInvoices as $inv)
                        <option value="{{ $inv->nomor_va }}" data-invoice="{{ $inv->nomor_invoice }}" data-nominal="Rp {{ number_format($inv->jumlah_tagihan, 0, ',', '.') }}" data-nama="{{ $inv->mahasiswa->nama ?? $inv->nim }}">
                            [{{ $inv->jenis_tagihan }}] {{ $inv->nomor_invoice }} &bull; {{ $inv->nim }} ({{ $inv->mahasiswa->nama ?? '' }}) &bull; Rp {{ number_format($inv->jumlah_tagihan, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--on-surface); margin-bottom: 6px;">
                    NOMOR VIRTUAL ACCOUNT (VA)
                </label>
                <input type="text" name="nomor_va" id="input-va" required placeholder="Contoh: 8808210101001001"
                       style="width: 100%; height: 46px; padding: 0 14px; border: 1px solid var(--outline-variant); border-radius: var(--radius-sm); font-size: 15px; font-family: var(--font-mono); font-weight: 700; background: var(--surface-container-lowest); color: var(--primary); outline: none;">
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--on-surface); margin-bottom: 6px;">
                    KANAL PEMBAYARAN BANK
                </label>
                <select name="channel_bayar" style="width: 100%; height: 46px; padding: 0 14px; border: 1px solid var(--outline-variant); border-radius: var(--radius-sm); font-size: 13.5px; font-family: var(--font-sans); background: var(--surface-container-lowest); color: var(--on-surface); outline: none;">
                    <option value="M-BANKING SIMULATOR (MANDIRI)">M-Banking Mandiri</option>
                    <option value="M-BANKING SIMULATOR (BCA)">M-Banking BCA</option>
                    <option value="M-BANKING SIMULATOR (BNI)">M-Banking BNI</option>
                    <option value="M-BANKING SIMULATOR (BRI)">M-Banking BRI</option>
                    <option value="TELLER BANK KAMPUS">Teller Kasir Kampus</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 18px; display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px dashed var(--outline-variant); flex-wrap: wrap; gap: 12px;">
            <div id="preview-tagihan" style="font-size: 13.5px; color: var(--primary);">
                Pilih salah satu tagihan di atas untuk melihat detail.
            </div>
            <button type="submit" class="btn-fintech primary" style="font-size: 13.5px;">
                Proses Pembayaran & Trigger Webhook &rarr;
            </button>
        </div>
    </form>
</div>

<!-- TABEL DAFTAR TAGIHAN FINANSIAL -->
<div class="m3-card" style="padding: 0; overflow: hidden;">
    <div style="padding: 22px 24px; border-bottom: 1px solid var(--outline-variant); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 class="m3-card-title">Daftar Tagihan Mahasiswa (Klik Baris untuk Detail & Modifikasi)</h2>
            <p class="m3-card-subtitle">Klik pada baris transaksi mana saja untuk memunculkan modal rincian dan mengubah status pembayaran secara manual</p>
        </div>
        <div style="display: flex; gap: 6px;">
            <a href="{{ route('billing.dashboard', ['status' => 'ALL']) }}" class="btn-fintech {{ $filterStatus == 'ALL' ? 'primary' : 'secondary' }} sm">Semua</a>
            <a href="{{ route('billing.dashboard', ['status' => 'UNPAID']) }}" class="btn-fintech {{ $filterStatus == 'UNPAID' ? 'primary' : 'secondary' }} sm">Unpaid</a>
            <a href="{{ route('billing.dashboard', ['status' => 'PAID']) }}" class="btn-fintech {{ $filterStatus == 'PAID' ? 'primary' : 'secondary' }} sm">Paid</a>
        </div>
    </div>

    <div class="m3-table-container" style="border: none; border-radius: 0; margin-bottom: 0;">
        <table class="m3-table">
            <thead>
                <tr>
                    <th>Nomor Invoice</th>
                    <th>NIM & Mahasiswa</th>
                    <th>Jenis Tagihan</th>
                    <th>Semester</th>
                    <th>Jumlah Tagihan</th>
                    <th>Nomor VA</th>
                    <th>Status</th>
                    <th>Kanal / Waktu Bayar</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tagihanList as $tagihan)
                @php
                    $tagihanData = [
                        'id' => $tagihan->id,
                        'nomor_invoice' => $tagihan->nomor_invoice,
                        'nim' => $tagihan->nim,
                        'nama' => $tagihan->mahasiswa->nama ?? '-',
                        'prodi' => $tagihan->mahasiswa->prodi ?? '-',
                        'angkatan' => $tagihan->mahasiswa->angkatan ?? '-',
                        'jenis_tagihan' => $tagihan->jenis_tagihan,
                        'deskripsi' => $tagihan->deskripsi ?? 'Tagihan Akademik Kampus',
                        'semester' => $tagihan->semester ?? '-',
                        'jumlah_tagihan' => (float)$tagihan->jumlah_tagihan,
                        'jumlah_tagihan_formatted' => 'Rp ' . number_format($tagihan->jumlah_tagihan, 0, ',', '.'),
                        'nomor_va' => $tagihan->nomor_va,
                        'status_pembayaran' => $tagihan->status_pembayaran,
                        'tanggal_jatuh_tempo' => $tagihan->tanggal_jatuh_tempo ? $tagihan->tanggal_jatuh_tempo->format('d/m/Y H:i') : '-',
                        'tanggal_bayar' => $tagihan->tanggal_bayar ? $tagihan->tanggal_bayar->format('d/m/Y H:i') : '-',
                        'channel_bayar' => $tagihan->channel_bayar ?? '-',
                        'update_url' => route('billing.update_status', $tagihan->id),
                    ];
                @endphp
                <tr class="row-clickable" onclick="openDetailModal({{ json_encode($tagihanData) }})" style="cursor: pointer;">
                    <td><code style="background: var(--surface-container-high); padding: 2px 6px; border-radius: 4px;">{{ $tagihan->nomor_invoice }}</code></td>
                    <td>
                        <strong style="color: var(--on-surface);">{{ $tagihan->nim }}</strong>
                        <div style="font-size: 11.5px; color: var(--on-surface-variant);">{{ $tagihan->mahasiswa->nama ?? '-' }}</div>
                    </td>
                    <td>
                        @if($tagihan->jenis_tagihan === 'UKT')
                            <span class="m3-badge-pill" style="background: var(--secondary-container); color: var(--on-secondary-container);">UKT</span>
                        @elseif($tagihan->jenis_tagihan === 'WISUDA')
                            <span class="m3-badge-pill" style="background: var(--primary-container); color: var(--on-primary-container);">WISUDA</span>
                        @else
                            <span class="m3-badge-pill danger">DENDA</span>
                        @endif
                    </td>
                    <td>{{ $tagihan->semester ?? '-' }}</td>
                    <td><strong style="color: var(--on-surface);">Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</strong></td>
                    <td>
                        <span style="font-family: var(--font-mono); font-weight: 700; color: var(--primary);">{{ $tagihan->nomor_va }}</span>
                    </td>
                    <td>
                        @if($tagihan->status_pembayaran === 'PAID')
                            <span class="m3-badge-pill success">PAID</span>
                        @else
                            <span class="m3-badge-pill danger">UNPAID</span>
                        @endif
                    </td>
                    <td>
                        @if($tagihan->status_pembayaran === 'PAID')
                            <div style="font-size: 12px; font-weight: 600;">{{ $tagihan->channel_bayar }}</div>
                            <div style="font-size: 11px; color: var(--on-surface-variant);">{{ $tagihan->tanggal_bayar ? $tagihan->tanggal_bayar->format('d/m/Y H:i') : '' }}</div>
                        @else
                            <span style="color: var(--on-surface-variant); font-size: 11.5px;">Jatuh tempo: {{ $tagihan->tanggal_jatuh_tempo ? $tagihan->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}</span>
                        @endif
                    </td>
                    <td style="text-align: right;" onclick="event.stopPropagation();">
                        <button type="button" class="btn-fintech secondary sm" onclick="openDetailModal({{ json_encode($tagihanData) }})">
                            Detail / Ubah
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--on-surface-variant); padding: 30px;">
                        Tidak ada transaksi tagihan yang sesuai dengan filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 16px 24px;">
        {{ $tagihanList->links() }}
    </div>
</div>

<!-- AUDIT LOG WEBHOOK EVENT DISPATCHER -->
<div class="m3-card" style="padding: 0; overflow: hidden; margin-top: 24px;">
    <div style="padding: 22px 24px; border-bottom: 1px solid var(--outline-variant); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 class="m3-card-title">Audit Log Webhook Event Dispatcher (EAI Asynchronous Callback)</h2>
            <p class="m3-card-subtitle">Riwayat transmisi HTTP webhook callback yang dikirim ke SIMAK (Port 8000) saat tagihan wisuda diverifikasi</p>
        </div>
        <span class="m3-badge-pill neutral">10 Riwayat Terakhir</span>
    </div>

    <div class="m3-table-container" style="border: none; border-radius: 0; margin-bottom: 0;">
        <table class="m3-table">
            <thead>
                <tr>
                    <th>Waktu Pengiriman</th>
                    <th>Nama Event</th>
                    <th>Target Endpoint SIMAK</th>
                    <th>Status HTTP</th>
                    <th>Status Callback</th>
                    <th>Payload JSON</th>
                </tr>
            </thead>
            <tbody>
                @forelse($webhookLogs as $log)
                <tr>
                    <td style="font-size: 11px; white-space: nowrap; font-family: var(--font-mono);">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td><strong style="color: var(--on-surface);">{{ $log->event_name }}</strong></td>
                    <td><code style="background: var(--surface-container-high); padding: 2px 6px; border-radius: 4px;">{{ $log->target_url }}</code></td>
                    <td>
                        @if($log->response_code == 200)
                            <span class="m3-badge-pill success">200 OK</span>
                        @else
                            <span class="m3-badge-pill danger">{{ $log->response_code ?? 'FAILED' }}</span>
                        @endif
                    </td>
                    <td>
                        @if($log->is_success)
                            <span class="m3-badge-pill success">BERHASIL DITERIMA</span>
                        @else
                            <span class="m3-badge-pill danger">GAGAL</span>
                        @endif
                    </td>
                    <td>
                        <div style="max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-family: var(--font-mono); font-size: 11px; color: var(--on-surface-variant);" title="{{ $log->payload }}">
                            {{ $log->payload }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--on-surface-variant); padding: 24px;">
                        Belum ada transmisi event webhook. Lakukan pelunasan paket wisuda untuk memicu callback otomatis.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- POPUP MODAL DETAIL & MODIFIKASI STATUS PELUNASAN (M3 DIALOG 28PX SQUIRCLE) -->
<div id="modal-detail" class="m3-dialog-backdrop" style="display: none;">
    <div class="m3-dialog" style="max-width: 600px; padding: 28px; background: var(--surface-container-high); border-radius: var(--radius-xl); box-shadow: 0 16px 32px rgba(0,0,0,0.25);">
        <!-- Modal Header -->
        <div style="padding-bottom: 16px; border-bottom: 1px solid var(--outline-variant); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span id="modal-jenis-badge" class="m3-badge-pill" style="margin-bottom: 4px;">JENIS</span>
                <h3 id="modal-invoice-title" style="font-size: 20px; font-weight: 700; color: var(--on-surface); font-family: var(--font-display);">INV-0000</h3>
            </div>
            <button type="button" onclick="closeDetailModal()" class="m3-toast-close" style="font-size: 24px;">&times;</button>
        </div>

        <!-- Modal Body Scrollable -->
        <div style="padding: 20px 0; max-height: 65vh; overflow-y: auto;">
            <!-- Rincian Mahasiswa & Tagihan -->
            <div class="m3-inner-block" style="margin-bottom: 20px; background: var(--surface-container-low);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; font-size: 13px;">
                    <div>
                        <span style="color: var(--on-surface-variant); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">NIM & Mahasiswa</span>
                        <strong id="modal-nama" style="color: var(--on-surface);">-</strong> (<span id="modal-nim">-</span>)
                    </div>
                    <div>
                        <span style="color: var(--on-surface-variant); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Program Studi</span>
                        <span id="modal-prodi">-</span>
                    </div>
                    <div>
                        <span style="color: var(--on-surface-variant); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Nomor Virtual Account</span>
                        <span id="modal-va" style="font-family: var(--font-mono); font-weight: 700; color: var(--primary); font-size: 15px;">-</span>
                    </div>
                    <div>
                        <span style="color: var(--on-surface-variant); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Jumlah Tagihan</span>
                        <strong id="modal-nominal" style="font-size: 16px; color: var(--on-surface); font-family: var(--font-display);">-</strong>
                    </div>
                    <div>
                        <span style="color: var(--on-surface-variant); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Deskripsi Tagihan</span>
                        <span id="modal-deskripsi">-</span>
                    </div>
                    <div>
                        <span style="color: var(--on-surface-variant); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Jatuh Tempo</span>
                        <span id="modal-tempo">-</span>
                    </div>
                    <div>
                        <span style="color: var(--on-surface-variant); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Status Pelunasan</span>
                        <span id="modal-status-badge" class="m3-badge-pill">STATUS</span>
                    </div>
                    <div>
                        <span style="color: var(--on-surface-variant); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Waktu & Kanal Bayar</span>
                        <span id="modal-waktu-bayar">-</span>
                    </div>
                </div>
            </div>

            <!-- FORM MODIFIKASI STATUS PELUNASAN OLEH ADMIN -->
            <div style="border: 1px solid var(--outline-variant); background: var(--surface-container-lowest); border-radius: var(--radius-sm); padding: 18px;">
                <h4 style="font-size: 14.5px; font-weight: 700; color: var(--primary); margin-bottom: 14px; display: flex; align-items: center; gap: 6px;">
                    Modifikasi Status Pelunasan oleh Admin Keuangan
                </h4>

                <form id="form-update-status" method="POST" action="">
                    @csrf
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--on-surface); margin-bottom: 8px;">
                            Ubah Status Pembayaran:
                        </label>
                        <div style="display: flex; gap: 20px;">
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 13.5px; cursor: pointer;">
                                <input type="radio" name="status_pembayaran" value="PAID" id="radio-paid" onchange="toggleKanalBayar(this.value)" style="accent-color: var(--primary); width: 16px; height: 16px;">
                                <strong style="color: var(--success);">PAID (Lunas)</strong>
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 13.5px; cursor: pointer;">
                                <input type="radio" name="status_pembayaran" value="UNPAID" id="radio-unpaid" onchange="toggleKanalBayar(this.value)" style="accent-color: var(--error); width: 16px; height: 16px;">
                                <strong style="color: var(--error);">UNPAID (Belum Lunas / Batal)</strong>
                            </label>
                        </div>
                    </div>

                    <div id="wrapper-kanal-bayar" style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: var(--on-surface); margin-bottom: 6px;">
                            Metode / Kanal Pembayaran:
                        </label>
                        <select name="channel_bayar" id="select-modal-channel" style="width: 100%; height: 42px; padding: 0 12px; border: 1px solid var(--outline-variant); border-radius: var(--radius-sm); font-size: 13px; font-family: var(--font-sans); background: var(--surface-container-low); color: var(--on-surface);">
                            <option value="KASIR KEUANGAN (UPDATE ADMIN)">Kasir Keuangan (Setoran Tunai)</option>
                            <option value="TRANSFER BANK (VERIFIKASI MANUAL)">Transfer Bank (Verifikasi Manual)</option>
                            <option value="M-BANKING SIMULATOR">M-Banking Simulator</option>
                            <option value="PEMBEBASAN BIAYA / BEASISWA">Pembebasan Biaya / Beasiswa</option>
                        </select>
                    </div>

                    <div id="modal-eai-notice" style="font-size: 12px; color: var(--success); margin-bottom: 16px; padding: 12px 14px; background: var(--success-container); border-radius: var(--radius-sm); display: none;">
                        <strong>Integrasi EAI:</strong> Mengubah status tagihan paket wisuda ini akan secara otomatis memancarkan Webhook Callback ke SIMAK (Port 8000) untuk sinkronisasi seketika.
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--outline-variant); padding-top: 14px;">
                        <button type="button" onclick="closeDetailModal()" class="btn-fintech secondary sm">Batal</button>
                        <button type="submit" class="btn-fintech primary sm">Simpan Perubahan Status &rarr;</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function autoFillVa(select) {
        const selectedOption = select.options[select.selectedIndex];
        const vaInput = document.getElementById('input-va');
        const previewEl = document.getElementById('preview-tagihan');

        if (selectedOption.value) {
            vaInput.value = selectedOption.value;
            const invoice = selectedOption.getAttribute('data-invoice');
            const nominal = selectedOption.getAttribute('data-nominal');
            const nama = selectedOption.getAttribute('data-nama');
            previewEl.innerHTML = `<strong>${nama}</strong> &bull; Invoice: <code>${invoice}</code> &bull; Nominal: <strong>${nominal}</strong>`;
        } else {
            vaInput.value = '';
            previewEl.innerHTML = 'Pilih salah satu tagihan di atas untuk melihat detail.';
        }
    }

    function openDetailModal(data) {
        document.getElementById('modal-invoice-title').textContent = data.nomor_invoice;
        document.getElementById('modal-nama').textContent = data.nama;
        document.getElementById('modal-nim').textContent = data.nim;
        document.getElementById('modal-prodi').textContent = data.prodi + ' (' + data.angkatan + ')';
        document.getElementById('modal-va').textContent = data.nomor_va;
        document.getElementById('modal-nominal').textContent = data.jumlah_tagihan_formatted;
        document.getElementById('modal-deskripsi').textContent = data.deskripsi + ' (Semester ' + data.semester + ')';
        document.getElementById('modal-tempo').textContent = data.tanggal_jatuh_tempo;

        const jenisBadge = document.getElementById('modal-jenis-badge');
        jenisBadge.textContent = data.jenis_tagihan;
        if (data.jenis_tagihan === 'UKT') {
            jenisBadge.className = 'm3-badge-pill';
            jenisBadge.style.cssText = 'background: var(--secondary-container); color: var(--on-secondary-container);';
        } else if (data.jenis_tagihan === 'WISUDA') {
            jenisBadge.className = 'm3-badge-pill';
            jenisBadge.style.cssText = 'background: var(--primary-container); color: var(--on-primary-container);';
        } else {
            jenisBadge.className = 'm3-badge-pill danger';
            jenisBadge.style.cssText = '';
        }

        const statusBadge = document.getElementById('modal-status-badge');
        statusBadge.textContent = data.status_pembayaran;
        if (data.status_pembayaran === 'PAID') {
            statusBadge.className = 'm3-badge-pill success';
            document.getElementById('radio-paid').checked = true;
            toggleKanalBayar('PAID');
        } else {
            statusBadge.className = 'm3-badge-pill danger';
            document.getElementById('radio-unpaid').checked = true;
            toggleKanalBayar('UNPAID');
        }

        const waktuBayarEl = document.getElementById('modal-waktu-bayar');
        if (data.status_pembayaran === 'PAID') {
            waktuBayarEl.textContent = data.tanggal_bayar + ' via ' + data.channel_bayar;
        } else {
            waktuBayarEl.textContent = 'Belum dibayar';
        }

        document.getElementById('form-update-status').action = data.update_url;

        const noticeEl = document.getElementById('modal-eai-notice');
        if (data.jenis_tagihan === 'WISUDA') {
            noticeEl.style.display = 'block';
        } else {
            noticeEl.style.display = 'none';
        }

        const modal = document.getElementById('modal-detail');
        modal.style.display = 'flex';
    }

    function closeDetailModal() {
        document.getElementById('modal-detail').style.display = 'none';
    }

    function toggleKanalBayar(status) {
        const wrapper = document.getElementById('wrapper-kanal-bayar');
        if (status === 'PAID') {
            wrapper.style.display = 'block';
        } else {
            wrapper.style.display = 'none';
        }
    }

    document.getElementById('modal-detail').addEventListener('click', function (e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDetailModal();
        }
    });
</script>
@endsection
