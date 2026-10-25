@extends('layouts.app')

@section('title', 'Financial & Billing Overview')

@section('content')
<!-- Header Page Info -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px;">
    <div>
        <span class="pill-badge success" style="margin-bottom: 6px;">FINANCIAL CORE & GATEWAY</span>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--text-heading); letter-spacing: -0.02em;">
            Dashboard Manajemen Billing & Keuangan
        </h1>
        <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">
            Pusat Rekonsiliasi Tagihan Mahasiswa, Simulator Gateway Bank, dan Webhook Dispatcher ke SIMAK
        </p>
    </div>
    <div style="display: flex; gap: 8px;">
        <button onclick="location.reload()" class="btn-fintech secondary sm">
            Refresh Data
        </button>
    </div>
</div>

<!-- Modern Metric KPI Cards -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
    <div class="card-fintech" style="margin-bottom: 0; padding: 18px 20px;">
        <span style="font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Total Tagihan Terbit</span>
        <div style="font-size: 28px; font-weight: 800; color: var(--text-heading); margin-top: 6px; letter-spacing: -0.02em;">
            {{ $totalTagihanCount }} <span style="font-size: 14px; font-weight: 500; color: var(--text-muted);">Invoice</span>
        </div>
        <span style="font-size: 12px; color: var(--text-muted);">Akumulasi seluruh semester</span>
    </div>

    <div class="card-fintech" style="margin-bottom: 0; padding: 18px 20px; border-top: 3px solid #10b981;">
        <span style="font-size: 11px; font-weight: 700; color: #047857; text-transform: uppercase; letter-spacing: 0.05em;">Penerimaan Lunas (PAID)</span>
        <div style="font-size: 24px; font-weight: 800; color: #065f46; margin-top: 6px; letter-spacing: -0.01em;">
            Rp {{ number_format($totalNominalPaid, 0, ',', '.') }}
        </div>
        <span style="font-size: 12px; color: #059669;">Dana terverifikasi masuk</span>
    </div>

    <div class="card-fintech" style="margin-bottom: 0; padding: 18px 20px; border-top: 3px solid #f59e0b;">
        <span style="font-size: 11px; font-weight: 700; color: #b45309; text-transform: uppercase; letter-spacing: 0.05em;">Piutang Berjalan (UNPAID)</span>
        <div style="font-size: 24px; font-weight: 800; color: #92400e; margin-top: 6px; letter-spacing: -0.01em;">
            Rp {{ number_format($totalNominalUnpaid, 0, ',', '.') }}
        </div>
        <span style="font-size: 12px; color: #b45309;">Menunggu penyelesaian</span>
    </div>

    <div class="card-fintech" style="margin-bottom: 0; padding: 18px 20px; border-top: 3px solid #ef4444;">
        <span style="font-size: 11px; font-weight: 700; color: #b91c1c; text-transform: uppercase; letter-spacing: 0.05em;">Invoice Menunggu Bayar</span>
        <div style="font-size: 28px; font-weight: 800; color: #b91c1c; margin-top: 6px; letter-spacing: -0.02em;">
            {{ $unpaidCount }} <span style="font-size: 14px; font-weight: 500; color: #dc2626;">Invoice</span>
        </div>
        <span style="font-size: 12px; color: var(--text-muted);">Belum diverifikasi lunas</span>
    </div>
</div>

<!-- INTERACTIVE PAYMENT SIMULATOR CARD -->
<div class="card-fintech" style="border: 2px solid #0f766e; background: linear-gradient(180deg, #f0fdfa 0%, #ffffff 100%);">
    <div class="card-header-fintech" style="border-color: #ccfbf1;">
        <div>
            <span class="pill-badge success" style="margin-bottom: 4px; font-size: 10px;">EAI LIVE DEMO CONSOLE</span>
            <h2 class="card-title-fintech" style="color: #0f766e; font-size: 18px;">
                Simulator Pembayaran Bank & Webhook Dispatcher
            </h2>
            <p class="card-subtitle-fintech" style="color: #115e59;">
                Gunakan simulator ini untuk melunasi tagihan Virtual Account dan menguji pengiriman Webhook Asinkron ke SIMAK (Port 8000)
            </p>
        </div>
    </div>

    <form action="{{ route('billing.simulasi_bayar') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 2fr 1.3fr 1.1fr; gap: 16px; align-items: flex-end;">
            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; color: #134e4a; margin-bottom: 6px;">
                    PILIH TAGIHAN MAHASISWA BELUM LUNAS (UNPAID)
                </label>
                <select id="select-unpaid" onchange="autoFillVa(this)" style="width: 100%; height: 42px; padding: 0 12px; border: 1px solid #99f6e4; border-radius: var(--radius-md); font-size: 13px; font-family: var(--font-sans); background:#fff; color: var(--text-heading); outline: none;">
                    <option value="">-- Pilih Tagihan untuk Disimulasikan --</option>
                    @foreach($unpaidInvoices as $inv)
                        <option value="{{ $inv->nomor_va }}" data-invoice="{{ $inv->nomor_invoice }}" data-nominal="Rp {{ number_format($inv->jumlah_tagihan, 0, ',', '.') }}" data-nama="{{ $inv->mahasiswa->nama ?? $inv->nim }}">
                            [{{ $inv->jenis_tagihan }}] {{ $inv->nomor_invoice }} &bull; {{ $inv->nim }} ({{ $inv->mahasiswa->nama ?? '' }}) &bull; Rp {{ number_format($inv->jumlah_tagihan, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; color: #134e4a; margin-bottom: 6px;">
                    NOMOR VIRTUAL ACCOUNT (VA)
                </label>
                <input type="text" name="nomor_va" id="input-va" required placeholder="Contoh: 8808210101001001"
                       style="width: 100%; height: 42px; padding: 0 12px; border: 1px solid #99f6e4; border-radius: var(--radius-md); font-size: 14px; font-family: var(--font-mono); font-weight: 700; background:#fff; color: var(--brand-fintech); outline: none;">
            </div>

            <div>
                <label style="display: block; font-size: 12px; font-weight: 700; color: #134e4a; margin-bottom: 6px;">
                    KANAL PEMBAYARAN BANK
                </label>
                <select name="channel_bayar" style="width: 100%; height: 42px; padding: 0 10px; border: 1px solid #99f6e4; border-radius: var(--radius-md); font-size: 13px; font-family: var(--font-sans); background:#fff; outline: none;">
                    <option value="M-BANKING SIMULATOR (MANDIRI)">M-Banking Mandiri</option>
                    <option value="M-BANKING SIMULATOR (BCA)">M-Banking BCA</option>
                    <option value="M-BANKING SIMULATOR (BNI)">M-Banking BNI</option>
                    <option value="M-BANKING SIMULATOR (BRI)">M-Banking BRI</option>
                    <option value="TELLER BANK KAMPUS">Teller Kasir Kampus</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center; padding-top: 14px; border-top: 1px dashed #99f6e4;">
            <div id="preview-tagihan" style="font-size: 13px; color: #0f766e;">
                Pilih salah satu tagihan di atas untuk melihat detail.
            </div>
            <button type="submit" class="btn-fintech primary" style="font-size: 13px;">
                Proses Pembayaran & Trigger Webhook &rarr;
            </button>
        </div>
    </form>
</div>

<!-- TABEL DAFTAR TAGIHAN FINANSIAL -->
<div class="card-fintech">
    <div class="card-header-fintech">
        <div>
            <h2 class="card-title-fintech">Daftar Tagihan Mahasiswa (Klik Baris untuk Detail & Modifikasi)</h2>
            <p class="card-subtitle-fintech">Klik pada baris transaksi mana saja untuk memunculkan modal rincian dan mengubah status pembayaran secara manual</p>
        </div>
        <div style="display: flex; gap: 6px;">
            <a href="{{ route('billing.dashboard', ['status' => 'ALL']) }}" class="btn-fintech {{ $filterStatus == 'ALL' ? 'primary' : 'secondary' }} sm">Semua</a>
            <a href="{{ route('billing.dashboard', ['status' => 'UNPAID']) }}" class="btn-fintech {{ $filterStatus == 'UNPAID' ? 'primary' : 'secondary' }} sm">Unpaid</a>
            <a href="{{ route('billing.dashboard', ['status' => 'PAID']) }}" class="btn-fintech {{ $filterStatus == 'PAID' ? 'primary' : 'secondary' }} sm">Paid</a>
        </div>
    </div>

    <div class="table-responsive-fintech">
        <table class="table-fintech">
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
                    <td><code>{{ $tagihan->nomor_invoice }}</code></td>
                    <td>
                        <strong style="color: var(--text-heading);">{{ $tagihan->nim }}</strong>
                        <div style="font-size: 11px; color: var(--text-muted);">{{ $tagihan->mahasiswa->nama ?? '-' }}</div>
                    </td>
                    <td>
                        @if($tagihan->jenis_tagihan === 'UKT')
                            <span class="pill-badge" style="background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;">UKT</span>
                        @elseif($tagihan->jenis_tagihan === 'WISUDA')
                            <span class="pill-badge" style="background:#f3e8ff; color:#7e22ce; border:1px solid #e9d5ff;">WISUDA</span>
                        @else
                            <span class="pill-badge danger">DENDA</span>
                        @endif
                    </td>
                    <td>{{ $tagihan->semester ?? '-' }}</td>
                    <td><strong style="color: var(--text-heading);">Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</strong></td>
                    <td>
                        <span style="font-family: var(--font-mono); font-weight: 700; color: #0f766e;">{{ $tagihan->nomor_va }}</span>
                    </td>
                    <td>
                        @if($tagihan->status_pembayaran === 'PAID')
                            <span class="pill-badge success">PAID</span>
                        @else
                            <span class="pill-badge danger">UNPAID</span>
                        @endif
                    </td>
                    <td>
                        @if($tagihan->status_pembayaran === 'PAID')
                            <div style="font-size: 12px; font-weight: 600;">{{ $tagihan->channel_bayar }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $tagihan->tanggal_bayar ? $tagihan->tanggal_bayar->format('d/m/Y H:i') : '' }}</div>
                        @else
                            <span style="color: var(--text-muted); font-size: 11px;">Jatuh tempo: {{ $tagihan->tanggal_jatuh_tempo ? $tagihan->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}</span>
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
                    <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Tidak ada transaksi tagihan yang sesuai dengan filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $tagihanList->links() }}
    </div>
</div>

<!-- AUDIT LOG WEBHOOK EVENT DISPATCHER -->
<div class="card-fintech">
    <div class="card-header-fintech">
        <div>
            <h2 class="card-title-fintech">Audit Log Webhook Event Dispatcher (EAI Asynchronous Callback)</h2>
            <p class="card-subtitle-fintech">Riwayat transmisi HTTP webhook callback yang dikirim ke SIMAK (Port 8000) saat tagihan wisuda diverifikasi</p>
        </div>
        <span class="pill-badge neutral">10 Riwayat Terakhir</span>
    </div>

    <div class="table-responsive-fintech">
        <table class="table-fintech">
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
                    <td><strong style="color: var(--text-heading);">{{ $log->event_name }}</strong></td>
                    <td><code>{{ $log->target_url }}</code></td>
                    <td>
                        @if($log->response_code == 200)
                            <span class="pill-badge success">200 OK</span>
                        @else
                            <span class="pill-badge danger">{{ $log->response_code ?? 'FAILED' }}</span>
                        @endif
                    </td>
                    <td>
                        @if($log->is_success)
                            <span class="pill-badge success">BERHASIL DITERIMA</span>
                        @else
                            <span class="pill-badge danger">GAGAL</span>
                        @endif
                    </td>
                    <td>
                        <div style="max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-family: var(--font-mono); font-size: 11px; color: var(--text-muted);" title="{{ $log->payload }}">
                            {{ $log->payload }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 24px;">
                        Belum ada transmisi event webhook. Lakukan pelunasan paket wisuda untuk memicu callback otomatis.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- POPUP MODAL DETAIL & MODIFIKASI STATUS PELUNASAN -->
<div id="modal-detail" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); z-index: 200; align-items: center; justify-content: center; padding: 20px;">
    <div class="modal-card" style="background: #ffffff; border-radius: var(--radius-lg); width: 100%; max-width: 620px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;">
        <!-- Modal Header -->
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <div>
                <span id="modal-jenis-badge" class="pill-badge" style="margin-bottom: 4px;">JENIS</span>
                <h3 id="modal-invoice-title" style="font-size: 18px; font-weight: 800; color: var(--text-heading);">INV-0000</h3>
            </div>
            <button type="button" onclick="closeDetailModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: var(--text-muted); line-height: 1; padding: 4px;">&times;</button>
        </div>

        <!-- Modal Body Scrollable -->
        <div style="padding: 24px; overflow-y: auto; flex: 1;">
            <!-- Rincian Mahasiswa & Tagihan -->
            <div style="background: var(--bg-subtle); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 16px; margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">NIM & Mahasiswa</span>
                        <strong id="modal-nama">-</strong> (<span id="modal-nim">-</span>)
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Program Studi</span>
                        <span id="modal-prodi">-</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Nomor Virtual Account</span>
                        <span id="modal-va" style="font-family: var(--font-mono); font-weight: 800; color: #0f766e; font-size: 14px;">-</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Jumlah Tagihan</span>
                        <strong id="modal-nominal" style="font-size: 15px; color: var(--text-heading);">-</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Deskripsi Tagihan</span>
                        <span id="modal-deskripsi">-</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Jatuh Tempo</span>
                        <span id="modal-tempo">-</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Status Pelunasan</span>
                        <span id="modal-status-badge" class="pill-badge">STATUS</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px; font-weight: 600; text-transform: uppercase;">Waktu & Kanal Bayar</span>
                        <span id="modal-waktu-bayar">-</span>
                    </div>
                </div>
            </div>

            <!-- FORM MODIFIKASI STATUS PELUNASAN OLEH ADMIN -->
            <div style="border: 1px solid #ccfbf1; background: #f0fdfa; border-radius: var(--radius-md); padding: 18px;">
                <h4 style="font-size: 14px; font-weight: 800; color: #0f766e; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                    Modifikasi Status Pelunasan oleh Admin Keuangan
                </h4>

                <form id="form-update-status" method="POST" action="">
                    @csrf
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #134e4a; margin-bottom: 6px;">
                            Ubah Status Pembayaran:
                        </label>
                        <div style="display: flex; gap: 20px;">
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                                <input type="radio" name="status_pembayaran" value="PAID" id="radio-paid" onchange="toggleKanalBayar(this.value)">
                                <strong style="color: var(--status-success-text);">PAID (Lunas)</strong>
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                                <input type="radio" name="status_pembayaran" value="UNPAID" id="radio-unpaid" onchange="toggleKanalBayar(this.value)">
                                <strong style="color: var(--status-danger-text);">UNPAID (Belum Lunas / Batal)</strong>
                            </label>
                        </div>
                    </div>

                    <div id="wrapper-kanal-bayar" style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #134e4a; margin-bottom: 4px;">
                            Metode / Kanal Pembayaran:
                        </label>
                        <select name="channel_bayar" id="select-modal-channel" style="width: 100%; height: 38px; padding: 0 10px; border: 1px solid #99f6e4; border-radius: var(--radius-md); font-size: 13px; font-family: var(--font-sans); background:#fff;">
                            <option value="KASIR KEUANGAN (UPDATE ADMIN)">Kasir Keuangan (Setoran Tunai)</option>
                            <option value="TRANSFER BANK (VERIFIKASI MANUAL)">Transfer Bank (Verifikasi Manual)</option>
                            <option value="M-BANKING SIMULATOR">M-Banking Simulator</option>
                            <option value="PEMBEBASAN BIAYA / BEASISWA">Pembebasan Biaya / Beasiswa</option>
                        </select>
                    </div>

                    <div id="modal-eai-notice" style="font-size: 12px; color: #047857; margin-bottom: 16px; padding: 10px 14px; background: #ecfdf5; border-radius: 6px; border: 1px dashed #a7f3d0; display: none;">
                        <strong>Integrasi EAI:</strong> Mengubah status tagihan paket wisuda ini akan secara otomatis memancarkan Webhook Callback ke SIMAK (Port 8000) untuk sinkronisasi seketika.
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #ccfbf1; padding-top: 12px;">
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
            jenisBadge.style.cssText = 'background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;';
        } else if (data.jenis_tagihan === 'WISUDA') {
            jenisBadge.style.cssText = 'background:#f3e8ff; color:#7e22ce; border:1px solid #e9d5ff;';
        } else {
            jenisBadge.style.cssText = 'background:#fee2e2; color:#b91c1c; border:1px solid #fecaca;';
        }

        const statusBadge = document.getElementById('modal-status-badge');
        statusBadge.textContent = data.status_pembayaran;
        if (data.status_pembayaran === 'PAID') {
            statusBadge.className = 'pill-badge success';
            document.getElementById('radio-paid').checked = true;
            toggleKanalBayar('PAID');
        } else {
            statusBadge.className = 'pill-badge danger';
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
