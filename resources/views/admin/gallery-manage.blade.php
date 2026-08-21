@extends('layouts.app')

@section('title', 'Kelola Gallery')

@section('content')

@if(auth()->user()->role == 'ortu')
    <div class="mb-5 flex items-center gap-3 rounded-xl border border-blue-200 bg-blue-50 px-5 py-4">
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-blue-600 shadow-sm">
            <i class="ph-fill ph-info text-lg"></i>
        </div>
        <p class="text-sm font-medium text-blue-800">
            Anda hanya dapat melihat gallery. Untuk menambah/mengedit gallery, hubungi guru atau admin.
        </p>
    </div>
@endif

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Kelola Gallery</h1>
        <p class="mt-1 text-sm text-slate-500">Tambah dan kelola foto kegiatan, jurusan, dan ekstrakurikuler.</p>
    </div>
    <a href="{{ route('school.dashboard') }}" class="btn btn-outline">
        <i class="ph ph-eye text-lg"></i>
        Lihat Dashboard Sekolah
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- FORM TAMBAH FOTO -->
    @if(auth()->user()->role != 'ortu')
    <div class="lg:col-span-1">
        <div class="card sticky top-8 overflow-hidden">
            <div class="border-b border-slate-100 bg-white px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <i class="ph-fill ph-image"></i>
                    </div>
                    <h2 class="text-base font-bold text-slate-900">Tambah Foto Baru</h2>
                </div>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('gallery.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="type" class="label">Tipe Foto</label>
                        <select name="type" id="type" class="input" required>
                            <option value="kegiatan">Kegiatan Sekolah</option>
                            <option value="jurusan">Jurusan</option>
                            <option value="eskul">Ekstrakurikuler</option>
                        </select>
                    </div>

                    <div>
                        <label for="title" class="label">Judul</label>
                        <input type="text" name="title" id="title" class="input"
                            required placeholder="Contoh: PPLG, Futsal, Kegiatan Lomba">
                    </div>

                    <div>
                        <label for="category" class="label">Kategori (Opsional)</label>
                        <input type="text" name="category" id="category" class="input"
                            placeholder="Contoh: Jurusan, Olahraga, Seni">
                    </div>

                    <div>
                        <label for="description" class="label">Deskripsi</label>
                        <textarea name="description" id="description" rows="3" class="input"
                            placeholder="Deskripsi singkat tentang foto ini"></textarea>
                    </div>

                    <div>
                        <label for="image" class="label">Upload Foto</label>
                        <div class="rounded-xl border-2 border-dashed border-slate-200 p-5 text-center transition-colors hover:border-blue-400 hover:bg-blue-50">
                            <i class="ph ph-image text-3xl text-slate-300 mb-2 block"></i>
                            <input type="file" name="image" id="image" accept="image/*" class="mx-auto w-full text-sm" required>
                            <p class="mt-1.5 text-xs text-slate-400">Maksimal 2MB (JPG, PNG, GIF, WEBP)</p>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full py-3">
                        <i class="ph ph-upload-simple text-lg"></i>
                        Upload Foto
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- DAFTAR FOTO -->
    <div class="{{ auth()->user()->role == 'ortu' ? 'lg:col-span-3' : 'lg:col-span-2' }}">
        <div class="card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/60 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <i class="ph-fill ph-images"></i>
                    </div>
                    <h2 class="text-base font-bold text-slate-900">Daftar Foto Gallery</h2>
                </div>
                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                    {{ $galleries->count() }} foto
                </span>
            </div>
            <div class="p-6">
                <div class="table-wrapper">
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-l-xl bg-slate-50">No</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Foto</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Tipe</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Judul</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50">Status</th>
                                <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500 rounded-r-xl bg-slate-50">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($galleries as $key => $g)
                                <tr class="transition-colors hover:bg-slate-50/70">
                                    <td class="px-4 py-3.5 whitespace-nowrap text-sm text-slate-500">{{ $key + 1 }}</td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <img src="{{ asset('storage/' . $g->image) }}" alt="{{ $g->title }}"
                                            class="h-12 w-12 rounded-xl object-cover border border-slate-100 shadow-sm">
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        @php
                                            $typeLabels = ['kegiatan' => 'Kegiatan', 'jurusan' => 'Jurusan', 'eskul' => 'Eskul'];
                                            $typeColors = [
                                                'kegiatan' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                                'jurusan' => 'bg-slate-100 text-slate-700 border border-slate-200',
                                                'eskul' => 'bg-blue-50 text-blue-700 border border-blue-200'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $typeColors[$g->type] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $typeLabels[$g->type] ?? $g->type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-sm">
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $g->title }}</div>
                                            @if($g->category)
                                                <div class="text-xs text-slate-400">{{ $g->category }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <form action="{{ route('gallery.toggle', $g->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold transition-colors {{ $g->is_active ? 'bg-blue-700 text-white border border-blue-700' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                                <span class="h-1.5 w-1.5 rounded-full {{ $g->is_active ? 'bg-white' : 'bg-slate-400' }}"></span>
                                                {{ $g->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" onclick="openEditModal({{ $g->id }})"
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-blue-700 border border-blue-700 transition-colors hover:bg-blue-50">
                                                <i class="ph ph-pencil"></i>
                                                Edit
                                            </button>
                                            <form action="{{ route('gallery.destroy', $g->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Yakin ingin menghapus foto ini?')"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 border border-rose-200 transition-colors hover:bg-rose-50">
                                                    <i class="ph ph-trash"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                                        <i class="ph ph-images text-5xl block mb-3 text-slate-200"></i>
                                        Belum ada foto gallery
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
@if(auth()->user()->role != 'ortu')
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
    <div class="w-full max-w-md rounded-2xl bg-white p-7 shadow-modal max-h-[90vh] overflow-y-auto">
        <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <i class="ph-fill ph-image text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Edit Foto</h3>
            </div>
            <button onclick="closeEditModal()" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_type" class="label">Tipe Foto</label>
                <select name="type" id="edit_type" class="input" required>
                    <option value="kegiatan">Kegiatan Sekolah</option>
                    <option value="jurusan">Jurusan</option>
                    <option value="eskul">Ekstrakurikuler</option>
                </select>
            </div>

            <div>
                <label for="edit_title" class="label">Judul</label>
                <input type="text" name="title" id="edit_title" class="input" required>
            </div>

            <div>
                <label for="edit_category" class="label">Kategori (Opsional)</label>
                <input type="text" name="category" id="edit_category" class="input">
            </div>

            <div>
                <label for="edit_description" class="label">Deskripsi</label>
                <textarea name="description" id="edit_description" rows="3" class="input"></textarea>
            </div>

            <div>
                <label for="edit_image" class="label">Ganti Foto (Opsional)</label>
                <input type="file" name="image" id="edit_image" accept="image/*" class="input file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-xs text-slate-400">Kosongkan jika tidak ingin mengganti foto</p>
            </div>

            <button type="submit" class="btn btn-primary w-full py-3">
                <i class="ph ph-save text-lg"></i>
                Update Foto
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEditModal(id) {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    fetch(`/gallery/${id}/edit`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            document.getElementById('edit_type').value = data.type;
            document.getElementById('edit_title').value = data.title;
            document.getElementById('edit_category').value = data.category || '';
            document.getElementById('edit_description').value = data.description || '';
            document.getElementById('editForm').action = `/gallery/${id}`;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data. Cek console untuk detail error.');
            closeEditModal();
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endpush
@endif

@endsection