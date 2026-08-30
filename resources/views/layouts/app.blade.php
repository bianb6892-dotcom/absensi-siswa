<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Absensi Siswa') | SMK BPPI Baleendah</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet">

    <!-- Mobile Nav CSS -->
    <link rel="stylesheet" href="{{ asset('css/mobile-nav.css') }}">

    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Custom Navbar CSS -->
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive-fixes.css') }}">

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1D4ED8">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Absensi Siswa">

    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        card: '0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 12px -4px rgba(15, 23, 42, 0.08)',
                        'card-hover': '0 2px 4px rgba(15, 23, 42, 0.05), 0 8px 24px -8px rgba(15, 23, 42, 0.10)',
                        modal: '0 10px 40px -10px rgba(15, 23, 42, 0.25)',
                    },
                }
            }
        }
    </script>

<style type="text/tailwindcss">
        body {
            background: #F8FAFC;
            color: #334155;
        }

        .card {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 1rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 12px -4px rgba(15, 23, 42, 0.08);
        }

        .card:hover {
            box-shadow: 0 2px 4px rgba(15, 23, 42, 0.05), 0 8px 24px -8px rgba(15, 23, 42, 0.10);
            border-color: #CBD5E1;
        }

        /* Status badge helpers */
        .badge-hadir { background: #ECFDF5; color: #047857; }
        .badge-ijin { background: #FFFBEB; color: #B45309; }
        .badge-sakit { background: #F0F9FF; color: #0369A1; }
        .badge-tidak { background: #FFF1F2; color: #BE123C; }

        .status-select.badge-hadir { border-color: #6EE7B7; }
        .status-select.badge-ijin { border-color: #FCD34D; }
        .status-select.badge-sakit { border-color: #7DD3FC; }
        .status-select.badge-tidak { border-color: #FDA4AF; }

        /* Table */
        table {
            border-collapse: separate;
            border-spacing: 0;
        }

        table thead tr th:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        table thead tr th:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        table thead tr {
            background-color: #F1F5F9 !important;
        }

        table tbody tr {
            border-color: #F1F5F9 !important;
        }

        table tbody tr:hover {
            background-color: #F8FAFC !important;
        }

        /* Focus state (accessibility) */
        button:focus-visible,
        a:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 2px solid rgba(29, 78, 216, 0.45);
            outline-offset: 2px;
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

        * {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .table-wrapper {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            margin: 0 -4px !important;
            padding: 0 4px !important;
        }

        .table-wrapper table {
            min-width: max-content !important;
            width: 100% !important;
        }

        .table-wrapper table th,
        .table-wrapper table td {
            white-space: nowrap !important;
            padding: 8px 12px !important;
        }

        @media (max-width: 640px) {
            .table-wrapper table {
                font-size: 13px !important;
            }

            .table-wrapper table th,
            .table-wrapper table td {
                padding: 6px 10px !important;
            }
        }

        /* Reusable component classes */
        .btn {
            @apply inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-colors;
        }
        .btn-primary {
            @apply bg-blue-700 text-white shadow-sm hover:bg-blue-800;
        }
        .btn-outline {
            @apply border border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 hover:text-slate-900;
        }
.btn-outline-blue {
            @apply border border-blue-700 bg-white text-blue-700 shadow-sm hover:bg-blue-50;
        }
        .btn-danger {
            @apply border border-rose-200 bg-white text-rose-600 shadow-sm hover:bg-rose-50;
        }
        .input {
            @apply w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15;
        }
        .label {
            @apply mb-1.5 block text-sm font-semibold text-slate-700;
        }
        .input.pl-11 {
            padding-left: 2.75rem;
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased min-h-screen flex">

    <!-- Overlay -->
    <div id="navOverlay" class="nav-overlay" onclick="toggleNav()"></div>

    <!-- Sidebar Navigation -->
    <nav id="sidebar" class="nav-sidebar hidden">
        <div class="brand">
            <h1>
                <span class="logo-icon"><i class="ph ph-student ph-fill"></i></span>
                <div>
                    Absensi Siswa
                    <span>SMK BPPI Baleendah</span>
                </div>
            </h1>
        </div>

        <div class="nav-menu">
            @auth
                @if(auth()->user()->role == 'admin')
                    <p class="nav-section">Menu Utama</p>
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-squares-four"></i></span><span class="label">Dashboard</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-users-three"></i></span><span class="label">Manajemen User</span>
                    </a>
                    <p class="nav-section">Absensi</p>
                    <a href="{{ route('absensi.create') }}" class="nav-item {{ request()->routeIs('absensi.create') || request()->routeIs('absensi.store') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-clipboard-text"></i></span><span class="label">Input Absensi</span>
                    </a>
                    <a href="{{ route('absensi.index') }}" class="nav-item {{ request()->routeIs('absensi.index') || request()->routeIs('absensi.show') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-clock-counter-clockwise"></i></span><span class="label">Riwayat Absensi</span>
                    </a>
                    <a href="{{ route('import.absensi') }}" class="nav-item {{ request()->routeIs('import.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-file-arrow-up"></i></span><span class="label">Import Absensi</span>
                    </a>
                    <p class="nav-section">Konten</p>
                    <a href="{{ route('gallery.manage') }}" class="nav-item {{ request()->routeIs('gallery.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-images"></i></span><span class="label">Gallery Sekolah</span>
                    </a>

                @elseif(auth()->user()->role == 'guru')
                    <p class="nav-section">Menu Utama</p>
                    <a href="{{ route('guru.dashboard') }}"
                        class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-squares-four"></i></span><span class="label">Dashboard</span>
                    </a>
                    <p class="nav-section">Absensi</p>
                    <a href="{{ route('guru.absensi.create') }}"
                        class="nav-item {{ request()->routeIs('guru.absensi.create') || request()->routeIs('guru.absensi.store') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-clipboard-text"></i></span><span class="label">Input Absensi</span>
                    </a>
                    <a href="{{ route('guru.absensi.index') }}"
                        class="nav-item {{ request()->routeIs('guru.absensi.index') || request()->routeIs('guru.absensi.show') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-clock-counter-clockwise"></i></span><span class="label">Riwayat Absensi</span>
                    </a>
                    <a href="{{ route('absensi.cepat') }}" class="nav-item {{ request()->routeIs('absensi.cepat*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-lightning"></i></span><span class="label">Absensi Cepat</span>
                    </a>
                    <p class="nav-section">Nilai</p>
                    <a href="{{ route('guru.nilai.index', 'setengah-semester') }}"
                        class="nav-item {{ request()->is('guru/nilai/setengah-semester*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-book-open"></i></span><span class="label">Nilai Setengah Semester</span>
                    </a>
                    <a href="{{ route('guru.nilai.index', 'akhir-semester') }}"
                        class="nav-item {{ request()->is('guru/nilai/akhir-semester*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-books"></i></span><span class="label">Nilai Akhir Semester</span>
                    </a>
                    <p class="nav-section">Keuangan</p>
                    <a href="{{ route('guru.tunggakan.index') }}"
                        class="nav-item {{ request()->routeIs('guru.tunggakan.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-money"></i></span><span class="label">Tunggakan SPP</span>
                    </a>
                    <p class="nav-section">Konten</p>
                    <a href="{{ route('gallery.manage') }}" class="nav-item {{ request()->routeIs('gallery.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-images"></i></span><span class="label">Gallery Sekolah</span>
                    </a>

                @elseif(auth()->user()->role == 'ortu')
                    <p class="nav-section">Menu Utama</p>
                    <a href="{{ route('ortu.dashboard') }}"
                        class="nav-item {{ request()->routeIs('ortu.dashboard') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-squares-four"></i></span><span class="label">Dashboard</span>
                    </a>
                    <a href="{{ route('ortu.nilai') }}"
                        class="nav-item {{ request()->routeIs('ortu.nilai') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-graduation-cap"></i></span><span class="label">Nilai Anak</span>
                    </a>
                    <p class="nav-section">Konten</p>
                    <a href="{{ route('school.dashboard') }}"
                        class="nav-item {{ request()->routeIs('school.dashboard') ? 'active' : '' }}">
                        <span class="icon"><i class="ph ph-images"></i></span>
                        <span class="label">Gallery Sekolah</span>
                    </a>
                @endif
                @if(auth()->user()->role == 'ortu')
                <!-- Tombol Download Aplikasi - Hanya untuk Ortu -->
                <p class="nav-section">Aplikasi</p>
                <a href="{{ route('pwa.install') }}" class="nav-item {{ request()->routeIs('pwa.install') ? 'active' : '' }}">
                    <span class="icon"><i class="ph ph-download-simple"></i></span><span class="label">Download Aplikasi</span>
                </a>
                <button id="pwa-install-btn" onclick="triggerPwaInstall()" class="nav-item hidden w-full text-left" style="background: #1D4ED8; color: #fff; border: none; margin-top: 6px;">
                    <span class="icon"><i class="ph-fill ph-download-simple"></i></span><span class="label">Install Sekarang</span>
                </button>
                @endif
            @endauth
            @guest
                <a href="{{ route('pwa.install') }}" class="nav-item {{ request()->routeIs('pwa.install') ? 'active' : '' }}">
                    <span class="icon"><i class="ph ph-download-simple"></i></span><span class="label">Download Aplikasi</span>
                </a>
            @endguest
        </div>

        <div class="nav-footer">
            @auth
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <div class="min-w-0">
                        <div class="user-name truncate">{{ auth()->user()->name }}</div>
                        <div class="user-role">
                            @if(auth()->user()->role == 'admin')
                                Administrator
                            @elseif(auth()->user()->role == 'guru')
                                Guru
                            @elseif(auth()->user()->role == 'siswa')
                                {{ auth()->user()->kelas ?? 'Siswa' }}
                            @else
                                Orang Tua
                            @endif
                        </div>
                    </div>
                </div>

                <a href="{{ route('pengaturan.show') }}"
                    class="nav-item w-full mt-3" style="background: rgba(255, 255, 255, 0.08); color: #fff; border: none; margin-bottom: 2px;">
                    <span class="icon"><i class="ph ph-gear"></i></span>
                    <span class="label">Pengaturan</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="nav-item w-full"
                        style="background: rgba(255, 255, 255, 0.08); color: #fff; border: none;">
                        <span class="icon"><i class="ph ph-sign-out"></i></span>
                        <span class="label">Keluar</span>
                    </button>
                </form>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main id="mainContent" class="main-content expanded">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3.5">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-700 text-white">
                        <i class="ph-fill ph-check-circle text-lg"></i>
                    </div>
                    <p class="text-sm font-medium text-blue-800">{{ session('success') }}</p>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-blue-600 hover:text-blue-800">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-rose-600 text-white">
                        <i class="ph-fill ph-x-circle text-lg"></i>
                    </div>
                    <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-rose-600 hover:text-rose-800">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-5 flex items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3.5">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-500 text-white">
                        <i class="ph-fill ph-warning-circle text-lg"></i>
                    </div>
                    <p class="text-sm font-medium text-amber-800">{{ session('warning') }}</p>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-amber-600 hover:text-amber-800">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="mb-5 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-rose-600 text-white">
                        <i class="ph-fill ph-warning text-lg"></i>
                    </div>
                    <div class="text-sm font-medium text-rose-800">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-rose-600 hover:text-rose-800">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <div id="toast"
        class="fixed bottom-6 right-6 flex items-center gap-3 rounded-xl bg-slate-900 px-5 py-4 text-white shadow-lg shadow-slate-900/20 opacity-0 translate-y-24 transition-all duration-300 z-50">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/10">
            <i class="ph-fill ph-check-circle text-lg text-white"></i>
        </div>
        <div>
            <h4 class="text-sm font-bold">Berhasil!</h4>
            <p class="text-xs text-blue-200" id="toast-message">Data telah disimpan.</p>
        </div>
    </div>

    <script>
        function applyResponsiveLayout() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            if (!sidebar || !mainContent) return;

            if (window.innerWidth > 768) {
                sidebar.classList.remove('hidden');
                mainContent.style.marginLeft = '264px';
            } else {
                mainContent.style.marginLeft = '';
            }
        }

        document.addEventListener('DOMContentLoaded', applyResponsiveLayout);
        window.addEventListener('resize', applyResponsiveLayout);

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

    <!-- Mobile Nav -->
    <button class="hamburger-btn-mobile" onclick="toggleMobileSidebar()" aria-label="Toggle Navigation">
        <i class="ph ph-list"></i>
    </button>

    <div id="sidebarOverlayMobile" class="sidebar-overlay-mobile" onclick="closeMobileSidebar()"></div>

    <script src="{{ asset('js/mobile-nav.js') }}"></script>

    <!-- PWA: Service Worker + Install Prompt -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(reg){
                    console.log('SW registered', reg.scope);
                }).catch(function(err){ console.log('SW failed', err); });
            });
        }
        let deferredPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const btn = document.getElementById('pwa-install-btn');
            if(btn){ btn.classList.remove('hidden'); btn.classList.add('flex'); }
            const banner = document.getElementById('pwa-banner');
            if(banner) banner.classList.remove('hidden');
        });
        async function triggerPwaInstall(){
            if(deferredPrompt){
                deferredPrompt.prompt();
                const r = await deferredPrompt.userChoice;
                if(r.outcome === 'accepted') console.log('PWA installed');
                deferredPrompt = null;
                const btn = document.getElementById('pwa-install-btn');
                if(btn) btn.classList.add('hidden');
            } else {
                // iOS atau sudah terinstall -> arahkan ke halaman bantuan
                window.location.href = '/install';
            }
        }
        window.addEventListener('appinstalled', () => { deferredPrompt = null; });
    </script>
</body>

</html>