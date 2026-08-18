<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Imports\AbsensiImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class GuruAbsensiController extends Controller
{
    private $daftarKelas = [
        'X PPLG', 'X TJKT', 'X ACP', 'X AKL',
        'XI PPLG', 'XI TJKT', 'XI ACP', 'XI AKL',
        'XII PPLG', 'XII TJKT', 'XII ACP', 'XII AKL',
    ];

    public function dashboard()
    {
        $kelasTerpilih = session('kelas_aktif', 'X PPLG');
        $rekap = $this->getRekap($kelasTerpilih);
        
        return view('guru.dashboard', compact('rekap', 'kelasTerpilih'));
    }

    public function index(Request $request)
    {
        $kelasTerpilih = $request->get('kelas', session('kelas_aktif', 'X PPLG'));
        session(['kelas_aktif' => $kelasTerpilih]);
        
        $absensis = Absensi::with('user')
            ->where('kelas', $kelasTerpilih)
            ->orderBy('tanggal', 'desc')
            ->paginate(25);
        
        $siswa = User::where('role', 'guru')  // Perhatikan: role 'guru' sekarang mewakili siswa
            ->where('kelas', $kelasTerpilih)
            ->orderBy('name')
            ->get();
        
        return view('guru.absensi-index', compact('absensis', 'siswa', 'kelasTerpilih'));
    }

    public function create(Request $request)
    {
        $kelasTerpilih = $request->get('kelas', session('kelas_aktif', 'X PPLG'));
        session(['kelas_aktif' => $kelasTerpilih]);
        
        $siswa = User::where('role', 'guru')  // Perhatikan: role 'guru' sekarang mewakili siswa
            ->where('kelas', $kelasTerpilih)
            ->orderBy('name')
            ->get();
        
        return view('guru.absensi-create', compact('siswa', 'kelasTerpilih'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kelas' => 'required|string',
            'absensi' => 'required|array',
            'absensi.*' => 'in:hadir,ijin,sakit,tidak_masuk',
        ]);

        foreach ($request->absensi as $userId => $keterangan) {
            Absensi::updateOrCreate(
                [
                    'user_id' => $userId,
                    'kelas' => $request->kelas,
                    'tanggal' => $request->tanggal
                ],
                ['keterangan' => $keterangan]
            );
        }

        session(['kelas_aktif' => $request->kelas]);

        return redirect()->route('guru.absensi.create', ['kelas' => $request->kelas])
            ->with('success', 'Absensi untuk kelas ' . $request->kelas . ' berhasil disimpan!');
    }

    private function getRekap($kelas)
    {
        $siswa = User::where('role', 'guru')  // Perhatikan: role 'guru'
            ->where('kelas', $kelas)
            ->orderBy('name')
            ->get();
        
        $rekap = [];
        foreach ($siswa as $s) {
            $hadir = Absensi::where('user_id', $s->id)
                ->where('kelas', $kelas)
                ->whereMonth('tanggal', now()->month)
                ->where('keterangan', 'hadir')
                ->count();
                
            $ijin = Absensi::where('user_id', $s->id)
                ->where('kelas', $kelas)
                ->whereMonth('tanggal', now()->month)
                ->where('keterangan', 'ijin')
                ->count();
                
            $sakit = Absensi::where('user_id', $s->id)
                ->where('kelas', $kelas)
                ->whereMonth('tanggal', now()->month)
                ->where('keterangan', 'sakit')
                ->count();
                
            $tidak = Absensi::where('user_id', $s->id)
                ->where('kelas', $kelas)
                ->whereMonth('tanggal', now()->month)
                ->where('keterangan', 'tidak_masuk')
                ->count();
                
            $rekap[] = [
                'nama' => $s->name,
                'kelas' => $s->kelas,
                'hadir' => $hadir,
                'ijin' => $ijin,
                'sakit' => $sakit,
                'tidak' => $tidak,
            ];
        }
        
        return $rekap;
    }
}