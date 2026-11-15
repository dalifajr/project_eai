<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Total Billing System') — Gateway Pembayaran & Invoicing EAI</title>

    {{-- Google Sans Font & FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- M3 Expressive Fintech / Flat Material You Design System --}}
    <link rel="stylesheet" href="{{ asset('css/m3-material-you.css') }}?v=2.1">

    @stack('styles')
</head>
<body>
    <div class="app-shell {{ !Auth::check() ? 'no-sidebar' : '' }}">
        {{-- Mobile Backdrop --}}
        <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

        {{-- === SIDEBAR (264PX FIGMA NAVIGATION DRAWER) === --}}
        <aside class="sidebar" id="sidebar">
            {{-- Top Brand Header --}}
            <a href="{{ route('billing.dashboard') }}" class="m3-brand-header">
                @include('partials.brand-logo')
                <div class="m3-brand-text">
                    <span class="m3-brand-title">TOTAL BILLING</span>
                    <span class="m3-brand-subtitle">Financial Gateway & Invoicing</span>
                </div>
            </a>

            {{-- Navigation Menus --}}
            <div style="flex: 1; overflow-y: auto; padding: 10px 0;">
                <div class="menu-group">
                    <div class="menu-header">
                        <i class="fas fa-landmark"></i>
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
                            <div class="menu-icon-box"><i class="fas fa-tower-broadcast"></i></div>
                            <span class="menu-label-text">Audit Log Webhook</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Sidebar Footer Info --}}
            <div class="sidebar-footer-card">
                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem;">
                    <span style="font-weight: 600; color: var(--on-surface);">EAI Billing Node</span>
                    <span class="badge-m3 badge-m3-primary" style="font-size: 0.65rem;">PORT 8001</span>
                </div>
                <div style="font-size: 0.7rem; color: var(--on-surface-variant); display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-shield-halved" style="color: var(--primary);"></i>
                    <span>Webhook HMAC SHA-256 Ready</span>
                </div>
            </div>
        </aside>

        {{-- === MAIN WRAPPER === --}}
        <div class="main-wrapper">
            {{-- Top App Bar --}}
            <header class="top-app-bar">
                <div style="display: flex; align-items: center; gap: 14px;">
                    {{-- Hamburger for mobile --}}
                    <button type="button" class="btn-m3-icon d-lg-none" onclick="toggleSidebar()" aria-label="Toggle Sidebar" style="border: 1px solid var(--outline-variant); background: var(--surface-container);">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div style="display: flex; flex-direction: column;">
                        <span style="font-family: var(--font-display); font-size: 1.05rem; font-weight: 700; color: var(--on-surface); line-height: 1.2;">
                            @yield('page_title', 'Dashboard Finansial')
                        </span>
                        <span style="font-size: 0.75rem; color: var(--on-surface-variant);">
                            @yield('page_subtitle', 'Total Billing System — Payment Gateway & Invoicing')
                        </span>
                    </div>
                </div>

                {{-- Right Controls --}}
                <div style="display: flex; align-items: center; gap: 12px;">
                    {{-- Status Badge --}}
                    <div class="d-none d-md-flex align-items-center gap-2" style="background: var(--surface-container); padding: 4px 12px; border-radius: var(--radius-pill); font-size: 0.75rem; border: 1px solid var(--outline-variant);">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #146c2e; display: inline-block;"></span>
                        <span style="font-weight: 600; color: var(--on-surface);">SIMAK Port 8000 Linked</span>
                    </div>

                    {{-- Theme Toggle --}}
                    <button type="button" class="btn-m3-icon" id="themeToggleBtn" title="Toggle Tema (Light / Dark)">
                        <i class="fas fa-moon" id="themeIcon"></i>
                    </button>

                    @auth
                        {{-- User Chip --}}
                        <div style="display: flex; align-items: center; gap: 10px; background: var(--surface-container-high); padding: 4px 12px 4px 6px; border-radius: var(--radius-pill); border: 1px solid var(--outline-variant);">
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--primary); color: var(--on-primary); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div style="display: flex; flex-direction: column; line-height: 1.1;">
                                <span style="font-size: 0.8125rem; font-weight: 600; color: var(--on-surface); max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ Auth::user()->name }}
                                </span>
                                <span style="font-size: 0.6875rem; color: var(--on-surface-variant);">
                                    Staf Keuangan
                                </span>
                            </div>
                        </div>

                        {{-- Logout --}}
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn-m3 btn-m3-outlined" style="height: 36px; padding: 0 14px; font-size: 0.8125rem; border-color: var(--outline-variant);" title="Keluar">
                                <i class="fas fa-right-from-bracket"></i>
                                <span class="d-none d-sm-inline">Keluar</span>
                            </button>
                        </form>
                    @endauth
                </div>
            </header>

            {{-- Main Content Canvas --}}
            <main class="main-content">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="m3-alert m3-alert-success" style="margin-bottom: 20px;">
                        <i class="fas fa-circle-check" style="font-size: 1.1rem;"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="m3-alert m3-alert-error" style="margin-bottom: 20px;">
                        <i class="fas fa-triangle-exclamation" style="font-size: 1.1rem;"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if(session('info'))
                    <div class="m3-alert m3-alert-info" style="margin-bottom: 20px;">
                        <i class="fas fa-circle-info" style="font-size: 1.1rem;"></i>
                        <div>{{ session('info') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="m3-alert m3-alert-error" style="margin-bottom: 20px;">
                        <i class="fas fa-triangle-exclamation" style="font-size: 1.1rem;"></i>
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
            </main>
        </div>
    </div>

    {{-- Interactive Scripts --}}
    <script>
        // Sidebar Toggle for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar && backdrop) {
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
            }
        }

        // Theme Switcher (Light / Dark)
        (function() {
            const savedTheme = localStorage.getItem('billing_theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);

            const toggleBtn = document.getElementById('themeToggleBtn');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                    document.documentElement.setAttribute('data-theme', newTheme);
                    localStorage.setItem('billing_theme', newTheme);
                    updateThemeIcon(newTheme);
                });
            }

            function updateThemeIcon(theme) {
                const icon = document.getElementById('themeIcon');
                if (icon) {
                    if (theme === 'dark') {
                        icon.className = 'fas fa-sun';
                        icon.style.color = '#70f7e5';
                    } else {
                        icon.className = 'fas fa-moon';
                        icon.style.color = 'inherit';
                    }
                }
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
