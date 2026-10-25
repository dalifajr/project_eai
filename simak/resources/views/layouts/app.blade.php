<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Akademik') | SIMAK UNIVERSE</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #f8fafc;
            --bg-surface: #ffffff;
            --bg-surface-elevated: #ffffff;
            --bg-subtle: #f1f5f9;
            --border-subtle: #e2e8f0;
            --border-strong: #cbd5e1;
            
            --brand-primary: #1e40af;
            --brand-primary-hover: #1d4ed8;
            --brand-surface: #eff6ff;
            --brand-text: #1e3a8a;

            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --text-on-brand: #ffffff;

            --status-success-bg: #ecfdf5;
            --status-success-border: #a7f3d0;
            --status-success-text: #065f46;
            
            --status-warning-bg: #fffbeb;
            --status-warning-border: #fde68a;
            --status-warning-text: #92400e;

            --status-danger-bg: #fef2f2;
            --status-danger-border: #fecaca;
            --status-danger-text: #991b1b;

            --status-info-bg: #f0f9ff;
            --status-info-border: #bae6fd;
            --status-info-text: #075985;

            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 18px;
            --radius-pill: 9999px;

            --shadow-xs: 0 1px 2px 0 rgba(15, 23, 42, 0.05);
            --shadow-sm: 0 2px 4px 0 rgba(15, 23, 42, 0.06), 0 1px 2px -1px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 4px 8px -2px rgba(15, 23, 42, 0.08), 0 2px 4px -2px rgba(15, 23, 42, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(15, 23, 42, 0.08), 0 4px 6px -4px rgba(15, 23, 42, 0.04);

            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', ui-monospace, SFMono-Regular, monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-base);
            color: var(--text-body);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        /* Top Bar */
        .navbar {
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border-subtle);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-heading);
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 800;
            font-size: 15px;
            box-shadow: 0 2px 6px rgba(30, 58, 138, 0.25);
        }

        .brand-meta {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: var(--radius-sm);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .brand-subtitle {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 6px;
            background: var(--bg-subtle);
            padding: 4px;
            border-radius: var(--radius-pill);
            border: 1px solid var(--border-subtle);
        }

        .nav-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: var(--radius-pill);
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover {
            color: var(--brand-primary);
            background-color: var(--bg-surface);
        }

        .nav-item.active {
            background-color: var(--bg-surface);
            color: var(--brand-primary);
            box-shadow: var(--shadow-xs);
        }

        .user-nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-profile-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 10px 4px 6px;
            border-radius: var(--radius-pill);
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
        }

        .avatar-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--brand-primary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .user-meta-text {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1.2;
        }

        .user-display-name {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .user-role-label {
            font-size: 10px;
            color: var(--text-muted);
            text-transform: capitalize;
        }

        .btn-logout-minimal {
            background: transparent;
            border: 1px solid var(--border-subtle);
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-logout-minimal:hover {
            color: var(--status-danger-text);
            border-color: var(--status-danger-border);
            background-color: var(--status-danger-bg);
        }

        /* EAI Live Connection Indicator Ribbon */
        .eai-status-ribbon {
            background: #0f172a;
            color: #94a3b8;
            font-size: 11px;
            padding: 6px 24px;
            border-bottom: 1px solid #1e293b;
        }

        .eai-status-inner {
            max-width: 1240px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .eai-pulse-dot {
            width: 7px;
            height: 7px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            box-shadow: 0 0 6px #10b981;
        }

        /* Main Canvas Layout */
        .page-content {
            max-width: 1240px;
            margin: 0 auto;
            padding: 28px 24px 60px;
            width: 100%;
            flex: 1;
        }

        /* Modern Card System */
        .card-modern {
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 22px 24px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 22px;
            transition: border-color 0.15s ease;
        }

        .card-header-modern {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 16px;
            margin-bottom: 18px;
            border-bottom: 1px solid var(--border-subtle);
        }

        .card-title-modern {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-heading);
            letter-spacing: -0.01em;
        }

        .card-subtitle-modern {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 3px;
        }

        /* Alerts & Callouts */
        .banner-alert {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.5;
        }

        .banner-alert.success {
            background-color: var(--status-success-bg);
            border: 1px solid var(--status-success-border);
            color: var(--status-success-text);
        }

        .banner-alert.warning {
            background-color: var(--status-warning-bg);
            border: 1px solid var(--status-warning-border);
            color: var(--status-warning-text);
        }

        .banner-alert.danger {
            background-color: var(--status-danger-bg);
            border: 1px solid var(--status-danger-border);
            color: var(--status-danger-text);
        }

        /* Modern Badges */
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: var(--radius-pill);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.02em;
            line-height: 1.3;
        }

        .badge-pill.success {
            background-color: var(--status-success-bg);
            color: var(--status-success-text);
            border: 1px solid var(--status-success-border);
        }

        .badge-pill.danger {
            background-color: var(--status-danger-bg);
            color: var(--status-danger-text);
            border: 1px solid var(--status-danger-border);
        }

        .badge-pill.warning {
            background-color: var(--status-warning-bg);
            color: var(--status-warning-text);
            border: 1px solid var(--status-warning-border);
        }

        .badge-pill.neutral {
            background-color: var(--bg-subtle);
            color: var(--text-muted);
            border: 1px solid var(--border-subtle);
        }

        .badge-pill.brand {
            background-color: var(--brand-surface);
            color: var(--brand-primary);
            border: 1px solid #bfdbfe;
        }

        /* Buttons */
        .btn-modern {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 40px;
        }

        .btn-modern.primary {
            background-color: var(--brand-primary);
            color: #ffffff;
            box-shadow: 0 1px 2px rgba(30, 64, 175, 0.2);
        }

        .btn-modern.primary:hover {
            background-color: var(--brand-primary-hover);
        }

        .btn-modern.secondary {
            background-color: var(--bg-surface);
            border-color: var(--border-subtle);
            color: var(--text-heading);
        }

        .btn-modern.secondary:hover {
            background-color: var(--bg-subtle);
            border-color: var(--border-strong);
        }

        .btn-modern.danger {
            background-color: #dc2626;
            color: #ffffff;
        }

        .btn-modern.danger:hover {
            background-color: #b91c1c;
        }

        .btn-modern.sm {
            min-height: 32px;
            padding: 4px 12px;
            font-size: 12px;
        }

        /* Tables */
        .table-container-modern {
            overflow-x: auto;
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            background: var(--bg-surface);
        }

        .table-modern {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
            font-feature-settings: "tnum";
        }

        .table-modern th {
            background-color: #f8fafc;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-subtle);
        }

        .table-modern td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-body);
        }

        .table-modern tr:last-child td {
            border-bottom: none;
        }

        .table-modern tr:hover td {
            background-color: #fbfcfe;
        }

        /* Code snippets */
        code {
            font-family: var(--font-mono);
            font-size: 12px;
            background: var(--bg-subtle);
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid var(--border-subtle);
            color: var(--text-heading);
        }

        /* Footer */
        .site-footer {
            background-color: var(--bg-surface);
            border-top: 1px solid var(--border-subtle);
            padding: 20px 24px;
            font-size: 12px;
            color: var(--text-muted);
            margin-top: auto;
        }

        .site-footer-inner {
            max-width: 1240px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
    @yield('styles')
</head>
<body>
    @auth
    <!-- Ribbon Status Integrasi Enterprise -->
    <div class="eai-status-ribbon">
        <div class="eai-status-inner">
            <div style="display: flex; align-items: center;">
                <span class="eai-pulse-dot"></span>
                <span>EAI Service Mesh: <strong>REST API & Webhook Active</strong> (Port 8000 &harr; 8001)</span>
            </div>
            <div>
                <span>Target Host: <code>127.0.0.1:8001</code> (Total Billing System)</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="navbar">
        <div class="navbar-container">
            <a href="{{ route('dashboard') }}" class="navbar-brand">
                <div class="brand-icon">S</div>
                <div class="brand-meta">
                    <div class="brand-title">
                        SIMAK UNIVERSE
                        <span class="brand-badge">EAI v2.0</span>
                    </div>
                    <div class="brand-subtitle">Portal Informasi Akademik Terintegrasi</div>
                </div>
            </a>

            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                @if(Auth::user()->role === 'mahasiswa')
                <a href="{{ route('krs.index') }}" class="nav-item {{ request()->routeIs('krs.*') ? 'active' : '' }}">
                    Pengisian KRS
                </a>
                <a href="{{ route('wisuda.index') }}" class="nav-item {{ request()->routeIs('wisuda.*') ? 'active' : '' }}">
                    Pendaftaran Wisuda
                </a>
                @endif
            </nav>

            <div class="user-nav-actions">
                <div class="user-profile-chip">
                    <div class="avatar-circle">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="user-meta-text">
                        <span class="user-display-name">{{ Auth::user()->name }}</span>
                        <span class="user-role-label">{{ Auth::user()->role }} ({{ Auth::user()->username }})</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout-minimal" title="Keluar dari sesi ini">Keluar</button>
                </form>
            </div>
        </div>
    </header>
    @endauth

    <main class="page-content">
        @if(session('success'))
            <div class="banner-alert success">
                <span style="font-size: 16px;">✓</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="banner-alert danger">
                <span style="font-size: 16px;">✕</span>
                <div>
                    @foreach($errors->all() as $error)
                        <p>{!! $error !!}</p>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="site-footer-inner">
            <p>Arsitektur Enterprise Application Integration (EAI) &copy; 2026. Portal Akademik SIMAK.</p>
            <p style="font-family: var(--font-mono); font-size: 11px;">Environment: Local Sandbox</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
