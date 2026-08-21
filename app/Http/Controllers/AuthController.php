<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $input = $credentials['login'];
        $password = $credentials['password'];

        // Jika input mengandung '@' → login pakai email
        if (str_contains($input, '@')) {
            $attempt = ['email' => $input, 'password' => $password];

            if (! Auth::attempt($attempt)) {
                return back()->withErrors([
                    'login' => 'Email atau password salah.',
                ])->onlyInput('login');
            }

            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'guru' || $user->role === 'siswa') {
                return redirect()->intended('/guru/dashboard');
            } elseif ($user->role === 'ortu') {
                return redirect()->intended('/ortu/dashboard');
            }

            abort(403, 'Role tidak dikenali.');
        }

        // Tanpa '@' → NIS siswa: login sebagai ORANG TUA dari siswa tersebut
        $siswa = User::where('nis', $input)->where('role', 'siswa')->first();

        if (! $siswa || ! Hash::check($password, $siswa->password)) {
            return back()->withErrors([
                'login' => 'NIS atau password salah.',
            ])->onlyInput('login');
        }

        $ortu = $siswa->orangTua?->user;

        if (! $ortu) {
            return back()->withErrors([
                'login' => 'Siswa dengan NIS ini belum terhubung dengan akun orang tua.',
            ])->onlyInput('login');
        }

        Auth::login($ortu);
        $request->session()->regenerate();

        return redirect()->intended('/ortu/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
