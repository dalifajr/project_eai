@extends('layouts.app')

@section('title', 'Audit Log Webhook Event — Total Billing')
@section('page_title', 'Audit Log Webhook')
@section('page_subtitle', 'Monitoring Transmisi Webhook Event HMAC SHA-256')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- Hero Banner --}}
    <div class="m3-hero-card">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="badge-m3 badge-m3-info" style="margin-bottom: 8px;">
                    <i class="fas fa-tower-broadcast me-1"></i> EAI DISPATCH AUDIT TRAIL
                </span>
                <h2 style="font-family: var(--font-display); font-size: 1.45rem; font-weight: 700; color: var(--on-surface); margin: 4px 0 0 0;">
                    Log Transmisi Webhook Realtime
                </h2>
                <p style="margin: 4px 0 0 0; color: var(--on-surface-variant); font-size: 0.875rem;">
                    Setiap event pelunasan pendaftaran wisuda dikirimkan secara asinkron ke SIMAK Universe (Port 8000) dan dicatat beserta status kode HTTP serta respon server.
                </p>
            </div>
            <div>
                <a href="{{ route('billing.dashboard') }}" class="btn-m3 btn-m3-tonal">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Overview</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="m3-card" style="padding: 16px 20px;">
        <form method="GET" action="{{ route('billing.webhook_logs') }}" style="display: flex; gap: 12px; align-items: center;">
            <div style="flex: 1;">
                <input type="text" name="q" value="{{ $search }}" class="input-m3" placeholder="Cari nama event, URL target, atau isi payload...">
            </div>
            <button type="submit" class="btn-m3 btn-m3-tonal" style="height: 42px; padding: 0 16px;">
                <i class="fas fa-magnifying-glass"></i>
                <span>Cari Log</span>
            </button>
            @if(!empty($search))
                <a href="{{ route('billing.webhook_logs') }}" class="btn-m3 btn-m3-outlined" style="height: 42px; padding: 0 12px;" title="Reset Pencarian">
                    <i class="fas fa-rotate-left"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Webhook Logs Table --}}
    <div class="m3-card" style="padding: 0; overflow: hidden;">
        <div class="table-container-modern">
            <table class="table-m3">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Event Name</th>
                        <th>Target Endpoint URL</th>
                        <th style="text-align: center; width: 110px;">HTTP Status</th>
                        <th style="text-align: center; width: 110px;">Status</th>
                        <th>Waktu Transmisi</th>
                        <th style="text-align: right; width: 120px;">Payload</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($webhookLogs as $index => $log)
                        <tr>
                            <td style="font-family: var(--font-mono); color: var(--on-surface-variant); font-size: 0.8rem;">
                                {{ $webhookLogs->firstItem() + $index }}
                            </td>
                            <td>
                                <strong style="font-family: var(--font-mono); font-size: 0.85rem; color: var(--primary);">
                                    {{ $log->event_name }}
                                </strong>
                            </td>
                            <td>
                                <span style="font-family: var(--font-mono); font-size: 0.775rem; color: var(--on-surface-variant); word-break: break-all;">
                                    {{ $log->target_url }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-m3 {{ $log->response_code >= 200 && $log->response_code < 300 ? 'badge-m3-success' : 'badge-m3-error' }}" style="font-family: var(--font-mono); font-weight: 700;">
                                    HTTP {{ $log->response_code }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                @if($log->is_success)
                                    <span class="badge-m3 badge-m3-success" style="font-size: 0.72rem; font-weight: 700;">
                                        SUCCESS
                                    </span>
                                @else
                                    <span class="badge-m3 badge-m3-error" style="font-size: 0.72rem; font-weight: 700;">
                                        FAILED
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="font-size: 0.8rem; color: var(--on-surface);">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </div>
                                <small style="color: var(--on-surface-variant); font-size: 0.7rem;">
                                    {{ $log->created_at->diffForHumans() }}
                                </small>
                            </td>
                            <td style="text-align: right;">
                                <button type="button" class="btn-m3 btn-m3-tonal" style="height: 30px; padding: 0 10px; font-size: 0.72rem;" onclick="viewPayload('{{ $log->id }}')">
                                    <i class="fas fa-code"></i>
                                    <span>Detail</span>
                                </button>
                                {{-- Hidden payload data for modal --}}
                                <div id="payloadData_{{ $log->id }}" style="display: none;" data-payload="{{ $log->payload }}" data-response="{{ $log->response_body }}"></div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 36px; color: var(--on-surface-variant);">
                                Belum ada log pengiriman webhook tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($webhookLogs->hasPages())
            <div style="padding: 16px 24px; border-top: 1px solid var(--outline-variant); display: flex; justify-content: center;">
                {{ $webhookLogs->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Payload Viewer Modal --}}
<div id="payloadModalBackdrop" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center; padding: 20px;">
    <div class="m3-card" style="width: 100%; max-width: 640px; max-height: 85vh; overflow-y: auto; padding: 24px; border-radius: var(--radius-xl);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--on-surface); margin: 0;">
                Payload & Response Webhook
            </h3>
            <button type="button" class="btn-m3-icon" onclick="closePayloadModal()" style="border: 1px solid var(--outline-variant);">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div style="margin-bottom: 16px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--on-surface); margin-bottom: 6px;">
                Request Payload (JSON Terkirim):
            </label>
            <pre id="modalPayloadBox" style="background: var(--surface-container-highest); color: var(--on-surface); padding: 12px; border-radius: var(--radius-sm); font-family: var(--font-mono); font-size: 0.775rem; white-space: pre-wrap; word-break: break-all; margin: 0; border: 1px solid var(--outline-variant); max-height: 200px; overflow-y: auto;"></pre>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--on-surface); margin-bottom: 6px;">
                SIMAK Response Body:
            </label>
            <pre id="modalResponseBox" style="background: var(--surface-container-highest); color: var(--on-surface); padding: 12px; border-radius: var(--radius-sm); font-family: var(--font-mono); font-size: 0.775rem; white-space: pre-wrap; word-break: break-all; margin: 0; border: 1px solid var(--outline-variant); max-height: 150px; overflow-y: auto;"></pre>
        </div>

        <div style="text-align: right;">
            <button type="button" class="btn-m3 btn-m3-tonal" onclick="closePayloadModal()">
                <span>Tutup</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function viewPayload(logId) {
        const el = document.getElementById('payloadData_' + logId);
        if (!el) return;
        
        let payload = el.getAttribute('data-payload');
        let response = el.getAttribute('data-response');

        try {
            payload = JSON.stringify(JSON.parse(payload), null, 2);
        } catch (e) {}

        try {
            response = JSON.stringify(JSON.parse(response), null, 2);
        } catch (e) {}

        document.getElementById('modalPayloadBox').textContent = payload || 'None';
        document.getElementById('modalResponseBox').textContent = response || 'None';

        const modal = document.getElementById('payloadModalBackdrop');
        modal.style.display = 'flex';
    }

    function closePayloadModal() {
        document.getElementById('payloadModalBackdrop').style.display = 'none';
    }
</script>
@endpush
@endsection
