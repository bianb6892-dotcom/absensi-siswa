<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\OrangTua;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AbsensiCepatController extends Controller
{
    private $daftarKelas = [
        'X PPLG', 'X TJKT', 'X ACP', 'X AKL',
        'XI PPLG', 'XI TJKT', 'XI ACP', 'XI AKL',
        'XII PPLG', 'XII TJKT', 'XII ACP', 'XII AKL',
    ];

    public function index(Request $request)
    {
        $kelasTerpilih = $request->get('kelas', session('kelas_aktif', 'X PPLG'));
        session(['kelas_aktif' => $kelasTerpilih]);

        $siswa = User::where('role', 'guru')
            ->where('kelas', $kelasTerpilih)
            ->orderBy('name')
            ->get();

        // Cek absensi hari ini
        foreach ($siswa as $s) {
            $absensi = Absensi::where('user_id', $s->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();
            $s->status_hari_ini = $absensi ? $absensi->keterangan : 'belum';
        }

        return view('admin.absensi-cepat', compact('siswa', 'kelasTerpilih'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required|string',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*' => 'in:hadir,ijin,sakit,tidak_masuk',
        ]);

        $notifikasiDikirim = 0;

        DB::transaction(function () use ($request, &$notifikasiDikirim) {
            foreach ($request->absensi as $userId => $keterangan) {
                // Simpan absensi
                $absensi = Absensi::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'kelas' => $request->kelas,
                        'tanggal' => $request->tanggal
                    ],
                    ['keterangan' => $keterangan]
                );

                // Kirim notifikasi jika ada perubahan
                $siswa = User::find($userId);
                if ($siswa && $siswa->ortu_id) {
                    $this->kirimNotifikasi($siswa, $keterangan);
                    $notifikasiDikirim++;
                }
            }
        });

        $message = 'Absensi berhasil disimpan! ';
        if ($notifikasiDikirim > 0) {
            $message .= "$notifikasiDikirim notifikasi dikirim ke orang tua.";
        }

        return redirect()->route('absensi.cepat', ['kelas' => $request->kelas])
            ->with('success', $message);
    }

    public function hadirkanSemua(Request $request)
    {
        $kelas = $request->kelas;
        $tanggal = $request->tanggal ?? Carbon::today()->format('Y-m-d');

        $siswa = User::where('role', 'guru')
            ->where('kelas', $kelas)
            ->get();

        $notifikasiDikirim = 0;

        DB::transaction(function () use ($siswa, $kelas, $tanggal, &$notifikasiDikirim) {
            foreach ($siswa as $s) {
                // Cek apakah sudah ada absensi hari ini
                $exists = Absensi::where('user_id', $s->id)
                    ->whereDate('tanggal', $tanggal)
                    ->exists();

                if (!$exists) {
                    // Buat absensi baru dengan status 'hadir'
                    Absensi::create([
                        'user_id' => $s->id,
                        'kelas' => $kelas,
                        'tanggal' => $tanggal,
                        'keterangan' => 'hadir',
                    ]);

                    // Kirim notifikasi ke orang tua
                    if ($s->ortu_id) {
                        $this->kirimNotifikasi($s, 'hadir');
                        $notifikasiDikirim++;
                    }
                }
            }
        });

        $message = 'Semua siswa dihadirkan! ';
        if ($notifikasiDikirim > 0) {
            $message .= "$notifikasiDikirim notifikasi dikirim ke orang tua.";
        }

        return redirect()->route('absensi.cepat', ['kelas' => $kelas])
            ->with('success', $message);
    }

    private function kirimNotifikasi($siswa, $status)
    {
        $statusLabel = [
            'hadir' => '✅ Hadir',
            'ijin' => '📝 Izin',
            'sakit' => '🤒 Sakit',
            'tidak_masuk' => '❌ Tidak Masuk',
        ];

        $pesan = "👋 Yth. Orang tua dari {$siswa->name}\n\n";
        $pesan .= "Anak Anda hari ini: {$statusLabel[$status]}\n";
        $pesan .= "📅 Tanggal: " . Carbon::today()->format('d F Y') . "\n";
        $pesan .= "🏫 Kelas: {$siswa->kelas}\n\n";
        $pesan .= "Terima kasih telah memantau pendidikan anak Anda. 🙏";

        Notifikasi::create([
            'ortu_id' => $siswa->ortu_id,
            'siswa_id' => $siswa->id,
            'judul' => "Status Kehadiran - " . Carbon::today()->format('d F Y'),
            'pesan' => $pesan,
            'status' => 'pending',
            'jenis' => 'kehadiran',
        ]);
    }

    public function getSiswaByKelas($kelas)
    {
        $siswa = User::where('role', 'guru')
            ->where('kelas', $kelas)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($siswa);
    }
}