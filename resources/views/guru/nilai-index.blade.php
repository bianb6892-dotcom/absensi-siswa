@php
    $title = $periodeDb === 'setengah_semester' ? 'Nilai Setengah Semester' : 'Nilai Akhir Semester';
    $subtitle = $periodeDb === 'setengah_semester' ? 'Penilaian Tengah Semester (PTS)' : 'Penilaian Akhir Semester (PAS)';

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

@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl flex items-center gap-3">
            {{ $title }}
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            {{ $subtitle }} · Tahun Ajaran <span class="font-semibold text-slate-900">{{ $tahunAjaran }}</span>
        </p>
    </div>
    <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 shadow-sm">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
            <i class="ph ph-sort-descending text-lg"></i>
        </div>
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Kelas aktif</p>
            <p class="text-sm font-bold text-slate-900">{{ $kelasTerpilih }}</p>
        </div>
    </div>
</div>

<!-- ===================== INPUT NILAI ===================== -->
<div class="card overflow-hidden mb-6">
    <div class="flex border-b border-slate-100">
        <button onclick="switchTab('manual')" id="tab-manual"
            class="flex items-center gap-2 rounded-t-xl border-b-2 border-blue-700 px-5 py-3 text-sm font-bold text-blue-600 transition-colors">
            <i class="ph ph-pencil-simple"></i> Input Manual
        </button>
        <button onclick="switchTab('import')" id="tab-import"
            class="flex items-center gap-2 rounded-t-xl border-b-2 border-transparent px-5 py-3 text-sm font-medium text-slate-500 transition-colors hover:text-blue-600">
            <i class="ph ph-file-arrow-up"></i> Import Excel
        </button>
    </div>

    <!-- ====== TAB MANUAL ====== -->
    <div id="manual-tab">
        <form method="POST" action="{{ route('guru.nilai.store', $periode) }}" class="border-b border-slate-100 bg-white px-6 py-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="form_kelas" class="label">Kelas</label>
                    <select id="form_kelas" name="kelas" data-kelas-sync class="input cursor-pointer">
                        <optgroup label="Kelas X">
                            @foreach(['X PPLG', 'X TJKT', 'X ACP', 'X AKL'] as $k)
                                <option value="{{ $k }}" @selected($kelasTerpilih == $k)>{{ $k }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Kelas XI">
                            @foreach(['XI PPLG', 'XI TJKT', 'XI ACP', 'XI AKL'] as $k)
                                <option value="{{ $k }}" @selected($kelasTerpilih == $k)>{{ $k }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Kelas XII">
                            @foreach(['XII PPLG', 'XII TJKT', 'XII ACP', 'XII AKL'] as $k)
                                <option value="{{ $k }}" @selected($kelasTerpilih == $k)>{{ $k }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div>
                    <label for="form_tahun" class="label">Tahun Ajaran</label>
                    <select id="form_tahun" name="tahun_ajaran" data-tahun-sync class="input cursor-pointer">
                        @foreach($daftarTahunAjaran as $ta)
                            <option value="{{ $ta }}" @selected($tahunAjaran == $ta)>{{ $ta }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="mata_pelajaran" class="label">Mata Pelajaran</label>
                    <input type="text" id="mata_pelajaran" name="mata_pelajaran" required placeholder="contoh: Matematika" class="input">
                </div>
            </div>

            <div class="mt-5">
                <div class="table-wrapper">
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-l-xl bg-slate-50">No</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Nama Siswa</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">NIS</th>
                                <th class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-r-xl bg-slate-50">Nilai (0 - 100)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($siswa as $key => $s)
                                <tr class="transition-colors hover:bg-slate-50/70">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-500">{{ $key + 1 }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-700 text-xs font-bold text-white">
                                                {{ strtoupper(substr($s->name, 0, 2)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-slate-900">{{ $s->name }}</p>
                                                <p class="text-xs text-slate-400">{{ $s->kelas }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-500">{{ $s->nis ?? '-' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <input type="number" name="nilai[{{ $s->id }}]" min="0" max="100" step="0.01" placeholder="0-100"
                                            class="w-24 input text-center">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-12 text-center text-slate-400">
                                        <i class="ph ph-user-minus text-5xl block mb-3 text-slate-200"></i>
                                        Belum ada siswa di kelas ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 text-xs text-slate-400">* Kosongkan nilai jika siswa belum dinilai pada mapel ini.</p>
            </div>

            <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:justify-end">
                <a href="{{ route('guru.dashboard') }}" class="btn btn-outline">
                    <i class="ph ph-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-floppy-disk"></i> Simpan Nilai
                </button>
            </div>
        </form>
    </div>

    <!-- ====== TAB IMPORT ====== -->
    <div id="import-tab" class="hidden">
        <form method="POST" action="{{ route('guru.nilai.import', $periode) }}" enctype="multipart/form-data" class="border-b border-slate-100 bg-white px-6 py-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="import_kelas" class="label">Kelas</label>
                    <select id="import_kelas" name="kelas" data-kelas-sync class="input cursor-pointer">
                        <optgroup label="Kelas X">
                            @foreach(['X PPLG', 'X TJKT', 'X ACP', 'X AKL'] as $k)
                                <option value="{{ $k }}" @selected($kelasTerpilih == $k)>{{ $k }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Kelas XI">
                            @foreach(['XI PPLG', 'XI TJKT', 'XI ACP', 'XI AKL'] as $k)
                                <option value="{{ $k }}" @selected($kelasTerpilih == $k)>{{ $k }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Kelas XII">
                            @foreach(['XII PPLG', 'XII TJKT', 'XII ACP', 'XII AKL'] as $k)
                                <option value="{{ $k }}" @selected($kelasTerpilih == $k)>{{ $k }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div>
                    <label for="import_tahun" class="label">Tahun Ajaran</label>
                    <select id="import_tahun" name="tahun_ajaran" data-tahun-sync class="input cursor-pointer">
                        @foreach($daftarTahunAjaran as $ta)
                            <option value="{{ $ta }}" @selected($tahunAjaran == $ta)>{{ $ta }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="label">File Excel</label>
                <div class="rounded-xl border-2 border-dashed border-slate-200 p-8 text-center transition-colors hover:border-blue-400 hover:bg-blue-50">
                    <i class="ph ph-file-xls text-4xl text-slate-300 mb-3 block"></i>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-700 file:px-4 file:py-2.5 file:text-sm file:font-bold file:text-white file:transition-colors hover:file:bg-blue-800">
                    <p class="mt-3 text-xs text-slate-400">Format: .xlsx, .xls, atau .csv (maks 2MB)</p>
                </div>
            </div>

            <div class="mt-5 flex flex-col sm:flex-row gap-3 sm:justify-end">
                <a href="{{ route('guru.nilai.template', $periode) }}" class="btn btn-outline">
                    <i class="ph ph-download-simple"></i> Download Template
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-file-arrow-up"></i> Upload & Import
                </button>
            </div>
        </form>

        <div class="bg-white px-6 py-5">
            <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
                <i class="ph ph-info text-blue-600"></i> Panduan Import
            </h3>
            <ol class="list-decimal list-inside space-y-1.5 text-sm text-slate-500">
                <li>Download template nilai terlebih dahulu.</li>
                <li>Isi kolom <b class="text-slate-700">nama</b> (sesuai nama siswa), <b class="text-slate-700">mata_pelajaran</b>, dan <b class="text-slate-700">nilai</b> (0 - 100).</li>
                <li>Nama siswa yang tidak ditemukan di kelas terpilih akan dilewati.</li>
                <li>Jika mapel sudah ada, nilai akan diperbarui otomatis.</li>
            </ol>
        </div>
    </div>
</div>

<!-- ===================== RIWAYAT NILAI ===================== -->
<div class="card overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 border-b border-slate-100 bg-slate-50/60 px-6 py-4">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                <i class="ph ph-clipboard-text"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900">Riwayat {{ $title }}</h2>
                <p class="text-xs text-slate-400">{{ $kelasTerpilih }} · {{ $tahunAjaran }}</p>
            </div>
        </div>
        <div class="flex flex-1 items-center gap-3 sm:justify-end">
            <select id="filter_kelas" data-kelas-sync class="input cursor-pointer">
                @foreach($daftarKelas as $k)
                    <option value="{{ $k }}" @selected($kelasTerpilih == $k)>{{ $k }}</option>
                @endforeach
            </select>
            <select id="filter_tahun" data-tahun-sync class="input cursor-pointer">
                @foreach($daftarTahunAjaran as $ta)
                    <option value="{{ $ta }}" @selected($tahunAjaran == $ta)>{{ $ta }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="p-6">
        <div class="table-wrapper">
            <table class="w-full text-sm" id="nilaiTable">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-l-xl bg-slate-50">No</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Nama Siswa</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">NIS</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Mata Pelajaran</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Nilai</th>
                        <th class="px-4 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Predikat</th>
                        <th class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-r-xl bg-slate-50">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($nilaiList as $key => $n)
                        <tr class="transition-colors hover:bg-slate-50/70">
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm text-slate-500">{{ $nilaiList->firstItem() + $key }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-700 text-xs font-bold text-white">
                                        {{ strtoupper(substr($n->user->name ?? '-', 0, 2)) }}
                                    </div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $n->user->name ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm text-slate-500">{{ $n->user->nis ?? '-' }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm font-medium text-slate-700">{{ $n->mata_pelajaran }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center text-sm font-extrabold text-slate-900">{{ number_format((float) $n->nilai, 0) }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-bold {{ $predikatCls($n->predikat['label']) }}"
                                    title="{{ $n->predikat['keterangan'] }}">
                                    {{ $n->predikat['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-right">
                                <form method="POST" action="{{ route('guru.nilai.destroy', $n->id) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus nilai {{ $n->mata_pelajaran }} milik {{ $n->user->name ?? '-' }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 border border-rose-200 transition-colors hover:bg-rose-50">
                                        <i class="ph ph-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                                <i class="ph ph-chart-bar text-5xl block mb-3 text-slate-200"></i>
                                Belum ada data nilai pada periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($nilaiList->hasPages())
            <div class="mt-5">
                {{ $nilaiList->appends(['kelas' => $kelasTerpilih, 'tahun' => $tahunAjaran])->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function switchTab(tab) {
    const manualTab = document.getElementById('manual-tab');
    const importTab = document.getElementById('import-tab');
    const tabManual = document.getElementById('tab-manual');
    const tabImport = document.getElementById('tab-import');

    if (tab === 'manual') {
        manualTab.classList.remove('hidden');
        importTab.classList.add('hidden');
        tabManual.className = 'flex items-center gap-2 rounded-t-xl border-b-2 border-blue-700 px-5 py-3 text-sm font-bold text-blue-600 transition-colors';
        tabImport.className = 'flex items-center gap-2 rounded-t-xl border-b-2 border-transparent px-5 py-3 text-sm font-medium text-slate-500 transition-colors hover:text-blue-600';
    } else {
        importTab.classList.remove('hidden');
        manualTab.classList.add('hidden');
        tabImport.className = 'flex items-center gap-2 rounded-t-xl border-b-2 border-blue-700 px-5 py-3 text-sm font-bold text-blue-600 transition-colors';
        tabManual.className = 'flex items-center gap-2 rounded-t-xl border-b-2 border-transparent px-5 py-3 text-sm font-medium text-slate-500 transition-colors hover:text-blue-600';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-kelas-sync]').forEach(function (sel) {
        sel.addEventListener('change', function () {
            document.querySelectorAll('[data-kelas-sync]').forEach(function (other) {
                if (other !== sel) other.value = sel.value;
            });
            if (sel.id === 'filter_kelas') applyFilter();
        });
    });

    document.querySelectorAll('[data-tahun-sync]').forEach(function (sel) {
        sel.addEventListener('change', function () {
            document.querySelectorAll('[data-tahun-sync]').forEach(function (other) {
                if (other !== sel) other.value = sel.value;
            });
            if (sel.id === 'filter_tahun') applyFilter();
        });
    });
});

function applyFilter() {
    const kelas = document.getElementById('filter_kelas').value;
    const tahun = document.getElementById('filter_tahun').value;
    window.location.href = "{{ url('/guru/nilai/' . $periode) }}?kelas=" + encodeURIComponent(kelas) + "&tahun=" + encodeURIComponent(tahun);
}
</script>
@endpush