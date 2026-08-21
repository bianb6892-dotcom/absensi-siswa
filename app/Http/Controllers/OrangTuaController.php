<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Gallery;
use App\Models\Nilai;
use App\Models\Notifikasi;
use App\Models\OrangTua;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrangTuaController extends Controller
{
    public function dashboard()
    {
        $ortu = OrangTua::where('user_id', auth()->user()->id)->first();

        if (! $ortu) {
            return redirect()->back()->with('error', 'Data orang tua tidak ditemukan!');
        }

        $anak = User::where('role', 'siswa')
            ->where('ortu_id', $ortu->id)
            ->orderBy('name')
            ->get();

        $data = [];
        foreach ($anak as $siswa) {
            $bulanIniRows = Absensi::where('user_id', $siswa->id)
                ->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year)
                ->get(['keterangan'])
                ->countBy('keterangan');

            $data[] = [
                'siswa' => $siswa,
                'hadir' => $bulanIniRows->get('hadir', 0),
                'ijin' => $bulanIniRows->get('ijin', 0),
                'sakit' => $bulanIniRows->get('sakit', 0),
                'alpa' => $bulanIniRows->get('tidak_masuk', 0),
                'total' => $bulanIniRows->sum(),
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

        $semuaAnak = User::where('role', 'siswa')
            ->where('ortu_id', $ortu->id)
            ->get();

        foreach ($semuaAnak as $siswa) {
            $rowsPerBulan = Absensi::where('user_id', $siswa->id)
                ->whereYear('tanggal', Carbon::now()->year)
                ->get(['tanggal', 'keterangan'])
                ->groupBy(fn ($absensi) => $absensi->tanggal->month)
                ->map(fn ($items) => $items->countBy('keterangan'));

            foreach ($bulanList as $index => $bulan) {
                $bulanNumber = $index + 1;
                $counts = $rowsPerBulan->get($bulanNumber, collect());

                $rekapBulanan[$siswa->id][$bulan] = [
                    'hadir' => $counts->get('hadir', 0),
                    'ijin' => $counts->get('ijin', 0),
                    'sakit' => $counts->get('sakit', 0),
                    'alpa' => $counts->get('tidak_masuk', 0),
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
        // NOTIFIKASI UNTUK ORANG TUA
        // ============================================
        $notifikasi = Notifikasi::with('siswa')
            ->where('ortu_id', $ortu->id)
            ->latest()
            ->take(5)
            ->get();

        // ============================================
        // KIRIM SEMUA VARIABLE KE VIEW (SATU RETURN)
        // ============================================
        return view('ortu.dashboard', compact('data', 'rekapBulanan', 'bulanList', 'kegiatan', 'jurusan', 'eskul', 'notifikasi'));
    }

    public function anak($id)
    {
        $ortu = OrangTua::where('user_id', auth()->user()->id)->first();

        if (! $ortu) {
            abort(403, 'Data orang tua tidak ditemukan.');
        }

        $siswa = User::findOrFail($id);

        if ($siswa->role !== 'siswa' || $siswa->ortu_id != $ortu->id) {
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

    public function nilai(Request $request)
    {
        $ortu = OrangTua::where('user_id', auth()->user()->id)->first();

        if (! $ortu) {
            return redirect()->back()->with('error', 'Data orang tua tidak ditemukan!');
        }

        $anak = User::where('ortu_id', $ortu->id)
            ->where('role', 'siswa')
            ->orderBy('name')
            ->get();

        $tahunAjaran = $request->get('tahun', $this->tahunAjaranDefault());
        $daftarTahunAjaran = $this->daftarTahunAjaran();

        $nilai = [];
        foreach ($anak as $siswa) {
            $nilai[$siswa->id] = [
                'setengah_semester' => Nilai::where('user_id', $siswa->id)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->where('periode', 'setengah_semester')
                    ->orderBy('mata_pelajaran')
                    ->get(),
                'akhir_semester' => Nilai::where('user_id', $siswa->id)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->where('periode', 'akhir_semester')
                    ->orderBy('mata_pelajaran')
                    ->get(),
            ];
        }

        return view('ortu.nilai', compact('anak', 'nilai', 'tahunAjaran', 'daftarTahunAjaran'));
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
