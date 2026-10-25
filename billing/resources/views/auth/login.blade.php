@extends('layouts.app')

@section('title', 'Masuk ke Sistem Keuangan')

@section('content')
<div style="max-width: 440px; margin: 50px auto;">
    <div class="card-fintech" style="padding: 34px 32px;">
        <div style="text-align: center; margin-bottom: 26px;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); color: #fff; font-weight: 800; font-size: 20px; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; margin-bottom: 14px; box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);">
                B
            </div>
            <h1 style="font-size: 22px; font-weight: 800; color: var(--text-heading); letter-spacing: -0.02em;">
                TOTAL BILLING SYSTEM
            </h1>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                Financial Core & Gateway Backoffice
            </p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-heading); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.03em;">
                    Email Staf Keuangan
                </label>
                <input type="email" name="email" value="{{ old('email', 'admin@billing.kampus.id') }}" required autofocus placeholder="nama@billing.kampus.id"
                       style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); font-size: 14px; font-family: var(--font-sans); background: #ffffff; color: var(--text-heading); outline: none;">
            </div>

            <div style="margin-bottom: 22px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-heading); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.03em;">
                    Kata Sandi
                </label>
                <input type="password" name="password" required value="password123" placeholder="Masukkan kata sandi"
                       style="width: 100%; height: 44px; padding: 0 14px; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); font-size: 14px; font-family: var(--font-sans); background: #ffffff; color: var(--text-heading); outline: none;">
            </div>

            <button type="submit" class="btn-fintech primary" style="width: 100%; font-size: 14px; height: 44px;">
                Masuk ke Konsol Billing &rarr;
            </button>
        </form>
    </div>
</div>
@endsection
