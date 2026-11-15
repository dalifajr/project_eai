@extends('layouts.app')

@section('title', 'Katalog Mata Kuliah')
@section('page_title', 'Katalog Mata Kuliah')
@section('page_subtitle', 'Kurikulum & Jadwal Perkuliahan SIMAK Universe')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">

    {{-- Header Hero --}}
    <div class="m3-hero-card">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <span class="badge-m3 badge-m3-neutral" style="margin-bottom: 8px;">
                    <i class="fas fa-book-bookmark me-1"></i> MASTER DATA KURIKULUM
                </span>
                <h2 style="font-family: var(--font-display); font-size: 1.45rem; font-weight: 700; color: var(--on-surface); margin: 4px 0 0 0;">
                    Katalog Mata Kuliah & Jadwal
                </h2>
                <p style="margin: 4px 0 0 0; color: var(--on-surface-variant); font-size: 0.875rem;">
                    Daftar mata kuliah yang ditawarkan untuk mahasiswa pada pengisian Kartu Rencana Studi (KRS).
                </p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="btn-m3 btn-m3-tonal">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Overview</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Stats & Filter Bar --}}
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <span class="badge-m3 badge-m3-info" style="font-size: 0.825rem;">
                Total: <strong>{{ $mataKuliahList->count() }}</strong> Mata Kuliah
            </span>
            <span class="badge-m3 badge-m3-neutral" style="font-size: 0.825rem;">
                Total Beban: <strong>{{ $mataKuliahList->sum('sks') }}</strong> SKS
            </span>
        </div>
        <div style="width: 100%; max-width: 320px;">
            <input type="text" id="courseSearchInput" class="input-m3" placeholder="Cari nama atau kode MK..." onkeyup="filterCourses()">
        </div>
    </div>

    {{-- Course Table Card --}}
    <div class="m3-card" style="padding: 0; overflow: hidden;">
        <div class="table-container-modern">
            <table class="table-m3" id="coursesTable">
                <thead>
                    <tr>
                        <th style="width: 100px;">Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th style="text-align: center; width: 70px;">SKS</th>
                        <th style="text-align: center; width: 90px;">Semester</th>
                        <th>Jadwal Kuliah</th>
                        <th>Ruangan</th>
                        <th style="text-align: center; width: 90px;">Kuota</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mataKuliahList as $mk)
                        <tr>
                            <td>
                                <span class="badge-m3 badge-m3-neutral" style="font-family: var(--font-mono); font-weight: 700;">
                                    {{ $mk->kode_mk }}
                                </span>
                            </td>
                            <td>
                                <strong style="color: var(--on-surface); font-size: 0.925rem;">{{ $mk->nama_mk }}</strong>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-m3 badge-m3-primary" style="font-weight: 700;">
                                    {{ $mk->sks }} SKS
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-m3 badge-m3-neutral">
                                    Sem {{ $mk->semester }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px; font-size: 0.85rem; color: var(--on-surface);">
                                    <i class="fas fa-clock text-primary"></i>
                                    <span>{{ $mk->hari }}, {{ substr($mk->jam_mulai, 0, 5) }} - {{ substr($mk->jam_selesai, 0, 5) }}</span>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.85rem; color: var(--on-surface); font-weight: 500;">
                                    <i class="fas fa-door-open me-1 text-secondary"></i> {{ $mk->ruangan }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge-m3 badge-m3-info" style="font-weight: 600;">
                                    {{ $mk->kuota }} Kursi
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 32px; color: var(--on-surface-variant);">
                                Tidak ada data mata kuliah tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function filterCourses() {
        const input = document.getElementById('courseSearchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('coursesTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const tdText = tr[i].textContent || tr[i].innerText;
            if (tdText.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>
@endpush
@endsection
