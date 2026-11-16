@extends('layouts.app')

@section('title', 'Masuk ke Total Billing System')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 28px;">
            <div style="margin-bottom: 14px;">
                @include('partials.brand-logo', ['style' => 'width: 58px; height: 58px; margin: 0 auto;'])
            </div>
            <h1 style="font-size: 22px; font-weight: 700; color: var(--on-surface); letter-spacing: -0.02em; margin-bottom: 4px;">
                TOTAL BILLING SYSTEM
            </h1>
            <p style="font-size: 13px; color: var(--on-surface-variant); margin: 0;">
                Financial Core & Gateway Backoffice (EAI v2.0)
            </p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: var(--on-surface-variant); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                    Email Staf Keuangan
                </label>
                <input type="email" name="email" value="{{ old('email', 'admin@billing.kampus.id') }}" required autofocus placeholder="nama@billing.kampus.id"
                       style="width: 100%; height: 46px; padding: 0 16px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); font-size: 14px; font-family: var(--font-sans); background: var(--surface-container-low); color: var(--on-surface); outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 11.5px; font-weight: 700; color: var(--on-surface-variant); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em;">
                    Kata Sandi
                </label>
                <input type="password" name="password" required value="password123" placeholder="Masukkan kata sandi"
                       style="width: 100%; height: 46px; padding: 0 16px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); font-size: 14px; font-family: var(--font-sans); background: var(--surface-container-low); color: var(--on-surface); outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
            </div>

            <button type="submit" class="btn-m3 btn-m3-primary" style="width: 100%; height: 46px; font-size: 14px; border-radius: var(--radius-pill);">
                Masuk ke Konsol Billing &rarr;
            </button>
        </form>

        <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--outline-variant); text-align: center;">
            <span style="font-size: 11px; color: var(--on-surface-variant); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 10px;">
                Petunjuk Akun Keuangan
            </span>
            <span class="badge-m3 badge-m3-neutral" style="font-size: 11px; cursor: pointer;" onclick="document.querySelector('input[name=email]').value='admin@billing.kampus.id'; document.querySelector('input[name=password]').value='password123';">
                Email: admin@billing.kampus.id / password123
            </span>
        </div>
    </div>
</div>
@endsection