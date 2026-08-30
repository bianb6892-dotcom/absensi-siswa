<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', 'Dashboard Orang Tua') | Absensi Siswa</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        card: '0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 12px -4px rgba(15, 23, 42, 0.08)',
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1D4ED8">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <style type="text/tailwindcss">
        body {
            background: #F8FAFC;
            color: #334155;
        }
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
        .pt-safe {
            padding-top: env(safe-area-inset-top, 0px);
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .gallery-scroll {
            scroll-snap-type: x mandatory;
        }
        .gallery-scroll > * {
            scroll-snap-align: start;
        }
        .card {
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 1rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 12px -4px rgba(15, 23, 42, 0.08);
        }
        .badge-hadir { background: #ECFDF5; color: #047857; }
        .badge-ijin { background: #FFFBEB; color: #B45309; }
        .badge-sakit { background: #F0F9FF; color: #0369A1; }
        .badge-tidak { background: #FFF1F2; color: #BE123C; }
        .btn {
            @apply inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-colors;
        }
        .btn-primary {
            @apply bg-blue-700 text-white shadow-sm hover:bg-blue-800;
        }
        .input {
            @apply w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15;
        }
        .input.pl-11 {
            padding-left: 2.75rem;
        }
        .label {
            @apply mb-1.5 block text-sm font-semibold text-slate-700;
        }
        .card-body {
            padding: 1.25rem;
        }
        .card-header {
            padding: 1rem 1.25rem;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/responsive-fixes.css') }}">
    @stack('styles')
</head>
<body class="font-sans antialiased min-h-screen text-slate-800">

    <!-- ===== HEADER STICKY ===== -->
    <header class="fixed top-0 inset-x-0 z-40 pt-safe">
        <div class="mx-auto w-full max-w-md px-4 pt-4">
            <div class="flex items-center gap-2 sm:gap-3 rounded-2xl bg-blue-700 px-3 sm:px-4 py-3 shadow-lg shadow-blue-900/20">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                    <div class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 text-white ring-1 ring-white/20">
                        <i class="ph-fill ph-student text-lg sm:text-xl"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.18em] text-blue-100">Absensi Siswa</p>
                        <h1 class="text-sm sm:text-base font-extrabold leading-tight text-white break-words line-clamp-2">
                            Halo, {{ Auth::user()->name }}
                        </h1>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                    <a href="{{ route('pengaturan.show') }}" title="Pengaturan"
                        class="flex h-9 w-9 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-xl bg-white/15 text-white transition-colors hover:bg-white/25">
                        <i class="ph-fill ph-gear text-base sm:text-lg"></i>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                        @csrf
                        <button type="submit" title="Keluar"
                            class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl bg-white/15 text-white transition-colors hover:bg-white/25">
                            <i class="ph-fill ph-sign-out text-base sm:text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- ===== KONTEN ===== -->
    <main class="mx-auto w-full max-w-md px-4 pb-32 pt-28">
        @if(session('success'))
            <div class="mb-4 flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-700 text-white">
                    <i class="ph-fill ph-check-circle text-lg"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-blue-800">Berhasil</p>
                    <p class="text-sm text-blue-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 p-4">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-rose-600 text-white">
                    <i class="ph-fill ph-warning-circle text-lg"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-rose-800">Perhatian</p>
                    <p class="text-sm text-rose-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif
        @if(session('warning'))
            <div class="mb-4 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-500 text-white">
                    <i class="ph-fill ph-warning text-lg"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-amber-800">Informasi</p>
                    <p class="text-sm text-amber-700">{{ session('warning') }}</p>
                </div>
            </div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="mb-4 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 p-4">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-rose-600 text-white">
                    <i class="ph-fill ph-warning-circle text-lg"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-rose-800">Terjadi kesalahan</p>
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-rose-700">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- ===== BOTTOM NAVIGATION ===== -->
    <nav class="fixed inset-x-0 bottom-0 z-40 pb-safe">
        <div class="mx-auto w-full max-w-md px-4 pb-4">
            <div class="flex items-center justify-around rounded-2xl border border-slate-200 bg-white px-2 py-2 shadow-lg shadow-slate-900/5">
                <a href="{{ route('ortu.dashboard') }}"
                    class="{{ request()->routeIs('ortu.dashboard') ? 'bg-blue-700 text-white shadow-sm shadow-blue-700/30' : 'text-slate-400 hover:text-blue-600' }} flex flex-1 flex-col items-center gap-0.5 rounded-xl px-2 py-2 transition-colors">
                    <i class="ph-fill ph-squares-four text-xl"></i>
                    <span class="text-[10px] font-semibold">Dashboard</span>
                </a>
                <a href="{{ route('ortu.nilai') }}"
                    class="{{ request()->routeIs('ortu.nilai') ? 'bg-blue-700 text-white shadow-sm shadow-blue-700/30' : 'text-slate-400 hover:text-blue-600' }} flex flex-1 flex-col items-center gap-0.5 rounded-xl px-2 py-2 transition-colors">
                    <i class="ph-fill ph-graduation-cap text-xl"></i>
                    <span class="text-[10px] font-semibold">Nilai</span>
                </a>
                <a href="{{ route('school.dashboard') }}"
                    class="{{ request()->routeIs('school.dashboard') ? 'bg-blue-700 text-white shadow-sm shadow-blue-700/30' : 'text-slate-400 hover:text-blue-600' }} flex flex-1 flex-col items-center gap-0.5 rounded-xl px-2 py-2 transition-colors">
                    <i class="ph-fill ph-images text-xl"></i>
                    <span class="text-[10px] font-semibold">Gallery</span>
                </a>
            </div>
        </div>
    </nav>

    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').catch(function(e){console.log(e);});
            });
        }
        let deferredPromptOrtu = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPromptOrtu = e;
            const b = document.getElementById('pwa-install-btn-ortu');
            if(b) b.classList.remove('hidden');
        });
        async function triggerPwaInstallOrtu(){
            if(deferredPromptOrtu){ deferredPromptOrtu.prompt(); await deferredPromptOrtu.userChoice; deferredPromptOrtu=null; }
            else { window.location.href='/install'; }
        }
    </script>
</body>
</html>