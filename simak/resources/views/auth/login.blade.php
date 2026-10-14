@extends('layouts.app')

@section('title', 'Masuk ke Portal Akademik')

@section('content')
<div style="max-width: 440px; margin: 40px auto;">
    <div class="card" style="padding: 32px;">
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="width: 48px; height: 48px; background: var(--primary); color: #fff; font-weight: 700; font-size: 22px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; margin-bottom: 12px;">
                S
            </div>
            <h2 style="font-size: 20px; font-weight: 700;">Masuk ke SIMAK UNIVERSE</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Sistem Informasi Akademik Terintegrasi EAI</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">NIM atau Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus
                       style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 14px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Kata Sandi</label>
                <input type="password" name="password" required
                       style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid var(--border-color); border-radius: var(--radius-md); font-size: 14px;">
                <small style="color: var(--text-muted); font-size: 11px; display: block; margin-top: 4px;">Kata sandi default: <code>password123</code></small>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Masuk ke Sistem</button>
        </form>

        <div style="margin-top: 28px; padding-top: 20px; border-top: 1px dashed var(--border-color);">
            <p style="font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em;">Pilih Akun Demo Pengujian Skenario:</p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="{{ route('quick.login', '210101001') }}" class="btn btn-outline" style="justify-content: flex-start; text-align: left; height: auto; padding: 8px 12px; font-size: 12px;">
                    <div>
                        <strong>Dzulfikri (210101001)</strong>
                        <div style="font-size: 11px; color: var(--danger);">Skenario: UKT Belum Lunas (Form KRS Terkunci)</div>
                    </div>
                </a>
                <a href="{{ route('quick.login', '210101002') }}" class="btn btn-outline" style="justify-content: flex-start; text-align: left; height: auto; padding: 8px 12px; font-size: 12px;">
                    <div>
                        <strong>Budi Santoso (210101002)</strong>
                        <div style="font-size: 11px; color: var(--success);">Skenario: UKT Lunas (Bisa Isi KRS)</div>
                    </div>
                </a>
                <a href="{{ route('quick.login', '210101003') }}" class="btn btn-outline" style="justify-content: flex-start; text-align: left; height: auto; padding: 8px 12px; font-size: 12px;">
                    <div>
                        <strong>Siti Rahmawati (210101003)</strong>
                        <div style="font-size: 11px; color: var(--warning);">Skenario Wisuda: Skripsi Belum Selesai</div>
                    </div>
                </a>
                <a href="{{ route('quick.login', '200101004') }}" class="btn btn-outline" style="justify-content: flex-start; text-align: left; height: auto; padding: 8px 12px; font-size: 12px;">
                    <div>
                        <strong>Ahmad Fauzan (200101004)</strong>
                        <div style="font-size: 11px; color: var(--danger);">Skenario Wisuda: Lulus Skripsi tapi Ada Denda</div>
                    </div>
                </a>
                <a href="{{ route('quick.login', '200101005') }}" class="btn btn-outline" style="justify-content: flex-start; text-align: left; height: auto; padding: 8px 12px; font-size: 12px;">
                    <div>
                        <strong>Rina Kusuma (200101005)</strong>
                        <div style="font-size: 11px; color: var(--primary);">Skenario Wisuda: Siap Bayar VA Wisuda & Callback Webhook</div>
                    </div>
                </a>
                <a href="{{ route('quick.login', 'admin') }}" class="btn btn-outline" style="justify-content: flex-start; text-align: left; height: auto; padding: 8px 12px; font-size: 12px;">
                    <div>
                        <strong>Administrator SIMAK (admin)</strong>
                        <div style="font-size: 11px; color: var(--text-muted);">Lihat Rekap Seluruh Mahasiswa</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
