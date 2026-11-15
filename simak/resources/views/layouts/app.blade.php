<!DOCTYPE html>
<html lang="id" data-theme="light" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Akademik') | SIMAK UNIVERSE</title>
    
    <!-- Instant Anti-FOUC Theme Initializer -->
    <script>
        (function() {
            try {
                var savedTheme = localStorage.getItem('simak-theme') || 
                                (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', savedTheme);
                document.documentElement.setAttribute('data-bs-theme', savedTheme);
                document.documentElement.style.colorScheme = savedTheme;
            } catch (e) {}
        })();
    </script>

    <!-- Google Fonts Preconnect & M3 Material You Core CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/m3-material-you.css') }}">
    
    @yield('styles')
</head>
<body>
    @auth
    <!-- M3 Top App Bar -->
    <header class="navbar">
        <div class="navbar-container">
            <a href="{{ route('dashboard') }}" class="navbar-brand">
                <div class="brand-icon">S</div>
                <div class="brand-meta">
                    <div class="brand-title">
                        SIMAK UNIVERSE
                        <span class="m3-badge-pill primary" style="font-size: 10px; padding: 2px 8px;">EAI v2.0</span>
                    </div>
                    <div class="brand-subtitle">Portal Informasi Akademik Terintegrasi</div>
                </div>
            </a>

            <!-- Navigation Pills -->
            <nav class="nav-links">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Dashboard
                </a>
                @if(Auth::user()->role === 'mahasiswa')
                <a href="{{ route('krs.index') }}" class="nav-item {{ request()->routeIs('krs.*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    Pengisian KRS
                </a>
                <a href="{{ route('wisuda.index') }}" class="nav-item {{ request()->routeIs('wisuda.*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    Pendaftaran Wisuda
                </a>
                @endif
            </nav>

            <!-- Actions: Dark Mode Switcher & User Profile -->
            <div style="display: flex; align-items: center; gap: 12px;">
                <!-- M3 Theme Switch Button -->
                <button type="button" class="m3-theme-toggle" id="theme-toggle-btn" title="Ganti Tema (Terang/Gelap)">
                    <span id="theme-icon">🌙</span>
                </button>

                <!-- User Profile Chip -->
                <div style="display: flex; align-items: center; gap: 10px; padding: 4px 12px 4px 6px; border-radius: var(--radius-pill); background: var(--surface-container-low); border: 1px solid var(--outline-variant);">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: var(--on-primary); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div style="display: flex; flex-direction: column; text-align: left; line-height: 1.2;">
                        <span style="font-size: 12.5px; font-weight: 700; color: var(--on-surface);">{{ Auth::user()->name }}</span>
                        <span style="font-size: 10.5px; color: var(--on-surface-variant); text-transform: capitalize;">{{ Auth::user()->role }} ({{ Auth::user()->username }})</span>
                    </div>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-modern secondary sm" title="Keluar dari sesi ini">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>
    @endauth

    <!-- Main Page Content -->
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

        @if(session('error') || $errors->any())
            <div class="m3-toast error">
                <div class="m3-toast-icon">✕</div>
                <div class="m3-toast-content">
                    @if(session('error'))
                        <div>{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div>{!! $error !!}</div>
                        @endforeach
                    @endif
                </div>
                <button type="button" class="m3-toast-close" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif
    </div>

    <!-- M3 Footer -->
    <footer class="footer">
        <div class="footer-container">
            <p>&copy; 2026 SIMAK UNIVERSE. Sistem Informasi Akademik Terpadu (Material Design 3 Expressive).</p>
            <p style="font-family: var(--font-mono); font-size: 11.5px; color: var(--on-surface-variant);">SIMAK Portal &bull; Enterprise Application Integration</p>
        </div>
    </footer>

    <!-- Theme Switcher & Toast Auto-dismiss Scripts -->
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
                    localStorage.setItem('simak-theme', nextTheme);
                    updateThemeUI(nextTheme);
                });
            }

            // Auto dismiss toasts after 5 seconds
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
