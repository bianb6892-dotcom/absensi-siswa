<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'guru') {
            return redirect()->route('guru.dashboard');
        } elseif ($user->role === 'ortu') {
            return redirect()->route('ortu.dashboard');
        }

        return abort(403, 'Role tidak dikenali.');
    }

    private function siswaDashboard()
    {
        $user = auth()->user();
        $absensis = Absensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('siswa.dashboard', compact('absensis'));
    }
}