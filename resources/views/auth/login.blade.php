<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1D4ED8">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'] },
                    boxShadow: {
                        card: '0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 12px -4px rgba(15, 23, 42, 0.08)',
                    },
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        body {
            background: #F8FAFC;
        }

        .btn {
            @apply inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-colors;
        }
        .btn-primary {
            @apply bg-blue-700 text-white shadow-sm hover:bg-blue-800;
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
</head>
<body class="font-sans antialiased min-h-screen flex">

    <!-- ===== PANEL BRANDING (DESKTOP) ===== -->
    <div class="hidden lg:flex w-[45%] xl:w-[40%] relative overflow-hidden bg-slate-900">
        <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute bottom-0 -left-24 h-80 w-80 rounded-full bg-blue-400/20 blur-3xl"></div>

        <div class="relative z-10 flex flex-col justify-between p-12 xl:p-16 w-full">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-blue-700 shadow-lg">
                    <i class="ph-fill ph-student text-2xl"></i>
                </div>
                <div>
                    <p class="text-lg font-extrabold text-white leading-tight">Absensi Siswa</p>
                    <p class="text-xs font-medium text-blue-200">SMK BPPI Baleendah</p>
                </div>
            </div>

            <div>
                <h1 class="text-3xl xl:text-4xl font-extrabold text-white leading-tight">
                    Kelola kehadiran siswa<br>lebih mudah &amp; efisien
                </h1>
                <p class="mt-4 text-blue-100/90 text-sm xl:text-base max-w-md leading-relaxed">
                    Sistem absensi digital untuk memantau kehadiran, nilai, dan kegiatan sekolah secara real-time.
                </p>
                <div class="mt-8 space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/15 text-white">
                            <i class="ph-fill ph-clock-counter-clockwise text-sm"></i>
                        </div>
                        <p class="text-sm text-blue-50">Absensi harian yang cepat &amp; akurat</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/15 text-white">
                            <i class="ph-fill ph-graduation-cap text-sm"></i>
                        </div>
                        <p class="text-sm text-blue-50">Pantau nilai &amp; rekap kehadiran siswa</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/15 text-white">
                            <i class="ph-fill ph-bell-ringing text-sm"></i>
                        </div>
                        <p class="text-sm text-blue-50">Notifikasi otomatis untuk orang tua</p>
                    </div>
                </div>
            </div>

            <p class="text-xs text-blue-200/70">&copy; {{ date('Y') }} SMK BPPI Baleendah</p>
        </div>
    </div>

    <!-- ===== FORM LOGIN ===== -->
    <div class="flex flex-1 items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="lg:hidden flex items-center justify-center gap-3 mb-8">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-700 text-white shadow-lg shadow-blue-700/30">
                    <i class="ph-fill ph-student text-2xl"></i>
                </div>
                <div>
                    <p class="text-lg font-extrabold text-slate-900 leading-tight">Absensi Siswa</p>
                    <p class="text-xs font-medium text-slate-500">SMK BPPI Baleendah</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 lg:p-10 shadow-card">
                <div class="mb-8">
                    <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Selamat Datang</h2>
                    <p class="text-sm text-slate-500 mt-1.5">Masuk ke portal untuk melanjutkan</p>
                </div>

                @if($errors->any())
                    <div class="mb-5 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3">
                        <i class="ph-fill ph-warning-circle text-xl text-rose-500"></i>
                        <span class="text-sm font-medium text-rose-700">{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="login" class="label">NIS / Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ph ph-identification-card text-lg text-slate-400"></i>
                            </div>
                            <input type="text" name="login" id="login" value="{{ old('login') }}"
                                class="input pl-11" placeholder="Masukkan NIS atau email" required autofocus>
                        </div>
                        <p class="mt-1.5 text-xs text-slate-400">Orang tua: gunakan NIS anak Anda untuk login</p>
                    </div>

                    <div>
                        <label for="password" class="label">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ph ph-lock-simple text-lg text-slate-400"></i>
                            </div>
                            <input type="password" name="password" id="password"
                                class="input pl-11" placeholder="Masukkan password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full py-3">
                        <i class="ph ph-sign-in text-lg"></i>
                        Masuk Sekarang
                    </button>
                </form>
            </div>

            <a href="/install" class="mt-4 flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <i class="ph ph-download-simple"></i> Download Aplikasi
            </a>
            <p class="mt-6 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} SMK BPPI Baleendah &middot; Sistem Absensi Siswa
            </p>
        </div>
    </div>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(()=>{}));
        }
    </script>
</body>
</html>