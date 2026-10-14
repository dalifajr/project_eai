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

<!-- TABEL DAFTAR TAGIHAN -->
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Daftar Tagihan Mahasiswa (Seluruh Semester & Kategori)</h3>
            <p class="card-subtitle">Data tagihan UKT, Denda Laboratorium/KRS, dan Paket Wisuda Sarjana</p>
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
                </tr>
            </thead>
            <tbody>
                @forelse($tagihanList as $tagihan)
                <tr>
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
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 30px;">
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
</script>
@endsection
