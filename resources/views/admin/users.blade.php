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
    <a href="{{ route('register.show') }}" class="inline-flex items-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm">
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
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700/50 rounded-xl">
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider rounded-l-xl">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wider rounded-r-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($users as $key => $u)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $key + 1 }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-700 dark:text-primary-300 font-bold text-xs">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $u->email }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <form action="{{ route('users.role', $u->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <select name="role" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" onchange="this.form.submit()">
                                    <option value="siswa" {{ $u->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                    <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex gap-2">
                                <form action="{{ route('users.reset-password', $u->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Reset password user ini menjadi default?')" 
                                        class="inline-flex items-center px-3 py-1 bg-amber-100 dark:bg-amber-900/30 hover:bg-amber-200 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-300 text-xs font-medium rounded-lg transition-colors">
                                        <i class="ph ph-key mr-1"></i>
                                        Reset Pass
                                    </button>
                                </form>
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
@endsection