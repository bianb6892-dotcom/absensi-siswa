<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi Siswa</title>
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
<body class="bg-cv-50 font-sans antialiased min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <div class="bg-white rounded-2xl shadow-xl border border-cv-200 p-8">
            <div class="text-center mb-8">
                <div class="bg-cv-500 text-white p-3 rounded-2xl inline-block mb-4">
                    <i class="ph ph-student text-4xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-cv-950">Login Absensi Siswa</h1>
                <p class="text-charcoal-700 text-sm mt-1">Masuk untuk melanjutkan</p>
            </div>
            
            @if($errors->any())
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg flex items-center gap-3">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-xl"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-cv-900 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-envelope text-cv-400"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" 
                            class="block w-full pl-10 pr-4 py-2.5 border border-cv-200 rounded-lg text-charcoal-900 bg-white focus:ring-2 focus:ring-cv-500 focus:border-cv-500 transition-shadow shadow-sm"
                            placeholder="Masukkan email" required autofocus>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-cv-900 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-lock text-cv-400"></i>
                        </div>
                        <input type="password" name="password" id="password" 
                            class="block w-full pl-10 pr-4 py-2.5 border border-cv-200 rounded-lg text-charcoal-900 bg-white focus:ring-2 focus:ring-cv-500 focus:border-cv-500 transition-shadow shadow-sm"
                            placeholder="Masukkan password" required>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-cv-600 hover:bg-cv-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="ph ph-sign-in"></i>
                    Login
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-charcoal-700">Belum punya akun? <a href="{{ route('register.show') }}" class="text-cv-600 hover:text-cv-700 font-semibold">Register disini</a></p>
            </div>
            
            <hr class="my-6 border-cv-200">
            
            <div class="text-center">
                <p class="text-xs text-charcoal-700">
                    <strong>Akun Demo:</strong><br>
                    Admin: admin@absensi.com / password<br>
                    Siswa: budi@siswa.com / password
                </p>
            </div>
        </div>
    </div>
</body>
</html>