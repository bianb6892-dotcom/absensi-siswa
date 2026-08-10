@extends('layouts.app')

@section('title', 'Input Absensi Harian')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-cv-950 flex items-center gap-2">
            <i class="ph-fill ph-calendar-check text-cv-500"></i>
            Input Absensi Harian
        </h1>
        <p class="text-charcoal-700 mt-1 text-sm">Catat kehadiran siswa untuk hari ini.</p>
    </div>
    <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-cv-100 flex items-center gap-3">
        <div class="bg-cv-50 p-2 rounded-md text-cv-600">
            <i class="ph ph-graduation-cap text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-charcoal-700 font-medium">Kelas Aktif</p>
            <p class="text-sm font-bold text-cv-950">{{ $kelasTerpilih ?? 'Pilih Kelas' }}</p>
        </div>
    </div>
</div>

<!-- Tabs: Manual Input & Import Excel -->
<div class="mb-6">
    <div class="border-b border-cv-200">
        <nav class="flex gap-4" aria-label="Tabs">
            <button onclick="switchTab('manual')" id="tab-manual" class="py-2 px-4 text-sm font-semibold text-cv-600 border-b-2 border-cv-500 transition-all">
                <i class="ph ph-pencil mr-2"></i>Input Manual
            </button>
            <button onclick="switchTab('import')" id="tab-import" class="py-2 px-4 text-sm font-medium text-charcoal-700 border-b-2 border-transparent hover:text-cv-600 hover:border-cv-300 transition-all">
                <i class="ph ph-file-csv mr-2"></i>Import Excel
            </button>
        </nav>
    </div>
</div>

<!-- Tab Manual Input -->
<div id="manual-tab">
    <div class="bg-white rounded-xl shadow-sm border border-cv-200 overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-cv-100 bg-gradient-to-r from-cv-50 to-white">
            <div class="flex flex-wrap items-center gap-4">
                <div class="max-w-md flex-1">
                    <label for="kelas_absensi" class="block text-sm font-semibold text-cv-900 mb-2">
                        Pilih Kelas
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-graduation-cap text-cv-500 text-lg"></i>
                        </div>
                        <select id="kelas_absensi" class="block w-full pl-10 pr-4 py-2.5 sm:text-sm border border-cv-200 rounded-lg text-charcoal-900 bg-white focus:ring-2 focus:ring-cv-500 focus:border-cv-500 transition-shadow shadow-sm cursor-pointer hover:border-cv-300">
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
                </div>
                <div class="max-w-md flex-1">
                    <label for="tanggal_absensi" class="block text-sm font-semibold text-cv-900 mb-2">
                        Pilih Tanggal Absensi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-calendar text-cv-500 text-lg"></i>
                        </div>
                        <input type="date" id="tanggal_absensi" name="tanggal" value="{{ date('Y-m-d') }}"
                            class="block w-full pl-10 pr-4 py-2.5 sm:text-sm border border-cv-200 rounded-lg text-charcoal-900 bg-white focus:ring-2 focus:ring-cv-500 focus:border-cv-500 transition-shadow shadow-sm cursor-pointer hover:border-cv-300 relative">
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('absensi.store') }}" id="absensi-form">
            @csrf
            <input type="hidden" name="tanggal" id="tanggal-input" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="kelas" id="kelas-input" value="{{ $kelasTerpilih ?? 'X PPLG' }}">
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cv-200 hidden md:table">
                    <thead class="bg-cv-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-cv-800 uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-cv-800 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-cv-800 uppercase tracking-wider">NIS</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-cv-800 uppercase tracking-wider w-64">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-cv-100" id="student-table-body">
                        @forelse($siswa as $key => $s)
                        <tr class="hover:bg-cv-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-charcoal-700">{{ $key + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-cv-100 flex items-center justify-center text-cv-700 font-bold border border-cv-200">
                                        {{ strtoupper(substr($s->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-charcoal-900">{{ $s->name }}</div>
                                        <div class="text-xs text-charcoal-700">{{ $s->kelas }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-charcoal-700">
                                {{ $s->nis ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative">
                                    <select name="absensi[{{ $s->id }}]" 
                                        class="status-select block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-cv-500 focus:border-cv-500 sm:text-sm rounded-md appearance-none bg-white border border-cv-200 shadow-sm cursor-pointer"
                                        data-student-id="{{ $s->id }}">
                                        <option value="hadir" selected>✅ Hadir</option>
                                        <option value="ijin">📝 Ijin</option>
                                        <option value="sakit">🏥 Sakit</option>
                                        <option value="tidak_masuk">❌ Tidak Masuk</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-charcoal-700">
                                        <i class="ph ph-caret-down"></i>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-charcoal-700">
                                <i class="ph ph-users text-4xl block mb-2 text-cv-300"></i>
                                Belum ada data siswa di kelas ini. Silahkan tambahkan siswa terlebih dahulu melalui menu Register.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Mobile View -->
                <div class="md:hidden divide-y divide-cv-100" id="mobile-student-list">
                    @forelse($siswa as $key => $s)
                    <div class="p-4 bg-white hover:bg-cv-50/30 transition-colors">
                        <div class="flex justify-between items-center mb-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-cv-100 flex items-center justify-center text-cv-700 font-bold border border-cv-200">
                                    {{ strtoupper(substr($s->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-charcoal-900">{{ $key + 1 }}. {{ $s->name }}</h3>
                                    <p class="text-xs text-charcoal-700">{{ $s->kelas }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-4 gap-2 mt-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="status_m_{{ $s->id }}" value="hadir" class="peer sr-only" checked>
                                <div class="text-center p-2 rounded-lg border border-cv-200 peer-checked:bg-emerald-100 peer-checked:border-emerald-500 peer-checked:text-emerald-700 text-charcoal-700 transition-all text-xs font-medium">
                                    <i class="ph ph-check-circle block text-lg mb-1"></i>
                                    Hadir
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status_m_{{ $s->id }}" value="ijin" class="peer sr-only">
                                <div class="text-center p-2 rounded-lg border border-cv-200 peer-checked:bg-amber-100 peer-checked:border-amber-500 peer-checked:text-amber-700 text-charcoal-700 transition-all text-xs font-medium">
                                    <i class="ph ph-envelope block text-lg mb-1"></i>
                                    Ijin
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status_m_{{ $s->id }}" value="sakit" class="peer sr-only">
                                <div class="text-center p-2 rounded-lg border border-cv-200 peer-checked:bg-blue-100 peer-checked:border-blue-500 peer-checked:text-blue-700 text-charcoal-700 transition-all text-xs font-medium">
                                    <i class="ph ph-first-aid block text-lg mb-1"></i>
                                    Sakit
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status_m_{{ $s->id }}" value="tidak_masuk" class="peer sr-only">
                                <div class="text-center p-2 rounded-lg border border-cv-200 peer-checked:bg-rose-100 peer-checked:border-rose-500 peer-checked:text-rose-700 text-charcoal-700 transition-all text-xs font-medium">
                                    <i class="ph ph-x-circle block text-lg mb-1"></i>
                                    Alpa
                                </div>
                            </label>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-charcoal-700">
                        <i class="ph ph-users text-4xl block mb-2 text-cv-300"></i>
                        Belum ada data siswa.
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="px-5 py-4 border-t border-cv-200 bg-cv-50/50 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 rounded-b-xl">
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-cv-300 shadow-sm text-sm font-medium rounded-lg text-charcoal-800 bg-white hover:bg-cv-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cv-500 transition-all duration-200">
                    <i class="ph ph-arrow-left mr-2 text-lg"></i>
                    Kembali
                </a>
                <button type="submit" id="btn-simpan" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2 border border-transparent shadow-md text-sm font-medium rounded-lg text-white bg-cv-600 hover:bg-cv-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cv-500 transform active:scale-95 transition-all duration-200">
                    <i class="ph ph-floppy-disk mr-2 text-lg"></i>
                    Simpan Absensi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tab Import Excel -->
<div id="import-tab" class="hidden">
    <div class="bg-white rounded-xl shadow-sm border border-cv-200 overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-cv-100 bg-gradient-to-r from-cv-50 to-white">
            <h2 class="text-lg font-bold text-cv-900 flex items-center gap-2">
                <i class="ph ph-file-csv text-cv-500"></i>
                Import Absensi dari Excel/CSV
            </h2>
            <p class="text-sm text-charcoal-700 mt-1">Upload file Excel/CSV untuk menginput absensi banyak siswa sekaligus.</p>
        </div>
        <div class="p-5">
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center gap-3">
                    <i class="ph-fill ph-check-circle text-emerald-500 text-xl"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="ph-fill ph-warning-circle text-amber-500 text-xl"></i>
                        <span class="font-semibold">{{ session('warning') }}</span>
                    </div>
                    @if(session('errors'))
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach(session('errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg flex items-center gap-3">
                    <i class="ph-fill ph-x-circle text-rose-500 text-xl"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            <form method="POST" action="{{ route('absensi.import') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="import_kelas" class="block text-sm font-semibold text-cv-900 mb-1">Pilih Kelas</label>
                        <select name="kelas" id="import_kelas" class="w-full px-3 py-2 border border-cv-200 rounded-lg focus:ring-2 focus:ring-cv-500 focus:border-cv-500" required>
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
                        <label for="import_tanggal" class="block text-sm font-semibold text-cv-900 mb-1">Tanggal Absensi</label>
                        <input type="date" name="tanggal" id="import_tanggal" class="w-full px-3 py-2 border border-cv-200 rounded-lg focus:ring-2 focus:ring-cv-500 focus:border-cv-500" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="file" class="block text-sm font-semibold text-cv-900 mb-1">File Excel/CSV</label>
                    <div class="border-2 border-dashed border-cv-200 rounded-lg p-6 text-center hover:border-cv-400 transition-colors">
                        <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" class="w-full" required>
                        <p class="text-xs text-charcoal-700 mt-2">Format: .xlsx, .xls, .csv | Maksimal 2MB</p>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="flex-1 bg-cv-600 hover:bg-cv-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="ph ph-upload"></i>
                        Upload & Import
                    </button>
                    <a href="{{ route('absensi.template') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <i class="ph ph-download mr-2"></i>
                        Download Template
                    </a>
                </div>
            </form>
            
            <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-4">
                <h4 class="font-semibold text-amber-700 text-sm mb-2 flex items-center gap-2">
                    <i class="ph-fill ph-info"></i>
                    Panduan Import
                </h4>
                <ul class="text-xs text-amber-700 space-y-1 list-disc list-inside">
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
    <div class="bg-white p-4 rounded-xl shadow-sm border border-emerald-100 flex items-center gap-3">
        <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
            <i class="ph-fill ph-check-circle text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-charcoal-700 font-medium">Hadir</p>
            <p class="text-lg font-bold text-emerald-700" id="stat-hadir">{{ $siswa->count() }}</p>
        </div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-amber-100 flex items-center gap-3">
        <div class="p-2 bg-amber-100 rounded-lg text-amber-600">
            <i class="ph-fill ph-envelope text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-charcoal-700 font-medium">Ijin</p>
            <p class="text-lg font-bold text-amber-700" id="stat-ijin">0</p>
        </div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-blue-100 flex items-center gap-3">
        <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
            <i class="ph-fill ph-first-aid text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-charcoal-700 font-medium">Sakit</p>
            <p class="text-lg font-bold text-blue-700" id="stat-sakit">0</p>
        </div>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-rose-100 flex items-center gap-3">
        <div class="p-2 bg-rose-100 rounded-lg text-rose-600">
            <i class="ph-fill ph-x-circle text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-charcoal-700 font-medium">Alpa</p>
            <p class="text-lg font-bold text-rose-700" id="stat-alpa">0</p>
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
        tabManual.className = 'py-2 px-4 text-sm font-semibold text-cv-600 border-b-2 border-cv-500 transition-all';
        tabImport.className = 'py-2 px-4 text-sm font-medium text-charcoal-700 border-b-2 border-transparent hover:text-cv-600 hover:border-cv-300 transition-all';
    } else {
        manualTab.classList.add('hidden');
        importTab.classList.remove('hidden');
        tabImport.className = 'py-2 px-4 text-sm font-semibold text-cv-600 border-b-2 border-cv-500 transition-all';
        tabManual.className = 'py-2 px-4 text-sm font-medium text-charcoal-700 border-b-2 border-transparent hover:text-cv-600 hover:border-cv-300 transition-all';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Pilih Kelas
    const kelasSelect = document.getElementById('kelas_absensi');
    const kelasInput = document.getElementById('kelas-input');
    const importKelas = document.getElementById('import_kelas');
    
    // Sync kelas select with import kelas
    kelasSelect.addEventListener('change', function() {
        const kelas = this.value;
        kelasInput.value = kelas;
        // Update import kelas juga
        if (importKelas) {
            importKelas.value = kelas;
        }
        window.location.href = "{{ url('/absensi/create') }}?kelas=" + encodeURIComponent(kelas);
    });
    
    // Sync import kelas with kelas select
    if (importKelas) {
        importKelas.addEventListener('change', function() {
            const kelas = this.value;
            if (kelasSelect) {
                kelasSelect.value = kelas;
            }
        });
    }

    // Date
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

    // Styling for select
    const selects = document.querySelectorAll('.status-select');
    
    const updateSelectStyle = (select) => {
        select.classList.remove('status-hadir', 'status-izin', 'status-sakit', 'status-alpa', 'status-tidak_masuk');
        const val = select.value;
        select.classList.add(`status-${val}`);
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