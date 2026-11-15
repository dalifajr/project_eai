<!DOCTYPE html>
<html lang="id" data-theme="light" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Financial Dashboard') | Total Billing System</title>
    
    <!-- Instant Anti-FOUC Theme Initializer -->
    <script>
        (function() {
            try {
                var savedTheme = localStorage.getItem('billing-theme') || 
                                (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', savedTheme);
                document.documentElement.setAttribute('data-bs-theme', savedTheme);
                document.documentElement.style.colorScheme = savedTheme;
            } catch (e) {}
        })();
    </script>

    <!-- Google Fonts Preconnect & M3 Fintech Core CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/m3-material-you.css') }}">
    
    @yield('styles')
</head>
<body>
    <!-- Live Core Status Bar -->
    <div class="billing-topbar">
        <div class="billing-topbar-inner">
            <div style="display: flex; align-items: center;">
                <span class="pulse-live"></span>
                <strong>EAI CORE ACTIVE:</strong>&nbsp; REST API Listener (Port 8001) &bull; Webhook Dispatcher Connected to SIMAK (Port 8000)
            </div>
            <div style="font-family: var(--font-mono); font-size: 11px;">
                Environment: LOCAL SANDBOX
            </div>
        </div>
    </div>

    <!-- M3 Top App Bar -->
    <header class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('billing.dashboard') }}" class="brand-link">
                <div class="brand-badge">B</div>
                <div>
                    <div class="brand-name">TOTAL BILLING SYSTEM</div>
                    <div class="brand-tag">Financial Core & Payment Gateway Service</div>
                </div>
            </a>

            <div style="display: flex; align-items: center; gap: 12px;">
                <!-- Theme Switch Button -->
                <button type="button" class="m3-theme-toggle" id="theme-toggle-btn" title="Ganti Tema (Terang/Gelap)">
                    <span id="theme-icon">🌙</span>
                </button>

                @auth
                <!-- User Profile Chip -->
                <div style="display: flex; align-items: center; gap: 10px; padding: 4px 12px 4px 6px; border-radius: var(--radius-pill); background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: var(--on-primary); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div style="display: flex; flex-direction: column; text-align: left; line-height: 1.2;">
                        <span style="font-size: 12.5px; font-weight: 700; color: var(--on-surface);">{{ Auth::user()->name }}</span>
                        <span style="font-size: 10.5px; color: var(--on-surface-variant);">Finance Admin</span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-fintech secondary sm" style="font-size: 12px;">
                        Keluar
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Page Canvas -->
    <main style="max-width: 1240px; margin: 0 auto; padding: 28px 24px 60px; width: 100%; flex: 1;">
        @yield('content')
    </main>

    <!-- M3 Floating Toast Notification Container (Tanpa Background Abu-abu) -->
    <div class="m3-toast-container" id="toast-container">
        @if(session('success'))
            <div class="m3-toast success">
                <div class="m3-toast-icon">✓</div>
                <div class="m3-toast-content">{{ session('success') }}</div>
                <button type="button" class="m3-toast-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @if(session('warning'))
            <div class="m3-toast warning">
                <div class="m3-toast-icon">!</div>
                <div class="m3-toast-content">{{ session('warning') }}</div>
                <button type="button" class="m3-toast-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @if(session('info'))
            <div class="m3-toast success">
                <div class="m3-toast-icon">ℹ</div>
                <div class="m3-toast-content">{{ session('info') }}</div>
                <button type="button" class="m3-toast-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="m3-toast error">
                <div class="m3-toast-icon">✕</div>
                <div class="m3-toast-content">
                    @if(session('error'))
                        <div>{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="m3-toast-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif
    </div>

    <!-- M3 Footer -->
    <footer class="footer-fintech">
        <div class="footer-fintech-inner">
            <p>&copy; 2026 Total Billing System. Financial Core & Payment Gateway (Material Design 3 Expressive).</p>
            <p style="font-family: var(--font-mono); font-size: 11.5px; color: var(--on-surface-variant);">Billing Core &bull; Enterprise Application Integration</p>
        </div>
    </footer>

    <!-- Theme Switcher & Toast Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleBtn = document.getElementById('theme-toggle-btn');
            var themeIcon = document.getElementById('theme-icon');

            function updateThemeUI(theme) {
                if (themeIcon) {
                    themeIcon.textContent = theme === 'dark' ? '☀️' : '🌙';
                }
            }

            var currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            updateThemeUI(currentTheme);

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    var nextTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', nextTheme);
                    document.documentElement.setAttribute('data-bs-theme', nextTheme);
                    document.documentElement.style.colorScheme = nextTheme;
                    localStorage.setItem('billing-theme', nextTheme);
                    updateThemeUI(nextTheme);
                });
            }

            // Auto dismiss toasts
            var toasts = document.querySelectorAll('.m3-toast');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(10px) scale(0.95)';
                    setTimeout(function() { toast.remove(); }, 300);
                }, 5000);
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
