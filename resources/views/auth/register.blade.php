<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Absensi Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet">
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

<body class="font-sans antialiased min-h-screen flex items-center justify-center py-10">

    <div class="w-full max-w-lg px-4">
        <div class="lg:hidden flex items-center justify-center gap-3 mb-8">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-700 text-white shadow-lg shadow-blue-700/30">
                <i class="ph-fill ph-user-plus text-2xl"></i>
            </div>
            <div>
                <p class="text-lg font-extrabold text-slate-900 leading-tight">Absensi Siswa</p>
                <p class="text-xs font-medium text-slate-500">SMK Digital Nusantara</p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-8 sm:p-10 shadow-card">
            <div class="mb-8">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-700 text-white shadow-lg shadow-blue-700/30 mb-5 hidden lg:flex">
                    <i class="ph-fill ph-user-plus text-2xl"></i>
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Buat Akun Baru</h1>
                <p class="text-sm text-slate-500 mt-1.5">Daftar untuk mulai menggunakan sistem absensi</p>
            </div>

            @if($errors->any())
                <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5">
                    <ul class="list-disc list-inside space-y-1 text-sm text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="label">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-user text-lg text-slate-400"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="input pl-11" placeholder="Masukkan nama lengkap" required>
                    </div>
                </div>

                <div>
                    <label for="email" class="label">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-envelope-simple text-lg text-slate-400"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="input pl-11" placeholder="nama@email.com" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="label">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ph ph-lock-simple text-lg text-slate-400"></i>
                            </div>
                            <input type="password" name="password" id="password"
                                class="input pl-11" placeholder="Minimal 6 karakter" required>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="label">Konfirmasi Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="ph ph-check-circle text-lg text-slate-400"></i>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="input pl-11" placeholder="Ulangi password" required>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="role" class="label">Daftar Sebagai</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-users-three text-lg text-slate-400"></i>
                        </div>
                        <select name="role" id="role"
                            class="input pl-11 pr-10 appearance-none cursor-pointer"
                            onchange="toggleKelas(); toggleOrtu(); toggleNis();">
                            <option value="ortu" {{ old('role') == 'ortu' ? 'selected' : '' }}>Orang Tua</option>
                            <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>
                </div>

                <div id="kelas-field">
                    <label for="kelas" class="label">Kelas</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-graduation-cap text-lg text-slate-400"></i>
                        </div>
                        <select name="kelas" id="kelas"
                            class="input pl-11 pr-10 appearance-none cursor-pointer">
                            <option value="">-- Pilih Kelas --</option>
                            <optgroup label="Kelas X">
                                <option value="X PPLG">X PPLG</option>
                                <option value="X TJKT">X TJKT</option>
                                <option value="X ACP">X ACP</option>
                                <option value="X AKL">X AKL</option>
                            </optgroup>
                            <optgroup label="Kelas XI">
                                <option value="XI PPLG">XI PPLG</option>
                                <option value="XI TJKT">XI TJKT</option>
                                <option value="XI ACP">XI ACP</option>
                                <option value="XI AKL">XI AKL</option>
                            </optgroup>
                            <optgroup label="Kelas XII">
                                <option value="XII PPLG">XII PPLG</option>
                                <option value="XII TJKT">XII TJKT</option>
                                <option value="XII ACP">XII ACP</option>
                                <option value="XII AKL">XII AKL</option>
                            </optgroup>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">* Wajib diisi untuk siswa</p>
                </div>

                <div id="ortuWrapper" class="{{ old('role') == 'siswa' ? '' : 'hidden' }}">
                    <label for="ortu_id" class="label">Pilih Orang Tua</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-family text-lg text-slate-400"></i>
                        </div>
                        <select name="ortu_id" id="ortu_id"
                            class="input pl-11 pr-10 appearance-none cursor-pointer">
                            <option value="">-- Pilih Orang Tua --</option>
                            @foreach($orangTuaList as $ortu)
                                @if($ortu->user)
                                    <option value="{{ $ortu->id }}" {{ old('ortu_id') == $ortu->id ? 'selected' : '' }}>
                                        {{ $ortu->user->name }} ({{ $ortu->user->email }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">* Pilih orang tua untuk siswa ini</p>
                </div>

                <div id="nis-field" class="{{ old('role') == 'siswa' ? '' : 'hidden' }}">
                    <label for="nis" class="label">NIS <span class="font-normal text-slate-400">(opsional)</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-identification-card text-lg text-slate-400"></i>
                        </div>
                        <input type="text" name="nis" id="nis" value="{{ old('nis') }}" maxlength="20"
                            class="input pl-11" placeholder="contoh: 2026001" autocomplete="off">
                    </div>
                    <p class="mt-1 text-xs text-slate-400">
                        * NIS dipakai orang tua untuk login ke portal pemantauan
                    </p>
                </div>

                <button type="submit" class="btn btn-primary w-full py-3">
                    <i class="ph ph-user-plus text-lg"></i>
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">Sudah punya akun?
                    <a href="{{ route('login') }}"
                        class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">Login disini</a>
                </p>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} SMK Digital Nusantara &middot; Sistem Absensi Siswa
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            toggleKelas();
            toggleOrtu();
            toggleNis();
        });

        document.getElementById('role').addEventListener('change', function () {
            toggleKelas();
            toggleOrtu();
            toggleNis();
        });

        function toggleNis() {
            const role = document.getElementById('role').value;
            const nisField = document.getElementById('nis-field');

            if (nisField) {
                nisField.style.display = role === 'siswa' ? 'block' : 'none';
                if (role !== 'siswa') document.getElementById('nis').value = '';
            }
        }

        function toggleKelas() {
            const role = document.getElementById('role').value;
            const kelasField = document.getElementById('kelas-field');
            const kelasSelect = document.getElementById('kelas');

            if (role === 'siswa') {
                kelasField.style.display = 'block';
                kelasSelect.required = true;
            } else {
                kelasField.style.display = 'none';
                kelasSelect.value = '';
                kelasSelect.required = false;
            }
        }

        function toggleOrtu() {
            const role = document.getElementById('role').value;
            const ortuWrapper = document.getElementById('ortuWrapper');

            if (ortuWrapper) {
                if (role === 'siswa') {
                    ortuWrapper.style.display = 'block';
                } else {
                    ortuWrapper.style.display = 'none';
                }
            }
        }
    </script>
</body>

</html>