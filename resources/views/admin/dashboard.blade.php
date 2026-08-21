@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Dashboard Admin</h1>
        <p class="mt-1 text-sm text-slate-500">
            Selamat datang kembali, <span class="font-semibold text-slate-700">{{ auth()->user()->name }}</span>!
        </p>
    </div>
    <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-2.5 shadow-sm">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
            <i class="ph ph-calendar-dots text-lg"></i>
        </div>
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Hari ini</p>
            <p class="text-sm font-bold text-slate-900">{{ date('d F Y') }}</p>
        </div>
    </div>
</div>

<!-- Statistik -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="card p-5 hover:-translate-y-0.5 transition-all">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i class="ph-fill ph-user-list text-2xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Guru & Siswa</p>
                <p class="mt-0.5 text-2xl font-bold tracking-tight tabular-nums text-slate-900">{{ App\Models\User::whereIn('role', ['guru', 'siswa'])->count() }}</p>
                <p class="mt-0.5 text-[11px] text-slate-400">Akun guru & siswa terdaftar</p>
            </div>
        </div>
    </div>

    <div class="card p-5 hover:-translate-y-0.5 transition-all">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i class="ph-fill ph-users-three text-2xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Orang Tua</p>
                <p class="mt-0.5 text-2xl font-bold tracking-tight tabular-nums text-slate-900">{{ App\Models\User::where('role', 'ortu')->count() }}</p>
                <p class="mt-0.5 text-[11px] text-slate-400">Akun wali murid terdaftar</p>
            </div>
        </div>
    </div>

    <div class="card p-5 hover:-translate-y-0.5 transition-all">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i class="ph-fill ph-calendar-check text-2xl"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Absensi Hari Ini</p>
                <p class="mt-0.5 text-2xl font-bold tracking-tight tabular-nums text-slate-900">{{ App\Models\Absensi::whereDate('tanggal', date('Y-m-d'))->count() }}</p>
                <p class="mt-0.5 text-[11px] text-slate-400">Total kehadiran tercatat</p>
            </div>
        </div>
    </div>
</div>

<!-- Menu Cepat -->
<div class="mb-4 flex items-center gap-3">
    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Menu Cepat</h2>
    <div class="h-px flex-1 bg-slate-200"></div>
</div>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="card p-6 hover:-translate-y-1 hover:shadow-card-hover transition-all group cursor-pointer" onclick="window.location='{{ route('users.index') }}'">
        <div class="flex items-center justify-between mb-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 group-hover:bg-blue-700 group-hover:text-white transition-all">
                <i class="ph-fill ph-users-three text-2xl"></i>
            </div>
            <i class="ph ph-arrow-right text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition-all"></i>
        </div>
        <h3 class="font-bold text-slate-900">Manajemen User</h3>
        <p class="mt-1 text-sm text-slate-500">Kelola semua akun guru, orang tua & admin</p>
    </div>

    <div class="card p-6 hover:-translate-y-1 hover:shadow-card-hover transition-all group cursor-pointer" onclick="window.location='{{ route('import.absensi') }}'">
        <div class="flex items-center justify-between mb-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 group-hover:bg-blue-700 group-hover:text-white transition-all">
                <i class="ph-fill ph-file-arrow-up text-2xl"></i>
            </div>
            <i class="ph ph-arrow-right text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition-all"></i>
        </div>
        <h3 class="font-bold text-slate-900">Import Absensi</h3>
        <p class="mt-1 text-sm text-slate-500">Upload file Excel absensi siswa sekaligus</p>
    </div>

    <div class="card p-6 hover:-translate-y-1 hover:shadow-card-hover transition-all group cursor-pointer" onclick="window.location='{{ route('gallery.manage') }}'">
        <div class="flex items-center justify-between mb-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 group-hover:bg-blue-700 group-hover:text-white transition-all">
                <i class="ph-fill ph-images text-2xl"></i>
            </div>
            <i class="ph ph-arrow-right text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition-all"></i>
        </div>
        <h3 class="font-bold text-slate-900">Gallery Sekolah</h3>
        <p class="mt-1 text-sm text-slate-500">Kelola foto kegiatan, jurusan & ekstrakurikuler</p>
    </div>
</div>
@endsection