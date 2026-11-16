@extends('layouts.app')

@section('title', 'Masuk ke SIMAK Universe')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 28px;">
            <div style="margin-bottom: 14px;">
                @include('partials.brand-logo', ['style' => 'width: 58px; height: 58px; margin: 0 auto;'])
            </div>
            <h1 style="font-size: 22px; font-weight: 700; color: var(--on-surface); letter-spacing: -0.02em; margin-bottom: 4px;">
                SIMAK UNIVERSE
            </h1>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin: 0;">
                Sistem Informasi Akademik Terpadu (EAI Architecture)
            </p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: var(--on-surface-variant); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                    NIM atau Username
                </label>
                <input type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Masukkan NIM atau username"
                       style="width: 100%; height: 46px; padding: 0 16px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); font-size: 14px; font-family: var(--font-sans); background: var(--surface-container-low); color: var(--on-surface); outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: var(--on-surface-variant); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                    Kata Sandi
                </label>
                <input type="password" name="password" required placeholder="Masukkan kata sandi"
                       style="width: 100%; height: 46px; padding: 0 16px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); font-size: 14px; font-family: var(--font-sans); background: var(--surface-container-low); color: var(--on-surface); outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
            </div>

            <button type="submit" class="btn-m3 btn-m3-primary" style="width: 100%; height: 46px; font-size: 14px; border-radius: var(--radius-pill);">
                Masuk ke Akun &rarr;
            </button>
        </form>

        <!-- Akun Demo Cepat (M3 Helper) -->
        <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--outline-variant); text-align: center;">
            <span style="font-size: 11px; color: var(--on-surface-variant); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 10px;">
                Petunjuk Akun Pengujian
            </span>
            <div style="display: flex; justify-content: center; gap: 8px; flex-wrap: wrap;">
                <span class="badge-m3 badge-m3-neutral" style="font-size: 11px; cursor: pointer;" onclick="document.querySelector('input[name=username]').value='admin'; document.querySelector('input[name=password]').value='password';">
                    Admin: admin / password
                </span>
                <span class="badge-m3 badge-m3-primary" style="font-size: 11px; cursor: pointer;" onclick="document.querySelector('input[name=username]').value='210101001'; document.querySelector('input[name=password]').value='password';">
                    Mhs: 210101001 / password
                </span>
            </div>
        </div>
    </div>
</div>
@endsection