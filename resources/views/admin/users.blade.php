@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-slate-900 sm:text-2xl">Manajemen User</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola semua akun yang terdaftar di sistem.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="button" onclick="openImportModal()" class="btn btn-outline">
                <i class="ph ph-file-csv text-lg"></i>
                Import Excel
            </button>
            <a href="{{ route('register.show') }}" class="btn btn-primary">
                <i class="ph ph-user-plus text-lg"></i>
                Tambah User
            </a>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/60 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <i class="ph-fill ph-users-three"></i>
                </div>
                <h2 class="text-base font-bold text-slate-900">Daftar User</h2>
            </div>
            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                {{ $users->count() }} user
            </span>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('users.index') }}"
                class="mb-4 flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[200px] max-w-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i class="ph ph-magnifying-glass text-slate-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="input pl-11" placeholder="Cari nama / NIS / email...">
                </div>
                <select name="role" onchange="this.form.submit()" class="input w-44">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="ortu" {{ request('role') == 'ortu' ? 'selected' : '' }}>Orang Tua</option>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-magnifying-glass text-lg"></i>
                    Cari
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('users.index') }}" class="btn btn-outline">
                        <i class="ph ph-x text-lg"></i>
                        Reset
                    </a>
                @endif
            </form>

            <div class="table-wrapper">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">No</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Nama</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">NIS</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Email</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Role</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $key => $u)
                            <tr class="transition-colors hover:bg-slate-50/70">
                                <td class="px-4 py-3.5 whitespace-nowrap text-sm font-medium text-slate-500">{{ $key + 1 }}</td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-9 w-9 rounded-lg bg-blue-700 flex items-center justify-center text-white font-bold text-xs">
                                            {{ strtoupper(substr($u->name, 0, 2)) }}
                                        </div>
                                        <span class="ml-3 text-sm font-semibold text-slate-900">{{ $u->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-sm {{ $u->nis ? 'text-slate-700' : 'text-slate-300 italic' }}">
                                    {{ $u->nis ?? 'belum ada' }}
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap text-sm text-slate-500">{{ $u->email }}</td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    @php
                                        $roleLabels = [
                                            'admin' => ['label' => 'Admin', 'cls' => 'bg-slate-100 text-slate-700 border border-slate-200'],
                                            'guru' => ['label' => 'Guru', 'cls' => 'bg-blue-50 text-blue-700 border border-blue-200'],
                                            'siswa' => ['label' => 'Siswa', 'cls' => 'bg-blue-50 text-blue-700 border border-blue-200'],
                                            'ortu' => ['label' => 'Orang Tua', 'cls' => 'bg-slate-100 text-slate-700 border border-slate-200'],
                                        ];
                                        $role = $roleLabels[$u->role] ?? ['label' => $u->role, 'cls' => 'bg-slate-100 text-slate-600 border border-slate-200'];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $role['cls'] }}">
                                        {{ $role['label'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" onclick="openEditModal({{ $u->id }})"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-blue-700 border border-blue-700 transition-colors hover:bg-blue-50">
                                            <i class="ph ph-pencil"></i>
                                            Edit
                                        </button>

                                        @if(auth()->user()->id !== $u->id)
                                            <button type="button" onclick="openPasswordModal({{ $u->id }}, '{{ addslashes($u->name) }}')"
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-blue-700 border border-blue-700 transition-colors hover:bg-blue-50">
                                                <i class="ph ph-key"></i>
                                                Ganti Pass
                                            </button>
                                        @endif

                                        <form action="{{ route('users.delete', $u->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus user ini?')"
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-rose-600 border border-rose-200 transition-colors hover:bg-rose-50">
                                                <i class="ph ph-trash"></i>
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
            @if($users->isEmpty())
                <div class="py-12 text-center">
                    <i class="ph ph-user-minus text-5xl text-slate-300"></i>
                    <p class="mt-3 text-sm font-semibold text-slate-600">Tidak ada user ditemukan</p>
                    <p class="mt-1 text-xs text-slate-400">Coba ubah kata kunci atau filter role.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MODAL EDIT USER                              -->
    <!-- ============================================ -->
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl bg-white p-7 shadow-modal max-h-[90vh] overflow-y-auto">
            <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i class="ph-fill ph-user-pencil text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Edit User</h3>
                </div>
                <button onclick="closeEditModal()" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit_name" class="label">Nama Lengkap</label>
                    <input type="text" name="name" id="edit_name" class="input" required>
                </div>

                <div>
                    <label for="edit_email" class="label">Email</label>
                    <input type="email" name="email" id="edit_email" class="input" required>
                </div>

                <div>
                    <label for="edit_role" class="label">Role</label>
                    <select name="role" id="edit_role" class="input" required>
                        <option value="ortu">Orang Tua</option>
                        <option value="guru">Guru</option>
                        <option value="siswa">Siswa</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div id="edit_nis_wrap" class="hidden">
                    <label for="edit_nis" class="label">NIS</label>
                    <input type="text" name="nis" id="edit_nis" class="input" maxlength="20" placeholder="contoh: 2026001" autocomplete="off">
                    <p class="mt-1 text-xs text-slate-400">
                        NIS dipakai orang tua untuk login &amp; pemantauan. Kosongkan bila bukan siswa.
                    </p>
                    @error('nis')
                        <p class="mt-1 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="edit_ortu_wrap" class="hidden">
                    <label for="edit_ortu_id" class="label">Orang Tua (Monitoring)</label>
                    <select name="ortu_id" id="edit_ortu_id" class="input">
                        <option value="">-- Tidak Terhubung --</option>
                        @foreach($orangTuaList as $o)
                            <option value="{{ $o->id }}">{{ $o->user?->name }} ({{ $o->user?->email }})</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-slate-400">Pilih orang tua agar siswa bisa dimonitoring (absensi, nilai, notifikasi).</p>
                </div>

                <button type="submit" class="btn btn-primary w-full py-3">
                    <i class="ph ph-save text-lg"></i>
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MODAL GANTI PASSWORD                        -->
    <!-- ============================================ -->
    <div id="passwordModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl bg-white p-7 shadow-modal">
            <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i class="ph-fill ph-key text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Ganti Password</h3>
                        <p class="text-xs text-slate-500" id="passwordUserName"></p>
                    </div>
                </div>
                <button onclick="closePasswordModal()" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            @if($errors->any())
                <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3">
                    <ul class="list-disc list-inside space-y-1 text-sm text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="passwordForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="password" class="label">Password Baru</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-lock-simple text-lg text-slate-400"></i>
                        </div>
                        <input type="password" name="password" id="password"
                            class="input pl-11" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="label">Konfirmasi Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="ph ph-check-circle text-lg text-slate-400"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="input pl-11" placeholder="Ulangi password baru" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full py-3">
                    <i class="ph ph-floppy-disk text-lg"></i>
                    Simpan Password
                </button>
            </form>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- MODAL IMPORT EXCEL SISWA                     -->
    <!-- ============================================ -->
    <div id="importModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-lg rounded-2xl bg-white p-7 shadow-modal max-h-[90vh] overflow-y-auto">
            <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i class="ph-fill ph-file-csv text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Import Siswa dari Excel</h3>
                </div>
                <button onclick="closeImportModal()" class="flex h-9 w-9 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

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
                    <label for="file" class="label">File Excel/CSV</label>
                    <div class="rounded-xl border-2 border-dashed border-slate-200 p-6 text-center transition-colors hover:border-blue-400 hover:bg-blue-50">
                        <i class="ph ph-file-csv text-4xl text-slate-300 mb-2 block"></i>
                        <input type="file" name="file" id="file" accept=".xlsx,.xls,.csv" class="mx-auto w-full max-w-sm" required>
                        <p class="mt-2 text-xs text-slate-400">Format: .xlsx, .xls, .csv | Maksimal 2MB</p>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <h4 class="mb-2 flex items-center gap-2 text-xs font-bold text-slate-700">
                        <i class="ph-fill ph-info"></i>
                        Panduan
                    </h4>
                    <ul class="list-inside list-disc space-y-1 text-xs text-slate-500">
                        <li>Download template, lalu isi kolom <strong>nis</strong> (nomor induk) dan <strong>nama</strong> siswa</li>
                        <li>Kolom <strong>email_ortu</strong> opsional — isi email akun orang tua agar siswa langsung terhubung untuk monitoring (absensi, nilai, notifikasi)</li>
                        <li>Email dibuat otomatis: <strong>nis@siswa.com</strong>, password default: <strong>password</strong></li>
                        <li>NIS yang sudah terdaftar akan di-update datanya (tidak ganda)</li>
                    </ul>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="ph ph-upload-simple text-lg"></i>
                        Upload & Import
                    </button>
                    <a href="{{ route('users.template') }}" class="btn btn-outline">
                        <i class="ph ph-download-simple text-lg"></i>
                        Download Template
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openImportModal() {
                document.getElementById('importModal').classList.remove('hidden');
                document.getElementById('importModal').classList.add('flex');
            }

            function closeImportModal() {
                document.getElementById('importModal').classList.add('hidden');
                document.getElementById('importModal').classList.remove('flex');
            }

            document.getElementById('importModal').addEventListener('click', function (e) {
                if (e.target === this) closeImportModal();
            });
        </script>

        <script>
            function openPasswordModal(id, name) {
                document.getElementById('passwordUserName').textContent = name;
                document.getElementById('passwordForm').action = `/admin/users/${id}/password`;
                document.getElementById('passwordForm').reset();
                document.getElementById('passwordModal').classList.remove('hidden');
                document.getElementById('passwordModal').classList.add('flex');
            }

            function closePasswordModal() {
                document.getElementById('passwordModal').classList.add('hidden');
                document.getElementById('passwordModal').classList.remove('flex');
            }

            document.getElementById('passwordModal').addEventListener('click', function (e) {
                if (e.target === this) closePasswordModal();
            });
        </script>

        <script>
            function openEditModal(id) {
                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editModal').classList.add('flex');
                fetch(`/admin/users/${id}/edit`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('edit_name').value = data.name;
                        document.getElementById('edit_email').value = data.email;
                        document.getElementById('edit_role').value = data.role;
                        document.getElementById('edit_ortu_id').value = data.ortu_id || '';
                        document.getElementById('edit_nis').value = data.nis || '';
                        document.getElementById('editForm').action = `/admin/users/${id}`;
                        toggleRoleFields(data.role);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat data user. Cek console untuk detail error.');
                        closeEditModal();
                    });
            }

            function toggleRoleFields(role) {
                const ortuWrap = document.getElementById('edit_ortu_wrap');
                const nisWrap = document.getElementById('edit_nis_wrap');

                if (role === 'siswa') {
                    ortuWrap.classList.remove('hidden');
                    nisWrap.classList.remove('hidden');
                } else {
                    ortuWrap.classList.add('hidden');
                    nisWrap.classList.add('hidden');
                }
            }

            document.getElementById('edit_role').addEventListener('change', function () {
                toggleRoleFields(this.value);
            });

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
                document.getElementById('editModal').classList.remove('flex');
            }

            document.getElementById('editModal').addEventListener('click', function (e) {
                if (e.target === this) closeEditModal();
            });
        </script>
    @endpush
@endsection