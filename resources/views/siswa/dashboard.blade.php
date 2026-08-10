@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-cv-950 flex items-center gap-2">
            <i class="ph-fill ph-gauge text-cv-500"></i>
            Dashboard Siswa
        </h1>
        <p class="text-charcoal-700 mt-1 text-sm">Selamat datang, {{ auth()->user()->name }}!</p>
    </div>
    <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-cv-100 flex items-center gap-3">
        <div class="bg-cv-50 p-2 rounded-md text-cv-600">
            <i class="ph ph-calendar-blank text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-charcoal-700 font-medium">Hari ini</p>
            <p class="text-sm font-bold text-cv-950">{{ date('d F Y') }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-cv-200 overflow-hidden">
    <div class="p-5 sm:p-6 border-b border-cv-100 bg-gradient-to-r from-cv-50 to-white">
        <h2 class="text-lg font-bold text-cv-900">Riwayat Absensi</h2>
    </div>
    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-cv-200" id="riwayatTable">
                <thead class="bg-cv-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-cv-800 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-cv-800 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-cv-800 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-cv-100">
                    @forelse($absensis as $key => $a)
                    <tr class="hover:bg-cv-50/50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-charcoal-700">{{ $key + 1 }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-charcoal-900">{{ $a->tanggal->format('d F Y') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($a->keterangan == 'hadir')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <i class="ph-fill ph-check-circle mr-1"></i> Hadir
                                </span>
                            @elseif($a->keterangan == 'ijin')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                    <i class="ph-fill ph-envelope mr-1"></i> Ijin
                                </span>
                            @elseif($a->keterangan == 'sakit')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    <i class="ph-fill ph-first-aid mr-1"></i> Sakit
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                    <i class="ph-fill ph-x-circle mr-1"></i> Tidak Masuk
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-charcoal-700">
                            <i class="ph ph-database text-4xl block mb-2 text-cv-300"></i>
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