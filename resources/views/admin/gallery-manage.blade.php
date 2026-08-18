@extends('layouts.app')

@section('title', 'Kelola Gallery')

@section('content')

@if(auth()->user()->role == 'ortu')
    <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-lg mb-4">
        <i class="ph-fill ph-info mr-2"></i>
        Anda hanya dapat melihat gallery. Untuk menambah/mengedit gallery, hubungi guru atau admin.
    </div>
@endif

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i class="ph-fill ph-images text-primary-500"></i>
            Kelola Gallery
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">Tambah dan kelola foto untuk kegiatan, jurusan, dan ekstrakurikuler.</p>
    </div>
    <a href="{{ route('school.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
        <i class="ph ph-eye mr-2"></i>
        Lihat Dashboard Sekolah
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- ============================================ -->
    <!-- FORM TAMBAH FOTO (HANYA UNTUK ADMIN & GURU)  -->
    <!-- ============================================ -->
    @if(auth()->user()->role != 'ortu')
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-20">
            <div class="p-5 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Tambah Foto Baru</h2>
            </div>
            <div class="p-5">
                @if(session('success'))
                    <div class="mb-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg flex items-center gap-3">
                        <i class="ph-fill ph-check-circle text-emerald-500 text-xl"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 px-4 py-3 rounded-lg flex items-center gap-3">
                        <i class="ph-fill ph-x-circle text-rose-500 text-xl"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('gallery.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="type" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Tipe Foto</label>
                        <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                            <option value="kegiatan">Kegiatan Sekolah</option>
                            <option value="jurusan">Jurusan</option>
                            <option value="eskul">Ekstrakurikuler</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Judul</label>
                        <input type="text" name="title" id="title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required placeholder="Contoh: PPLG, Futsal, Kegiatan Lomba">
                    </div>

                    <div class="mb-3">
                        <label for="category" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Kategori (Opsional)</label>
                        <input type="text" name="category" id="category" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Contoh: Jurusan, Olahraga, Seni">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Deskripsi</label>
                        <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" placeholder="Deskripsi singkat tentang foto ini"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Upload Foto</label>
                        <input type="file" name="image" id="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Maksimal 2MB (JPG, PNG, GIF, WEBP)</p>
                    </div>

                    <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="ph ph-upload"></i>
                        Upload Foto
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- ============================================ -->
    <!-- DAFTAR FOTO (SEMUA ROLE BISA LIHAT)          -->
    <!-- ============================================ -->
    <div class="{{ auth()->user()->role == 'ortu' ? 'lg:col-span-3' : 'lg:col-span-2' }}">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-5 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Foto Gallery</h2>
            </div>
            <div class="p-5">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700/50 rounded-xl">
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider rounded-l-xl">No</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Foto</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tipe</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Judul</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider rounded-r-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($galleries as $key => $g)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $key + 1 }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <img src="{{ asset('storage/' . $g->image) }}" alt="{{ $g->title }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @php
                                            $typeLabels = ['kegiatan' => 'Kegiatan', 'jurusan' => 'Jurusan', 'eskul' => 'Eskul'];
                                            $typeColors = [
                                                'kegiatan' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300',
                                                'jurusan' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300',
                                                'eskul' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$g->type] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                            {{ $typeLabels[$g->type] ?? $g->type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $g->title }}</div>
                                            @if($g->category)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $g->category }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <form action="{{ route('gallery.toggle', $g->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            @if(auth()->user()->role != 'ortu')
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $g->is_active ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300' }}">
                                                    {{ $g->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </button>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $g->is_active ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300' }}">
                                                    {{ $g->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            @endif
                                        </form>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if(auth()->user()->role != 'ortu')
                                            <div class="flex flex-wrap gap-2">
                                                <button type="button" onclick="openEditModal({{ $g->id }})" class="inline-flex items-center px-3 py-1 bg-amber-100 dark:bg-amber-900/30 hover:bg-amber-200 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-xs font-medium rounded-lg transition-colors">
                                                    <i class="ph ph-pencil mr-1"></i>
                                                    Edit
                                                </button>
                                                <form action="{{ route('gallery.destroy', $g->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus foto ini?')" class="inline-flex items-center px-3 py-1 bg-rose-100 dark:bg-rose-900/30 hover:bg-rose-200 dark:hover:bg-rose-900/50 text-rose-700 dark:text-rose-300 text-xs font-medium rounded-lg transition-colors">
                                                        <i class="ph ph-trash mr-1"></i>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">View Only</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <i class="ph ph-images text-4xl block mb-2 text-gray-300 dark:text-gray-600"></i>
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

<!-- Modal Edit (HANYA UNTUK ADMIN & GURU) -->
@if(auth()->user()->role != 'ortu')
<div id="editModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4 p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4 sticky top-0 bg-white dark:bg-gray-800 pb-3 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Foto</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="ph ph-x text-2xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="edit_type" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Tipe Foto</label>
                <select name="type" id="edit_type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                    <option value="kegiatan">Kegiatan Sekolah</option>
                    <option value="jurusan">Jurusan</option>
                    <option value="eskul">Ekstrakurikuler</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="edit_title" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Judul</label>
                <input type="text" name="title" id="edit_title" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
            </div>

            <div class="mb-3">
                <label for="edit_category" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Kategori (Opsional)</label>
                <input type="text" name="category" id="edit_category" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div class="mb-3">
                <label for="edit_description" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Deskripsi</label>
                <textarea name="description" id="edit_description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
            </div>

            <div class="mb-4">
                <label for="edit_image" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Ganti Foto (Opsional)</label>
                <input type="file" name="image" id="edit_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti foto</p>
            </div>

            <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                <i class="ph ph-save"></i>
                Update Foto
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEditModal(id) {
    fetch(`/admin/gallery/${id}/edit`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('edit_type').value = data.type;
            document.getElementById('edit_title').value = data.title;
            document.getElementById('edit_category').value = data.category || '';
            document.getElementById('edit_description').value = data.description || '';
            document.getElementById('editForm').action = `/admin/gallery/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data. Cek console untuk detail error.');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>
@endpush
@endif

@endsection