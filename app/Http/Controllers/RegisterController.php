<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
        return view('auth.register', ['daftarKelas' => $this->daftarKelas]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,siswa',
            'kelas' => 'required_if:role,siswa|nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'kelas' => $request->kelas,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang ' . $user->name);
    }

    public function index()
    {
        $users = User::where('id', '!=', auth()->user()->id)->orderBy('name')->get();
        return view('admin.users', compact('users'));
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
            'role' => 'required|in:admin,siswa',
        ]);
        
        $user->update([
            'role' => $request->role,
        ]);
        
        return redirect()->route('users.index')
            ->with('success', 'Role user berhasil diubah!');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id == auth()->user()->id) {
            return redirect()->back()->with('error', 'Tidak bisa reset password sendiri!');
        }
        
        $user->update([
            'password' => Hash::make('password'),
        ]);
        
        return redirect()->route('users.index')
            ->with('success', 'Password user berhasil direset menjadi: password');
    }
}