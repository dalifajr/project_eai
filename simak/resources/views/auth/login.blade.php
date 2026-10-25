@extends('layouts.app')

@section('title', 'Masuk ke Portal Akademik')

@section('content')
<div style="max-width: 440px; margin: 50px auto;">
    <div class="card-modern" style="padding: 34px 32px;">
        <div style="text-align: center; margin-bottom: 26px;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); color: #fff; font-weight: 800; font-size: 20px; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; margin-bottom: 14px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);">
                S
            </div>
            <h1 style="font-size: 22px; font-weight: 800; color: var(--text-heading); letter-spacing: -0.02em;">
                SIMAK UNIVERSE
            </h1>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                Sistem Informasi Akademik Terpadu
            </p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-heading); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.03em;">
                    NIM atau Username
                </label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Masukkan NIM atau username"
                       style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); font-size: 14px; font-family: var(--font-sans); background: #ffffff; color: var(--text-heading); outline: none; transition: border-color 0.15s ease;">
            </div>

            <div style="margin-bottom: 22px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-heading); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.03em;">
                    Kata Sandi
                </label>
                <input type="password" name="password" required placeholder="Masukkan kata sandi"
                       style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); font-size: 14px; font-family: var(--font-sans); background: #ffffff; color: var(--text-heading); outline: none; transition: border-color 0.15s ease;">
            </div>

            <button type="submit" class="btn-modern primary" style="width: 100%; font-size: 14px; height: 44px;">
                Masuk ke Akun &rarr;
            </button>
        </form>
    </div>
</div>
@endsection
