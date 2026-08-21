@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Dashboard Guru</h1>
            <p class="mt-1 text-sm text-slate-500">
                Selamat datang kembali, <span class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>!
            </p>
        </div>
        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-2.5 shadow-sm">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <i class="ph ph-graduation-cap text-xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Kelas Aktif</p>
                <p class="text-sm font-bold text-slate-900">{{ $kelasTerpilih ?? 'Belum Pilih Kelas' }}</p>
            </div>
        </div>
    </div>

    <!-- Statistik ringkas -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="card p-5 flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <i class="ph-fill ph-check-circle text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-900">{{ collect($rekap)->sum('hadir') }}</p>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Hadir</p>
            </div>
        </div>
        <div class="card p-5 flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <i class="ph-fill ph-envelope text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-900">{{ collect($rekap)->sum('ijin') }}</p>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Ijin</p>
            </div>
        </div>
        <div class="card p-5 flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                <i class="ph-fill ph-first-aid text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-900">{{ collect($rekap)->sum('sakit') }}</p>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Sakit</p>
            </div>
        </div>
        <div class="card p-5 flex items-center gap-4">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                <i class="ph-fill ph-x-circle text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-900">{{ collect($rekap)->sum('tidak') }}</p>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tidak Masuk</p>
            </div>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/60 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <i class="ph-fill ph-chart-bar"></i>
                </div>
                <h2 class="text-base font-bold text-slate-900">Rekap Kehadiran Bulan Ini - {{ $kelasTerpilih ?? 'Semua Kelas' }}</h2>
            </div>
            <a href="{{ route('guru.absensi.create') }}" class="btn btn-primary">
                <i class="ph ph-plus-circle text-lg"></i>
                Input Absensi
            </a>
        </div>
        <div class="table-wrapper p-6">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-l-xl bg-slate-50">No</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Nama Siswa</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Kelas</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Hadir</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Ijin</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Sakit</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-r-xl bg-slate-50">Tidak Masuk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rekap as $key => $r)
                        <tr class="transition-colors hover:bg-slate-50/70">
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm font-medium text-slate-500">{{ $key + 1 }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-700 text-white font-bold text-xs">
                                        {{ strtoupper(substr($r['nama'], 0, 2)) }}
                                    </div>
                                    <span class="ml-3 text-sm font-semibold text-slate-900">{{ $r['nama'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                    {{ $r['kelas'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                <span class="badge-hadir inline-flex items-center rounded-full px-3 py-1 text-sm font-bold">{{ $r['hadir'] }}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                <span class="badge-ijin inline-flex items-center rounded-full px-3 py-1 text-sm font-bold">{{ $r['ijin'] }}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                <span class="badge-sakit inline-flex items-center rounded-full px-3 py-1 text-sm font-bold">{{ $r['sakit'] }}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                <span class="badge-tidak inline-flex items-center rounded-full px-3 py-1 text-sm font-bold">{{ $r['tidak'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                                <i class="ph ph-users-three text-5xl block mb-3 text-slate-200"></i>
                                Belum ada data siswa di kelas ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('guru.absensi.create') }}" class="btn btn-primary">
            <i class="ph ph-plus-circle text-lg"></i>
            Input Absensi Hari Ini
        </a>
        <a href="{{ route('guru.absensi.index') }}" class="btn btn-outline">
            <i class="ph ph-list-bullets text-lg"></i>
            Lihat Semua Absensi
        </a>
    </div>
@endsection