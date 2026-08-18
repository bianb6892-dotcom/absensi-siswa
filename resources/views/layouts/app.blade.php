<!doctype html>
<html lang="id" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Absensi Siswa')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Mobile Nav CSS -->
    <link rel="stylesheet" href="{{ asset('css/mobile-nav.css') }}">

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Custom Navbar CSS -->
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">

    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#e8f0fe',
                            100: '#c5ddfc',
                            200: '#a3c9fa',
                            300: '#80b5f8',
                            400: '#5ea1f6',
                            500: '#47A5FF',
                            600: '#2b7fd9',
                            700: '#1f5fb3',
                            800: '#14408c',
                            900: '#0a2066',
                        },
                        orange: {
                            500: '#FF8F00',
                            600: '#e68000',
                            700: '#cc7200',
                            800: '#b26400',
                            900: '#995500',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Light Mode - Default */
        body {
            background: linear-gradient(135deg, #f0f7ff 0%, #e8f0fe 100%);
            color: #1a2332;
        }

        /* Light Mode - Elemen biru muda */
        .bg-white {
            background-color: #ffffff !important;
        }

        .card-gradient-light {
            background: linear-gradient(145deg, #ffffff 0%, #f5faff 100%) !important;
            border: 1px solid #d6e8ff !important;
            box-shadow: 0 2px 16px rgba(71, 165, 255, 0.10) !important;
            border-radius: 12px;
        }

        .card-gradient-light:hover {
            box-shadow: 0 6px 28px rgba(71, 165, 255, 0.18) !important;
            border-color: #b8d9ff !important;
        }

        /* Light Mode - Background card dalam */
        .bg-gray-50 {
            background-color: #f5faff !important;
        }

        .bg-gray-100 {
            background-color: #edf5ff !important;
        }

        .bg-gray-200 {
            background-color: #dce8f5 !important;
        }

        /* Light Mode - Text Colors - Jelas dan Kontras */
        .text-gray-900 {
            color: #0a1a2b !important;
        }

        .text-gray-800 {
            color: #1a2d44 !important;
        }

        .text-gray-700 {
            color: #2a4058 !important;
        }

        .text-gray-600 {
            color: #3a5570 !important;
        }

        .text-gray-500 {
            color: #4a6a88 !important;
        }

        .text-gray-400 {
            color: #6a8aa8 !important;
        }

        .text-gray-300 {
            color: #8aaac8 !important;
        }

        /* Light Mode - Border */
        .border-gray-200 {
            border-color: #d6e8ff !important;
        }

        .border-gray-300 {
            border-color: #c0d8f0 !important;
        }

        .border-gray-100 {
            border-color: #e8f2ff !important;
        }

        /* ============================================ */
        /* DARK MODE - FULL UPDATE */
        /* ============================================ */
        .dark body {
            background: linear-gradient(135deg, #0a0a0a 0%, #141414 50%, #1a1a1a 100%);
            color: #e8e8e8;
        }

        .dark .bg-white {
            background: linear-gradient(145deg, #1a1a1a 0%, #222222 100%) !important;
        }

        .dark .card-gradient-light {
            background: linear-gradient(145deg, #1a1a1a 0%, #262626 100%) !important;
            border: 1px solid #3d3d3d !important;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.5) !important;
        }

        .dark .card-gradient-light:hover {
            box-shadow: 0 6px 28px rgba(255, 143, 0, 0.06) !important;
            border-color: #4d4d4d !important;
        }

        .dark .bg-gray-50 {
            background: #1a1a1a !important;
        }

        .dark .bg-gray-100 {
            background: #262626 !important;
        }

        .dark .bg-gray-200 {
            background: #333333 !important;
        }

        .dark .bg-gray-300 {
            background: #404040 !important;
        }

        /* Dark Mode - Text Colors - Jelas dan Kontras */
        .dark .text-gray-900 {
            color: #f0f0f0 !important;
        }

        .dark .text-gray-800 {
            color: #e0e0e0 !important;
        }

        .dark .text-gray-700 {
            color: #d0d0d0 !important;
        }

        .dark .text-gray-600 {
            color: #bfbfbf !important;
        }

        .dark .text-gray-500 {
            color: #a0a0a0 !important;
        }

        .dark .text-gray-400 {
            color: #888888 !important;
        }

        .dark .text-gray-300 {
            color: #707070 !important;
        }

        /* Dark Mode - Border Colors */
        .dark .border-gray-200 {
            border-color: #3d3d3d !important;
        }

        .dark .border-gray-300 {
            border-color: #4a4a4a !important;
        }

        .dark .border-gray-100 {
            border-color: #2a2a2a !important;
        }

        .dark .divide-gray-200>*+* {
            border-color: #3d3d3d !important;
        }

        /* Dark Mode - Input */
        .dark input,
        .dark select,
        .dark textarea {
            background: #262626 !important;
            color: #e8e8e8 !important;
            border-color: #4a4a4a !important;
        }

        .dark input:focus,
        .dark select:focus,
        .dark textarea:focus {
            border-color: #FF8F00 !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(255, 143, 0, 0.15) !important;
        }

        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #888888 !important;
        }

        /* Dark Mode - Table */
        .dark table thead tr {
            background: #2a2a2a !important;
        }

        .dark table thead tr th {
            color: #e8e8e8 !important;
            font-weight: 700 !important;
            border-color: #3d3d3d !important;
        }

        .dark table tbody tr {
            border-color: #3d3d3d !important;
        }

        .dark table tbody tr:hover {
            background: rgba(255, 255, 255, 0.03) !important;
        }

        .dark table tbody td {
            color: #d0d0d0 !important;
            border-color: #3d3d3d !important;
        }

        /* Dark Mode - Badge Status */
        .dark .status-hadir {
            background: rgba(16, 185, 129, 0.2) !important;
            color: #6ee7b7 !important;
            border-color: rgba(16, 185, 129, 0.3) !important;
        }

        .dark .status-izin {
            background: rgba(245, 158, 11, 0.2) !important;
            color: #fcd34d !important;
            border-color: rgba(245, 158, 11, 0.3) !important;
        }

        .dark .status-sakit {
            background: rgba(59, 130, 246, 0.2) !important;
            color: #93c5fd !important;
            border-color: rgba(59, 130, 246, 0.3) !important;
        }

        .dark .status-alpa {
            background: rgba(244, 63, 94, 0.2) !important;
            color: #fca5a5 !important;
            border-color: rgba(244, 63, 94, 0.3) !important;
        }

        .dark .status-tidak_masuk {
            background: rgba(244, 63, 94, 0.2) !important;
            color: #fca5a5 !important;
            border-color: rgba(244, 63, 94, 0.3) !important;
        }

        /* Dark Mode - Alerts */
        .dark .bg-emerald-50 {
            background: rgba(16, 185, 129, 0.1) !important;
            border-color: rgba(16, 185, 129, 0.2) !important;
        }

        .dark .bg-rose-50 {
            background: rgba(244, 63, 94, 0.1) !important;
            border-color: rgba(244, 63, 94, 0.2) !important;
        }

        .dark .bg-amber-50 {
            background: rgba(245, 158, 11, 0.1) !important;
            border-color: rgba(245, 158, 11, 0.2) !important;
        }

        .dark .text-emerald-700 {
            color: #6ee7b7 !important;
        }

        .dark .text-rose-700 {
            color: #fca5a5 !important;
        }

        .dark .text-amber-700 {
            color: #fcd34d !important;
        }

        .dark .border-emerald-200 {
            border-color: rgba(16, 185, 129, 0.2) !important;
        }

        .dark .border-rose-200 {
            border-color: rgba(244, 63, 94, 0.2) !important;
        }

        .dark .border-amber-200 {
            border-color: rgba(245, 158, 11, 0.2) !important;
        }

        /* Dark Mode - Buttons */
        .dark .bg-primary-500 {
            background: #FF8F00 !important;
        }

        .dark .bg-primary-500:hover {
            background: #e68000 !important;
        }

        .dark .text-primary-500 {
            color: #FF8F00 !important;
        }

        .dark .text-primary-600 {
            color: #e68000 !important;
        }

        .dark .border-primary-500 {
            border-color: #FF8F00 !important;
        }

        .dark .hover\:bg-primary-600:hover {
            background: #e68000 !important;
        }

        /* Dark Mode - Summary Stats Cards */
        .dark .border-emerald-100 {
            border-color: rgba(16, 185, 129, 0.15) !important;
        }

        .dark .border-amber-100 {
            border-color: rgba(245, 158, 11, 0.15) !important;
        }

        .dark .border-blue-100 {
            border-color: rgba(59, 130, 246, 0.15) !important;
        }

        .dark .border-rose-100 {
            border-color: rgba(244, 63, 94, 0.15) !important;
        }

        .dark .bg-emerald-100 {
            background: rgba(16, 185, 129, 0.15) !important;
        }

        .dark .bg-amber-100 {
            background: rgba(245, 158, 11, 0.15) !important;
        }

        .dark .bg-blue-100 {
            background: rgba(59, 130, 246, 0.15) !important;
        }

        .dark .bg-rose-100 {
            background: rgba(244, 63, 94, 0.15) !important;
        }

        .dark .text-emerald-600 {
            color: #6ee7b7 !important;
        }

        .dark .text-amber-600 {
            color: #fcd34d !important;
        }

        .dark .text-blue-600 {
            color: #93c5fd !important;
        }

        .dark .text-rose-600 {
            color: #fca5a5 !important;
        }

        /* Dark Mode - Navbar Sidebar */
        .dark .nav-sidebar {
            background: linear-gradient(180deg, #0a0a0a 0%, #141414 50%, #1a1a1a 100%) !important;
            border-right: 1px solid #2a2a2a !important;
        }

        .dark .nav-sidebar .nav-item .label {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .dark .nav-sidebar .nav-item:hover .label {
            color: #ffffff !important;
        }

        .dark .nav-sidebar .nav-item.active .label {
            color: #FF8F00 !important;
        }

        .dark .nav-sidebar .nav-item.active {
            background: rgba(255, 143, 0, 0.15) !important;
            box-shadow: inset 0 0 0 1px rgba(255, 143, 0, 0.2) !important;
        }

        .dark .nav-sidebar .nav-item.active::before {
            background: #FF8F00 !important;
        }

        .dark .nav-sidebar .brand span {
            color: rgba(255, 255, 255, 0.4) !important;
        }

        .dark .nav-sidebar .nav-divider {
            background: rgba(255, 255, 255, 0.06) !important;
        }

        .dark .nav-sidebar .nav-footer {
            border-color: rgba(255, 255, 255, 0.06) !important;
        }

        .dark .nav-sidebar .nav-footer .user-info {
            background: rgba(255, 255, 255, 0.03) !important;
        }

        .dark .nav-sidebar .nav-footer .user-info:hover {
            background: rgba(255, 255, 255, 0.06) !important;
        }

        .dark .nav-sidebar .nav-footer .user-role {
            color: rgba(255, 255, 255, 0.3) !important;
        }

        /* Dark Mode - Toggle Button */
        .dark .nav-toggle {
            background: #1a1a1a !important;
            color: #e8e8e8 !important;
            border-color: #2a2a2a !important;
        }

        .dark .nav-toggle:hover {
            background: #262626 !important;
        }

        /* Dark Mode - Tabs */
        .dark .border-b-2 {
            border-color: #3d3d3d !important;
        }

        .dark .border-cv-500 {
            border-color: #FF8F00 !important;
        }

        .dark .text-cv-600 {
            color: #FF8F00 !important;
        }

        .dark .hover\:text-cv-600:hover {
            color: #FF8F00 !important;
        }

        /* Dark Mode - Scrollbar */
        .dark ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #FF8F00;
            border-radius: 4px;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #e68000;
        }

        /* Dark Mode - Card Shadows */
        .dark .shadow-sm {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5) !important;
        }

        .dark .shadow-lg {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6) !important;
        }

        .dark .shadow-xl {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7) !important;
        }

        /* Table Styles */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        table thead tr th:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        table thead tr th:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        /* Light mode table */
        table thead tr {
            background-color: #e8f2ff !important;
        }

        table thead tr th {
            color: #1a2d44 !important;
            font-weight: 700 !important;
        }

        table tbody tr {
            border-color: #d6e8ff !important;
        }

        table tbody tr:hover {
            background-color: #f0f7ff !important;
        }

        table tbody td {
            color: #1a2332 !important;
        }

        /* Toast */
        .toast-show {
            transform: translateY(0) !important;
            opacity: 1 !important;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            opacity: 0;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            cursor: pointer;
        }

        /* Transition */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
        }

        /* ============================================ */
        /* SCROLLABLE TABLE                            */
        /* ============================================ */
        .table-wrapper {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            margin: 0 -4px !important;
            padding: 0 4px !important;
        }

        .table-wrapper table {
            min-width: 600px !important;
            width: 100% !important;
        }

        .table-wrapper table th,
        .table-wrapper table td {
            white-space: nowrap !important;
            padding: 8px 12px !important;
        }

        @media (max-width: 640px) {
            .table-wrapper table {
                min-width: 500px !important;
                font-size: 13px !important;
            }

            .table-wrapper table th,
            .table-wrapper table td {
                padding: 6px 10px !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex">

    <!-- Overlay -->
    <div id="navOverlay" class="nav-overlay" onclick="toggleNav()"></div>

    <!-- Sidebar Navigation -->
    <nav id="sidebar" class="nav-sidebar hidden">
        <div class="brand">
            <h1>
                <span class="logo-icon"></span>
                Absensi Siswa
            </h1>
        </div>

        <div class="nav-menu">
            @auth
                @if(auth()->user()->role == 'admin')
                    <!-- ADMIN: Dashboard + Users -->
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="icon">📊</span><span class="label">Dashboard</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <span class="icon">👥</span><span class="label">Users</span>
                    </a>
                    <form id="logout-form-bottom" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf
                    </form>

                @elseif(auth()->user()->role == 'guru')
                    <!-- GURU: Dashboard + Gallery + Absensi Cepat -->
                    <a href="{{ route('guru.dashboard') }}"
                        class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                        <span class="icon">📊</span><span class="label">Dashboard</span>
                    </a>
                    <a href="{{ route('gallery.manage') }}"
                        class="nav-item {{ request()->routeIs('gallery.*') ? 'active' : '' }}">
                        <span class="icon">🖼️</span><span class="label">Gallery</span>
                    </a>
                    <a href="{{ route('absensi.cepat') }}"
                        class="nav-item {{ request()->routeIs('absensi.cepat*') ? 'active' : '' }}">
                        <span class="icon">⚡</span><span class="label">Absen</span>
                    </a>
                    <a href="{{ route('guru.absensi.index') }}"
                        class="nav-item {{ request()->routeIs('guru.absensi.index') ? 'active' : '' }}">
                        <span class="icon">📋</span> <span class="label">Riwayat Absensi</span>
                    </a>
                    <form id="logout-form-bottom" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf
                    </form>

                @elseif(auth()->user()->role == 'ortu')
                    <a href="{{ route('ortu.dashboard') }}"
                        class="nav-item {{ request()->routeIs('ortu.dashboard') ? 'active' : '' }}">
                        <span class="icon">📊</span><span class="label">Dashboard</span>
                    </a>
                    <a href="{{ route('school.dashboard') }}"
                        class="nav-item {{ request()->routeIs('school.dashboard') ? 'active' : '' }}">
                        <span class="icon">🖼️</span>
                        <span class="label">Gallery Sekolah</span>
                    </a>
                    <form id="logout-form-bottom" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf
                    </form>
                @endif
            @endauth
        </div>

        <div class="nav-footer">
            @auth
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <div>
                        <div class="user-name">{{ auth()->user()->name }}</div>
                        <div class="user-role">
                            {{ auth()->user()->role == 'admin' ? 'Administrator' : auth()->user()->kelas ?? 'Siswa' }}
                        </div>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="nav-item w-full"
                        style="background: rgba(239, 68, 68, 0.1); color: #f87171; border: none; width: 100%; text-align: left;">
                        <span class="icon"><i class="ph ph-sign-out"></i></span>
                        <span class="label">Logout</span>
                    </button>
                </form>
            @endauth
        </div>
    </nav>

    <!-- Main Content - Centered -->
    <main id="mainContent" class="main-content expanded">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
                <div
                    class="mb-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-check-circle text-emerald-500 text-xl"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div
                    class="mb-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 px-4 py-3 rounded-lg flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-x-circle text-rose-500 text-xl"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('warning'))
                <div
                    class="mb-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 px-4 py-3 rounded-lg flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-amber-500 text-xl"></i>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <div id="toast"
        class="fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-3 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 flex items-center gap-3 z-50">
        <i class="ph-fill ph-check-circle text-emerald-400 text-xl"></i>
        <div>
            <h4 class="text-sm font-bold">Berhasil!</h4>
            <p class="text-xs text-gray-300" id="toast-message">Data telah disimpan.</p>
        </div>
    </div>

    <script>
        // ============================================
        // SIDEBAR SELALU TERBUKA - TANPA TOGGLE
        // ============================================
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar selalu terbuka - tidak ada tombol toggle
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            // Pastikan sidebar terlihat
            sidebar.style.display = 'flex';
            sidebar.style.transform = 'translateX(0)';
            mainContent.style.marginLeft = '280px';

            // Load theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light');
            }
        });

        function showToast(message = 'Data berhasil disimpan!') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            if (toast && toastMessage) {
                toastMessage.textContent = message;
                toast.classList.add('toast-show');
                setTimeout(() => {
                    toast.classList.remove('toast-show');
                }, 3000);
            }
        }
    </script>

    @stack('scripts')

    <!-- Mobile Nav HTML -->
    <button class="hamburger-btn-mobile" onclick="toggleMobileSidebar()" aria-label="Toggle Navigation">
        <i class="ph ph-list"></i>
    </button>

    <div id="sidebarOverlayMobile" class="sidebar-overlay-mobile" onclick="closeMobileSidebar()"></div>

    <!-- Mobile Nav JS -->
    <script src="{{ asset('js/mobile-nav.js') }}"></script>
</body>

</html>