<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\OrangTua;
use Carbon\Carbon;
use App\Models\Gallery;

class OrangTuaController extends Controller
{
    public function dashboard()
    {
        $ortu = OrangTua::where('user_id', auth()->user()->id)->first();

        if (!$ortu) {
            return redirect()->back()->with('error', 'Data orang tua tidak ditemukan!');
        }

        $anak = User::where('ortu_id', $ortu->id)->get();

        $data = [];
        foreach ($anak as $siswa) {
            $bulanIni = Absensi::where('user_id', $siswa->id)
                ->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year);

            $data[] = [
                'siswa' => $siswa,
                'hadir' => (clone $bulanIni)->where('keterangan', 'hadir')->count(),
                'ijin' => (clone $bulanIni)->where('keterangan', 'ijin')->count(),
                'sakit' => (clone $bulanIni)->where('keterangan', 'sakit')->count(),
                'alpa' => (clone $bulanIni)->where('keterangan', 'tidak_masuk')->count(),
                'total' => $bulanIni->count(),
                'hari_ini' => Absensi::where('user_id', $siswa->id)
                    ->whereDate('tanggal', Carbon::today())
                    ->first(),
            ];
        }

        // ============================================
        // REKAP BULANAN UNTUK SEMUA ANAK
        // ============================================
        $rekapBulanan = [];
        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $semuaAnak = User::where('ortu_id', $ortu->id)->get();

        foreach ($semuaAnak as $siswa) {
            foreach ($bulanList as $index => $bulan) {
                $bulanNumber = $index + 1;
                $absensiBulan = Absensi::where('user_id', $siswa->id)
                    ->whereMonth('tanggal', $bulanNumber)
                    ->whereYear('tanggal', Carbon::now()->year);

                $rekapBulanan[$siswa->id][$bulan] = [
                    'hadir' => (clone $absensiBulan)->where('keterangan', 'hadir')->count(),
                    'ijin' => (clone $absensiBulan)->where('keterangan', 'ijin')->count(),
                    'sakit' => (clone $absensiBulan)->where('keterangan', 'sakit')->count(),
                    'alpa' => (clone $absensiBulan)->where('keterangan', 'tidak_masuk')->count(),
                ];
            }
        }

        // ============================================
        // AMBIL DATA GALLERY UNTUK DITAMPILKAN
        // ============================================
        $kegiatan = Gallery::where('type', 'kegiatan')->where('is_active', true)->get();
        $jurusan = Gallery::where('type', 'jurusan')->where('is_active', true)->get();
        $eskul = Gallery::where('type', 'eskul')->where('is_active', true)->get();

        // ============================================
        // KIRIM SEMUA VARIABLE KE VIEW (SATU RETURN)
        // ============================================
        return view('ortu.dashboard', compact('data', 'rekapBulanan', 'bulanList', 'kegiatan', 'jurusan', 'eskul'));
    }

    public function anak($id)
    {
        $siswa = User::findOrFail($id);

        $ortu = OrangTua::where('user_id', auth()->user()->id)->first();
        if ($siswa->ortu_id != $ortu->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini!');
        }

        $absensis = Absensi::where('user_id', $siswa->id)
            ->orderBy('tanggal', 'desc')
            ->get();

        $rekap = [
            'hadir' => $absensis->where('keterangan', 'hadir')->count(),
            'ijin' => $absensis->where('keterangan', 'ijin')->count(),
            'sakit' => $absensis->where('keterangan', 'sakit')->count(),
            'alpa' => $absensis->where('keterangan', 'tidak_masuk')->count(),
        ];

        return view('ortu.anak', compact('siswa', 'absensis', 'rekap'));
    }
}