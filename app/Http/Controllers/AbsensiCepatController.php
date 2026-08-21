<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Notifikasi;
use App\Models\User;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

        $siswa = User::where('role', 'siswa')
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

        $validIds = User::where('role', 'siswa')
            ->where('kelas', $request->kelas)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        if (array_diff(array_keys($request->absensi), $validIds)) {
            abort(403, 'Salah satu siswa tidak valid untuk kelas ini.');
        }

        $notifikasiDikirim = 0;

        // Normalisasi tanggal agar cocok dengan format penyimpanan (Y-m-d H:i:s)
        $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d H:i:s');

        DB::transaction(function () use ($request, $tanggal, &$notifikasiDikirim) {
            foreach ($request->absensi as $userId => $keterangan) {
                // Simpan absensi
                $absensi = Absensi::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'kelas' => $request->kelas,
                        'tanggal' => $tanggal,
                    ],
                    ['keterangan' => $keterangan]
                );

                // Kirim notifikasi hanya jika status berubah
                $siswa = User::find($userId);
                if ($siswa && $siswa->ortu_id && ($absensi->wasChanged() || $absensi->wasRecentlyCreated)) {
                    $this->kirimNotifikasi($siswa, $keterangan, $tanggal);
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
        $request->validate([
            'kelas' => 'required|string',
            'tanggal' => 'nullable|date',
        ]);

        $kelas = $request->kelas;
        $tanggal = $request->tanggal ?? Carbon::today()->format('Y-m-d');

        $siswa = User::where('role', 'siswa')
            ->where('kelas', $kelas)
            ->get();

        $notifikasiDikirim = 0;

        DB::transaction(function () use ($siswa, $kelas, $tanggal, &$notifikasiDikirim) {
            foreach ($siswa as $s) {
                // Cek apakah sudah ada absensi hari ini
                $exists = Absensi::where('user_id', $s->id)
                    ->whereDate('tanggal', $tanggal)
                    ->exists();

                if (! $exists) {
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

    private function kirimNotifikasi($siswa, $status, $tanggal)
    {
        $statusLabel = [
            'hadir' => '✅ Hadir',
            'ijin' => '📝 Izin',
            'sakit' => '🤒 Sakit',
            'tidak_masuk' => '❌ Tidak Masuk',
        ];

        $tanggalCarbon = Carbon::parse($tanggal);

        $pesan = "👋 Yth. Orang tua dari {$siswa->name}\n\n";
        $pesan .= "Anak Anda hari ini: {$statusLabel[$status]}\n";
        $pesan .= '📅 Tanggal: '.$tanggalCarbon->format('d F Y')."\n";
        $pesan .= "🏫 Kelas: {$siswa->kelas}\n\n";
        $pesan .= 'Terima kasih telah memantau pendidikan anak Anda. 🙏';

        // Kirim via WhatsApp jika nomor orang tua terisi & gateway dikonfigurasi
        $noWa = $siswa->orangTua?->no_wa;
        $statusWa = 'pending';
        if ($noWa) {
            $statusWa = WhatsAppService::kirim($noWa, $pesan) ? 'terkirim' : 'gagal';
        }

        Notifikasi::create([
            'ortu_id' => $siswa->ortu_id,
            'siswa_id' => $siswa->id,
            'judul' => 'Status Kehadiran - '.$tanggalCarbon->format('d F Y'),
            'pesan' => $pesan,
            'status' => $statusWa,
            'jenis' => 'kehadiran',
            'dikirim_at' => now(),
        ]);
    }

    public function getSiswaByKelas($kelas)
    {
        $siswa = User::where('role', 'siswa')
            ->where('kelas', $kelas)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($siswa);
    }
}
