@extends('layouts.app')

@section('title', 'Financial & Billing Overview')

@section('content')
<!-- Metric Cards -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
    <div class="card" style="margin-bottom: 0; padding: 18px;">
        <span style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Total Tagihan Terbit</span>
        <div style="font-size: 24px; font-weight: 700; margin-top: 4px; color: var(--text-main);">{{ $totalTagihanCount }} Invoice</div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 18px;">
        <span style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Penerimaan Lunas (PAID)</span>
        <div style="font-size: 24px; font-weight: 700; margin-top: 4px; color: var(--primary);">Rp {{ number_format($totalNominalPaid, 0, ',', '.') }}</div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 18px;">
        <span style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Piutang / Pending (UNPAID)</span>
        <div style="font-size: 24px; font-weight: 700; margin-top: 4px; color: #b45309;">Rp {{ number_format($totalNominalUnpaid, 0, ',', '.') }}</div>
    </div>
    <div class="card" style="margin-bottom: 0; padding: 18px;">
        <span style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Invoice Menunggu Bayar</span>
        <div style="font-size: 24px; font-weight: 700; margin-top: 4px; color: var(--danger);">{{ $unpaidCount }} Invoice</div>
    </div>
</div>

<!-- INTERACTIVE PAYMENT SIMULATOR CARD -->
<div class="card" style="border: 2px solid #0f766e; background: #f0fdfa;">
    <div class="card-header" style="border-color: #ccfbf1;">
        <div>
            <span class="badge" style="background:#0f766e; color:#fff; margin-bottom:4px;">EAI LIVE DEMONSTRATION</span>
            <h2 class="card-title" style="color: #0f766e; font-size: 18px;">Simulator Pembayaran Instan & Webhook Dispatcher</h2>
            <p class="card-subtitle" style="color: #115e59;">
                Gunakan simulator ini untuk melunasi Virtual Account mahasiswa dan memicu Webhook Callback secara otomatis ke SIMAK.
            </p>
        </div>
    </div>

    <form action="{{ route('billing.simulasi_bayar') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 2fr 1.5fr 1fr; gap: 16px; align-items: flex-end;">
            <div>
                <label style="display: block; font-size: 13px; font-weight: 700; color: #134e4a; margin-bottom: 6px;">
                    Pilih Tagihan Mahasiswa yang Belum Lunas (UNPAID):
                </label>
                <select id="select-unpaid" onchange="autoFillVa(this)" style="width: 100%; height: 44px; padding: 0 12px; border: 1px solid #99f6e4; border-radius: var(--radius-md); font-size: 13px; background:#fff;">
                    <option value="">-- Pilih Tagihan untuk Disimulasikan --</option>
                    @foreach($unpaidInvoices as $inv)
                        <option value="{{ $inv->nomor_va }}" data-invoice="{{ $inv->nomor_invoice }}" data-nominal="Rp {{ number_format($inv->jumlah_tagihan, 0, ',', '.') }}" data-nama="{{ $inv->mahasiswa->nama ?? $inv->nim }}">
                            [{{ $inv->jenis_tagihan }}] {{ $inv->nomor_invoice }} - {{ $inv->nim }} ({{ $inv->mahasiswa->nama ?? '' }}) - Rp {{ number_format($inv->jumlah_tagihan, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 13px; font-weight: 700; color: #134e4a; margin-bottom: 6px;">
                    Nomor Virtual Account (VA):
                </label>
                <input type="text" name="nomor_va" id="input-va" required placeholder="Contoh: 8808210101001001"
                       style="width: 100%; height: 44px; padding: 0 12px; border: 1px solid #99f6e4; border-radius: var(--radius-md); font-size: 14px; font-family: monospace; font-weight: 700; background:#fff;">
            </div>

            <div>
                <label style="display: block; font-size: 13px; font-weight: 700; color: #134e4a; margin-bottom: 6px;">
                    Kanal Pembayaran:
                </label>
                <select name="channel_bayar" style="width: 100%; height: 44px; padding: 0 10px; border: 1px solid #99f6e4; border-radius: var(--radius-md); font-size: 13px; background:#fff;">
                    <option value="M-BANKING SIMULATOR (MANDIRI)">M-Banking Mandiri</option>
                    <option value="M-BANKING SIMULATOR (BCA)">M-Banking BCA</option>
                    <option value="M-BANKING SIMULATOR (BNI)">M-Banking BNI</option>
                    <option value="M-BANKING SIMULATOR (BRI)">M-Banking BRI</option>
                    <option value="TELLER BANK KAMPUS">Teller Kasir</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 14px; display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px dashed #99f6e4;">
            <div id="preview-tagihan" style="font-size: 13px; color: #0f766e;">
                Pilih salah satu tagihan di atas untuk melihat detail.
            </div>
            <button type="submit" class="btn btn-primary" style="background:#0f766e; font-size: 14px;">
                Proses Pembayaran Instan & Trigger Webhook &rarr;
            </button>
        </div>
    </form>
</div>

<!-- TABEL DAFTAR TAGIHAN (DENGAN KLIK ROW POPUP DETAIL & MODIFIKASI) -->
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Daftar Tagihan Mahasiswa (Klik Baris untuk Detail & Modifikasi)</h3>
            <p class="card-subtitle">Klik salah satu baris transaksi untuk membuka popup detail dan mengubah status pelunasan</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('billing.dashboard', ['status' => 'ALL']) }}" class="btn {{ $filterStatus == 'ALL' ? 'btn-primary' : 'btn-outline' }}" style="min-height:34px; padding:4px 12px; font-size:12px;">Semua</a>
            <a href="{{ route('billing.dashboard', ['status' => 'UNPAID']) }}" class="btn {{ $filterStatus == 'UNPAID' ? 'btn-primary' : 'btn-outline' }}" style="min-height:34px; padding:4px 12px; font-size:12px;">Unpaid</a>
            <a href="{{ route('billing.dashboard', ['status' => 'PAID']) }}" class="btn {{ $filterStatus == 'PAID' ? 'btn-primary' : 'btn-outline' }}" style="min-height:34px; padding:4px 12px; font-size:12px;">Paid</a>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>NIM & Nama Mahasiswa</th>
                    <th>Jenis Tagihan</th>
                    <th>Semester</th>
                    <th>Jumlah Tagihan</th>
                    <th>Nomor Virtual Account</th>
                    <th>Status</th>
                    <th>Kanal / Waktu Bayar</th>
                    <th style="text-align: center;">Aksi</th>
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
                <tr class="row-clickable" onclick="openDetailModal({{ json_encode($tagihanData) }})" style="cursor: pointer; transition: background 0.15s ease;">
                    <td><code>{{ $tagihan->nomor_invoice }}</code></td>
                    <td>
                        <strong>{{ $tagihan->nim }}</strong>
                        <div style="font-size: 11px; color: var(--text-muted);">{{ $tagihan->mahasiswa->nama ?? '-' }}</div>
                    </td>
                    <td>
                        @if($tagihan->jenis_tagihan === 'UKT')
                            <span class="badge" style="background:#e0f2fe; color:#0369a1;">UKT</span>
                        @elseif($tagihan->jenis_tagihan === 'WISUDA')
                            <span class="badge" style="background:#f3e8ff; color:#7e22ce;">WISUDA</span>
                        @else
                            <span class="badge" style="background:#fee2e2; color:#b91c1c;">DENDA</span>
                        @endif
                    </td>
                    <td>{{ $tagihan->semester ?? '-' }}</td>
                    <td><strong>Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</strong></td>
                    <td>
                        <span style="font-family: monospace; font-weight: 700; color: #0f766e;">{{ $tagihan->nomor_va }}</span>
                    </td>
                    <td>
                        @if($tagihan->status_pembayaran === 'PAID')
                            <span class="badge badge-success">PAID</span>
                        @else
                            <span class="badge badge-danger">UNPAID</span>
                        @endif
                    </td>
                    <td>
                        @if($tagihan->status_pembayaran === 'PAID')
                            <div style="font-size: 12px;">{{ $tagihan->channel_bayar }}</div>
                            <div style="font-size: 10px; color: var(--text-muted);">{{ $tagihan->tanggal_bayar ? $tagihan->tanggal_bayar->format('d/m/Y H:i') : '' }}</div>
                        @else
                            <span style="color: var(--text-muted); font-size: 11px;">Jatuh tempo: {{ $tagihan->tanggal_jatuh_tempo ? $tagihan->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;" onclick="event.stopPropagation();">
                        <button type="button" class="btn btn-outline" onclick="openDetailModal({{ json_encode($tagihanData) }})" style="min-height: 32px; padding: 4px 10px; font-size: 11px;">
                            Detail / Ubah
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 30px;">
                        Tidak ada tagihan yang sesuai dengan filter.
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
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Audit Log Webhook Event Dispatcher (EAI Asynchronous Callback)</h3>
            <p class="card-subtitle">Riwayat event callback otomatis yang dipancarkan ke SIMAK setelah verifikasi pembayaran wisuda</p>
        </div>
        <span class="badge badge-secondary">10 Riwayat Terakhir</span>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
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
                    <td style="font-size: 11px; white-space: nowrap;">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td><strong>{{ $log->event_name }}</strong></td>
                    <td><code style="font-size: 11px;">{{ $log->target_url }}</code></td>
                    <td>
                        @if($log->response_code == 200)
                            <span class="badge badge-success">{{ $log->response_code }} OK</span>
                        @else
                            <span class="badge badge-danger">{{ $log->response_code ?? 'FAILED' }}</span>
                        @endif
                    </td>
                    <td>
                        @if($log->is_success)
                            <span class="badge badge-success">BERHASIL DITERIMA</span>
                        @else
                            <span class="badge badge-danger">GAGAL</span>
                        @endif
                    </td>
                    <td>
                        <div style="max-width: 320px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-family: monospace; font-size: 11px; color: var(--text-muted);">
                            {{ $log->payload }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 20px;">
                        Belum ada event webhook yang dipancarkan. Lakukan pembayaran paket wisuda untuk memicu callback otomatis.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- POPUP MODAL DETAIL & MODIFIKASI STATUS PELUNASAN -->
<div id="modal-detail" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 100; align-items: center; justify-content: center; padding: 20px;">
    <div class="modal-card" style="background: #ffffff; border-radius: var(--radius-lg); width: 100%; max-width: 620px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; max-height: 90vh; display: flex; flex-direction: column;">
        <!-- Modal Header -->
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <div>
                <span id="modal-jenis-badge" class="badge" style="margin-bottom: 4px;">JENIS</span>
                <h3 id="modal-invoice-title" style="font-size: 18px; font-weight: 700; color: var(--text-main);">INV-0000</h3>
            </div>
            <button type="button" onclick="closeDetailModal()" style="background: none; border: none; font-size: 22px; cursor: pointer; color: var(--text-muted); line-height: 1; padding: 4px;">&times;</button>
        </div>

        <!-- Modal Body Scrollable -->
        <div style="padding: 24px; overflow-y: auto; flex: 1;">
            <!-- Rincian Mahasiswa & Tagihan -->
            <div style="background: #f8fafc; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 16px; margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px;">NIM & Nama Mahasiswa</span>
                        <strong id="modal-nama">-</strong> (<span id="modal-nim">-</span>)
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px;">Program Studi</span>
                        <span id="modal-prodi">-</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px;">Nomor Virtual Account</span>
                        <span id="modal-va" style="font-family: monospace; font-weight: 700; color: #0f766e; font-size: 14px;">-</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px;">Jumlah Tagihan</span>
                        <strong id="modal-nominal" style="font-size: 15px; color: var(--text-main);">-</strong>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px;">Deskripsi Tagihan</span>
                        <span id="modal-deskripsi">-</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px;">Jatuh Tempo</span>
                        <span id="modal-tempo">-</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px;">Status Pelunasan Saat Ini</span>
                        <span id="modal-status-badge" class="badge">STATUS</span>
                    </div>
                    <div>
                        <span style="color: var(--text-muted); display: block; font-size: 11px;">Waktu & Kanal Bayar</span>
                        <span id="modal-waktu-bayar">-</span>
                    </div>
                </div>
            </div>

            <!-- FORM MODIFIKASI STATUS PELUNASAN OLEH ADMIN -->
            <div style="border: 1px solid #ccfbf1; background: #f0fdfa; border-radius: var(--radius-md); padding: 18px;">
                <h4 style="font-size: 14px; font-weight: 700; color: #0f766e; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                    Modifikasi Status Pelunasan oleh Admin Keuangan
                </h4>

                <form id="form-update-status" method="POST" action="">
                    @csrf
                    <div style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #134e4a; margin-bottom: 6px;">
                            Ubah Status Pembayaran:
                        </label>
                        <div style="display: flex; gap: 16px;">
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                                <input type="radio" name="status_pembayaran" value="PAID" id="radio-paid" onchange="toggleKanalBayar(this.value)">
                                <strong style="color: var(--success);">PAID (Lunas)</strong>
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer;">
                                <input type="radio" name="status_pembayaran" value="UNPAID" id="radio-unpaid" onchange="toggleKanalBayar(this.value)">
                                <strong style="color: var(--danger);">UNPAID (Belum Lunas / Batal)</strong>
                            </label>
                        </div>
                    </div>

                    <div id="wrapper-kanal-bayar" style="margin-bottom: 14px;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #134e4a; margin-bottom: 4px;">
                            Keterangan Kanal / Metode Pembayaran:
                        </label>
                        <select name="channel_bayar" id="select-modal-channel" style="width: 100%; height: 38px; padding: 0 10px; border: 1px solid #99f6e4; border-radius: var(--radius-md); font-size: 13px; background:#fff;">
                            <option value="KASIR KEUANGAN (UPDATE ADMIN)">Kasir Keuangan (Setoran Manual)</option>
                            <option value="TRANSFER BANK (VERIFIKASI MANUAL)">Transfer Bank (Verifikasi Manual)</option>
                            <option value="M-BANKING SIMULATOR">M-Banking Simulator</option>
                            <option value="PEMBEBASAN BIAYA / BEASISWA">Pembebasan Biaya / Beasiswa</option>
                        </select>
                    </div>

                    <div id="modal-eai-notice" style="font-size: 12px; color: #047857; margin-bottom: 16px; padding: 8px 12px; background: #ecfdf5; border-radius: 6px; border: 1px dashed #a7f3d0; display: none;">
                        <strong>Integrasi EAI:</strong> Mengubah status tagihan paket wisuda ini akan secara otomatis memicu Webhook Callback ke SIMAK (Port 8000) untuk sinkronisasi seketika.
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #ccfbf1; padding-top: 12px;">
                        <button type="button" onclick="closeDetailModal()" class="btn btn-outline" style="min-height: 38px; font-size: 13px;">Batal</button>
                        <button type="submit" class="btn btn-primary" style="min-height: 38px; font-size: 13px; background: #0f766e;">Simpan Perubahan Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .row-clickable:hover {
        background-color: #f0fdfa !important;
    }
</style>
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

        // Badge Jenis
        const jenisBadge = document.getElementById('modal-jenis-badge');
        jenisBadge.textContent = data.jenis_tagihan;
        if (data.jenis_tagihan === 'UKT') {
            jenisBadge.style.cssText = 'background:#e0f2fe; color:#0369a1;';
        } else if (data.jenis_tagihan === 'WISUDA') {
            jenisBadge.style.cssText = 'background:#f3e8ff; color:#7e22ce;';
        } else {
            jenisBadge.style.cssText = 'background:#fee2e2; color:#b91c1c;';
        }

        // Badge Status
        const statusBadge = document.getElementById('modal-status-badge');
        statusBadge.textContent = data.status_pembayaran;
        if (data.status_pembayaran === 'PAID') {
            statusBadge.className = 'badge badge-success';
            document.getElementById('radio-paid').checked = true;
            toggleKanalBayar('PAID');
        } else {
            statusBadge.className = 'badge badge-danger';
            document.getElementById('radio-unpaid').checked = true;
            toggleKanalBayar('UNPAID');
        }

        // Waktu & Kanal Bayar
        const waktuBayarEl = document.getElementById('modal-waktu-bayar');
        if (data.status_pembayaran === 'PAID') {
            waktuBayarEl.textContent = data.tanggal_bayar + ' via ' + data.channel_bayar;
        } else {
            waktuBayarEl.textContent = 'Belum dibayar';
        }

        // Form Action
        document.getElementById('form-update-status').action = data.update_url;

        // Notice EAI
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

    // Tutup modal jika klik di luar box (backdrop) atau tekan tombol Escape (R-32 accessibility)
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
