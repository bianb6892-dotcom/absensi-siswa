@extends('layouts.app')

@section('title', 'Daftar Absensi')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i class="ph-fill ph-list-checks text-primary-500"></i>
            Daftar Absensi
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">Semua data absensi siswa.</p>
    </div>
    <div class="flex items-center gap-3">
        <div class="bg-white dark:bg-gray-800 px-4 py-2 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex items-center gap-3">
            <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-md text-primary-500">
                <i class="ph ph-graduation-cap text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Kelas</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $kelasTerpilih ?? 'Pilih Kelas' }}</p>
            </div>
        </div>
        <a href="{{ route('absensi.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
            <i class="ph ph-plus-circle mr-2"></i>
            Input Absensi
        </a>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="p-5 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Data Absensi - {{ $kelasTerpilih ?? 'Semua Kelas' }}</h2>
            <div class="flex gap-2">
                <select id="filter_kelas" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
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
            </div>
        </div>
    </div>
    <div class="p-5">
        @if(session('success'))
            <div class="mb-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg flex items-center gap-3">
                <i class="ph-fill ph-check-circle text-emerald-500 text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700/50 rounded-xl">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider rounded-l-xl">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Siswa</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Kelas</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider rounded-r-xl">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($absensis as $key => $a)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $absensis->firstItem() + $key }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($a->tanggal)->format('d F Y') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-700 dark:text-primary-300 font-bold text-xs">
                                    {{ strtoupper(substr($a->user->name, 0, 2)) }}
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $a->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                {{ $a->kelas }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($a->keterangan == 'hadir')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                    <i class="ph-fill ph-check-circle mr-1"></i> Hadir
                                </span>
                            @elseif($a->keterangan == 'ijin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                                    <i class="ph-fill ph-envelope mr-1"></i> Ijin
                                </span>
                            @elseif($a->keterangan == 'sakit')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                    <i class="ph-fill ph-first-aid mr-1"></i> Sakit
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300">
                                    <i class="ph-fill ph-x-circle mr-1"></i> Tidak Masuk
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            <i class="ph ph-database text-4xl block mb-2 text-gray-300 dark:text-gray-600"></i>
                            Belum ada data absensi untuk kelas ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $absensis->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#filter_kelas').on('change', function() {
        const kelas = $(this).val();
        window.location.href = "{{ url('/absensi') }}?kelas=" + encodeURIComponent(kelas);
    });
});
</script>
@endpush
@endsection