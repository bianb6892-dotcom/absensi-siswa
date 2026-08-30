<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Sesi habis. Silakan login ulang.'], 401);
            }
            // Kalau buka halaman guru tapi belum login, arahkan ke login bukan 403 serem
            return redirect()->route('login')->with('error', 'Silakan login dulu.');
        }

        // Cek apakah role user sesuai (role:admin,guru -> ['admin', 'guru'])
        if (! in_array(auth()->user()->role, $roles, true)) {
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Kamu tidak punya izin untuk aksi ini.'], 403);
            }
            // Jangan kasih halaman 403 serem, arahkan ke dashboard yang benar sesuai role
            $role = auth()->user()->role;
            $target = match ($role) {
                'admin' => route('admin.dashboard'),
                'guru' => route('guru.dashboard'),
                'siswa' => route('guru.dashboard'),
                'ortu' => route('ortu.dashboard'),
                default => route('login'),
            };
            return redirect($target)->with('error', 'Kamu tidak punya izin untuk membuka halaman itu. Sudah diarahkan ke dashboard kamu.');
        }

        return $next($request);
    }
}
