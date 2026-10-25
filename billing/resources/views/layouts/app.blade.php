<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Financial Dashboard') | Total Billing System</title>
    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #f8fafc;
            --bg-surface: #ffffff;
            --bg-subtle: #f1f5f9;
            --border-subtle: #e2e8f0;
            --border-strong: #cbd5e1;

            --brand-fintech: #0f766e;
            --brand-fintech-hover: #115e59;
            --brand-fintech-surface: #f0fdfa;
            --brand-fintech-border: #99f6e4;
            --brand-accent: #059669;

            --text-heading: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;

            --status-success-bg: #ecfdf5;
            --status-success-border: #a7f3d0;
            --status-success-text: #065f46;

            --status-danger-bg: #fef2f2;
            --status-danger-border: #fecaca;
            --status-danger-text: #991b1b;

            --status-warning-bg: #fffbeb;
            --status-warning-border: #fde68a;
            --status-warning-text: #92400e;

            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-pill: 9999px;

            --shadow-xs: 0 1px 2px 0 rgba(15, 23, 42, 0.05);
            --shadow-sm: 0 2px 4px 0 rgba(15, 23, 42, 0.06), 0 1px 2px -1px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 4px 8px -2px rgba(15, 23, 42, 0.08);

            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', ui-monospace, monospace;
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

        /* Top Bar Fintech */
        .billing-topbar {
            background: #0f172a;
            color: #94a3b8;
            font-size: 11px;
            padding: 6px 24px;
            border-bottom: 1px solid #1e293b;
        }

        .billing-topbar-inner {
            max-width: 1240px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pulse-live {
            width: 7px;
            height: 7px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            box-shadow: 0 0 6px #10b981;
        }

        .navbar {
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border-subtle);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-inner {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-heading);
        }

        .brand-badge-box {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 800;
            font-size: 15px;
            box-shadow: 0 2px 6px rgba(15, 118, 110, 0.25);
        }

        .brand-text-block {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand-tag {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            background: var(--brand-fintech-surface);
            color: var(--brand-fintech);
            border-radius: var(--radius-sm);
            text-transform: uppercase;
        }

        .brand-subtitle {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Container */
        .page-canvas {
            max-width: 1240px;
            margin: 0 auto;
            padding: 28px 24px 60px;
            width: 100%;
            flex: 1;
        }

        /* Card Modern */
        .card-fintech {
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 22px 24px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 22px;
        }

        .card-header-fintech {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 16px;
            margin-bottom: 18px;
            border-bottom: 1px solid var(--border-subtle);
        }

        .card-title-fintech {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.01em;
        }

        .card-subtitle-fintech {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 3px;
        }

        /* Modern Buttons */
        .btn-fintech {
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
            transition: all 0.15s ease;
            min-height: 40px;
        }

        .btn-fintech.primary {
            background-color: var(--brand-fintech);
            color: #ffffff;
            box-shadow: 0 1px 2px rgba(15, 118, 110, 0.2);
        }

        .btn-fintech.primary:hover {
            background-color: var(--brand-fintech-hover);
        }

        .btn-fintech.secondary {
            background-color: var(--bg-surface);
            border-color: var(--border-subtle);
            color: var(--text-heading);
        }

        .btn-fintech.secondary:hover {
            background-color: var(--bg-subtle);
            border-color: var(--border-strong);
        }

        .btn-fintech.sm {
            min-height: 32px;
            padding: 4px 12px;
            font-size: 12px;
        }

        /* Badges */
        .pill-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: var(--radius-pill);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .pill-badge.success {
            background: var(--status-success-bg);
            color: var(--status-success-text);
            border: 1px solid var(--status-success-border);
        }

        .pill-badge.danger {
            background: var(--status-danger-bg);
            color: var(--status-danger-text);
            border: 1px solid var(--status-danger-border);
        }

        .pill-badge.warning {
            background: var(--status-warning-bg);
            color: var(--status-warning-text);
            border: 1px solid var(--status-warning-border);
        }

        .pill-badge.neutral {
            background: var(--bg-subtle);
            color: var(--text-muted);
            border: 1px solid var(--border-subtle);
        }

        /* Table */
        .table-responsive-fintech {
            overflow-x: auto;
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-md);
            background: var(--bg-surface);
        }

        .table-fintech {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
            font-feature-settings: "tnum";
        }

        .table-fintech th {
            background-color: #f8fafc;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-subtle);
        }

        .table-fintech td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-body);
        }

        .table-fintech tr:last-child td {
            border-bottom: none;
        }

        .table-fintech tr:hover td {
            background-color: #f0fdfa;
        }

        code {
            font-family: var(--font-mono);
            font-size: 12px;
            background: var(--bg-subtle);
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid var(--border-subtle);
            color: var(--text-heading);
        }

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
    <!-- Live Core Pulse Bar -->
    <div class="billing-topbar">
        <div class="billing-topbar-inner">
            <div style="display: flex; align-items: center;">
                <span class="pulse-live"></span>
                <span>Financial Engine: <strong>REST API & Webhook Service Active</strong> (Listening on Port 8001)</span>
            </div>
            <div>
                <span>Database: <code>db_billing</code> (MySQL 3306)</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('billing.dashboard') }}" class="brand-link">
                <div class="brand-badge-box">B</div>
                <div class="brand-text-block">
                    <div class="brand-title">
                        TOTAL BILLING SYSTEM
                        <span class="brand-tag">EAI Core</span>
                    </div>
                    <div class="brand-subtitle">Financial Core & Payment Gateway Gateway Service</div>
                </div>
            </a>

            <div style="display: flex; align-items: center; gap: 12px;">
                <span class="pill-badge success" style="padding: 6px 12px;">
                    EAI API Provider Ready
                </span>
                <a href="http://localhost:8000" target="_blank" class="btn-fintech secondary sm">
                    Buka Portal SIMAK (Port 8000) &rarr;
                </a>
            </div>
        </div>
    </header>

    <main class="page-canvas">
        @if(session('success'))
            <div style="display: flex; align-items: flex-start; gap: 12px; background: var(--status-success-bg); border: 1px solid var(--status-success-border); color: var(--status-success-text); padding: 14px 18px; border-radius: var(--radius-md); margin-bottom: 20px; font-size: 13px;">
                <span style="font-size: 16px;">✓</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if(session('info'))
            <div style="display: flex; align-items: flex-start; gap: 12px; background: #f0f9ff; border: 1px solid #bae6fd; color: #0369a1; padding: 14px 18px; border-radius: var(--radius-md); margin-bottom: 20px; font-size: 13px;">
                <span style="font-size: 16px;">ℹ</span>
                <div>{{ session('info') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div style="display: flex; align-items: flex-start; gap: 12px; background: var(--status-danger-bg); border: 1px solid var(--status-danger-border); color: var(--status-danger-text); padding: 14px 18px; border-radius: var(--radius-md); margin-bottom: 20px; font-size: 13px;">
                <span style="font-size: 16px;">✕</span>
                <div>
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="site-footer-inner">
            <p>Total Billing System &copy; 2026. Arsitektur Enterprise Application Integration (EAI).</p>
            <p style="font-family: var(--font-mono); font-size: 11px;">EAI Provider Port: 8001</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
