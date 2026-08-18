<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Absensi Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        cv: {
                            50: '#f5f3fa', 100: '#ebe6f3', 200: '#dcd3e9',
                            300: '#c2b3d9', 400: '#a38bc4', 500: '#8765af',
                            600: '#714d97', 700: '#5e3f7f', 800: '#4e3568',
                            900: '#412d55', 950: '#2b1b3b',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-cv-50 font-sans antialiased min-h-screen flex items-center justify-center py-8">
    <div class="w-full max-w-md px-4">
        <div class="bg-white rounded-2xl shadow-xl border border-cv-200 p-8">
            <div class="text-center mb-6">
                <div class="bg-emerald-500 text-white p-3 rounded-2xl inline-block mb-4">
                    <i class="ph ph-user-plus text-4xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-cv-950">Daftar Akun Baru</h1>
                <p class="text-charcoal-700 text-sm mt-1">Buat akun untuk mulai menggunakan</p>
            </div>

            @if($errors->any())
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="block text-sm font-semibold text-cv-900 mb-1">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-user text-cv-400"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="block w-full pl-10 pr-4 py-2.5 border border-cv-200 rounded-lg text-charcoal-900 bg-white focus:ring-2 focus:ring-cv-500 focus:border-cv-500 transition-shadow shadow-sm"
                            placeholder="Masukkan nama lengkap" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="block text-sm font-semibold text-cv-900 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-envelope text-cv-400"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="block w-full pl-10 pr-4 py-2.5 border border-cv-200 rounded-lg text-charcoal-900 bg-white focus:ring-2 focus:ring-cv-500 focus:border-cv-500 transition-shadow shadow-sm"
                            placeholder="Masukkan email" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="block text-sm font-semibold text-cv-900 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-lock text-cv-400"></i>
                        </div>
                        <input type="password" name="password" id="password"
                            class="block w-full pl-10 pr-4 py-2.5 border border-cv-200 rounded-lg text-charcoal-900 bg-white focus:ring-2 focus:ring-cv-500 focus:border-cv-500 transition-shadow shadow-sm"
                            placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="block text-sm font-semibold text-cv-900 mb-1">Konfirmasi
                        Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-check-circle text-cv-400"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="block w-full pl-10 pr-4 py-2.5 border border-cv-200 rounded-lg text-charcoal-900 bg-white focus:ring-2 focus:ring-cv-500 focus:border-cv-500 transition-shadow shadow-sm"
                            placeholder="Konfirmasi password" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="role" class="block text-sm font-semibold text-cv-900 mb-1">Daftar Sebagai</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-users text-cv-400"></i>
                        </div>
                        <select name="role" id="role"
                            class="block w-full pl-10 pr-4 py-2.5 border border-cv-200 rounded-lg text-charcoal-900 bg-white focus:ring-2 focus:ring-cv-500 focus:border-cv-500 transition-shadow shadow-sm appearance-none"
                            onchange="toggleKelas()">
                            <option value="ortu">Orang Tua</option>
                            <option value="guru">Guru</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4" id="kelas-field">
                    <label for="kelas" class="block text-sm font-semibold text-cv-900 mb-1">Kelas</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-graduation-cap text-cv-400"></i>
                        </div>
                        <select name="kelas" id="kelas"
                            class="block w-full pl-10 pr-4 py-2.5 border border-cv-200 rounded-lg text-charcoal-900 bg-white focus:ring-2 focus:ring-cv-500 focus:border-cv-500 transition-shadow shadow-sm appearance-none">
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
                    </div>
                    <p class="text-xs text-charcoal-700 mt-1">* Wajib diisi untuk siswa</p>
                </div>

                @if(old('role') == 'guru' || old('role') == 'siswa')
                    <div class="mb-4" id="ortuWrapper">
                        <label for="ortu_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Pilih
                            Orang Tua</label>
                        <select name="ortu_id" id="ortu_id"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">-- Pilih Orang Tua --</option>
                            @foreach($orangTuaList as $ortu)
                                <option value="{{ $ortu->id }}" {{ old('ortu_id') == $ortu->id ? 'selected' : '' }}>
                                    {{ $ortu->name }} ({{ $ortu->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">* Pilih orang tua untuk siswa ini</p>
                    </div>
                @endif

                <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="ph ph-user-plus"></i>
                    Daftar
                </button>
            </form>

            <div class="mt-4 text-center">
                <p class="text-sm text-charcoal-700">Sudah punya akun? <a href="{{ route('login') }}"
                        class="text-cv-600 hover:text-cv-700 font-semibold">Login disini</a></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Jalankan toggle saat halaman dimuat
            toggleKelas();
            toggleOrtu();
        });

        // Event listener untuk role
        document.getElementById('role').addEventListener('change', function () {
            toggleKelas();
            toggleOrtu();
        });

        function toggleKelas() {
            const role = document.getElementById('role').value;
            const kelasField = document.getElementById('kelas-field');
            const kelasSelect = document.getElementById('kelas');

            if (role === 'admin') {
                kelasField.style.display = 'none';
                kelasSelect.value = '';
                kelasSelect.required = false;
            } else {
                kelasField.style.display = 'block';
                kelasSelect.required = true;
            }
        }

        function toggleOrtu() {
            const role = document.getElementById('role').value;
            const ortuWrapper = document.getElementById('ortuWrapper');

            if (ortuWrapper) {
                if (role === 'guru' || role === 'siswa') {
                    ortuWrapper.style.display = 'block';
                } else {
                    ortuWrapper.style.display = 'none';
                }
            }
        }
    </script>
</body>

</html>