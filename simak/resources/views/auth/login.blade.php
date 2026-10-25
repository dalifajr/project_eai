@extends('layouts.app')

@section('title', 'Masuk ke Portal Akademik')

@section('content')
<div style="max-width: 960px; margin: 30px auto; display: grid; grid-template-columns: 1fr 1.35fr; gap: 32px; align-items: start;">
    <!-- Sisi Kiri: Form Login Standar -->
    <div class="card-modern" style="padding: 30px;">
        <div style="margin-bottom: 24px;">
            <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); color: #fff; font-weight: 800; font-size: 18px; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; margin-bottom: 14px; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);">
                S
            </div>
            <h1 style="font-size: 20px; font-weight: 800; color: var(--text-heading); letter-spacing: -0.02em;">Masuk ke SIMAK</h1>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Sistem Informasi Akademik Terintegrasi EAI</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-heading); margin-bottom: 6px;">
                    NIM ATAU USERNAME
                </label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Masukkan NIM atau username"
                       style="width: 100%; height: 42px; padding: 0 14px; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); font-size: 14px; font-family: var(--font-sans); background: #ffffff; color: var(--text-heading); outline: none;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-heading); margin-bottom: 6px;">
                    KATA SANDI
                </label>
                <input type="password" name="password" required placeholder="Masukkan kata sandi"
                       style="width: 100%; height: 42px; padding: 0 14px; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); font-size: 14px; font-family: var(--font-sans); background: #ffffff; color: var(--text-heading); outline: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px;">
                    <span style="font-size: 11px; color: var(--text-muted);">Default: <code>password123</code></span>
                </div>
            </div>

            <button type="submit" class="btn-modern primary" style="width: 100%; font-size: 14px; height: 44px;">
                Masuk ke Portal Akademik &rarr;
            </button>
        </form>

        <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border-subtle); font-size: 12px; color: var(--text-muted); text-align: center;">
            Terhubung ke Total Billing System di port <code>8001</code>
        </div>
    </div>

    <!-- Sisi Kanan: Quick Switcher Skenario Pengujian EAI -->
    <div>
        <div style="margin-bottom: 14px;">
            <span class="badge-pill brand" style="margin-bottom: 6px;">SKENARIO PENGUJIAN INSTAN</span>
            <h2 style="font-size: 18px; font-weight: 800; color: var(--text-heading);">Pilih Akun Demo Integrasi EAI</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">
                Klik salah satu kartu di bawah ini untuk langsung masuk tanpa perlu mengetik kredensial:
            </p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            <!-- Persona 1: Dzulfikri (UKT Belum Lunas) -->
            <a href="{{ route('quick.login', '210101001') }}" style="text-decoration: none; color: inherit;">
                <div class="card-modern" style="padding: 14px 18px; margin-bottom: 0; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid #ef4444; transition: all 0.15s ease;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="font-size: 14px; color: var(--text-heading);">Dzulfikri Alifajri</strong>
                            <code style="font-size: 11px;">210101001</code>
                        </div>
                        <p style="font-size: 12px; color: var(--status-danger-text); margin-top: 2px;">
                            Skenario 1: UKT Semester 7 Belum Lunas (Form KRS Terkunci EAI)
                        </p>
                    </div>
                    <span class="badge-pill danger">UKT UNPAID</span>
                </div>
            </a>

            <!-- Persona 2: Budi (UKT Lunas) -->
            <a href="{{ route('quick.login', '210101002') }}" style="text-decoration: none; color: inherit;">
                <div class="card-modern" style="padding: 14px 18px; margin-bottom: 0; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid #10b981; transition: all 0.15s ease;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="font-size: 14px; color: var(--text-heading);">Budi Santoso</strong>
                            <code style="font-size: 11px;">210101002</code>
                        </div>
                        <p style="font-size: 12px; color: var(--status-success-text); margin-top: 2px;">
                            Skenario 2: UKT Lunas (Akses Pengisian KRS Terbuka Penuh)
                        </p>
                    </div>
                    <span class="badge-pill success">UKT PAID</span>
                </div>
            </a>

            <!-- Persona 3: Siti (Wisuda - Skripsi Belum) -->
            <a href="{{ route('quick.login', '210101003') }}" style="text-decoration: none; color: inherit;">
                <div class="card-modern" style="padding: 14px 18px; margin-bottom: 0; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid #f59e0b; transition: all 0.15s ease;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="font-size: 14px; color: var(--text-heading);">Siti Rahmawati</strong>
                            <code style="font-size: 11px;">210101003</code>
                        </div>
                        <p style="font-size: 12px; color: var(--status-warning-text); margin-top: 2px;">
                            Skenario Wisuda: Skripsi Belum Lulus (Wisuda Ditolak Tahap 1)
                        </p>
                    </div>
                    <span class="badge-pill warning">SKRIPSI BELUM</span>
                </div>
            </a>

            <!-- Persona 4: Ahmad (Wisuda - Ada Tunggakan Denda) -->
            <a href="{{ route('quick.login', '200101004') }}" style="text-decoration: none; color: inherit;">
                <div class="card-modern" style="padding: 14px 18px; margin-bottom: 0; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid #dc2626; transition: all 0.15s ease;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="font-size: 14px; color: var(--text-heading);">Ahmad Fauzan</strong>
                            <code style="font-size: 11px;">200101004</code>
                        </div>
                        <p style="font-size: 12px; color: var(--status-danger-text); margin-top: 2px;">
                            Skenario Wisuda: Skripsi Lulus, Namun Dihambat Tunggakan Denda
                        </p>
                    </div>
                    <span class="badge-pill danger">ADA DENDA</span>
                </div>
            </a>

            <!-- Persona 5: Rina (Wisuda Siap Bayar & Webhook) -->
            <a href="{{ route('quick.login', '200101005') }}" style="text-decoration: none; color: inherit;">
                <div class="card-modern" style="padding: 14px 18px; margin-bottom: 0; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid #2563eb; transition: all 0.15s ease;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="font-size: 14px; color: var(--text-heading);">Rina Kusuma</strong>
                            <code style="font-size: 11px;">200101005</code>
                        </div>
                        <p style="font-size: 12px; color: var(--brand-primary); margin-top: 2px;">
                            Skenario Wisuda: Siap Bayar VA Wisuda & Simulasi Webhook Callback
                        </p>
                    </div>
                    <span class="badge-pill brand">SIAP WISUDA</span>
                </div>
            </a>

            <!-- Persona 6: Admin -->
            <a href="{{ route('quick.login', 'admin') }}" style="text-decoration: none; color: inherit;">
                <div class="card-modern" style="padding: 14px 18px; margin-bottom: 0; display: flex; align-items: center; justify-content: space-between; border-left: 4px solid #475569; transition: all 0.15s ease;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <strong style="font-size: 14px; color: var(--text-heading);">Administrator Kampus</strong>
                            <code style="font-size: 11px;">admin</code>
                        </div>
                        <p style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                            Dashboard Admin: Rekapitulasi Mahasiswa, Status KRS & Wisuda
                        </p>
                    </div>
                    <span class="badge-pill neutral">ADMIN PORTAL</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
