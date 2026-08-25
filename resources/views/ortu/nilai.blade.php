@extends('layouts.ortu')

@section('title', 'Nilai Anak')

@php
    $predikatCls = function ($label) {
        return match ($label) {
            'A' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'B' => 'bg-green-50 text-green-700 border border-green-200',
            'C' => 'bg-lime-50 text-lime-700 border border-lime-200',
            'D' => 'bg-green-100 text-green-800 border border-green-300',
            default => 'bg-blue-50 text-blue-700 border border-blue-200',
        };
    };
@endphp

@section('content')
<div class="mb-4 flex items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-extrabold text-slate-900">Nilai Anak</h1>
        <p class="text-sm text-slate-500">Pantau nilai setengah & akhir semester</p>
    </div>
    <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 shadow-card">
        <i class="ph ph-calendar-dots text-blue-600"></i>
        <select onchange="window.location.href='?tahun=' + encodeURIComponent(this.value)"
            class="bg-transparent text-sm font-bold text-slate-900 outline-none">
            @foreach($daftarTahunAjaran as $ta)
                <option value="{{ $ta }}" @selected($tahunAjaran == $ta)>{{ $ta }}</option>
            @endforeach
        </select>
    </div>
</div>

@forelse($anak as $siswa)
    <div class="card overflow-hidden mb-5">
        <div class="flex items-center gap-3 border-b border-slate-100 bg-white px-5 py-4">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-700 text-sm font-bold text-white">
                {{ strtoupper(substr($siswa->name, 0, 2)) }}
            </div>
            <div class="min-w-0">
                <h2 class="truncate text-base font-extrabold text-slate-900">{{ $siswa->name }}</h2>
                <p class="text-xs text-slate-500">NIS: {{ $siswa->nis ?? '-' }} · Kelas {{ $siswa->kelas }}</p>
            </div>
        </div>

        @php
            $setengah = $nilai[$siswa->id]['setengah_semester'] ?? collect();
            $akhir = $nilai[$siswa->id]['akhir_semester'] ?? collect();
        @endphp

        <div class="p-5">
            <h3 class="mb-3 flex items-center gap-2 text-sm font-bold text-slate-900">
                <i class="ph-fill ph-book-open text-blue-600"></i> Nilai Setengah Semester
            </h3>

            @if($setengah->count() > 0)
                <div class="overflow-x-auto rounded-xl border border-slate-100 shadow-sm">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Mata Pelajaran</th>
                                <th class="px-4 py-2.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">Nilai</th>
                                <th class="px-4 py-2.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">Predikat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($setengah as $n)
                                <tr>
                                    <td class="px-4 py-2.5 font-semibold text-slate-700">{{ $n->mata_pelajaran }}</td>
                                    <td class="px-4 py-2.5 text-center font-extrabold text-slate-900">{{ number_format((float) $n->nilai, 0) }}</td>
                                    <td class="px-4 py-2.5 text-center">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold {{ $predikatCls($n->predikat['label']) }}" title="{{ $n->predikat['keterangan'] }}">
                                            {{ $n->predikat['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="rounded-xl border-2 border-dashed border-slate-200 px-4 py-6 text-center">
                    <i class="ph ph-book-open-text text-3xl text-slate-200 block mb-2"></i>
                    <p class="text-xs text-slate-400">Belum ada nilai setengah semester</p>
                </div>
            @endif

            <h3 class="mb-3 mt-5 flex items-center gap-2 text-sm font-bold text-slate-900">
                <i class="ph-fill ph-books text-blue-600"></i> Nilai Akhir Semester
            </h3>

            @if($akhir->count() > 0)
                <div class="overflow-x-auto rounded-xl border border-slate-100 shadow-sm">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-4 py-2.5 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Mata Pelajaran</th>
                                <th class="px-4 py-2.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">Nilai</th>
                                <th class="px-4 py-2.5 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">Predikat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($akhir as $n)
                                <tr>
                                    <td class="px-4 py-2.5 font-semibold text-slate-700">{{ $n->mata_pelajaran }}</td>
                                    <td class="px-4 py-2.5 text-center font-extrabold text-slate-900">{{ number_format((float) $n->nilai, 0) }}</td>
                                    <td class="px-4 py-2.5 text-center">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold {{ $predikatCls($n->predikat['label']) }}" title="{{ $n->predikat['keterangan'] }}">
                                            {{ $n->predikat['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="rounded-xl border-2 border-dashed border-slate-200 px-4 py-6 text-center">
                    <i class="ph ph-books text-3xl text-slate-200 block mb-2"></i>
                    <p class="text-xs text-slate-400">Belum ada nilai akhir semester</p>
                </div>
            @endif
        </div>
    </div>
@empty
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
        <i class="ph ph-user-minus text-5xl text-slate-200 block mb-3"></i>
        <p class="text-sm font-semibold text-slate-500">Belum ada anak terdaftar</p>
    </div>
@endforelse
@endsection