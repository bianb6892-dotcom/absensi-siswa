@extends('layouts.app')

@section('title', 'Detail Anak')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i class="ph-fill ph-user-circle text-primary-500"></i>
            {{ $siswa->name }}
        </h1>
        <p class="text-gray-600 mt-1">NIS: {{ $siswa->nis ?? '-' }} | Kelas: {{ $siswa->kelas }}</p>
    </div>
    <a href="{{ route('ortu.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
        <i class="ph ph-arrow-left mr-2"></i>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-emerald-50 rounded-xl p-4 text-center border border-emerald-100">
        <p class="text-2xl font-bold text-emerald-600">{{ $rekap['hadir'] }}</p>
        <p class="text-sm text-gray-600">✅ Hadir</p>
    </div>
    <div class="bg-amber-50 rounded-xl p-4 text-center border border-amber-100">
        <p class="text-2xl font-bold text-amber-600">{{ $rekap['ijin'] }}</p>
        <p class="text-sm text-gray-600">📝 Izin</p>
    </div>
    <div class="bg-blue-50 rounded-xl p-4 text-center border border-blue-100">
        <p class="text-2xl font-bold text-blue-600">{{ $rekap['sakit'] }}</p>
        <p class="text-sm text-gray-600">🤒 Sakit</p>
    </div>
    <div class="bg-rose-50 rounded-xl p-4 text-center border border-rose-100">
        <p class="text-2xl font-bold text-rose-600">{{ $rekap['alpa'] }}</p>
        <p class="text-sm text-gray-600">❌ Alpa</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-5 border-b border-gray-100">
        <h2 class="font-semibold text-gray-900">Riwayat Absensi</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($absensis as $key => $a)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-4 py-3 text-gray-600">{{ $key + 1 }}</td>
                    <td class="px-4 py-3 text-gray-900">{{ \Carbon\Carbon::parse($a->tanggal)->format('d F Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                            @if($a->keterangan == 'hadir') bg-emerald-100 text-emerald-700
                            @elseif($a->keterangan == 'ijin') bg-amber-100 text-amber-700
                            @elseif($a->keterangan == 'sakit') bg-blue-100 text-blue-700
                            @else bg-rose-100 text-rose-700 @endif">
                            {{ ucfirst($a->keterangan) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                        <i class="ph ph-database text-4xl block mb-2 text-gray-300"></i>
                        Belum ada data absensi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection