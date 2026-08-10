<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua user dengan role siswa
        $siswaIds = User::where('role', 'siswa')->pluck('id');
        
        // Buat data absensi untuk bulan Juli 2026 (seperti di gambar)
        $tanggalMulai = Carbon::create(2026, 7, 1);
        $tanggalSelesai = Carbon::create(2026, 7, 31);
        
        foreach ($siswaIds as $id) {
            $tanggal = clone $tanggalMulai;
            while ($tanggal <= $tanggalSelesai) {
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
                
                Absensi::create([
                    'user_id' => $id,
                    'tanggal' => $tanggal->format('Y-m-d'),
                    'keterangan' => $keterangan,
                ]);
                
                $tanggal->addDay();
            }
        }
    }
}