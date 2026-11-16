@php
    $isAuthenticated = Auth::check();
    $hideSidebar = !$isAuthenticated;
    $isLoginPage = request()->routeIs('login*');
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Total Billing System') — Gateway Pembayaran & Invoicing EAI</title>

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Sans Font & FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- M3 Expressive Fintech / Flat Material You Design System --}}
    <link rel="stylesheet" href="{{ asset('css/m3-material-you.css') }}?v=2.3">

    @stack('styles')
</head>
<body class="{{ $hideSidebar ? 'no-sidebar' : '' }}">
    {{-- Top App Bar / Navbar (M3 Flat Material You) --}}
    <nav class="navbar navbar-expand fixed-top px-3 px-md-4 pcc-platform-bar" style="z-index: 1030;">
        @if(!$hideSidebar)
            {{-- Mobile Brand & Sidebar Toggle (< 992px) Saat Login --}}
            <div class="d-flex align-items-center gap-2 d-lg-none">
                <button type="button" class="btn btn-link link-body-emphasis m3-icon-btn p-0" id="sidebarToggle" aria-label="Buka Menu" style="width: 36px; height: 36px;">
                    <i class="fas fa-bars" style="font-size: 1.1rem;"></i>
                </button>
                <a href="{{ route('billing.dashboard') }}" class="navbar-brand d-flex align-items-center gap-2 m-0 text-decoration-none" style="font-size: 0.95rem;">
                    @include('partials.brand-logo', ['style' => 'width: 32px; height: 32px;'])
                    <span class="fw-bold" style="color: var(--on-surface); letter-spacing: -0.01em;">
                        TOTAL BILLING
                    </span>
                </a>
            </div>

            {{-- Desktop Page Title & Subtitle (>= 992px) --}}
            <div class="d-none d-lg-flex flex-column" style="line-height: 1.2;">
                <span style="font-family: var(--font-display); font-size: 1.05rem; font-weight: 700; color: var(--on-surface);">
                    @yield('page_title', 'Dashboard Finansial')
                </span>
                <span style="font-size: 0.75rem; color: var(--on-surface-variant);">
                    @yield('page_subtitle', 'Total Billing System — Payment Gateway & Invoicing')
                </span>
            </div>
        @else
            {{-- Guest / Pre-Login Brand Header --}}
            <div class="d-flex align-items-center gap-2">
                <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center gap-2.5 m-0 text-decoration-none">
                    @include('partials.brand-logo', ['style' => 'width: 34px; height: 34px;'])
                    <div class="d-flex flex-column text-start" style="line-height: 1.15;">
                        <span class="fw-bold" style="color: var(--on-surface); font-size: 0.95rem; letter-spacing: -0.01em;">
                            TOTAL BILLING
                        </span>
                        <span style="font-size: 0.7rem; color: var(--on-surface-variant); font-weight: 500;">
                            Financial Gateway & Invoicing
                        </span>
                    </div>
                </a>
            </div>
        @endif

        {{-- Right Controls --}}
        <div class="ms-auto d-flex align-items-center gap-2">
            {{-- SIMAK Link Badge --}}
            <div class="d-none d-sm-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill" style="background: var(--surface-container-high); border: 1px solid var(--outline-variant); font-size: 0.75rem;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background-color: var(--primary, #006a60);"></span>
                <span style="color: var(--on-surface-variant); font-weight: 600;">SIMAK :8000 LINKED</span>
            </div>

            {{-- Theme Toggle --}}
            <button class="btn btn-link link-body-emphasis m3-icon-btn me-1" id="themeToggle" title="Ganti Tema" aria-label="Ganti Tema">
                <i class="fas fa-moon" style="font-size: 0.95rem;" id="themeIcon"></i>
            </button>

            {{-- User Avatar Dropdown (Desktop >= 992px) - Figma M3 Style --}}
            @auth
            <div class="dropdown d-none d-lg-block ms-1">
                <button class="m3-user-avatar-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ Auth::user()->name }}">
                    <div class="m3-user-avatar-img">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="d-flex flex-column text-start me-1" style="line-height: 1.15;">
                        <span style="font-size: 0.8125rem; font-weight: 600; color: var(--on-surface); max-width: 130px;" class="text-truncate">{{ Auth::user()->name }}</span>
                        <span style="font-size: 0.6875rem; color: var(--on-surface-variant);">Staf Keuangan</span>
                    </div>
                    <i class="fas fa-chevron-down" style="font-size: 0.72rem; color: var(--on-surface-variant);"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-2" style="border-radius: var(--radius-md); min-width: 220px; background: var(--surface-container-low); border-color: var(--outline-variant) !important;">
                    <li class="px-3 py-2 border-bottom" style="border-color: var(--outline-variant) !important;">
                        <div class="fw-bold text-truncate" style="color: var(--on-surface);">{{ Auth::user()->name }}</div>
                        <small class="text-muted text-truncate d-block">{{ Auth::user()->email ?: 'Fintech Gateway Admin' }}</small>
                    </li>
                    <li><a class="dropdown-item py-2" href="{{ route('billing.dashboard') }}"><i class="fas fa-chart-line me-2 text-primary"></i>Overview Finansial</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('billing.simulator') }}"><i class="fas fa-building-columns me-2 text-success"></i>Simulator Bank</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('billing.invoices') }}"><i class="fas fa-receipt me-2 text-info"></i>Manajemen Tagihan</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('billing.webhook_logs') }}"><i class="fas fa-satellite-dish me-2 text-warning"></i>Audit Webhook</a></li>
                    <li><hr class="dropdown-divider" style="border-color: var(--outline-variant);"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger py-2">
                                <i class="fas fa-sign-out-alt me-2"></i>Keluar dari akun
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @else
                @if(!$isLoginPage)
                <a href="{{ route('login') }}" class="btn btn-sm btn-primary rounded-pill px-3 d-flex align-items-center gap-1.5 ms-1">
                    <i class="fas fa-sign-in-alt"></i> <span>Masuk</span>
                </a>
                @endif
            @endauth
        </div>
    </nav>

    {{-- App Container --}}
    <div class="app-container {{ $hideSidebar ? 'no-sidebar' : '' }}">
        @if(!$hideSidebar)
        {{-- Sidebar (Desktop Drawer / Mobile Bottom Sheet) --}}
        <div id="sidebar" class="sidebar">
            {{-- Mobile Bottom Sheet Grab Handle & Header (< 992px) --}}
            <div class="sheet-header-mobile d-lg-none sticky-top bg-body pt-2 pb-1 border-bottom" style="border-top-left-radius: var(--radius-xl); border-top-right-radius: var(--radius-xl); z-index: 10;">
                <div class="sheet-handle-area" id="sheetHandleArea" title="Geser ke atas untuk memperluas, geser ke bawah untuk menutup">
                    <div class="sheet-handle-bar"></div>
                </div>
                <div class="d-flex align-items-center justify-content-between px-3 pb-2 pt-1">
                    <a href="{{ route('billing.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                        @include('partials.brand-logo', ['style' => 'width: 32px; height: 32px;'])
                        <div class="d-flex flex-column text-start" style="line-height: 1.15;">
                            <span class="fw-bold text-body" style="font-size: 0.92rem; letter-spacing: -0.01em;">TOTAL BILLING</span>
                            <small class="text-secondary" style="font-size: 0.72rem; font-weight: 500;">Financial Gateway & Invoicing</small>
                        </div>
                    </a>
                    <button type="button" class="btn btn-sm btn-icon btn-light rounded-pill text-secondary" id="sidebarCloseBtn" aria-label="Tutup" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; padding: 0;">
                        <i class="fas fa-times" style="font-size: 0.9rem;"></i>
                    </button>
                </div>
            </div>

            {{-- Desktop Top Brand Header (>= 992px) --}}
            <div class="d-none d-lg-block sticky-top" style="position: sticky; top: 0; z-index: 10; background: var(--surface-container-low); padding-bottom: 4px;">
                <a href="{{ route('billing.dashboard') }}" class="m3-brand-header">
                    @include('partials.brand-logo')
                    <div class="m3-brand-text">
                        <span class="m3-brand-title">TOTAL BILLING</span>
                        <span class="m3-brand-subtitle">Financial Gateway & Invoicing</span>
                    </div>
                </a>
            </div>

            {{-- Mobile User Header (< 992px) --}}
            @auth
            <div class="sidebar-header d-flex align-items-center gap-3 d-lg-none px-3 py-2 border-bottom" style="border-color: var(--outline-variant) !important;">
                <div class="user-avatar rounded-circle d-flex align-items-center justify-content-center fw-bold text-white bg-primary overflow-hidden" style="width: 38px; height: 38px; flex-shrink: 0;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="d-flex flex-column text-truncate">
                    <span class="fw-bold text-body text-truncate" style="font-size: 0.9rem;">{{ Auth::user()->name }}</span>
                    <small class="text-secondary" style="font-size: 0.75rem;">Staf Keuangan</small>
                </div>
                <div class="ms-auto d-lg-none">
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 d-flex align-items-center gap-1.5" aria-label="Logout" title="Logout">
                            <i class="fas fa-sign-out-alt"></i> <span style="font-size: 0.78rem;" class="fw-semibold">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
            @endauth

            {{-- Navigation Menus --}}
            <div class="flex-grow-1 py-2">
                <div class="menu-group">
                    <div class="menu-header">
                        <i class="fas fa-landmark me-1"></i>
                        <span>Menu Finansial</span>
                    </div>
                    <div class="menu-items-grid">
                        <a href="{{ route('billing.dashboard') }}" class="menu-item {{ request()->routeIs('billing.dashboard') ? 'active' : '' }}">
                            <div class="menu-icon-box"><i class="fas fa-chart-line"></i></div>
                            <span class="menu-label-text">Overview Finansial</span>
                        </a>
                        <a href="{{ route('billing.simulator') }}" class="menu-item {{ request()->routeIs('billing.simulator') ? 'active' : '' }}">
                            <div class="menu-icon-box"><i class="fas fa-building-columns"></i></div>
                            <span class="menu-label-text">Simulator Gateway Bank</span>
                        </a>
                        <a href="{{ route('billing.invoices') }}" class="menu-item {{ request()->routeIs('billing.invoices') ? 'active' : '' }}">
                            <div class="menu-icon-box"><i class="fas fa-receipt"></i></div>
                            <span class="menu-label-text">Manajemen Tagihan</span>
                        </a>
                        <a href="{{ route('billing.webhook_logs') }}" class="menu-item {{ request()->routeIs('billing.webhook_logs') ? 'active' : '' }}">
                            <div class="menu-icon-box"><i class="fas fa-satellite-dish"></i></div>
                            <span class="menu-label-text">Audit Log Webhook</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Sidebar Footer (EAI Node & HMAC Info) --}}
            <div class="m3-sidebar-footer mt-auto pt-3 border-top" style="border-color: var(--outline-variant) !important;">
                <div class="m3-help-card">
                    <div class="m3-help-title">
                        <i class="fas fa-shield-halved text-primary"></i> EAI Billing Node
                    </div>
                    <div class="m3-help-sub">
                        Sinkronisasi dua arah via REST & HMAC SHA-256 Webhook
                    </div>
                    <a href="http://127.0.0.1:8000" target="_blank" rel="noopener noreferrer" class="m3-help-link">
                        Buka SIMAK Universe <i class="fas fa-arrow-up-right-from-square" style="font-size: 0.72rem;"></i>
                    </a>
                </div>
                <div class="d-flex align-items-center justify-content-between px-2 pt-2" style="font-size: 0.72rem; color: var(--on-surface-variant);">
                    <span>Billing Gateway v2.3</span>
                    <span class="badge rounded-pill" style="background: var(--primary-container); color: var(--on-primary-container); font-size: 0.68rem; font-weight: 600;">PORT 8001</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Main Content Canvas --}}
        <main class="main-content position-relative">
            <div class="main-background"></div>

            <div class="{{ $hideSidebar ? 'p-0' : 'container-fluid position-relative px-4 pb-4 pt-4' }}">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="m3-alert m3-alert-success mb-3 {{ $hideSidebar ? 'm-3' : '' }}">
                        <i class="fas fa-circle-check"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="m3-alert m3-alert-error mb-3 {{ $hideSidebar ? 'm-3' : '' }}">
                        <i class="fas fa-triangle-exclamation"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="m3-alert m3-alert-info mb-3 {{ $hideSidebar ? 'm-3' : '' }}">
                        <i class="fas fa-circle-info"></i>
                        <div>{{ session('info') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="m3-alert m3-alert-error mb-3 {{ $hideSidebar ? 'm-3' : '' }}">
                        <i class="fas fa-triangle-exclamation"></i>
                        <div>
                            <strong>Terjadi Kesalahan:</strong>
                            <ul style="margin: 4px 0 0 16px; padding: 0;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        @if(!$hideSidebar)
        {{-- Sidebar Mobile Overlay --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        @endif
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Application JS (Theme Toggle & M3 Sidebar / Mobile Bottom Sheet gestures) --}}
    <script src="{{ asset('js/app.js') }}?v=2.3"></script>

    @stack('scripts')
</body>
</html>