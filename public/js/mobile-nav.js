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
// INIT - DESKTOP SIDEBAR TETAP TERBUKA
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    if (window.innerWidth > 768) {
        sidebar.style.display = 'flex';
        sidebar.style.transform = 'translateX(0)';
        mainContent.style.marginLeft = '280px';
    }
});