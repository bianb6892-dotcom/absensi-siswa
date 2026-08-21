@extends('layouts.app')

@section('title', 'Import Absensi')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Import Absensi</h1>
        <p class="mt-1 text-sm text-slate-500">Upload file Excel/CSV untuk menginput absensi banyak siswa sekaligus.</p>
    </div>
    <a href="{{ route('absensi.create') }}" class="btn btn-outline">
        <i class="ph ph-arrow-left text-lg"></i>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Import -->
    <div class="lg:col-span-2">
        <div class="card overflow-hidden">
            <div class="border-b border-slate-100 bg-white px-6 py-5">
                <h2 class="flex items-center gap-2 text-lg font-bold text-slate-900">
                    <i class="ph ph-upload-simple text-blue-600"></i>
                    Upload File Absensi
                </h2>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('import.absensi.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="kelas" class="label">Pilih Kelas</label>
                            <select name="kelas" id="kelas" class="input" required>
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
                            <label for="tanggal" class="label">Tanggal Absensi</label>
                            <input type="date" name="tanggal" id="tanggal" class="input" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="file" class="label">File Excel/CSV</label>
                        <div class="rounded-xl border-2 border-dashed border-slate-200 p-8 text-center transition-colors hover:border-blue-400 hover:bg-blue-50">
                            <i class="ph ph-file-csv text-4xl text-slate-300 mb-2 block"></i>
                            <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" class="mx-auto w-full max-w-sm" required>
                            <p class="mt-2 text-xs text-slate-400">Format: .xlsx, .xls, .csv | Maksimal 2MB</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="btn btn-primary flex-1">
                            <i class="ph ph-upload-simple text-lg"></i>
                            Upload & Import
                        </button>
                        <a href="{{ route('import.template') }}" class="btn btn-outline-blue">
                            <i class="ph ph-download-simple text-lg"></i>
                            Download Template
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Panduan -->
    <div class="lg:col-span-1">
        <div class="card sticky top-8 overflow-hidden">
            <div class="border-b border-slate-100 bg-white px-6 py-5">
                <h2 class="flex items-center gap-2 text-lg font-bold text-slate-900">
                    <i class="ph ph-info text-blue-600"></i>
                    Panduan
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-5">
                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">1</div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-700">Download Template</h4>
                            <p class="mt-0.5 text-xs text-slate-500">Klik tombol "Download Template" untuk mendapatkan file contoh.</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">2</div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-700">Isi Data</h4>
                            <p class="mt-0.5 text-xs text-slate-500">Isi kolom dengan data siswa:</p>
                            <ul class="mt-1 list-inside list-disc space-y-0.5 text-xs text-slate-500">
                                <li><strong>nama</strong> - Nama lengkap siswa (harus sesuai database)</li>
                                <li><strong>keterangan</strong> - hadir / ijin / sakit / tidak_masuk</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-700">3</div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-700">Upload File</h4>
                            <p class="mt-0.5 text-xs text-slate-500">Pilih file yang sudah diisi, pilih kelas dan tanggal, lalu klik "Upload & Import".</p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <h4 class="mb-2 flex items-center gap-2 text-xs font-bold text-amber-800">
                            <i class="ph-fill ph-warning"></i>
                            Perhatian
                        </h4>
                        <ul class="list-inside list-disc space-y-1 text-xs text-amber-700">
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