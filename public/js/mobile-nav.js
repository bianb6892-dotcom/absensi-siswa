// ============================================
// MOBILE NAV - HAMBURGER & BOTTOM NAV
// ============================================

function toggleMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlayMobile');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('active');
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlayMobile');
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
}

// ============================================
// DESKTOP SIDEBAR - COLLAPSE / EXPAND
// ============================================
function toggleNav() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('navOverlay');
    const mainContent = document.getElementById('mainContent');

    if (!sidebar || !overlay) return;

    if (window.innerWidth <= 768) return;

    const isHidden = sidebar.classList.toggle('hidden');
    overlay.classList.toggle('active', isHidden);

    if (mainContent) {
        mainContent.style.marginLeft = isHidden ? '0px' : '264px';
    }
}

// ============================================
// INIT - DESKTOP SIDEBAR TETAP TERBUKA
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    if (typeof applyResponsiveLayout === 'function') {
        applyResponsiveLayout();
        return;
    }

    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    if (sidebar && window.innerWidth > 768) {
        sidebar.classList.remove('hidden');
    }
    if (mainContent && window.innerWidth > 768) {
        mainContent.style.marginLeft = '264px';
    }
});