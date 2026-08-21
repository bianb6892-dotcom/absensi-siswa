@extends(auth()->check() && auth()->user()->role == 'ortu' ? 'layouts.ortu' : 'layouts.app')

@section('title', 'Gallery Sekolah')

@section('content')
@if(auth()->check() && auth()->user()->role == 'ortu')
    <!-- INTRO MOBILE -->
    <div class="rounded-2xl bg-blue-700 p-6 text-white shadow-lg shadow-blue-900/20">
        <div class="flex items-center gap-4">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/20">
                <i class="ph-fill ph-images text-2xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-extrabold">Gallery Sekolah</h2>
                <p class="text-sm font-medium text-blue-100">Momen kegiatan anak-anak</p>
            </div>
        </div>
    </div>
@else
    <!-- HERO DESKTOP -->
    <div class="mb-10 rounded-2xl border border-slate-200 bg-white p-8 sm:p-12 text-center shadow-card">
        <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-700 text-white shadow-lg shadow-blue-700/30">
            <i class="ph-fill ph-buildings text-3xl"></i>
        </div>
        <h1 class="mb-3 text-3xl font-extrabold tracking-tight text-slate-900 md:text-4xl">
            Selamat Datang di Portal Sekolah
        </h1>
        <p class="mx-auto max-w-2xl text-base text-slate-600">
            {{ $informasi['visi'] ?? 'Membentuk generasi unggul, kreatif, dan berakhlak mulia melalui pendidikan yang berkualitas dan inovatif.' }}
        </p>
    </div>
@endif

<!-- ============================================ -->
<!-- GALLERY KEGIATAN                            -->
<!-- ============================================ -->
<div class="mt-8">
    <div class="mb-4 flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
            <i class="ph-fill ph-calendar-check text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900">Gallery Kegiatan</h2>
            @if(auth()->check() && auth()->user()->role == 'ortu')
                <p class="text-xs text-slate-400">Foto dokumentasi kegiatan sekolah</p>
            @endif
        </div>
    </div>
    @if(isset($kegiatan) && $kegiatan->count() > 0)
        <div class="grid grid-cols-3 gap-2 sm:grid-cols-4 md:gap-3 lg:grid-cols-5">
            @foreach($kegiatan as $item)
                <a href="{{ asset('storage/' . $item->image) }}" target="_blank"
                    class="group relative block overflow-hidden rounded-xl bg-slate-100 shadow-sm ring-1 ring-slate-200/60 transition-all active:scale-[0.96] sm:rounded-xl sm:ring-0 sm:shadow-md">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" loading="lazy"
                        class="aspect-square h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="pointer-events-none absolute inset-0 bg-slate-900/50 opacity-100 sm:opacity-0 sm:transition-opacity sm:duration-300 sm:group-hover:opacity-100"></div>
                    <div class="pointer-events-none absolute inset-x-0 bottom-0 p-2 sm:opacity-0 sm:transition-opacity sm:duration-300 sm:group-hover:opacity-100">
                        <p class="truncate text-[11px] font-bold leading-snug text-white drop-shadow sm:text-xs">{{ $item->title }}</p>
                        @if($item->category)
                            <p class="text-[9px] font-semibold text-blue-100 sm:text-[10px]">{{ $item->category }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="card p-10 text-center text-slate-400">
            <i class="ph ph-images text-4xl block mb-2 text-slate-200"></i>
            Belum ada foto kegiatan.
        </div>
    @endif
</div>

<!-- ============================================ -->
<!-- GALLERY JURUSAN                             -->
<!-- ============================================ -->
<div class="mt-8">
    <div class="mb-4 flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
            <i class="ph-fill ph-buildings text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900">Gallery Jurusan</h2>
            @if(auth()->check() && auth()->user()->role == 'ortu')
                <p class="text-xs text-slate-400">Profil jurusan &amp; kompetensi keahlian</p>
            @endif
        </div>
    </div>
    @if(isset($jurusan) && $jurusan->count() > 0)
        <div class="grid grid-cols-3 gap-2 sm:grid-cols-4 md:gap-3 lg:grid-cols-5">
            @foreach($jurusan as $item)
                <a href="{{ asset('storage/' . $item->image) }}" target="_blank"
                    class="group relative block overflow-hidden rounded-xl bg-slate-100 shadow-sm ring-1 ring-slate-200/60 transition-all active:scale-[0.96] sm:rounded-xl sm:ring-0 sm:shadow-md">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" loading="lazy"
                        class="aspect-square h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="pointer-events-none absolute inset-0 bg-slate-900/50 opacity-100 sm:opacity-0 sm:transition-opacity sm:duration-300 sm:group-hover:opacity-100"></div>
                    <div class="pointer-events-none absolute inset-x-0 bottom-0 p-2 sm:opacity-0 sm:transition-opacity sm:duration-300 sm:group-hover:opacity-100">
                        <p class="truncate text-[11px] font-bold leading-snug text-white drop-shadow sm:text-xs">{{ $item->title }}</p>
                        @if($item->category)
                            <p class="text-[9px] font-semibold text-slate-100 sm:text-[10px]">{{ $item->category }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="card p-10 text-center text-slate-400">
            <i class="ph ph-images text-4xl block mb-2 text-slate-200"></i>
            Belum ada foto jurusan.
        </div>
    @endif
</div>

<!-- ============================================ -->
<!-- GALLERY EKSKUL                              -->
<!-- ============================================ -->
<div class="mt-8">
    <div class="mb-4 flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
            <i class="ph-fill ph-football text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900">Gallery Ekstrakurikuler</h2>
            @if(auth()->check() && auth()->user()->role == 'ortu')
                <p class="text-xs text-slate-400">Kegiatan pengembangan bakat siswa</p>
            @endif
        </div>
    </div>
    @if(isset($eskul) && $eskul->count() > 0)
        <div class="grid grid-cols-3 gap-2 sm:grid-cols-4 md:gap-3 lg:grid-cols-5">
            @foreach($eskul as $item)
                <a href="{{ asset('storage/' . $item->image) }}" target="_blank"
                    class="group relative block overflow-hidden rounded-xl bg-slate-100 shadow-sm ring-1 ring-slate-200/60 transition-all active:scale-[0.96] sm:rounded-xl sm:ring-0 sm:shadow-md">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" loading="lazy"
                        class="aspect-square h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <div class="pointer-events-none absolute inset-0 bg-slate-900/50 opacity-100 sm:opacity-0 sm:transition-opacity sm:duration-300 sm:group-hover:opacity-100"></div>
                    <div class="pointer-events-none absolute inset-x-0 bottom-0 p-2 sm:opacity-0 sm:transition-opacity sm:duration-300 sm:group-hover:opacity-100">
                        <p class="truncate text-[11px] font-bold leading-snug text-white drop-shadow sm:text-xs">{{ $item->title }}</p>
                        @if($item->category)
                            <p class="text-[9px] font-semibold text-blue-100 sm:text-[10px]">{{ $item->category }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="card p-10 text-center text-slate-400">
            <i class="ph ph-images text-4xl block mb-2 text-slate-200"></i>
            Belum ada foto ekstrakurikuler.
        </div>
    @endif
</div>

<!-- ============================================ -->
<!-- TOMBOL KELOLA GALLERY (ADMIN & GURU)        -->
<!-- ============================================ -->
@if(auth()->check() && (auth()->user()->role == 'admin' || auth()->user()->role == 'guru'))
    <div class="mt-10 text-center">
        <a href="{{ route('gallery.manage') }}" class="btn btn-primary">
            <i class="ph ph-images text-lg"></i>
            Kelola Gallery (Kegiatan, Jurusan, Eskul)
        </a>
    </div>
@endif
@endsection