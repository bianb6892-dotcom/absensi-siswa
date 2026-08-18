@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i class="ph-fill ph-gauge text-primary-500"></i>
            Dashboard Admin
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">Selamat datang, {{ auth()->user()->name }}!</p>
    </div>
</div>

<!-- Statistik Sederhana -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center gap-3">
            <div class="bg-primary-100 dark:bg-primary-900/30 p-3 rounded-lg text-primary-600 dark:text-primary-400">
                <i class="ph ph-users text-2xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Guru</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ App\Models\User::where('role', 'guru')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center gap-3">
            <div class="bg-emerald-100 dark:bg-emerald-900/30 p-3 rounded-lg text-emerald-600 dark:text-emerald-400">
                <i class="ph ph-family text-2xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Orang Tua</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ App\Models\User::where('role', 'ortu')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center gap-3">
            <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-lg text-amber-600 dark:text-amber-400">
                <i class="ph ph-check-circle text-2xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Absensi Hari Ini</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ App\Models\Absensi::whereDate('tanggal', date('Y-m-d'))->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Menu Cepat -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
            <i class="ph ph-users text-primary-500 mr-2"></i>
            Manajemen User
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Kelola semua user (guru, orang tua)</p>
        <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="ph ph-arrow-right mr-2"></i>
            Kelola User
        </a>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
            <i class="ph ph-image text-primary-500 mr-2"></i>
            Gallery
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Kelola foto kegiatan sekolah</p>
        <a href="{{ route('gallery.manage') }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="ph ph-arrow-right mr-2"></i>
            Kelola Gallery
        </a>
    </div>
</div>

<!-- Tombol ke Absensi Cepat -->
<div class="mt-6">
    <a href="{{ route('absensi.cepat') }}" class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-colors">
        <i class="ph ph-check-circle mr-2"></i>
        Absensi Cepat
    </a>
</div>
@endsection