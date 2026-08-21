@extends(auth()->user()->role === 'ortu' ? 'layouts.ortu' : 'layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Pengaturan</h1>
    <p class="mt-1 text-sm text-slate-500">Kelola keamanan akun Anda.</p>
</div>

@if(auth()->user()->role === 'ortu')
    <div class="card overflow-hidden mb-6">
        <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/60 px-5 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <i class="ph ph-whatsapp-logo"></i>
            </div>
            <h2 class="text-base font-bold text-slate-900">Nomor WhatsApp</h2>
        </div>
        <div class="p-5">
            <p class="text-xs text-slate-500">
                Nomor ini dipakai untuk menerima notifikasi absensi anak Anda via WhatsApp.
                Kosongkan jika tidak ingin menerima notifikasi.
            </p>
            <form method="POST" action="{{ route('pengaturan.whatsapp') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="no_wa" class="label">Nomor WhatsApp (Opsional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-whatsapp-logo text-lg text-slate-400"></i>
                        </div>
                        <input type="text" name="no_wa" id="no_wa" value="{{ $ortuProfile?->no_wa }}"
                            class="input pl-11" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-full py-3">
                    <i class="ph ph-floppy-disk text-lg"></i>
                    Simpan Nomor
                </button>
            </form>
        </div>
    </div>
@endif

<div class="space-y-6">
    <div class="card overflow-hidden">
        <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/60 px-5 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <i class="ph-fill ph-user-circle"></i>
            </div>
            <h2 class="text-base font-bold text-slate-900">Informasi Akun</h2>
        </div>
        <div class="p-5 space-y-4">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-700 text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Role</p>
                    <p class="mt-0.5 text-sm font-bold capitalize text-slate-800">{{ auth()->user()->role }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Bergabung</p>
                    <p class="mt-0.5 text-sm font-bold text-slate-800">{{ auth()->user()->created_at->format('d F Y') }}</p>
                </div>
            </div>
            <p class="text-xs text-slate-400">
                <i class="ph ph-info mr-1"></i>
                Gunakan password yang kuat dan jangan berbagi akun dengan orang lain.
            </p>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/60 px-5 py-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <i class="ph-fill ph-key"></i>
            </div>
            <h2 class="text-base font-bold text-slate-900">Ganti Password</h2>
        </div>
        <div class="p-5">
            @if($errors->any())
                <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3">
                    <ul class="list-disc list-inside space-y-1 text-sm text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('pengaturan.password') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="current_password" class="label">Password Saat Ini</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-lock-simple text-lg text-slate-400"></i>
                        </div>
                        <input type="password" name="current_password" id="current_password"
                            class="input pl-11" placeholder="Masukkan password saat ini" required>
                    </div>
                </div>

                <div>
                    <label for="password" class="label">Password Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-lock-key text-lg text-slate-400"></i>
                        </div>
                        <input type="password" name="password" id="password"
                            class="input pl-11" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="label">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-check-circle text-lg text-slate-400"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="input pl-11" placeholder="Ulangi password baru" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full py-3">
                    <i class="ph ph-floppy-disk text-lg"></i>
                    Simpan Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
