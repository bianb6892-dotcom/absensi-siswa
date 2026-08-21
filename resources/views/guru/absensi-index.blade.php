@extends('layouts.app')

@section('title', 'Daftar Absensi')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Daftar Absensi</h1>
        <p class="mt-1 text-sm text-slate-500">Semua data absensi siswa per kelas.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-2.5 shadow-sm">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <i class="ph ph-graduation-cap text-xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Kelas</p>
                <p class="text-sm font-bold text-slate-900">{{ $kelasTerpilih ?? 'Pilih Kelas' }}</p>
            </div>
        </div>
        <a href="{{ route('guru.absensi.create') }}" class="btn btn-primary">
            <i class="ph ph-plus-circle text-lg"></i>
            Input Absensi
        </a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/60 px-6 py-4">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <i class="ph-fill ph-list-checks"></i>
            </div>
            <h2 class="text-base font-bold text-slate-900">Data Absensi - {{ $kelasTerpilih ?? 'Semua Kelas' }}</h2>
        </div>
        <select id="filter_kelas" class="input cursor-pointer">
            <optgroup label="Kelas X">
                <option value="X PPLG" {{ ($kelasTerpilih ?? '') == 'X PPLG' ? 'selected' : '' }}>X PPLG</option>
                <option value="X TJKT" {{ ($kelasTerpilih ?? '') == 'X TJKT' ? 'selected' : '' }}>X TJKT</option>
                <option value="X ACP" {{ ($kelasTerpilih ?? '') == 'X ACP' ? 'selected' : '' }}>X ACP</option>
                <option value="X AKL" {{ ($kelasTerpilih ?? '') == 'X AKL' ? 'selected' : '' }}>X AKL</option>
            </optgroup>
            <optgroup label="Kelas XI">
                <option value="XI PPLG" {{ ($kelasTerpilih ?? '') == 'XI PPLG' ? 'selected' : '' }}>XI PPLG</option>
                <option value="XI TJKT" {{ ($kelasTerpilih ?? '') == 'XI TJKT' ? 'selected' : '' }}>XI TJKT</option>
                <option value="XI ACP" {{ ($kelasTerpilih ?? '') == 'XI ACP' ? 'selected' : '' }}>XI ACP</option>
                <option value="XI AKL" {{ ($kelasTerpilih ?? '') == 'XI AKL' ? 'selected' : '' }}>XI AKL</option>
            </optgroup>
            <optgroup label="Kelas XII">
                <option value="XII PPLG" {{ ($kelasTerpilih ?? '') == 'XII PPLG' ? 'selected' : '' }}>XII PPLG</option>
                <option value="XII TJKT" {{ ($kelasTerpilih ?? '') == 'XII TJKT' ? 'selected' : '' }}>XII TJKT</option>
                <option value="XII ACP" {{ ($kelasTerpilih ?? '') == 'XII ACP' ? 'selected' : '' }}>XII ACP</option>
                <option value="XII AKL" {{ ($kelasTerpilih ?? '') == 'XII AKL' ? 'selected' : '' }}>XII AKL</option>
            </optgroup>
        </select>
        <form method="GET" action="{{ route('guru.absensi.export') }}" class="flex flex-wrap items-center gap-2">
            <input type="hidden" name="kelas" value="{{ $kelasTerpilih ?? '' }}">
            <input type="month" name="bulan" value="{{ now()->format('Y-m') }}" class="input !w-auto">
            <button type="submit" class="btn btn-outline">
                <i class="ph ph-download-simple text-lg"></i>
                Export
            </button>
        </form>
    </div>
    <div class="p-6">
        <div class="table-wrapper">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-l-xl bg-slate-50">No</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Tanggal</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Siswa</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Kelas</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-r-xl bg-slate-50">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($absensis as $key => $a)
                    <tr class="transition-colors hover:bg-slate-50/70">
                        <td class="px-4 py-3.5 whitespace-nowrap text-sm text-slate-500">{{ $absensis->firstItem() + $key }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::parse($a->tanggal)->format('d F Y') }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-700 text-white font-bold text-xs">
                                    {{ strtoupper(substr($a->user->name, 0, 2)) }}
                                </div>
                                <span class="ml-3 text-sm font-medium text-slate-800">{{ $a->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                {{ $a->kelas }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            @if($a->keterangan == 'hadir')
                                <span class="badge-hadir inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-bold">
                                    <i class="ph-fill ph-check-circle"></i> Hadir
                                </span>
                            @elseif($a->keterangan == 'ijin')
                                <span class="badge-ijin inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-bold">
                                    <i class="ph-fill ph-envelope"></i> Ijin
                                </span>
                            @elseif($a->keterangan == 'sakit')
                                <span class="badge-sakit inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-bold">
                                    <i class="ph-fill ph-first-aid"></i> Sakit
                                </span>
                            @else
                                <span class="badge-tidak inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-bold">
                                    <i class="ph-fill ph-x-circle"></i> Tidak Masuk
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-slate-400">
                            <i class="ph ph-clipboard-text text-5xl block mb-3 text-slate-200"></i>
                            Belum ada data absensi untuk kelas ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">
            {{ $absensis->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#filter_kelas').on('change', function() {
        const kelas = $(this).val();
        window.location.href = "{{ url('/guru/absensi') }}?kelas=" + encodeURIComponent(kelas);
    });
});
</script>
@endpush
@endsection