@extends('layouts.ortu')

@section('title', 'Detail Anak')

@section('content')
    <a href="{{ route('ortu.dashboard') }}"
        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 transition-colors hover:text-blue-700">
        <i class="ph ph-arrow-left text-base"></i>
        Kembali ke Dashboard
    </a>

    <!-- PROFIL ANAK -->
    <div class="mt-4 rounded-2xl bg-blue-700 p-6 text-center text-white shadow-lg shadow-blue-900/20">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-white/10 text-2xl font-extrabold ring-1 ring-white/20">
            {{ strtoupper(substr($siswa->name, 0, 2)) }}
        </div>
        <h1 class="mt-3 text-xl font-extrabold">{{ $siswa->name }}</h1>
        <p class="mt-0.5 text-sm font-medium text-blue-100">
            NIS: {{ $siswa->nis ?? '-' }} · Kelas <span class="font-bold text-white">{{ $siswa->kelas }}</span>
        </p>
        <p class="mt-3 text-[11px] font-bold uppercase tracking-[0.18em] text-blue-100">
            {{ now()->translatedFormat('l, d F Y') }}
        </p>
    </div>

    <!-- STATISTIK -->
    <div class="mt-5 grid grid-cols-2 gap-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-card">
            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i class="ph-fill ph-check-circle text-xl"></i>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $rekap['hadir'] }}</p>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Hadir</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-card">
            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <i class="ph-fill ph-envelope text-xl"></i>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $rekap['ijin'] }}</p>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Izin</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-card">
            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                <i class="ph-fill ph-first-aid text-xl"></i>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $rekap['sakit'] }}</p>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sakit</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-card">
            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                <i class="ph-fill ph-x-circle text-xl"></i>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $rekap['alpa'] }}</p>
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Alpa</p>
        </div>
    </div>

    <!-- RIWAYAT -->
    <div class="mt-6">
        <div class="mb-3 flex items-center gap-3 px-1">
            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                <i class="ph-fill ph-clock-counter-clockwise text-xl"></i>
            </div>
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Riwayat Absensi</h2>
                <p class="text-xs text-slate-400">{{ $absensis->count() }} catatan kehadiran</p>
            </div>
        </div>

        @forelse($absensis as $a)
            <div class="mb-3 flex items-center gap-3.5 rounded-2xl border border-slate-200/60 bg-white p-4 shadow-card">
                <div class="flex h-12 w-12 shrink-0 flex-col items-center justify-center rounded-xl bg-slate-50 ring-1 ring-slate-100">
                    <span class="text-base font-extrabold leading-none text-slate-900">{{ \Carbon\Carbon::parse($a->tanggal)->format('d') }}</span>
                    <span class="text-[9px] font-bold uppercase text-slate-400">{{ \Carbon\Carbon::parse($a->tanggal)->format('M') }}</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('l, d F Y') }}</p>
                    <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($a->tanggal)->diffForHumans() }}</p>
                </div>
                @if($a->keterangan == 'hadir')
                    <span class="badge-hadir shrink-0 inline-flex items-center gap-1 rounded-full px-3 py-1.5 text-xs font-bold">
                        <i class="ph-fill ph-check-circle"></i> Hadir
                    </span>
                @elseif($a->keterangan == 'ijin')
                    <span class="badge-ijin shrink-0 inline-flex items-center gap-1 rounded-full px-3 py-1.5 text-xs font-bold">
                        <i class="ph-fill ph-envelope"></i> Izin
                    </span>
                @elseif($a->keterangan == 'sakit')
                    <span class="badge-sakit shrink-0 inline-flex items-center gap-1 rounded-full px-3 py-1.5 text-xs font-bold">
                        <i class="ph-fill ph-first-aid"></i> Sakit
                    </span>
                @else
                    <span class="badge-tidak shrink-0 inline-flex items-center gap-1 rounded-full px-3 py-1.5 text-xs font-bold">
                        <i class="ph-fill ph-x-circle"></i> Tidak Masuk
                    </span>
                @endif
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white/60 p-10 text-center">
                <i class="ph ph-clipboard-text text-5xl text-slate-200"></i>
                <p class="mt-3 text-sm font-semibold text-slate-400">Belum ada data absensi</p>
            </div>
        @endforelse
    </div>
@endsection