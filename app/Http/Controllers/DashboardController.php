<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            return app(AbsensiController::class)->dashboard();
        } else {
            return $this->siswaDashboard();
        }
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