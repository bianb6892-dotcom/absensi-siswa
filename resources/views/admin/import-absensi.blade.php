@extends('layouts.app')

@section('title', 'Import Absensi')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-cv-950 flex items-center gap-2">
            <i class="ph-fill ph-file-csv text-cv-500"></i>
            Import Absensi
        </h1>
        <p class="text-charcoal-700 mt-1 text-sm">Upload file Excel/CSV untuk menginput absensi banyak siswa sekaligus.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('absensi.create') }}" class="inline-flex items-center px-4 py-2 bg-cv-600 hover:bg-cv-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
            <i class="ph ph-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Import -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-cv-200 overflow-hidden">
            <div class="p-5 bg-gradient-to-r from-cv-50 to-white border-b border-cv-100">
                <h2 class="text-lg font-bold text-cv-900 flex items-center gap-2">
                    <i class="ph ph-upload text-cv-500"></i>
                    Upload File Absensi
                </h2>
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
                
                <form method="POST" action="{{ route('import.absensi.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="kelas" class="block text-sm font-semibold text-cv-900 mb-1">Pilih Kelas</label>
                            <select name="kelas" id="kelas" class="w-full px-3 py-2 border border-cv-200 rounded-lg focus:ring-2 focus:ring-cv-500 focus:border-cv-500" required>
                                <option value="">-- Pilih Kelas --</option>
                                <optgroup label="Kelas X">
                                    <option value="X PPLG">X PPLG</option>
                                    <option value="X TJKT">X TJKT</option>
                                    <option value="X ACP">X ACP</option>
                                    <option value="X AKL">X AKL</option>
                                </optgroup>
                                <optgroup label="Kelas XI">
                                    <option value="XI PPLG">XI PPLG</option>
                                    <option value="XI TJKT">XI TJKT</option>
                                    <option value="XI ACP">XI ACP</option>
                                    <option value="XI AKL">XI AKL</option>
                                </optgroup>
                                <optgroup label="Kelas XII">
                                    <option value="XII PPLG">XII PPLG</option>
                                    <option value="XII TJKT">XII TJKT</option>
                                    <option value="XII ACP">XII ACP</option>
                                    <option value="XII AKL">XII AKL</option>
                                </optgroup>
                            </select>
                        </div>
                        
                        <div>
                            <label for="tanggal" class="block text-sm font-semibold text-cv-900 mb-1">Tanggal Absensi</label>
                            <input type="date" name="tanggal" id="tanggal" class="w-full px-3 py-2 border border-cv-200 rounded-lg focus:ring-2 focus:ring-cv-500 focus:border-cv-500" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="file" class="block text-sm font-semibold text-cv-900 mb-1">File Excel/CSV</label>
                        <div class="border-2 border-dashed border-cv-200 rounded-lg p-6 text-center hover:border-cv-400 transition-colors">
                            <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" class="w-full" required>
                            <p class="text-xs text-charcoal-700 mt-2">Format: .xlsx, .xls, .csv | Maksimal 2MB</p>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 bg-cv-600 hover:bg-cv-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                            <i class="ph ph-upload"></i>
                            Upload & Import
                        </button>
                        <a href="{{ route('import.template') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                            <i class="ph ph-download mr-2"></i>
                            Download Template
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Panduan -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-cv-200 overflow-hidden sticky top-20">
            <div class="p-5 bg-gradient-to-r from-cv-50 to-white border-b border-cv-100">
                <h2 class="text-lg font-bold text-cv-900 flex items-center gap-2">
                    <i class="ph ph-info text-cv-500"></i>
                    Panduan
                </h2>
            </div>
            <div class="p-5">
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-cv-900 text-sm mb-1">1. Download Template</h4>
                        <p class="text-xs text-charcoal-700">Klik tombol "Download Template" untuk mendapatkan file contoh.</p>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-cv-900 text-sm mb-1">2. Isi Data</h4>
                        <p class="text-xs text-charcoal-700">Isi kolom dengan data siswa:</p>
                        <ul class="text-xs text-charcoal-700 list-disc list-inside mt-1">
                            <li><strong>nama</strong> - Nama lengkap siswa (harus sesuai dengan database)</li>
                            <li><strong>keterangan</strong> - hadir / ijin / sakit / tidak_masuk</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-cv-900 text-sm mb-1">3. Upload File</h4>
                        <p class="text-xs text-charcoal-700">Pilih file yang sudah diisi, pilih kelas dan tanggal, lalu klik "Upload & Import".</p>
                    </div>
                    
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <h4 class="font-semibold text-amber-700 text-xs mb-1">⚠️ Perhatian</h4>
                        <ul class="text-xs text-amber-700 list-disc list-inside">
                            <li>Nama siswa harus sesuai dengan database</li>
                            <li>Data yang sudah ada akan diupdate</li>
                            <li>File maksimal 2MB</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection