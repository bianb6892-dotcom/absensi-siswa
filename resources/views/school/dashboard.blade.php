@extends('layouts.app')

@section('title', 'Dashboard Sekolah')

@section('content')
<!-- Hero Section -->
<div class="bg-white dark:bg-gray-800 rounded-2xl p-8 mb-8 text-center relative overflow-hidden shadow-lg border border-gray-200 dark:border-gray-700">
    <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-primary-500/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
    <div class="relative z-10">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary-500/10 text-primary-500 mb-4">
            <i class="ph-fill ph-buildings text-4xl"></i>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">Selamat Datang di Portal Sekolah</h1>
        <p class="text-lg text-gray-700 dark:text-gray-300 max-w-2xl mx-auto">{{ $informasi['visi'] ?? 'Membentuk generasi unggul, kreatif, dan berakhlak mulia melalui pendidikan yang berkualitas dan inovatif.' }}</p>
    </div>
</div>

<!-- ============================================ -->
<!-- GALLERY KEGIATAN                            -->
<!-- ============================================ -->
<div class="mt-8">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500">
            <i class="ph-fill ph-calendar-check text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📸 Gallery Kegiatan</h2>
    </div>
    @if(isset($kegiatan) && $kegiatan->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($kegiatan as $item)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white text-base">{{ $item->title }}</h3>
                        @if($item->category)
                            <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs rounded-full">{{ $item->category }}</span>
                        @endif
                        @if($item->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $item->description }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">Belum ada foto kegiatan.</p>
    @endif
</div>

<!-- ============================================ -->
<!-- GALLERY JURUSAN                             -->
<!-- ============================================ -->
<div class="mt-8">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500">
            <i class="ph-fill ph-buildings text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">🏫 Gallery Jurusan</h2>
    </div>
    @if(isset($jurusan) && $jurusan->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($jurusan as $item)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white text-base">{{ $item->title }}</h3>
                        @if($item->category)
                            <span class="inline-block mt-1 px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-xs rounded-full">{{ $item->category }}</span>
                        @endif
                        @if($item->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $item->description }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">Belum ada foto jurusan.</p>
    @endif
</div>

<!-- ============================================ -->
<!-- GALLERY EKSKUL                              -->
<!-- ============================================ -->
<div class="mt-8">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-500">
            <i class="ph-fill ph-football text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">⚽ Gallery Ekstrakurikuler</h2>
    </div>
    @if(isset($eskul) && $eskul->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($eskul as $item)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white text-base">{{ $item->title }}</h3>
                        @if($item->category)
                            <span class="inline-block mt-1 px-2 py-0.5 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs rounded-full">{{ $item->category }}</span>
                        @endif
                        @if($item->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $item->description }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">Belum ada foto ekstrakurikuler.</p>
    @endif
</div>

<!-- ============================================ -->
<!-- TOMBOL KELOLA GALLERY (HANYA ADMIN & GURU)  -->
<!-- ============================================ -->
@if(auth()->user()->role == 'admin' || auth()->user()->role == 'guru')
    <div class="text-center mt-6">
        <a href="{{ route('gallery.manage') }}" class="inline-flex items-center px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg shadow-primary-500/20 hover:shadow-primary-500/30">
            <i class="ph ph-images mr-2"></i>
            Kelola Gallery (Kegiatan, Jurusan, Eskul)
        </a>
    </div>
@endif

<!-- ============================================ -->
<!-- TOMBOL UNTUK ORANG TUA (VIEW ONLY)          -->
<!-- ============================================ -->
@if(auth()->user()->role == 'ortu')
    <div class="text-center mt-6">
    </div>
@endif
@endsection