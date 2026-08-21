<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user dengan role siswa
        $siswa = User::where('role', 'siswa')->whereNotNull('kelas')->get();

        if ($siswa->isEmpty()) {
            $this->command->warn('Tidak ada siswa (role siswa dengan kelas). Seeder absensi dilewati.');

            return;
        }

        // Buat data absensi untuk bulan berjalan (agar terlihat di rekap dashboard)
        $tanggalMulai = Carbon::now()->startOfMonth();
        $tanggalSelesai = Carbon::now()->endOfMonth();

        foreach ($siswa as $s) {
            $tanggal = clone $tanggalMulai;
            while ($tanggal <= $tanggalSelesai) {
                // Skip hari Minggu (libur)
                if ($tanggal->dayOfWeek === Carbon::SUNDAY) {
                    $tanggal->addDay();

                    continue;
                }

                // Random status, dengan peluang lebih besar untuk hadir
                $random = rand(1, 10);
                if ($random <= 7) {
                    $keterangan = 'hadir';
                } elseif ($random <= 8) {
                    $keterangan = 'ijin';
                } elseif ($random <= 9) {
                    $keterangan = 'sakit';
                } else {
                    $keterangan = 'tidak_masuk';
                }

                Absensi::updateOrCreate(
                    [
                        'user_id' => $s->id,
                        'kelas' => $s->kelas,
                        'tanggal' => $tanggal->format('Y-m-d'),
                    ],
                    ['keterangan' => $keterangan]
                );

                $tanggal->addDay();
            }
        }

        $this->command->info('Absensi berhasil di-seed untuk bulan '.$tanggalMulai->translatedFormat('F Y'));
    }
}
