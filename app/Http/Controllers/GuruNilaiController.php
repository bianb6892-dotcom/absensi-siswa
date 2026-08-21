<?php

namespace App\Http\Controllers;

use App\Imports\NilaiImport;
use App\Models\Nilai;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GuruNilaiController extends Controller
{
    private $daftarKelas = [
        'X PPLG', 'X TJKT', 'X ACP', 'X AKL',
        'XI PPLG', 'XI TJKT', 'XI ACP', 'XI AKL',
        'XII PPLG', 'XII TJKT', 'XII ACP', 'XII AKL',
    ];

    public function index(Request $request, $periode)
    {
        $periodeDb = $this->mapPeriode($periode);

        $kelasTerpilih = $request->get('kelas', session('kelas_aktif', 'X PPLG'));
        $tahunAjaran = $request->get('tahun', $this->tahunAjaranDefault());
        $daftarTahunAjaran = $this->daftarTahunAjaran();

        session(['kelas_aktif' => $kelasTerpilih]);

        $daftarKelas = $this->daftarKelas;

        $siswa = User::where('role', 'siswa')
            ->where('kelas', $kelasTerpilih)
            ->orderBy('name')
            ->get();

        $nilaiList = Nilai::with('user')
            ->where('kelas', $kelasTerpilih)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('periode', $periodeDb)
            ->orderBy('mata_pelajaran')
            ->orderBy('user_id')
            ->paginate(25);

        return view('guru.nilai-index', compact(
            'periode',
            'periodeDb',
            'kelasTerpilih',
            'tahunAjaran',
            'daftarTahunAjaran',
            'daftarKelas',
            'siswa',
            'nilaiList'
        ));
    }

    public function store(Request $request, $periode)
    {
        $periodeDb = $this->mapPeriode($periode);

        $request->validate([
            'kelas' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'mata_pelajaran' => 'required|string|max:100',
            'nilai' => 'nullable|array',
            'nilai.*' => 'nullable|numeric|between:0,100',
        ]);

        $validIds = User::where('role', 'siswa')
            ->where('kelas', $request->kelas)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        if (array_diff(array_keys($request->nilai ?? []), $validIds)) {
            abort(403, 'Salah satu siswa tidak valid untuk kelas ini.');
        }

        foreach (($request->nilai ?? []) as $userId => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            Nilai::updateOrCreate(
                [
                    'user_id' => $userId,
                    'kelas' => $request->kelas,
                    'tahun_ajaran' => $request->tahun_ajaran,
                    'periode' => $periodeDb,
                    'mata_pelajaran' => $request->mata_pelajaran,
                ],
                ['nilai' => $value]
            );
        }

        session(['kelas_aktif' => $request->kelas]);

        return redirect()->route('guru.nilai.index', [
            'periode' => $periode,
            'kelas' => $request->kelas,
            'tahun' => $request->tahun_ajaran,
        ])->with('success', 'Nilai '.$request->mata_pelajaran.' untuk kelas '.$request->kelas.' berhasil disimpan!');
    }

    public function import(Request $request, $periode)
    {
        $periodeDb = $this->mapPeriode($periode);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
            'kelas' => 'required|string',
            'tahun_ajaran' => 'required|string',
        ]);

        try {
            $import = new NilaiImport($request->kelas, $request->tahun_ajaran, $periodeDb);
            Excel::import($import, $request->file('file'));

            $failures = $import->failures();

            if (count($failures) > 0) {
                $errorMessages = [];
                foreach ($failures as $failure) {
                    $errorMessages[] = "Row {$failure->row()}: ".implode(', ', $failure->errors());
                }

                return redirect()->route('guru.nilai.index', [
                    'periode' => $periode,
                    'kelas' => $request->kelas,
                    'tahun' => $request->tahun_ajaran,
                ])->with('warning', 'Data berhasil diimport sebagian. Beberapa baris gagal:')
                    ->withErrors($errorMessages);
            }

            return redirect()->route('guru.nilai.index', [
                'periode' => $periode,
                'kelas' => $request->kelas,
                'tahun' => $request->tahun_ajaran,
            ])->with('success', 'Data nilai berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function downloadTemplate($periode)
    {
        $this->mapPeriode($periode);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_nilai.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['nama', 'mata_pelajaran', 'nilai']);
            fputcsv($file, ['Budi Santoso', 'Matematika', 85]);
            fputcsv($file, ['Ani Rahayu', 'Matematika', 92]);
            fputcsv($file, ['Citra Dewi', 'Matematika', 76]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();

        return redirect()->back()->with('success', 'Data nilai berhasil dihapus!');
    }

    private function mapPeriode($slug)
    {
        if ($slug === 'setengah-semester') {
            return 'setengah_semester';
        }
        if ($slug === 'akhir-semester') {
            return 'akhir_semester';
        }
        abort(404, 'Periode tidak ditemukan.');
    }

    private function tahunAjaranDefault()
    {
        return now()->year.'/'.(now()->year + 1);
    }

    private function daftarTahunAjaran()
    {
        $tahun = now()->year;
        $list = [];
        for ($i = $tahun - 2; $i <= $tahun + 1; $i++) {
            $list[$i.'/'.($i + 1)] = $i.'/'.($i + 1);
        }

        return $list;
    }
}
