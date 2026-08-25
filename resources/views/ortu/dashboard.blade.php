@extends('layouts.ortu')

@section('title', 'Dashboard Orang Tua')

@section('content')
    @if(empty($data) || count($data) == 0)
        <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-card">
            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-blue-50">
                <i class="ph-fill ph-user-circle text-5xl text-blue-300"></i>
            </div>
            <p class="font-bold text-slate-700">Belum ada data anak yang terdaftar.</p>
            <p class="mt-1 text-sm text-slate-400">Hubungi admin sekolah untuk menautkan data anak Anda.</p>
        </div>
    @else
        <!-- HERO -->
        <div class="rounded-2xl bg-blue-700 p-6 text-white shadow-lg shadow-blue-900/20">
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-blue-100">{{ now()->translatedFormat('l, d F Y') }}</p>
            <h2 class="mt-1.5 text-2xl font-extrabold leading-snug">Pantau Kehadiran<br>Anak Anda</h2>
            <div class="mt-4 flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold">
                    <i class="ph-fill ph-student text-sm"></i>
                    {{ count($data) }} anak terdaftar
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold text-white">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-200"></span>
                    Real-time
                </span>
            </div>
        </div>

        <!-- CARD ANAK -->
        <div class="mt-6 space-y-5">
            @foreach($data as $item)
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
                    <div class="flex items-center justify-between gap-3 bg-white p-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-700 text-lg font-extrabold text-white">
                                {{ strtoupper(substr($item['siswa']->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="truncate text-base font-extrabold text-slate-900">{{ $item['siswa']->name }}</h3>
                                <p class="truncate text-xs font-medium text-slate-400">
                                    NIS: {{ $item['siswa']->nis ?? '-' }} · Kelas {{ $item['siswa']->kelas }}
                                </p>
                            </div>
                        </div>
                        @if($item['hari_ini'])
                            <span class="shrink-0 inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs font-bold
                                @if($item['hari_ini']->keterangan == 'hadir') badge-hadir
                                @elseif($item['hari_ini']->keterangan == 'ijin') badge-ijin
                                @elseif($item['hari_ini']->keterangan == 'sakit') badge-sakit
                                @else badge-tidak @endif">
                                <span class="h-1.5 w-1.5 rounded-full
                                    @if($item['hari_ini']->keterangan == 'hadir') bg-blue-700
                                    @elseif($item['hari_ini']->keterangan == 'ijin') bg-amber-500
                                    @elseif($item['hari_ini']->keterangan == 'sakit') bg-sky-500
                                    @else bg-rose-500 @endif"></span>
                                {{ ucfirst($item['hari_ini']->keterangan) }}
                            </span>
                        @else
                            <span class="shrink-0 inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                                Belum Absen
                            </span>
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="grid grid-cols-4 gap-2.5">
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-2 sm:p-3 text-center">
                                <i class="ph-fill ph-check-circle text-base text-blue-600"></i>
                                <p class="mt-0.5 text-lg sm:text-xl font-extrabold text-slate-900">{{ $item['hadir'] }}</p>
                                <p class="text-[11px] font-bold text-slate-400">Hadir</p>
                            </div>
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-2 sm:p-3 text-center">
                                <i class="ph-fill ph-envelope text-base text-amber-500"></i>
                                <p class="mt-0.5 text-lg sm:text-xl font-extrabold text-slate-900">{{ $item['ijin'] }}</p>
                                <p class="text-[11px] font-bold text-slate-400">Izin</p>
                            </div>
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-2 sm:p-3 text-center">
                                <i class="ph-fill ph-first-aid text-base text-sky-500"></i>
                                <p class="mt-0.5 text-lg sm:text-xl font-extrabold text-slate-900">{{ $item['sakit'] }}</p>
                                <p class="text-[11px] font-bold text-slate-400">Sakit</p>
                            </div>
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-2 sm:p-3 text-center">
                                <i class="ph-fill ph-x-circle text-base text-rose-500"></i>
                                <p class="mt-0.5 text-lg sm:text-xl font-extrabold text-slate-900">{{ $item['alpa'] }}</p>
                                <p class="text-[11px] font-bold text-slate-400">Alpa</p>
                            </div>
                        </div>
                        @if(isset($item['tunggakan']) && $item['tunggakan']->count() > 0)
                            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <i class="ph-fill ph-warning-circle text-base text-amber-600"></i>
                                    <p class="text-sm font-extrabold text-amber-800">
                                        Tunggakan SPP ({{ $item['tunggakan']->count() }} bulan)
                                    </p>
                                    <span class="ml-auto rounded-full bg-amber-500 px-3 py-1 text-xs font-bold text-white">
                                        Rp {{ number_format($item['total_tunggakan'], 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($item['tunggakan'] as $t)
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-white px-2.5 py-1.5 text-xs font-bold text-amber-800"
                                            title="{{ $t->keterangan ?? 'Tunggakan SPP' }}">
                                            <i class="ph ph-calendar-x text-sm"></i>
                                            {{ $t->bulan_label }}
                                            <span class="font-medium text-amber-600">· {{ $t->jumlah_rupiah }}</span>
                                        </span>
                                    @endforeach
                                </div>
                                <p class="mt-3 text-[11px] font-medium text-amber-700">
                                    Mohon segera lakukan pembayaran ke bendahara sekolah.
                                </p>
                            </div>
                        @endif
                        <a href="{{ route('ortu.anak', $item['siswa']->id) }}"
                            class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-800">
                            <i class="ph ph-eye text-lg"></i>
                            Lihat Detail Kehadiran
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- NOTIFIKASI -->
        @if(isset($notifikasi) && $notifikasi->count() > 0)
        <div class="mt-8">
            <div class="mb-3 flex items-center gap-3 px-1">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <i class="ph-fill ph-bell-ringing text-xl"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Notifikasi</h2>
                    <p class="text-xs text-slate-400">Informasi terbaru dari sekolah</p>
                </div>
            </div>
            <div class="space-y-3">
                @foreach($notifikasi as $n)
                    <div class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3.5 shadow-card">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-700 text-white">
                            <i class="ph-fill ph-bell text-base"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm font-extrabold text-slate-900">{{ $n->judul }}</h3>
                            <p class="mt-0.5 text-xs text-slate-500">{{ $n->pesan }}</p>
                            <p class="mt-1 text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                                {{ $n->siswa->name ?? 'Anak Anda' }} ·
                                {{ $n->dikirim_at ? $n->dikirim_at->diffForHumans() : $n->created_at->diffForHumans() }}
                            </p>
                        </div>
                        @if($n->status === 'pending')
                            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-blue-500"></span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- REKAP BULANAN -->
        <div class="mt-8">
            <div class="mb-3 flex items-center gap-3 px-1">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                    <i class="ph-fill ph-chart-bar text-xl"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Rekap Bulanan</h2>
                    <p class="text-xs text-slate-400">Ringkasan 1 tahun terakhir</p>
                </div>
            </div>
            <div class="space-y-3">
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
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3.5 shadow-card">
                        <span class="text-sm font-extrabold text-slate-900">{{ $bulan }}</span>
                        <div class="flex items-center gap-1.5">
                            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-700">H {{ $hadir }}</span>
                            <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700">I {{ $ijin }}</span>
                            <span class="rounded-full bg-sky-50 px-2.5 py-1 text-[11px] font-bold text-sky-700">S {{ $sakit }}</span>
                            <span class="rounded-full bg-rose-50 px-2.5 py-1 text-[11px] font-bold text-rose-700">A {{ $alpa }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- GALLERY PREVIEW -->
        @php
            $galleryPreview = collect()
                ->merge(isset($kegiatan) ? $kegiatan->take(4) : collect())
                ->merge(isset($jurusan) ? $jurusan->take(4) : collect())
                ->merge(isset($eskul) ? $eskul->take(4) : collect())
                ->take(6);
        @endphp
        <div class="mt-8">
            <div class="mb-3 flex items-center justify-between px-1">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <i class="ph-fill ph-images text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Gallery Sekolah</h2>
                        <p class="text-xs text-slate-400">Momen kegiatan siswa</p>
                    </div>
                </div>
                <a href="{{ route('school.dashboard') }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600">
                    Lihat Semua <i class="ph ph-arrow-right text-sm"></i>
                </a>
            </div>
            @if($galleryPreview->count() > 0)
                <div class="gallery-scroll no-scrollbar -mx-4 flex gap-3 overflow-x-auto px-4 pb-2">
                    @foreach($galleryPreview as $g)
                        <a href="{{ route('school.dashboard') }}"
                            class="relative h-36 w-44 shrink-0 overflow-hidden rounded-2xl border border-slate-200 shadow-card">
                            <img src="{{ asset('storage/' . $g->image) }}" alt="{{ $g->title }}"
                                class="h-full w-full object-cover transition-transform duration-300 hover:scale-105">
                            <div class="absolute inset-x-0 bottom-0 bg-slate-900/60 p-2.5 pt-8">
                                <p class="truncate text-xs font-bold text-white">{{ $g->title }}</p>
                                @if($g->category)
                                    <p class="text-[10px] font-medium text-blue-200">{{ ucfirst($g->category) }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-center">
                    <i class="ph ph-images text-3xl text-slate-300"></i>
                    <p class="mt-2 text-sm text-slate-400">Belum ada foto di gallery.</p>
                </div>
            @endif
        </div>
    @endif
@endsection