@extends('layouts.app')

@section('title', 'Absensi Cepat')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Absensi Cepat</h1>
        <p class="mt-1 text-sm text-slate-500">Input kehadiran semua siswa dalam satu klik.</p>
    </div>
    <div class="flex flex-wrap gap-3">
        <form action="{{ route('absensi.cepat.hadirkan') }}" method="POST">
            @csrf
            <input type="hidden" name="kelas" value="{{ $kelasTerpilih }}">
            <button type="submit"
                class="btn btn-outline-blue">
                <i class="ph ph-check-circle text-lg"></i>
                Hadirkan Semua
            </button>
        </form>
        <a href="{{ route('guru.absensi.create') }}" class="btn btn-primary">
            <i class="ph ph-pencil-line text-lg"></i>
            Input Manual
        </a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/60 px-6 py-4">
        <div class="flex flex-wrap items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <i class="ph-fill ph-lightning"></i>
            </div>
            <label for="kelas-select" class="text-sm font-semibold text-slate-600">Pilih Kelas:</label>
            <select id="kelas-select" onchange="window.location.href='?kelas='+this.value" class="input cursor-pointer">
                @foreach(['X PPLG','X TJKT','X ACP','X AKL','XI PPLG','XI TJKT','XI ACP','XI AKL','XII PPLG','XII TJKT','XII ACP','XII AKL'] as $k)
                <option value="{{ $k }}" @selected($kelasTerpilih == $k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2 rounded-xl border border-blue-700 bg-blue-50 px-4 py-2 text-sm font-bold text-blue-700">
            <i class="ph ph-calendar-dots"></i>
            {{ date('d F Y') }}
        </div>
    </div>

    <form action="{{ route('absensi.cepat.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kelas" value="{{ $kelasTerpilih }}">
        <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">

        <div class="table-wrapper p-5">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-l-xl bg-slate-50">No</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Nama Siswa</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-r-xl bg-slate-50">Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswa as $key => $s)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-4 py-3.5 whitespace-nowrap text-sm font-medium text-slate-500">{{ $key + 1 }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-9 w-9 rounded-lg bg-blue-700 flex items-center justify-center text-white font-bold text-xs">
                                    {{ strtoupper(substr($s->name, 0, 2)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-slate-900">{{ $s->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $s->kelas }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex flex-wrap justify-center gap-2">
                                @foreach(['hadir', 'ijin', 'sakit', 'tidak_masuk'] as $status)
                                <label class="cursor-pointer rounded-xl border px-3 py-2 text-xs font-semibold transition-all
                                    @if($s->status_hari_ini == $status || ($s->status_hari_ini == 'belum' && $status == 'hadir'))
                                        @if($status == 'hadir') border-blue-700 bg-blue-700 text-white shadow-sm
                                        @elseif($status == 'ijin') border-amber-500 bg-amber-50 text-amber-700 shadow-sm
                                        @elseif($status == 'sakit') border-sky-500 bg-sky-50 text-sky-700 shadow-sm
                                        @else border-rose-500 bg-rose-50 text-rose-700 shadow-sm @endif
                                    @else border-slate-200 text-slate-500 hover:border-blue-300 hover:bg-white hover:text-blue-600 @endif">
                                    <input type="radio" name="absensi[{{ $s->id }}]" value="{{ $status }}"
                                        @checked($s->status_hari_ini == $status || ($s->status_hari_ini == 'belum' && $status == 'hadir'))
                                        class="hidden">
                                    <span class="flex items-center gap-1.5">
                                        @if($status == 'hadir') <i class="ph-fill ph-check-circle"></i>
                                        @elseif($status == 'ijin') <i class="ph-fill ph-envelope"></i>
                                        @elseif($status == 'sakit') <i class="ph-fill ph-first-aid"></i>
                                        @else <i class="ph-fill ph-x-circle"></i> @endif
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-12 text-center text-slate-400">
                            <i class="ph ph-users-three text-5xl block mb-3 text-slate-200"></i>
                            Belum ada siswa di kelas ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
            <button type="submit" class="btn btn-primary w-full sm:w-auto">
                <i class="ph ph-floppy-disk text-lg"></i>
                Simpan Absensi
            </button>
        </div>
    </form>
</div>
@endsection