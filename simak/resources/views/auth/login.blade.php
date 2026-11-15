@extends('layouts.app')

@section('title', 'Masuk ke Portal Akademik')

@section('content')
<div style="max-width: 460px; margin: 40px auto;">
    <div class="m3-card" style="border-radius: var(--radius-xl); padding: 40px 36px; background: var(--surface-container-lowest);">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="width: 56px; height: 56px; background: var(--primary); color: var(--on-primary); font-weight: 700; font-size: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: var(--radius-md); margin-bottom: 16px; font-family: var(--font-display);">
                S
            </div>
            <h1 style="font-size: 24px; font-weight: 700; color: var(--on-surface); letter-spacing: -0.02em;">
                SIMAK UNIVERSE
            </h1>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin-top: 6px;">
                Sistem Informasi Akademik Terpadu (EAI Architecture)
            </p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--on-surface); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.04em;">
                    NIM atau Username
                </label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Masukkan NIM atau username"
                       style="width: 100%; height: 48px; padding: 0 16px; border: 1px solid var(--outline-variant); border-radius: var(--radius-sm); font-size: 14px; font-family: var(--font-sans); background: var(--surface-container-low); color: var(--on-surface); outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
            </div>

            <div style="margin-bottom: 26px;">
                <label style="display: block; font-size: 12px; font-weight: 600; color: var(--on-surface); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.04em;">
                    Kata Sandi
                </label>
                <input type="password" name="password" required placeholder="Masukkan kata sandi"
                       style="width: 100%; height: 48px; padding: 0 16px; border: 1px solid var(--outline-variant); border-radius: var(--radius-sm); font-size: 14px; font-family: var(--font-sans); background: var(--surface-container-low); color: var(--on-surface); outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
            </div>

            <button type="submit" class="btn-modern primary" style="width: 100%; font-size: 14.5px; height: 48px; border-radius: var(--radius-pill);">
                Masuk ke Akun &rarr;
            </button>
        </form>

        <!-- Akun Demo Cepat (M3 Helper) -->
        <div style="margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--outline-variant); text-align: center;">
            <span style="font-size: 11.5px; color: var(--on-surface-variant); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 12px;">
                Petunjuk Akun Pengujian
            </span>
            <div style="display: flex; justify-content: center; gap: 8px; flex-wrap: wrap;">
                <span class="m3-badge-pill neutral" style="font-size: 11px; cursor: pointer;" onclick="document.querySelector('input[name=username]').value='admin'; document.querySelector('input[name=password]').value='password';">
                    Admin: admin / password
                </span>
                <span class="m3-badge-pill primary" style="font-size: 11px; cursor: pointer;" onclick="document.querySelector('input[name=username]').value='210101001'; document.querySelector('input[name=password]').value='password';">
                    Mhs: 210101001 / password
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
