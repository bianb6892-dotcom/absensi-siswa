<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Imports\AbsensiImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    // Daftar kelas yang tersedia
    private $daftarKelas = [
        'X PPLG' => 'X PPLG',
        'X TJKT' => 'X TJKT',
        'X ACP' => 'X ACP',
        'X AKL' => 'X AKL',
        'XI PPLG' => 'XI PPLG',
        'XI TJKT' => 'XI TJKT',
        'XI ACP' => 'XI ACP',
        'XI AKL' => 'XI AKL',
        'XII PPLG' => 'XII PPLG',
        'XII TJKT' => 'XII TJKT',
        'XII ACP' => 'XII ACP',
        'XII AKL' => 'XII AKL',
    ];

    public function index(Request $request)
    {
        $kelasTerpilih = $request->get('kelas', session('kelas_aktif', 'X PPLG'));
        
        session(['kelas_aktif' => $kelasTerpilih]);
        
        $absensis = Absensi::with('user')
            ->where('kelas', $kelasTerpilih)
            ->orderBy('tanggal', 'desc')
            ->paginate(25);
        
        $siswa = User::where('role', 'siswa')
            ->where('kelas', $kelasTerpilih)
            ->orderBy('name')
            ->get();
        
        return view('admin.absensi-index', compact('absensis', 'siswa', 'kelasTerpilih'));
    }

    public function create(Request $request)
    {
        $kelasTerpilih = $request->get('kelas', session('kelas_aktif', 'X PPLG'));
        
        session(['kelas_aktif' => $kelasTerpilih]);
        
        $siswa = User::where('role', 'siswa')
            ->where('kelas', $kelasTerpilih)
            ->orderBy('name')
            ->get();
        
        return view('admin.absensi-create', compact('siswa', 'kelasTerpilih'));
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

        return redirect()->route('absensi.create', ['kelas' => $request->kelas])
            ->with('success', 'Absensi untuk kelas ' . $request->kelas . ' berhasil disimpan!');
    }

    // Method untuk Import Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'kelas' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        try {
            $import = new AbsensiImport($request->kelas, $request->tanggal);
            Excel::import($import, $request->file('file'));

            $failures = $import->failures();
            
            if (count($failures) > 0) {
                $errorMessages = [];
                foreach ($failures as $failure) {
                    $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
                }
                return redirect()->back()
                    ->with('warning', 'Data berhasil diimport sebagian. Ada beberapa data yang gagal:')
                    ->with('errors', $errorMessages);
            }

            return redirect()->route('absensi.create', ['kelas' => $request->kelas])
                ->with('success', 'Data absensi untuk kelas ' . $request->kelas . ' berhasil diimport!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Download Template Excel
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_absensi.csv"',
        ];

        $columns = ['nama', 'keterangan'];
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Contoh data
            fputcsv($file, ['Budi Santoso', 'hadir']);
            fputcsv($file, ['Ani Rahayu', 'ijin']);
            fputcsv($file, ['Citra Dewi', 'sakit']);
            fputcsv($file, ['Dedi Firmansyah', 'tidak_masuk']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show($kelas)
    {
        session(['kelas_aktif' => $kelas]);
        
        $absensis = Absensi::with('user')
            ->where('kelas', $kelas)
            ->orderBy('tanggal', 'desc')
            ->paginate(25);
        
        $siswa = User::where('role', 'siswa')
            ->where('kelas', $kelas)
            ->orderBy('name')
            ->get();
        
        return view('admin.absensi-index', compact('absensis', 'siswa', 'kelas'));
    }

    public function dashboard()
    {
        $kelasTerpilih = session('kelas_aktif', 'X PPLG');
        $siswa = User::where('role', 'siswa')
            ->where('kelas', $kelasTerpilih)
            ->orderBy('name')
            ->get();
        
        $rekap = [];
        foreach ($siswa as $s) {
            $hadir = Absensi::where('user_id', $s->id)
                ->where('kelas', $kelasTerpilih)
                ->whereMonth('tanggal', now()->month)
                ->where('keterangan', 'hadir')
                ->count();
                
            $ijin = Absensi::where('user_id', $s->id)
                ->where('kelas', $kelasTerpilih)
                ->whereMonth('tanggal', now()->month)
                ->where('keterangan', 'ijin')
                ->count();
                
            $sakit = Absensi::where('user_id', $s->id)
                ->where('kelas', $kelasTerpilih)
                ->whereMonth('tanggal', now()->month)
                ->where('keterangan', 'sakit')
                ->count();
                
            $tidak = Absensi::where('user_id', $s->id)
                ->where('kelas', $kelasTerpilih)
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
        
        return view('admin.dashboard', compact('rekap', 'kelasTerpilih'));
    }
}