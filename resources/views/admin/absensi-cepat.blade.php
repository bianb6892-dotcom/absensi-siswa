@extends('layouts.app')

@section('title', 'Absensi Cepat')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <i class="ph-fill ph-check-circle text-primary-500"></i>
            Absensi Cepat
        </h1>
        <p class="text-gray-600 mt-1">Input absensi 1 klik untuk semua siswa</p>
    </div>
    <div class="flex gap-2">
        <form action="{{ route('absensi.cepat.hadirkan') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="kelas" value="{{ $kelasTerpilih }}">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="ph ph-check-circle mr-2"></i>
                Hadirkan Semua
            </button>
        </form>
        <a href="{{ route('guru.absensi.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="ph ph-pencil mr-2"></i>
            Input Manual
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700">Kelas:</label>
            <select onchange="window.location.href='?kelas='+this.value" class="rounded-lg border-gray-300 text-sm">
                @foreach(['X PPLG','X TJKT','X ACP','X AKL','XI PPLG','XI TJKT','XI ACP','XI AKL','XII PPLG','XII TJKT','XII ACP','XII AKL'] as $k)
                <option value="{{ $k }}" @selected($kelasTerpilih == $k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div class="text-sm text-gray-500">
            <i class="ph ph-calendar mr-1"></i>
            {{ date('d F Y') }}
        </div>
    </div>

    <form action="{{ route('absensi.cepat.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kelas" value="{{ $kelasTerpilih }}">
        <input type="hidden" name="tanggal" value="{{ date('Y-m-d') }}">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($siswa as $key => $s)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-gray-600">{{ $key + 1 }}</td>
                        <td class="px-4 py-3 text-gray-900 font-medium">{{ $s->name }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                @foreach(['hadir', 'ijin', 'sakit', 'tidak_masuk'] as $status)
                                <label class="inline-flex items-center gap-1 text-xs cursor-pointer px-2 py-1 rounded-lg border transition-all
                                    @if($s->status_hari_ini == $status) 
                                        @if($status == 'hadir') bg-emerald-100 border-emerald-400
                                        @elseif($status == 'ijin') bg-amber-100 border-amber-400
                                        @elseif($status == 'sakit') bg-blue-100 border-blue-400
                                        @else bg-rose-100 border-rose-400 @endif
                                    @else border-gray-200 hover:bg-gray-100 @endif">
                                    <input type="radio" name="absensi[{{ $s->id }}]" value="{{ $status }}"
                                        @checked($s->status_hari_ini == $status)
                                        class="hidden">
                                    <span>
                                        @if($status == 'hadir') ✅
                                        @elseif($status == 'ijin') 📝
                                        @elseif($status == 'sakit') 🤒
                                        @else ❌ @endif
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                            <i class="ph ph-database text-4xl block mb-2 text-gray-300"></i>
                            Belum ada siswa di kelas ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors">
                <i class="ph ph-floppy-disk mr-2"></i>
                Simpan Absensi
            </button>
        </div>
    </form>
</div>

@if(session('success'))
<div class="mt-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg">
    {{ session('success') }}
</div>
@endif
@endsection