<?php

namespace App\Http\Controllers;

use App\Imports\UserImport;
use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class RegisterController extends Controller
{
    // Daftar kelas yang tersedia
    private $daftarKelas = [
        'X PPLG',
        'X TJKT',
        'X ACP',
        'X AKL',
        'XI PPLG',
        'XI TJKT',
        'XI ACP',
        'XI AKL',
        'XII PPLG',
        'XII TJKT',
        'XII ACP',
        'XII AKL',
    ];

    public function showRegister()
    {
        // Ambil semua orang tua (dari tabel orang_tua) untuk dropdown
        $orangTuaList = OrangTua::with('user')->orderBy('id')->get();

        return view('auth.register', [
            'daftarKelas' => $this->daftarKelas,
            'orangTuaList' => $orangTuaList,  // ← Tambahkan ini
        ]);
    }

    public function register(Request $request)
    {
        // Tambahkan ortu_id di validasi (hanya untuk siswa/guru)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:ortu,guru,siswa',
            'kelas' => 'required_if:role,siswa|nullable|string',
            'ortu_id' => 'nullable|exists:orang_tua,id',  // ← Tambahkan ini
            'nis' => 'nullable|string|max:20|unique:users,nis',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'kelas' => $request->kelas,
            'ortu_id' => $request->ortu_id,  // ← Tambahkan ini
            'nis' => $request->filled('nis') ? $request->nis : null,
        ]);

        // Buat data orang tua di tabel orang_tua agar dashboard ortu berfungsi
        if ($user->role === 'ortu') {
            OrangTua::create(['user_id' => $user->id]);
        }

        return redirect()->route('users.index')
            ->with('success', 'Akun "'.$user->name.'" berhasil dibuat!');
    }

    public function index(Request $request)
    {
        $query = User::orderBy('name');

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan nama / NIS / email
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('nis', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $users = $query->get();
        $orangTuaList = OrangTua::with('user')->orderBy('id')->get();

        return view('admin.users', compact('users', 'orangTuaList'));
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == auth()->user()->id) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    public function editRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id == auth()->user()->id) {
            return redirect()->back()->with('error', 'Tidak bisa mengubah role sendiri!');
        }

        $request->validate([
            'role' => 'required|in:admin,guru,siswa,ortu',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        $this->syncOrangTua($user);

        return redirect()->route('users.index')
            ->with('success', 'Role user berhasil diubah!');
    }

    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id == auth()->user()->id) {
            return redirect()->back()->with('error', 'Tidak bisa mengubah password akun sendiri di sini!');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Password "'.$user->name.'" berhasil diubah!');
    }

    // Method untuk mengambil data user (via AJAX)
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    // Method untuk update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|in:admin,guru,siswa,ortu',
            'ortu_id' => 'nullable|exists:orang_tua,id',
            'nis' => ['nullable', 'string', 'max:20', Rule::unique('users', 'nis')->ignore($id)],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'ortu_id' => $request->ortu_id,
            'nis' => $request->filled('nis') ? $request->nis : null,
        ]);

        $this->syncOrangTua($user);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    // Pastikan data orang_tua selalu sinkron dengan role user
    private function syncOrangTua(User $user): void
    {
        if ($user->role === 'ortu') {
            if (! OrangTua::where('user_id', $user->id)->exists()) {
                OrangTua::create(['user_id' => $user->id]);
            }
        } else {
            OrangTua::where('user_id', $user->id)->delete();
        }
    }

    // Method untuk import siswa via Excel
    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'kelas' => 'required|string',
        ]);

        try {
            $import = new UserImport($request->kelas);
            Excel::import($import, $request->file('file'));

            $failures = $import->failures();

            if (count($failures) > 0) {
                $errorMessages = [];
                foreach ($failures as $failure) {
                    $errorMessages[] = 'Row '.$failure->row().': '.implode(', ', $failure->errors());
                }

                return redirect()->route('users.index')
                    ->with('warning', 'Data berhasil diimport sebagian. Ada beberapa baris yang gagal:')
                    ->withErrors($errorMessages);
            }

            return redirect()->route('users.index')
                ->with('success', 'Semua siswa berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    // Download Template Excel Siswa
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="template_siswa.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['nis', 'nama', 'email_ortu']);

            // Contoh data
            fputcsv($file, ['2026001', 'Budi Santoso', 'ortu@budi.com']);
            fputcsv($file, ['2026002', 'Ani Rahayu', '']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
