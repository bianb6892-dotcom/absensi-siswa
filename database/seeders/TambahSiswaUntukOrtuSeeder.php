<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\OrangTua;
use Illuminate\Support\Facades\Hash;

class TambahSiswaUntukOrtuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT SISWA UNTUK ortu@budi.com
        $ortu = User::where('email', 'ortu@budi.com')->first();
        
        if (!$ortu) {
            $this->command->error('❌ Orang tua dengan email ortu@budi.com tidak ditemukan!');
            return;
        }

        // Cari data orang tua (dari tabel orang_tua)
        $ortuData = OrangTua::where('user_id', $ortu->id)->first();
        
        if (!$ortuData) {
            $this->command->error('❌ Data orang tua tidak ditemukan di tabel orang_tua!');
            return;
        }

        // Buat siswa baru
        $siswa = User::create([
            'name' => 'Budi Santoso Jr',
            'email' => 'budi.jr@siswa.com',
            'password' => Hash::make('password'),
            'role' => 'guru',  // role guru = siswa
            'kelas' => 'X PPLG',
            'ortu_id' => $ortuData->id,  // Hubungkan ke orang tua
        ]);

        $this->command->info("✅ Siswa Budi Santoso Jr berhasil dibuat dan dihubungkan ke {$ortu->name}");

        // 2. BUAT SISWA UNTUK SEMUA ORANG TUA LAIN (opsional)
        $this->command->info("\n📋 Daftar orang tua yang sudah punya anak:");
        $ortuWithAnak = User::whereHas('orangTua')->get();
        foreach ($ortuWithAnak as $o) {
            $this->command->line("- {$o->name} ({$o->email})");
        }
    }
}