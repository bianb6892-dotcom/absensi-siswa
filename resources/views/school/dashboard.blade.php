@extends('layouts.app')

@section('title', 'Dashboard Sekolah')

@section('content')
<!-- Hero Section dengan Card -->
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

<!-- Informasi Sekolah -->
<div class="bg-white dark:bg-gray-800 rounded-2xl p-6 mb-8 shadow-lg border border-gray-200 dark:border-gray-700">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-lg bg-primary-500/10 flex items-center justify-center text-primary-500">
            <i class="ph-fill ph-info text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Informasi Sekolah</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Visi & Misi -->
        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-6 border border-gray-300 dark:border-gray-600">
            <div class="flex items-center gap-2 mb-3">
                <i class="ph-fill ph-target text-primary-500 text-xl"></i>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Visi & Misi</h3>
            </div>
            <p class="text-gray-800 dark:text-gray-300 leading-relaxed">
                {{ $informasi['visi'] ?? 'Membentuk generasi unggul, kreatif, dan berakhlak mulia melalui pendidikan yang berkualitas dan inovatif.' }}
            </p>
        </div>
        
        <!-- Fasilitas -->
        <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-6 border border-gray-300 dark:border-gray-600">
            <div class="flex items-center gap-2 mb-3">
                <i class="ph-fill ph-buildings text-primary-500 text-xl"></i>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Fasilitas Unggulan</h3>
            </div>
            <ul class="space-y-2">
                @foreach(($informasi['fasilitas'] ?? []) as $fasilitas)
                    <li class="flex items-center gap-2 text-gray-800 dark:text-gray-300">
                        <i class="ph-fill ph-check-circle text-emerald-500 text-sm"></i>
                        {{ $fasilitas }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<!-- Prestasi -->
<div class="bg-white dark:bg-gray-800 rounded-2xl p-6 mb-8 shadow-lg border border-gray-200 dark:border-gray-700">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500">
            <i class="ph-fill ph-trophy text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Prestasi Sekolah</h2>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach(($informasi['prestasi'] ?? []) as $prestasi)
            <div class="bg-gray-100 dark:bg-gray-700 rounded-xl p-4 border border-gray-300 dark:border-gray-600 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-500 flex-shrink-0">
                    <i class="ph-fill ph-medal text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800 dark:text-gray-200 text-sm">{{ $prestasi }}</h4>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Daftar Jurusan -->
<section id="jurusan" class="mb-8">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-lg bg-primary-500/10 flex items-center justify-center text-primary-500">
            <i class="ph-fill ph-graduation-cap text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Jurusan</h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse(($jurusan ?? []) as $j)
            <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="h-40 overflow-hidden">
                    <img src="{{ asset('storage/' . $j->image) }}" 
                         alt="{{ $j->title }}" 
                         class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-primary-500/10 flex items-center justify-center text-primary-500 flex-shrink-0">
                            <i class="ph-fill ph-code text-sm"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $j->title }}</h3>
                    </div>
                    @if($j->category)
                        <p class="text-xs font-medium text-primary-600 dark:text-primary-400 mb-2">{{ $j->category }}</p>
                    @endif
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $j->description ?? 'Deskripsi jurusan belum tersedia.' }}</p>
                </div>
            </div>
        @empty
            <!-- Default Jurusan -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-14 h-14 rounded-2xl bg-primary-500/10 flex items-center justify-center mb-4">
                    <i class="ph ph-code text-2xl text-primary-500"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">PPLG</h3>
                <p class="text-xs text-primary-600 dark:text-primary-400 font-medium mb-2">Pengembangan Perangkat Lunak dan Gim</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">Fokus pada programming, pembuatan aplikasi, web development, dan game design.</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center mb-4">
                    <i class="ph ph-wifi-high text-2xl text-emerald-500"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">TJKT</h3>
                <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium mb-2">Teknik Jaringan Komputer dan Telekomunikasi</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">Mempelajari infrastruktur jaringan, keamanan cyber, dan administrasi server.</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center mb-4">
                    <i class="ph ph-coins text-2xl text-amber-500"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">AKL</h3>
                <p class="text-xs text-amber-600 dark:text-amber-400 font-medium mb-2">Akuntansi dan Keuangan Lembaga</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">Mencetak tenaga profesional di bidang pembukuan, perpajakan, dan administrasi keuangan.</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-14 h-14 rounded-2xl bg-purple-500/10 flex items-center justify-center mb-4">
                    <i class="ph ph-desktop text-2xl text-purple-500"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">ACP</h3>
                <p class="text-xs text-purple-600 dark:text-purple-400 font-medium mb-2">Axioo Class Program</p>
                <p class="text-sm text-gray-700 dark:text-gray-300">Program kelas industri berkolaborasi langsung dengan Axioo untuk keahlian hardware dan perakitan.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Ekstrakurikuler -->
<section id="eskul" class="mb-8">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-500">
            <i class="ph-fill ph-users-three text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Ekstrakurikuler</h2>
    </div>
    
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @forelse(($eskul ?? []) as $e)
            <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700 text-center">
                <div class="h-32 overflow-hidden">
                    <img src="{{ asset('storage/' . $e->image) }}" 
                         alt="{{ $e->title }}" 
                         class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                </div>
                <div class="p-3">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $e->title }}</h4>
                    @if($e->description)
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $e->description }}</p>
                    @endif
                </div>
            </div>
        @empty
            <!-- Default Eskul -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center mx-auto mb-2">
                    <i class="ph ph-football text-xl text-blue-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-800 dark:text-gray-300">Futsal</span>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 rounded-full bg-orange-500/10 flex items-center justify-center mx-auto mb-2">
                    <i class="ph ph-basketball text-xl text-orange-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-800 dark:text-gray-300">Basket</span>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center mx-auto mb-2">
                    <i class="ph ph-shuttlecock text-xl text-green-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-800 dark:text-gray-300">Badminton</span>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 rounded-full bg-red-500/10 flex items-center justify-center mx-auto mb-2">
                    <i class="ph ph-volleyball text-xl text-red-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-800 dark:text-gray-300">Volly</span>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 rounded-full bg-indigo-500/10 flex items-center justify-center mx-auto mb-2">
                    <i class="ph ph-users text-xl text-indigo-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-800 dark:text-gray-300">OSIS</span>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 rounded-full bg-cyan-500/10 flex items-center justify-center mx-auto mb-2">
                    <i class="ph ph-laptop text-xl text-cyan-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-800 dark:text-gray-300">Andro IT</span>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 rounded-full bg-rose-500/10 flex items-center justify-center mx-auto mb-2">
                    <i class="ph ph-heart text-xl text-rose-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-800 dark:text-gray-300">PMR</span>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 rounded-full bg-amber-500/10 flex items-center justify-center mx-auto mb-2">
                    <i class="ph ph-tree text-xl text-amber-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-800 dark:text-gray-300">Pramuka</span>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 text-center hover:shadow-xl transition-all duration-300 hover:-translate-y-1 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="w-12 h-12 rounded-full bg-red-500/10 flex items-center justify-center mx-auto mb-2">
                    <i class="ph ph-flag text-xl text-red-500"></i>
                </div>
                <span class="text-sm font-medium text-gray-800 dark:text-gray-300">Paskibra</span>
            </div>
        @endforelse
    </div>
</section>

<!-- Gallery Kegiatan -->
<section id="gallery" class="mb-8">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-lg bg-pink-500/10 flex items-center justify-center text-pink-500">
            <i class="ph-fill ph-images text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Gallery Kegiatan</h2>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @forelse(($galleries ?? []) as $gallery)
            <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden group hover:shadow-xl transition-all duration-300 shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="relative aspect-w-16 aspect-h-9 overflow-hidden">
                    <img src="{{ asset('storage/' . $gallery->image) }}" 
                         alt="{{ $gallery->title }}" 
                         class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                        <div class="text-white">
                            <h4 class="font-bold text-sm">{{ $gallery->title }}</h4>
                            @if($gallery->description)
                                <p class="text-xs text-white/80">{{ $gallery->description }}</p>
                            @endif
                            @if($gallery->category)
                                <span class="inline-block mt-1 text-xs bg-white/20 px-2 py-0.5 rounded-full">{{ $gallery->category }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="p-3">
                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $gallery->title }}</h4>
                    @if($gallery->category)
                        <span class="text-xs text-primary-600 dark:text-primary-400">{{ $gallery->category }}</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <i class="ph ph-images text-5xl text-gray-300 dark:text-gray-600 block mb-3"></i>
                <p class="text-gray-500 dark:text-gray-400">Belum ada foto gallery. Admin akan segera menambahkan.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Lokasi -->
<section id="alamat" class="mb-8">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-lg bg-red-500/10 flex items-center justify-center text-red-500">
            <i class="ph-fill ph-map-pin text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Lokasi Kami</h2>
    </div>
    
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 grid grid-cols-1 md:grid-cols-2 gap-6 shadow-lg border border-gray-200 dark:border-gray-700">
        <div class="space-y-4">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="ph-fill ph-buildings text-primary-500"></i>
                SMK Harapan Bangsa
            </h3>
            <div class="flex items-start gap-3 text-gray-800 dark:text-gray-300">
                <i class="ph ph-map-pin text-primary-500 text-xl mt-1"></i>
                <span>Jl. Pendidikan No. 123, Kelurahan Maju Jaya, Kecamatan Sukses Makmur, Kota Harapan, 40123</span>
            </div>
            <div class="flex items-center gap-3 text-gray-800 dark:text-gray-300">
                <i class="ph ph-phone text-primary-500 text-xl"></i>
                <span>(022) 1234567</span>
            </div>
            <div class="flex items-center gap-3 text-gray-800 dark:text-gray-300">
                <i class="ph ph-envelope text-primary-500 text-xl"></i>
                <span>info@smkharapanbangsa.sch.id</span>
            </div>
            <div class="flex items-center gap-3 text-gray-800 dark:text-gray-300">
                <i class="ph ph-globe text-primary-500 text-xl"></i>
                <span>www.smkharapanbangsa.sch.id</span>
            </div>
        </div>
        <div class="h-48 bg-gray-200 dark:bg-gray-700 rounded-xl flex items-center justify-center relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-500/10 to-primary-500/5 flex items-center justify-center">
                <div class="text-center">
                    <i class="ph ph-map-pin text-5xl text-primary-500/50 block mb-2"></i>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-400">Lokasi Sekolah</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500">Google Maps (Placeholder)</p>
                </div>
            </div>
        </div>
    </div>
</section>

@if(auth()->user()->role == 'admin')
    <div class="text-center mt-6">
        <a href="{{ route('gallery.manage') }}" class="inline-flex items-center px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-lg shadow-primary-500/20 hover:shadow-primary-500/30">
            <i class="ph ph-images mr-2"></i>
            Kelola Gallery (Kegiatan, Jurusan, Eskul)
        </a>
    </div>
@endif
@endsection

@push('styles')
<style>
    .aspect-w-16 {
        position: relative;
        padding-bottom: 56.25%;
    }
    .aspect-w-16 img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endpush