@extends('layouts.app')

@section('title', 'Input Absensi Harian')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Input Absensi Harian</h1>
        <p class="mt-1 text-sm text-slate-500">Catat kehadiran siswa dengan cepat dan akurat.</p>
    </div>
    <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-2.5 shadow-sm">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
            <i class="ph ph-graduation-cap text-xl"></i>
        </div>
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Kelas Aktif</p>
            <p class="text-sm font-bold text-slate-900">{{ $kelasTerpilih ?? 'Pilih Kelas' }}</p>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="mb-6">
    <div class="border-b border-slate-200">
        <nav class="flex gap-2" aria-label="Tabs">
            <button onclick="switchTab('manual')" id="tab-manual"
                class="flex items-center gap-2 rounded-t-xl border-b-2 border-blue-700 px-5 py-3 text-sm font-bold text-blue-600 transition-all">
                <i class="ph ph-pencil-line text-lg"></i>Input Manual
            </button>
            <button onclick="switchTab('import')" id="tab-import"
                class="flex items-center gap-2 rounded-t-xl border-b-2 border-transparent px-5 py-3 text-sm font-medium text-slate-500 transition-colors hover:text-blue-600">
                <i class="ph ph-file-csv text-lg"></i>Import Excel
            </button>
        </nav>
    </div>
</div>

<!-- Tab Manual Input -->
<div id="manual-tab">
    <div class="card overflow-hidden">
        <div class="border-b border-slate-100 bg-white px-4 sm:px-6 py-4 sm:py-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="w-full">
                    <label for="kelas_absensi" class="mb-2 block text-sm font-semibold text-slate-700">
                        Pilih Kelas
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-graduation-cap text-lg text-slate-400"></i>
                        </div>
                        <select id="kelas_absensi"
                            class="input pl-11 pr-10 appearance-none cursor-pointer">
                            <optgroup label="Kelas X">
                                <option value="X PPLG" {{ $kelasTerpilih == 'X PPLG' ? 'selected' : '' }}>X PPLG</option>
                                <option value="X TJKT" {{ $kelasTerpilih == 'X TJKT' ? 'selected' : '' }}>X TJKT</option>
                                <option value="X ACP" {{ $kelasTerpilih == 'X ACP' ? 'selected' : '' }}>X ACP</option>
                                <option value="X AKL" {{ $kelasTerpilih == 'X AKL' ? 'selected' : '' }}>X AKL</option>
                            </optgroup>
                            <optgroup label="Kelas XI">
                                <option value="XI PPLG" {{ $kelasTerpilih == 'XI PPLG' ? 'selected' : '' }}>XI PPLG</option>
                                <option value="XI TJKT" {{ $kelasTerpilih == 'XI TJKT' ? 'selected' : '' }}>XI TJKT</option>
                                <option value="XI ACP" {{ $kelasTerpilih == 'XI ACP' ? 'selected' : '' }}>XI ACP</option>
                                <option value="XI AKL" {{ $kelasTerpilih == 'XI AKL' ? 'selected' : '' }}>XI AKL</option>
                            </optgroup>
                            <optgroup label="Kelas XII">
                                <option value="XII PPLG" {{ $kelasTerpilih == 'XII PPLG' ? 'selected' : '' }}>XII PPLG</option>
                                <option value="XII TJKT" {{ $kelasTerpilih == 'XII TJKT' ? 'selected' : '' }}>XII TJKT</option>
                                <option value="XII ACP" {{ $kelasTerpilih == 'XII ACP' ? 'selected' : '' }}>XII ACP</option>
                                <option value="XII AKL" {{ $kelasTerpilih == 'XII AKL' ? 'selected' : '' }}>XII AKL</option>
                            </optgroup>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <label for="tanggal_absensi" class="mb-2 block text-sm font-semibold text-slate-700">
                        Pilih Tanggal Absensi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-calendar-blank text-lg text-slate-400"></i>
                        </div>
                        <input type="date" id="tanggal_absensi" name="tanggal" value="{{ date('Y-m-d') }}"
                            class="input pl-11 cursor-pointer">
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('absensi.store') }}" id="absensi-form">
            @csrf
            <input type="hidden" name="tanggal" id="tanggal-input" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="kelas" id="kelas-input" value="{{ $kelasTerpilih ?? 'X PPLG' }}">

            <div class="table-wrapper">
                <table class="hidden w-full md:table">
                    <thead>
                        <tr>
                            <th class="w-16 px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">No</th>
                            <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Nama Siswa</th>
                            <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">NIS</th>
                            <th class="w-72 px-6 py-4 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="student-table-body">
                        @forelse($siswa as $key => $s)
                        <tr class="group transition-colors hover:bg-slate-50/70">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-500">{{ $key + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-700 font-bold text-white border border-blue-200">
                                        {{ strtoupper(substr($s->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-slate-900">{{ $s->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $s->kelas }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $s->nis ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative">
                                    <select name="absensi[{{ $s->id }}]"
                                        class="status-select input pl-3 pr-10 appearance-none cursor-pointer"
                                        data-student-id="{{ $s->id }}">
                                        <option value="hadir" selected>Hadir</option>
                                        <option value="ijin">Ijin</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="tidak_masuk">Tidak Masuk</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                        <i class="ph ph-caret-down"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <i class="ph ph-users-three text-5xl block mb-3 text-slate-200"></i>
                                Belum ada data siswa di kelas ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile View -->
                <div class="divide-y divide-slate-100 md:hidden" id="mobile-student-list">
                    @forelse($siswa as $key => $s)
                    <div class="bg-white p-4 transition-colors hover:bg-slate-50/70">
                        <div class="mb-3 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-700 font-bold text-white border border-blue-200">
                                {{ strtoupper(substr($s->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">{{ $key + 1 }}. {{ $s->name }}</h3>
                                <p class="text-xs text-slate-400">{{ $s->kelas }}</p>
                            </div>
                        </div>

                        <div class="mt-2 grid grid-cols-4 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="status_m_{{ $s->id }}" value="hadir" class="peer sr-only" checked>
                                <div class="rounded-xl border border-slate-200 p-2 text-center text-xs font-semibold text-slate-600 transition-all peer-checked:border-blue-700 peer-checked:bg-blue-700 peer-checked:text-white">
                                    <i class="ph-fill ph-check-circle block text-lg mb-1"></i>
                                    Hadir
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status_m_{{ $s->id }}" value="ijin" class="peer sr-only">
                                <div class="rounded-xl border border-slate-200 p-2 text-center text-xs font-semibold text-slate-600 transition-all peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700">
                                    <i class="ph-fill ph-envelope block text-lg mb-1"></i>
                                    Ijin
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status_m_{{ $s->id }}" value="sakit" class="peer sr-only">
                                <div class="rounded-xl border border-slate-200 p-2 text-center text-xs font-semibold text-slate-600 transition-all peer-checked:border-sky-500 peer-checked:bg-sky-50 peer-checked:text-sky-700">
                                    <i class="ph-fill ph-first-aid block text-lg mb-1"></i>
                                    Sakit
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status_m_{{ $s->id }}" value="tidak_masuk" class="peer sr-only">
                                <div class="rounded-xl border border-slate-200 p-2 text-center text-xs font-semibold text-slate-600 transition-all peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:text-rose-700">
                                    <i class="ph-fill ph-x-circle block text-lg mb-1"></i>
                                    Alpa
                                </div>
                            </label>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-400">
                        <i class="ph ph-users-three text-5xl block mb-3 text-slate-200"></i>
                        Belum ada data siswa.
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 py-4">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline w-full sm:w-auto">
                    <i class="ph ph-arrow-left text-lg"></i>
                    Kembali
                </a>
                <button type="submit" id="btn-simpan" class="btn btn-primary w-full sm:w-auto">
                    <i class="ph ph-floppy-disk text-lg"></i>
                    Simpan Absensi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tab Import Excel -->
<div id="import-tab" class="hidden">
    <div class="card overflow-hidden">
        <div class="border-b border-slate-100 bg-white px-6 py-5">
            <h2 class="flex items-center gap-2 text-lg font-bold text-slate-900">
                <i class="ph ph-file-csv text-blue-600"></i>
                Import Absensi dari Excel/CSV
            </h2>
            <p class="mt-1 text-sm text-slate-500">Upload file Excel/CSV untuk menginput absensi banyak siswa sekaligus.</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('absensi.import') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="import_kelas" class="label">Pilih Kelas</label>
                        <select name="kelas" id="import_kelas" class="input" required>
                            <option value="">-- Pilih Kelas --</option>
                            <optgroup label="Kelas X">
                                <option value="X PPLG" {{ $kelasTerpilih == 'X PPLG' ? 'selected' : '' }}>X PPLG</option>
                                <option value="X TJKT" {{ $kelasTerpilih == 'X TJKT' ? 'selected' : '' }}>X TJKT</option>
                                <option value="X ACP" {{ $kelasTerpilih == 'X ACP' ? 'selected' : '' }}>X ACP</option>
                                <option value="X AKL" {{ $kelasTerpilih == 'X AKL' ? 'selected' : '' }}>X AKL</option>
                            </optgroup>
                            <optgroup label="Kelas XI">
                                <option value="XI PPLG" {{ $kelasTerpilih == 'XI PPLG' ? 'selected' : '' }}>XI PPLG</option>
                                <option value="XI TJKT" {{ $kelasTerpilih == 'XI TJKT' ? 'selected' : '' }}>XI TJKT</option>
                                <option value="XI ACP" {{ $kelasTerpilih == 'XI ACP' ? 'selected' : '' }}>XI ACP</option>
                                <option value="XI AKL" {{ $kelasTerpilih == 'XI AKL' ? 'selected' : '' }}>XI AKL</option>
                            </optgroup>
                            <optgroup label="Kelas XII">
                                <option value="XII PPLG" {{ $kelasTerpilih == 'XII PPLG' ? 'selected' : '' }}>XII PPLG</option>
                                <option value="XII TJKT" {{ $kelasTerpilih == 'XII TJKT' ? 'selected' : '' }}>XII TJKT</option>
                                <option value="XII ACP" {{ $kelasTerpilih == 'XII ACP' ? 'selected' : '' }}>XII ACP</option>
                                <option value="XII AKL" {{ $kelasTerpilih == 'XII AKL' ? 'selected' : '' }}>XII AKL</option>
                            </optgroup>
                        </select>
                    </div>

                    <div>
                        <label for="import_tanggal" class="label">Tanggal Absensi</label>
                        <input type="date" name="tanggal" id="import_tanggal"
                            class="input" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="mb-5">
                    <label for="file" class="label">File Excel/CSV</label>
                    <div class="rounded-xl border-2 border-dashed border-slate-200 p-8 text-center transition-colors hover:border-blue-400 hover:bg-blue-50">
                        <i class="ph ph-upload-simple text-4xl text-slate-300 mb-2 block"></i>
                        <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" class="mx-auto w-full max-w-sm" required>
                        <p class="mt-2 text-xs text-slate-400">Format: .xlsx, .xls, .csv | Maksimal 2MB</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="ph ph-upload text-lg"></i>
                        Upload & Import
                    </button>
                    <a href="{{ route('absensi.template') }}" class="btn btn-outline">
                        <i class="ph ph-download-simple text-lg"></i>
                        Download Template
                    </a>
                </div>
            </form>

            <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-5">
                <h4 class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-700">
                    <i class="ph-fill ph-info"></i>
                    Panduan Import
                </h4>
                <ul class="list-inside list-disc space-y-1 text-xs text-slate-500">
                    <li>Download template CSV terlebih dahulu</li>
                    <li>Isi kolom <strong>nama</strong> dengan nama siswa (harus sesuai dengan database)</li>
                    <li>Isi kolom <strong>keterangan</strong> dengan: hadir / ijin / sakit / tidak_masuk</li>
                    <li>Nama siswa yang tidak ditemukan akan dilewati</li>
                    <li>Data yang sudah ada akan diupdate</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Summary Stats -->
<div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
    <div class="card p-4 flex items-center gap-3">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
            <i class="ph-fill ph-check-circle text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-medium text-slate-400">Hadir</p>
            <p class="text-lg font-extrabold text-slate-900" id="stat-hadir">{{ $siswa->count() }}</p>
        </div>
    </div>
    <div class="card p-4 flex items-center gap-3">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
            <i class="ph-fill ph-envelope text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-medium text-slate-400">Ijin</p>
            <p class="text-lg font-extrabold text-slate-900" id="stat-ijin">0</p>
        </div>
    </div>
    <div class="card p-4 flex items-center gap-3">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
            <i class="ph-fill ph-first-aid text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-medium text-slate-400">Sakit</p>
            <p class="text-lg font-extrabold text-slate-900" id="stat-sakit">0</p>
        </div>
    </div>
    <div class="card p-4 flex items-center gap-3">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
            <i class="ph-fill ph-x-circle text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-medium text-slate-400">Alpa</p>
            <p class="text-lg font-extrabold text-slate-900" id="stat-alpa">0</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Tab Switching
function switchTab(tab) {
    const manualTab = document.getElementById('manual-tab');
    const importTab = document.getElementById('import-tab');
    const tabManual = document.getElementById('tab-manual');
    const tabImport = document.getElementById('tab-import');

    if (tab === 'manual') {
        manualTab.classList.remove('hidden');
        importTab.classList.add('hidden');
        tabManual.className = 'flex items-center gap-2 rounded-t-xl border-b-2 border-blue-700 px-5 py-3 text-sm font-bold text-blue-600 transition-all';
        tabImport.className = 'flex items-center gap-2 rounded-t-xl border-b-2 border-transparent px-5 py-3 text-sm font-medium text-slate-500 transition-colors hover:text-blue-600';
    } else {
        manualTab.classList.add('hidden');
        importTab.classList.remove('hidden');
        tabImport.className = 'flex items-center gap-2 rounded-t-xl border-b-2 border-blue-700 px-5 py-3 text-sm font-bold text-blue-600 transition-all';
        tabManual.className = 'flex items-center gap-2 rounded-t-xl border-b-2 border-transparent px-5 py-3 text-sm font-medium text-slate-500 transition-colors hover:text-blue-600';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const kelasSelect = document.getElementById('kelas_absensi');
    const kelasInput = document.getElementById('kelas-input');
    const importKelas = document.getElementById('import_kelas');

    kelasSelect.addEventListener('change', function() {
        const kelas = this.value;
        kelasInput.value = kelas;
        if (importKelas) {
            importKelas.value = kelas;
        }
        window.location.href = "{{ url('/absensi/create') }}?kelas=" + encodeURIComponent(kelas);
    });

    if (importKelas) {
        importKelas.addEventListener('change', function() {
            const kelas = this.value;
            if (kelasSelect) {
                kelasSelect.value = kelas;
            }
        });
    }

    const dateInput = document.getElementById('tanggal_absensi');
    const dateInputHidden = document.getElementById('tanggal-input');
    const importTanggal = document.getElementById('import_tanggal');

    dateInput.addEventListener('change', (e) => {
        dateInputHidden.value = e.target.value;
        if (importTanggal) {
            importTanggal.value = e.target.value;
        }
    });

    if (importTanggal) {
        importTanggal.addEventListener('change', (e) => {
            dateInput.value = e.target.value;
            dateInputHidden.value = e.target.value;
        });
    }

    const selects = document.querySelectorAll('.status-select');

    const updateSelectStyle = (select) => {
        select.classList.remove('badge-hadir', 'badge-ijin', 'badge-sakit', 'badge-tidak');
        const val = select.value;
        select.classList.add(`badge-${val === 'tidak_masuk' ? 'tidak' : val}`);
        updateStats();
    };

    selects.forEach(select => {
        updateSelectStyle(select);
        select.addEventListener('change', (e) => {
            updateSelectStyle(e.target);
            syncMobileToDesktop(e.target.dataset.studentId, e.target.value);
        });
    });

    const mobileRadios = document.querySelectorAll('input[type="radio"]');
    mobileRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if (e.target.checked) {
                const nameParts = e.target.name.split('_');
                const id = nameParts[2];
                const val = e.target.value;

                const select = document.querySelector(`.status-select[data-student-id="${id}"]`);
                if (select) {
                    select.value = val;
                    updateSelectStyle(select);
                } else {
                    updateStats();
                }
            }
        });
    });

    function syncMobileToDesktop(id, value) {
        const radio = document.querySelector(`input[name="status_m_${id}"][value="${value}"]`);
        if (radio) {
            radio.checked = true;
        }
    }

    function updateStats() {
        let counts = { hadir: 0, ijin: 0, sakit: 0, tidak_masuk: 0 };

        selects.forEach(s => {
            counts[s.value]++;
        });

        document.getElementById('stat-hadir').textContent = counts.hadir || 0;
        document.getElementById('stat-ijin').textContent = counts.ijin || 0;
        document.getElementById('stat-sakit').textContent = counts.sakit || 0;
        document.getElementById('stat-alpa').textContent = counts.tidak_masuk || 0;
    }
});
</script>
@endpush
@endsection