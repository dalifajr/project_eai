/**
 * SIMAK Universe — Application JavaScript
 * ========================================
 * Handles: theme toggle, sidebar (desktop & mobile bottom sheet), page loader, CSRF
 */

function initAll() {
    initThemeToggle();
    initSidebar();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
} else {
    initAll();
}

/* === THEME TOGGLE (Dark/Light) === */
function initThemeToggle() {
    const toggle = document.getElementById('themeToggle');
    const icon = document.getElementById('themeIcon');
    const html = document.documentElement;

    if (!toggle || !icon) return;

    // Load saved theme
    const saved = localStorage.getItem('simak-theme') || 'light';
    html.setAttribute('data-bs-theme', saved);
    html.setAttribute('data-theme', saved);
    html.style.colorScheme = saved;
    updateThemeIcon(icon, saved);

    toggle.addEventListener('click', () => {
        const current = html.getAttribute('data-bs-theme') || 'light';
        const next = current === 'dark' ? 'light' : 'dark';

        html.classList.add('theme-transitioning');
        html.setAttribute('data-bs-theme', next);
        html.setAttribute('data-theme', next);
        html.style.colorScheme = next;
        localStorage.setItem('simak-theme', next);

        updateThemeIcon(icon, next);
        setTimeout(() => html.classList.remove('theme-transitioning'), 300);
    });
}

function updateThemeIcon(icon, theme) {
    icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    icon.style.color = theme === 'dark' ? '#ffdf9e' : 'inherit';
}

/* === SIDEBAR & BOTTOM SHEET (Mobile Bottom Sheet + Interactive Realtime Drag Up/Down) === */
function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarCloseBtn');
    const handleArea = document.getElementById('sheetHandleArea');
    const headerMobile = document.querySelector('.sheet-header-mobile');
    const overlay = document.getElementById('sidebarOverlay');
    const container = document.querySelector('.app-container');

    if (!sidebar) return;

    // States: 'closed' | 'normal' | 'expanded'
    let sheetState = 'closed';

    const getNormalHeight = () => Math.round(window.innerHeight * 0.74);
    const getExpandedHeight = () => Math.round(window.innerHeight * 0.94);

    const applySheetState = (state, animate = true) => {
        sheetState = state;
        sidebar.classList.remove('is-dragging');

        if (animate) {
            sidebar.style.transition = 'transform 0.35s cubic-bezier(0.2, 0, 0, 1), height 0.35s cubic-bezier(0.2, 0, 0, 1)';
            if (overlay) overlay.style.transition = 'opacity 0.3s cubic-bezier(0.2, 0, 0, 1)';
        } else {
            sidebar.style.transition = 'none';
            if (overlay) overlay.style.transition = 'none';
        }

        if (state === 'closed') {
            sidebar.style.transform = 'translateY(105%)';
            sidebar.classList.remove('show');
            if (overlay) {
                overlay.style.opacity = '0';
                setTimeout(() => {
                    if (sheetState === 'closed') {
                        overlay.classList.remove('show');
                        overlay.style.opacity = '';
                    }
                }, 300);
            }
            document.body.classList.remove('sidebar-open');
        } else if (state === 'normal') {
            const h = getNormalHeight();
            sidebar.style.height = h + 'px';
            sidebar.style.maxHeight = h + 'px';
            sidebar.style.transform = 'translateY(0)';
            sidebar.classList.add('show');
            if (overlay) {
                overlay.classList.add('show');
                overlay.style.opacity = '1';
            }
            document.body.classList.add('sidebar-open');
        } else if (state === 'expanded') {
            const h = getExpandedHeight();
            sidebar.style.height = h + 'px';
            sidebar.style.maxHeight = h + 'px';
            sidebar.style.transform = 'translateY(0)';
            sidebar.classList.add('show');
            if (overlay) {
                overlay.classList.add('show');
                overlay.style.opacity = '1';
            }
            document.body.classList.add('sidebar-open');
        }
    };

    const closeMobileSheet = () => applySheetState('closed', true);
    const openMobileSheet = () => applySheetState('normal', true);

    const toggleMobileSheet = () => {
        if (sidebar.classList.contains('show') && sheetState !== 'closed') {
            closeMobileSheet();
        } else {
            openMobileSheet();
        }
    };

    if (toggleBtn) {
        toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (window.innerWidth >= 992) {
                if (container) {
                    container.classList.toggle('sidebar-collapsed');
                }
            } else {
                toggleMobileSheet();
            }
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            closeMobileSheet();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeMobileSheet);
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && sidebar.classList.contains('show') && window.innerWidth < 992) {
            closeMobileSheet();
        }
    });

    const menuLinks = sidebar.querySelectorAll('a.menu-item');
    menuLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                closeMobileSheet();
            }
        });
    });

    // Gestures
    let isDragging = false;
    let startY = 0;
    let startX = 0;
    let currentY = 0;
    let startTime = 0;
    let startHeight = 0;
    let isHorizontalGesture = false;

    const onDragStart = (clientY, clientX, target) => {
        if (window.innerWidth >= 992) return;
        if (!sidebar.classList.contains('show') || sheetState === 'closed') return;
        if (target && (target.closest('#sidebarCloseBtn') || target.closest('a'))) return;

        isDragging = true;
        isHorizontalGesture = false;
        startY = clientY;
        startX = clientX;
        currentY = clientY;
        startTime = performance.now();
        startHeight = sidebar.offsetHeight || (sheetState === 'expanded' ? getExpandedHeight() : getNormalHeight());

        sidebar.classList.add('is-dragging');
        sidebar.style.transition = 'none';
        if (overlay) overlay.style.transition = 'none';
    };

    const onDragMove = (clientY, clientX, e) => {
        if (!isDragging || window.innerWidth >= 992) return;
        const deltaX = clientX - startX;
        const deltaY = clientY - startY;

        if (!isHorizontalGesture && Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 10) {
            isHorizontalGesture = true;
            return;
        }
        if (isHorizontalGesture) return;

        if (e && e.cancelable) e.preventDefault();
        currentY = clientY;

        const normalH = getNormalHeight();
        const expandedH = getExpandedHeight();

        if (sheetState === 'normal') {
            if (deltaY < 0) {
                const pullUp = -deltaY;
                const targetH = startHeight + pullUp;
                if (targetH <= expandedH) {
                    sidebar.style.height = targetH + 'px';
                    sidebar.style.maxHeight = targetH + 'px';
                    sidebar.style.transform = 'translateY(0)';
                } else {
                    sidebar.style.height = expandedH + 'px';
                    sidebar.style.maxHeight = expandedH + 'px';
                }
                if (overlay) overlay.style.opacity = '1';
            } else {
                sidebar.style.height = startHeight + 'px';
                sidebar.style.maxHeight = startHeight + 'px';
                sidebar.style.transform = `translateY(${deltaY}px)`;
                const progress = Math.max(0, 1 - (deltaY / (startHeight * 0.75)));
                if (overlay) overlay.style.opacity = progress.toString();
            }
        } else {
            if (deltaY > 0) {
                const pullDown = deltaY;
                const targetH = startHeight - pullDown;
                if (targetH >= normalH) {
                    sidebar.style.height = targetH + 'px';
                    sidebar.style.maxHeight = targetH + 'px';
                    sidebar.style.transform = 'translateY(0)';
                    if (overlay) overlay.style.opacity = '1';
                } else {
                    sidebar.style.height = normalH + 'px';
                    sidebar.style.maxHeight = normalH + 'px';
                    const offsetDown = pullDown - (startHeight - normalH);
                    sidebar.style.transform = `translateY(${offsetDown}px)`;
                    const progress = Math.max(0, 1 - (offsetDown / (normalH * 0.75)));
                    if (overlay) overlay.style.opacity = progress.toString();
                }
            }
        }
    };

    const onDragEnd = () => {
        if (!isDragging || window.innerWidth >= 992) return;
        isDragging = false;
        sidebar.classList.remove('is-dragging');

        const deltaY = currentY - startY;
        const elapsed = Math.max(1, performance.now() - startTime);
        const velocity = deltaY / elapsed;
        const moveDist = Math.abs(deltaY);

        if (moveDist < 6) {
            if (sheetState === 'normal') applySheetState('expanded', true);
            else if (sheetState === 'expanded') applySheetState('normal', true);
            return;
        }

        if (sheetState === 'normal') {
            if (velocity < -0.32 || deltaY < -55) applySheetState('expanded', true);
            else if (velocity > 0.4 || deltaY > 90) applySheetState('closed', true);
            else applySheetState('normal', true);
        } else {
            if (velocity > 0.75 || deltaY > (startHeight * 0.55)) applySheetState('closed', true);
            else if (velocity > 0.3 || deltaY > 65) applySheetState('normal', true);
            else applySheetState('expanded', true);
        }
    };

    const targetAreas = [handleArea, headerMobile].filter(Boolean);
    targetAreas.forEach(area => {
        area.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1) onDragStart(e.touches[0].clientY, e.touches[0].clientX, e.target);
        }, { passive: true });
        area.addEventListener('mousedown', (e) => {
            if (e.button === 0) onDragStart(e.clientY, e.clientX, e.target);
        });
    });

    window.addEventListener('touchmove', (e) => {
        if (isDragging && e.touches.length === 1) onDragMove(e.touches[0].clientY, e.touches[0].clientX, e);
    }, { passive: false });
    window.addEventListener('touchend', onDragEnd, { passive: true });
    window.addEventListener('touchcancel', onDragEnd, { passive: true });
    window.addEventListener('mousemove', (e) => {
        if (isDragging) onDragMove(e.clientY, e.clientX, e);
    });
    window.addEventListener('mouseup', () => {
        if (isDragging) onDragEnd();
    });
}
