@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Dashboard Siswa</h1>
        <p class="mt-1 text-sm text-slate-500">
            Selamat datang, <span class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>!
        </p>
    </div>
    <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-2.5 shadow-sm">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
            <i class="ph ph-calendar-blank text-xl"></i>
        </div>
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Hari ini</p>
            <p class="text-sm font-bold text-slate-900">{{ date('d F Y') }}</p>
        </div>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="flex items-center gap-3 border-b border-slate-100 bg-white px-6 py-5">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
            <i class="ph-fill ph-clock-counter-clockwise"></i>
        </div>
        <h2 class="text-lg font-bold text-slate-900">Riwayat Absensi</h2>
    </div>
    <div class="p-6">
        <div class="table-wrapper">
            <table class="w-full text-sm" id="riwayatTable">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-l-xl bg-slate-50">No</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Tanggal</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-r-xl bg-slate-50">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($absensis as $key => $a)
                    <tr class="transition-colors hover:bg-slate-50/70">
                        <td class="px-4 py-3.5 whitespace-nowrap text-sm text-slate-500">{{ $key + 1 }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap text-sm font-semibold text-slate-700">{{ $a->tanggal->format('d F Y') }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            @if($a->keterangan == 'hadir')
                                <span class="badge-hadir inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-bold">
                                    <i class="ph-fill ph-check-circle"></i> Hadir
                                </span>
                            @elseif($a->keterangan == 'ijin')
                                <span class="badge-ijin inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-bold">
                                    <i class="ph-fill ph-envelope"></i> Ijin
                                </span>
                            @elseif($a->keterangan == 'sakit')
                                <span class="badge-sakit inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-bold">
                                    <i class="ph-fill ph-first-aid"></i> Sakit
                                </span>
                            @else
                                <span class="badge-tidak inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-bold">
                                    <i class="ph-fill ph-x-circle"></i> Tidak Masuk
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-12 text-center text-slate-400">
                            <i class="ph ph-clipboard-text text-5xl block mb-3 text-slate-200"></i>
                            Belum ada data absensi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#riwayatTable').DataTable({
        "pageLength": 25,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data",
            "zeroRecords": "Data tidak ditemukan"
        }
    });
});
</script>
@endpush