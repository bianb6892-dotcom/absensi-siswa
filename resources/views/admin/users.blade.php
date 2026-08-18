@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="ph-fill ph-users text-primary-500"></i>
                Kelola User
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">Kelola semua user yang terdaftar.</p>
        </div>
        <a href="{{ route('register.show') }}"
            class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
            <i class="ph ph-user-plus mr-2"></i>
            Tambah User Baru
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar User</h2>
        </div>
        <div class="p-5">
            @if(session('success'))
                <div
                    class="mb-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-lg flex items-center gap-3">
                    <i class="ph-fill ph-check-circle text-emerald-500 text-xl"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div
                    class="mb-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 px-4 py-3 rounded-lg flex items-center gap-3">
                    <i class="ph-fill ph-x-circle text-rose-500 text-xl"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="table-responsive">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700/50 rounded-xl">
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider rounded-l-xl">
                                No</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                Nama</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                Email</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                Role</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider rounded-r-xl">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($users as $key => $u)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td data-label="No"
                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $key + 1 }}
                                </td>
                                <td data-label="Nama" class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-700 dark:text-primary-300 font-bold text-xs">
                                            {{ strtoupper(substr($u->name, 0, 2)) }}
                                        </div>
                                        <span
                                            class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $u->name }}</span>
                                    </div>
                                </td>
                                <td data-label="Email"
                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $u->email }}
                                </td>
                                <td data-label="Role" class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $roleLabels = [
                                            'admin' => ['label' => 'Admin', 'color' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300'],
                                            'guru' => ['label' => 'Guru', 'color' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300'],
                                            'ortu' => ['label' => 'Orang Tua', 'color' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300'],
                                        ];
                                        $role = $roleLabels[$u->role] ?? ['label' => $u->role, 'color' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $role['color'] }}">
                                        {{ $role['label'] }}
                                    </span>
                                </td>
                                <td data-label="Aksi" class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-2">
                                        <!-- Tombol Edit -->
                                        <button type="button" onclick="openEditModal({{ $u->id }})"
                                            class="inline-flex items-center px-3 py-1 bg-amber-100 dark:bg-amber-900/30 hover:bg-amber-200 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-xs font-medium rounded-lg transition-colors">
                                            <i class="ph ph-pencil mr-1"></i>
                                            Edit
                                        </button>

                                        <!-- Reset Password -->
                                        <form action="{{ route('users.reset-password', $u->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Reset password user ini menjadi default?')"
                                                class="inline-flex items-center px-3 py-1 bg-amber-100 dark:bg-amber-900/30 hover:bg-amber-200 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-xs font-medium rounded-lg transition-colors">
                                                <i class="ph ph-key mr-1"></i>
                                                Reset Pass
                                            </button>
                                        </form>

                                        <!-- Hapus User -->
                                        <form action="{{ route('users.delete', $u->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus user ini?')"
                                                class="inline-flex items-center px-3 py-1 bg-rose-100 dark:bg-rose-900/30 hover:bg-rose-200 dark:hover:bg-rose-900/50 text-rose-700 dark:text-rose-300 text-xs font-medium rounded-lg transition-colors">
                                                <i class="ph ph-trash mr-1"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MODAL EDIT USER                              -->
    <!-- ============================================ -->
    <div id="editModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4 p-6 max-h-[90vh] overflow-y-auto">
            <div
                class="flex justify-between items-center mb-4 sticky top-0 bg-white dark:bg-gray-800 pb-3 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit User</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="edit_name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Nama
                        Lengkap</label>
                    <input type="text" name="name" id="edit_name"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        required>
                </div>

                <div class="mb-3">
                    <label for="edit_email"
                        class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Email</label>
                    <input type="email" name="email" id="edit_email"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        required>
                </div>

                <div class="mb-4">
                    <label for="edit_role"
                        class="block text-sm font-semibold text-gray-900 dark:text-white mb-1">Role</label>
                    <select name="role" id="edit_role"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                        required>
                        <option value="ortu">Orang Tua</option>
                        <option value="guru">Guru</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="ph ph-save"></i>
                    Update User
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openEditModal(id) {
                fetch(`/admin/users/${id}/edit`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('edit_name').value = data.name;
                        document.getElementById('edit_email').value = data.email;
                        document.getElementById('edit_role').value = data.role;
                        document.getElementById('editForm').action = `/admin/users/${id}`;
                        document.getElementById('editModal').classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat data user. Cek console untuk detail error.');
                    });
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }

            // Tutup modal saat klik di luar
            document.getElementById('editModal').addEventListener('click', function (e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });
        </script>
    @endpush
@endsection