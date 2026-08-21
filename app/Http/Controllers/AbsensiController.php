<?php

namespace App\Http\Controllers;

use App\Imports\AbsensiImport;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function export(Request $request)
    {
        $kelas = $request->get('kelas');
        $bulan = $request->get('bulan', now()->format('Y-m'));

        $start = Carbon::parse($bulan.'-01')->startOfDay();
        $end = $start->copy()->endOfMonth()->endOfDay();

        $query = User::where('role', 'siswa')->orderBy('name');
        if ($kelas) {
            $query->where('kelas', $kelas);
        }
        $siswa = $query->get();

        $idSiswa = $siswa->pluck('id');

        $absensi = Absensi::whereIn('user_id', $idSiswa)
            ->whereBetween('tanggal', [$start, $end])
            ->get()
            ->groupBy('user_id');

        $dates = Absensi::whereIn('user_id', $idSiswa)
            ->whereBetween('tanggal', [$start, $end])
            ->selectRaw('DATE(tanggal) as tgl')
            ->distinct()
            ->orderBy('tgl')
            ->pluck('tgl');

        $statusSingkat = ['hadir' => 'H', 'ijin' => 'I', 'sakit' => 'S', 'tidak_masuk' => 'A'];

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rekap_absensi_'.$kelas.'_'.$bulan.'.csv"',
        ];

        $callback = function () use ($siswa, $absensi, $dates, $statusSingkat, $kelas, $bulan) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, ['Rekap Absensi', $kelas ?? 'Semua Kelas', Carbon::parse($bulan.'-01')->translatedFormat('F Y')]);

            $header = ['No', 'NIS', 'Nama', 'Kelas', 'Hadir', 'Izin', 'Sakit', 'Tidak Masuk'];
            foreach ($dates as $d) {
                $header[] = Carbon::parse($d)->format('d');
            }
            fputcsv($file, $header);

            $no = 1;
            foreach ($siswa as $s) {
                $rows = $absensi->get($s->id, collect());
                $counts = $rows->groupBy('keterangan')->map->count();

                $baris = [
                    $no++,
                    $s->nis,
                    $s->name,
                    $s->kelas,
                    $counts->get('hadir', 0),
                    $counts->get('ijin', 0),
                    $counts->get('sakit', 0),
                    $counts->get('tidak_masuk', 0),
                ];

                $byTgl = $rows->keyBy(fn ($r) => Carbon::parse($r->tanggal)->format('Y-m-d'));
                foreach ($dates as $d) {
                    $baris[] = isset($byTgl[$d]) ? ($statusSingkat[$byTgl[$d]->keterangan] ?? '?') : '';
                }

                fputcsv($file, $baris);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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

        $this->ensureSiswaIds($request);

        // Normalisasi tanggal agar cocok dengan format penyimpanan (Y-m-d H:i:s)
        $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d H:i:s');

        foreach ($request->absensi as $userId => $keterangan) {
            Absensi::updateOrCreate(
                [
                    'user_id' => $userId,
                    'kelas' => $request->kelas,
                    'tanggal' => $tanggal,
                ],
                ['keterangan' => $keterangan]
            );
        }

        session(['kelas_aktif' => $request->kelas]);

        return redirect()->route('absensi.create', ['kelas' => $request->kelas])
            ->with('success', 'Absensi untuk kelas '.$request->kelas.' berhasil disimpan!');
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
                    $errorMessages[] = "Row {$failure->row()}: ".implode(', ', $failure->errors());
                }

                return redirect()->back()
                    ->with('warning', 'Data berhasil diimport sebagian. Ada beberapa data yang gagal:')
                    ->withErrors($errorMessages);
            }

            return redirect()->route('absensi.create', ['kelas' => $request->kelas])
                ->with('success', 'Data absensi untuk kelas '.$request->kelas.' berhasil diimport!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
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
        $callback = function () use ($columns) {
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
        if (! in_array($kelas, $this->daftarKelas)) {
            abort(404, 'Kelas tidak ditemukan.');
        }

        session(['kelas_aktif' => $kelas]);

        $absensis = Absensi::with('user')
            ->where('kelas', $kelas)
            ->orderBy('tanggal', 'desc')
            ->paginate(25);

        $siswa = User::where('role', 'siswa')
            ->where('kelas', $kelas)
            ->orderBy('name')
            ->get();

        return view('admin.absensi-index', [
            'absensis' => $absensis,
            'siswa' => $siswa,
            'kelasTerpilih' => $kelas,
        ]);
    }

    public function dashboard()
    {
        $kelasTerpilih = session('kelas_aktif', 'X PPLG');
        $siswa = User::where('role', 'siswa')
            ->where('kelas', $kelasTerpilih)
            ->orderBy('name')
            ->get();

        $rekap = $this->buildRekap($siswa, $kelasTerpilih);

        return view('admin.dashboard', compact('rekap', 'kelasTerpilih'));
    }

    private function ensureSiswaIds(Request $request): void
    {
        $validIds = User::where('role', 'siswa')
            ->where('kelas', $request->kelas)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        if (array_diff(array_keys($request->absensi ?? []), $validIds)) {
            abort(403, 'Salah satu siswa tidak valid untuk kelas ini.');
        }
    }

    private function buildRekap($siswa, $kelas)
    {
        $barisRekap = Absensi::whereIn('user_id', $siswa->pluck('id'))
            ->where('kelas', $kelas)
            ->whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->selectRaw('user_id, keterangan, COUNT(*) as total')
            ->groupBy('user_id', 'keterangan')
            ->get()
            ->groupBy('user_id');

        $rekap = [];
        foreach ($siswa as $s) {
            $baris = $barisRekap->get($s->id, collect())->keyBy('keterangan');

            $rekap[] = [
                'nama' => $s->name,
                'kelas' => $s->kelas,
                'hadir' => (int) ($baris->get('hadir')->total ?? 0),
                'ijin' => (int) ($baris->get('ijin')->total ?? 0),
                'sakit' => (int) ($baris->get('sakit')->total ?? 0),
                'tidak' => (int) ($baris->get('tidak_masuk')->total ?? 0),
            ];
        }

        return $rekap;
    }
}
