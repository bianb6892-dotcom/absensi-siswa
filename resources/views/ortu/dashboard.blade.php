@extends('layouts.app')

@section('title', 'Dashboard Orang Tua')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i class="ph-fill ph-users text-primary-500"></i>
            Dashboard Orang Tua
        </h1>
        <p class="text-gray-600 mt-1">Pantau kehadiran anak Anda</p>
    </div>

    @if(empty($data) || count($data) == 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
            <i class="ph ph-user-circle text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Belum ada data anak yang terdaftar.</p>
        </div>
    @else
        <!-- ============================================ -->
        <!-- CARD ANAK (TIDAK DIUBAH)                     -->
        <!-- ============================================ -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($data as $item)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-sm">
                                    {{ strtoupper(substr($item['siswa']->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $item['siswa']->name }}</h3>
                                    <p class="text-xs text-gray-500">NIS: {{ $item['siswa']->nis ?? '-' }} | Kelas:
                                        {{ $item['siswa']->kelas }}</p>
                                </div>
                            </div>
                            @if($item['hari_ini'])
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($item['hari_ini']->keterangan == 'hadir') bg-emerald-100 text-emerald-700
                                        @elseif($item['hari_ini']->keterangan == 'ijin') bg-amber-100 text-amber-700
                                        @elseif($item['hari_ini']->keterangan == 'sakit') bg-blue-100 text-blue-700
                                        @else bg-rose-100 text-rose-700 @endif">
                                    {{ ucfirst($item['hari_ini']->keterangan) }}
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    Belum Absen
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-4 gap-2 text-center">
                            <div class="bg-emerald-50 rounded-lg p-2">
                                <p class="text-lg font-bold text-emerald-600">{{ $item['hadir'] }}</p>
                                <p class="text-xs text-gray-500">Hadir</p>
                            </div>
                            <div class="bg-amber-50 rounded-lg p-2">
                                <p class="text-lg font-bold text-amber-600">{{ $item['ijin'] }}</p>
                                <p class="text-xs text-gray-500">Izin</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-2">
                                <p class="text-lg font-bold text-blue-600">{{ $item['sakit'] }}</p>
                                <p class="text-xs text-gray-500">Sakit</p>
                            </div>
                            <div class="bg-rose-50 rounded-lg p-2">
                                <p class="text-lg font-bold text-rose-600">{{ $item['alpa'] }}</p>
                                <p class="text-xs text-gray-500">Alpa</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('ortu.anak', $item['siswa']->id) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                <i class="ph ph-eye mr-2"></i>
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ============================================ -->
        <!-- TABEL REKAP BULANAN (RESPONSIF)              -->
        <!-- ============================================ -->
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">📊 Rekap Kehadiran Bulanan</h2>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-3 sm:p-5">
                    <!-- DESKTOP: Tabel normal -->
                    <div class="hidden md:block">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700/50 rounded-xl">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider rounded-l-xl">
                                        No.</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                        Bulan</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                        Hadir</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                        Izin</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider rounded-r-xl">
                                        Sakit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @php
                                    $firstChild = $data[0]['siswa'] ?? null;
                                @endphp
                                @foreach($bulanList as $bulan)
                                    @php
                                        $hadir = 0;
                                        $ijin = 0;
                                        $sakit = 0;
                                        if ($firstChild && isset($rekapBulanan[$firstChild->id][$bulan])) {
                                            $hadir = $rekapBulanan[$firstChild->id][$bulan]['hadir'];
                                            $ijin = $rekapBulanan[$firstChild->id][$bulan]['ijin'];
                                            $sakit = $rekapBulanan[$firstChild->id][$bulan]['sakit'];
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                            {{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $bulan }}</td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                            {{ $hadir }}</td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium text-amber-600 dark:text-amber-400">
                                            {{ $ijin }}</td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium text-blue-600 dark:text-blue-400">
                                            {{ $sakit }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- MOBILE: Card/Block (tanpa overflow) -->
                    <!-- MOBILE: Card/Block (tanpa nomor) -->
                    <div class="md:hidden space-y-3">
                        @php
                            $firstChild = $data[0]['siswa'] ?? null;
                        @endphp
                        @foreach($bulanList as $bulan)
                            @php
                                $hadir = 0;
                                $ijin = 0;
                                $sakit = 0;
                                $alpa = 0;
                                if ($firstChild && isset($rekapBulanan[$firstChild->id][$bulan])) {
                                    $hadir = $rekapBulanan[$firstChild->id][$bulan]['hadir'];
                                    $ijin = $rekapBulanan[$firstChild->id][$bulan]['ijin'];
                                    $sakit = $rekapBulanan[$firstChild->id][$bulan]['sakit'];
                                    $alpa = $rekapBulanan[$firstChild->id][$bulan]['alpa'];
                                }
                            @endphp
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $bulan }}</span>
                                    <div class="flex gap-2 text-xs">
                                        <span class="font-medium text-emerald-600 dark:text-emerald-400">hadir {{ $hadir }}</span>
                                        <span class="font-medium text-amber-600 dark:text-amber-400">ijin {{ $ijin }}</span>
                                        <span class="font-medium text-blue-600 dark:text-blue-400">sakit {{ $sakit }}</span>
                                        <span class="font-medium text-rose-600 dark:text-rose-400">alpa {{ $alpa }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- GALLERY PREVIEW (3 CARD + 1 TOMBOL)          -->
        <!-- ============================================ -->
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">🖼️ Gallery Sekolah</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- CARD GALLERY KEGIATAN -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <div
                        class="p-4 bg-gradient-to-r from-blue-500/10 to-blue-500/5 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">📸</span>
                            <h3 class="font-bold text-gray-900 dark:text-white">Gallery Kegiatan</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        @if(isset($kegiatan) && $kegiatan->count() > 0)
                            <div class="space-y-3">
                                @foreach($kegiatan->take(3) as $item)
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                            class="w-14 h-14 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $item->title }}</h4>
                                            @if($item->category)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item->category }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                @if($kegiatan->count() > 3)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">+{{ $kegiatan->count() - 3 }} foto lainnya</p>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada foto kegiatan.</p>
                        @endif
                    </div>
                </div>

                <!-- CARD GALLERY JURUSAN -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <div
                        class="p-4 bg-gradient-to-r from-emerald-500/10 to-emerald-500/5 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">🏫</span>
                            <h3 class="font-bold text-gray-900 dark:text-white">Gallery Jurusan</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        @if(isset($jurusan) && $jurusan->count() > 0)
                            <div class="space-y-3">
                                @foreach($jurusan->take(3) as $item)
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                            class="w-14 h-14 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $item->title }}</h4>
                                            @if($item->category)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item->category }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                @if($jurusan->count() > 3)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">+{{ $jurusan->count() - 3 }} foto lainnya</p>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada foto jurusan.</p>
                        @endif
                    </div>
                </div>

                <!-- CARD GALLERY EKSKUL -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <div
                        class="p-4 bg-gradient-to-r from-purple-500/10 to-purple-500/5 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">⚽</span>
                            <h3 class="font-bold text-gray-900 dark:text-white">Gallery Ekstrakurikuler</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        @if(isset($eskul) && $eskul->count() > 0)
                            <div class="space-y-3">
                                @foreach($eskul->take(3) as $item)
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                            class="w-14 h-14 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $item->title }}</h4>
                                            @if($item->category)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item->category }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                @if($eskul->count() > 3)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">+{{ $eskul->count() - 3 }} foto lainnya</p>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada foto ekstrakurikuler.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- 1 TOMBOL LIHAT SELENGKAPNYA (BAWAH CARD)     -->
            <!-- ============================================ -->
            <div class="text-center mt-6">
                <a href="{{ route('school.dashboard') }}"
                    class="inline-flex items-center px-6 py-3 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors shadow-md hover:shadow-lg">
                    <i class="ph ph-images mr-2"></i>
                    Lihat Selengkapnya Gallery
                    <i class="ph ph-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    @endif
@endsection