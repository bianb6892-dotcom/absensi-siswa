@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="ph-fill ph-gauge text-primary-500"></i>
                Dashboard Guru
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">Selamat datang, {{ auth()->user()->name }}!</p>
        </div>
        <div
            class="bg-white dark:bg-gray-800 px-4 py-2 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex items-center gap-3">
            <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-md text-primary-500">
                <i class="ph ph-graduation-cap text-xl"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Kelas Aktif</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $kelasTerpilih ?? 'Belum Pilih Kelas' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Rekap Kehadiran Bulan Ini -
                    {{ $kelasTerpilih ?? 'Semua Kelas' }}
                </h2>
                <a href="{{ route('guru.absensi.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
                    <i class="ph ph-plus-circle mr-2"></i>
                    Input Absensi
                </a>
            </div>
        </div>
        <div class="table-wrapper p-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700/50 rounded-xl">
                        <th
                            class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider rounded-l-xl">
                            No</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Nama Siswa</th>
                        <th
                            class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Kelas</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Hadir</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Ijin</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Sakit</th>
                        <th
                            class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider rounded-r-xl">
                            Tidak Masuk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($rekap as $key => $r)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-600 dark:text-gray-400">
                                {{ $key + 1 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-700 dark:text-primary-300 font-bold text-xs">
                                        {{ strtoupper(substr($r['nama'], 0, 2)) }}
                                    </div>
                                    <span
                                        class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $r['nama'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    {{ $r['kelas'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                    {{ $r['hadir'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                                    {{ $r['ijin'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                    {{ $r['sakit'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300">
                                    {{ $r['tidak'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                <i class="ph ph-database text-4xl block mb-2 text-gray-300 dark:text-gray-600"></i>
                                Belum ada data siswa di kelas ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('guru.absensi.create') }}"
            class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
            <i class="ph ph-plus-circle mr-2"></i>
            Input Absensi Hari Ini
        </a>
        <a href="{{ route('guru.absensi.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
            <i class="ph ph-list mr-2"></i>
            Lihat Semua Absensi
        </a>
    </div>
@endsection