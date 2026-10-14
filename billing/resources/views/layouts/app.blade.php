<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Billing Dashboard') | Total Billing System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0f766e;
            --primary-hover: #115e59;
            --primary-light: #f0fdfa;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --success: #15803d;
            --success-bg: #f0fdf4;
            --success-border: #bbf7d0;
            --warning: #b45309;
            --warning-bg: #fffbeb;
            --warning-border: #fde68a;
            --danger: #b91c1c;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;
            --radius-md: 8px;
            --radius-lg: 12px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-inner {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 20px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-md);
        }

        .brand-text h1 {
            font-size: 16px;
            font-weight: 700;
        }

        .brand-text p {
            font-size: 11px;
            color: var(--text-muted);
        }

        .main-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 24px 20px;
            width: 100%;
            flex: 1;
        }

        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-title {
            font-size: 17px;
            font-weight: 700;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 600;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-main);
        }

        .btn-outline:hover {
            background-color: #f1f5f9;
        }

        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background-color: var(--success-bg);
            border: 1px solid var(--success-border);
            color: var(--success);
        }

        .alert-danger {
            background-color: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: var(--danger);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-success { background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border); }
        .badge-danger { background: var(--danger-bg); color: var(--danger); border: 1px solid var(--danger-border); }
        .badge-warning { background: var(--warning-bg); color: var(--warning); border: 1px solid var(--warning-border); }
        .badge-secondary { background: #f1f5f9; color: var(--text-muted); border: 1px solid var(--border-color); }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }

        th {
            background-color: #f8fafc;
            color: var(--text-muted);
            font-weight: 600;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border-color);
        }

        .footer {
            background-color: var(--bg-card);
            border-top: 1px solid var(--border-color);
            padding: 16px 20px;
            font-size: 13px;
            color: var(--text-muted);
            text-align: center;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-inner">
            <a href="{{ route('billing.dashboard') }}" class="brand">
                <div class="brand-logo">B</div>
                <div class="brand-text">
                    <h1>TOTAL BILLING SYSTEM</h1>
                    <p>Financial Core & Payment Gateway EAI</p>
                </div>
            </a>

            <div style="display: flex; align-items: center; gap: 16px;">
                <span class="badge badge-success" style="padding: 6px 12px; font-size: 12px;">
                    EAI API Provider Active (Port 8001)
                </span>
                <a href="http://localhost:8000" target="_blank" class="btn btn-outline" style="min-height: 38px; font-size: 13px; padding: 6px 14px;">
                    Buka SIMAK (Port 8000) &rarr;
                </a>
            </div>
        </div>
    </header>

    <main class="main-container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-success" style="background:#f0f9ff; border-color:#bae6fd; color:#0369a1;">
                {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">
        <p>Total Billing System &copy; 2026. Enterprise Application Integration (EAI) Architecture.</p>
    </footer>

    @yield('scripts')
</body>
</html>
