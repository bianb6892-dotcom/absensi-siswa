<?php

namespace Database\Seeders;

use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TambahSiswaUntukOrtuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. PASTIKAN AKUN ORANG TUA ortu@budi.com ADA
        $ortu = User::where('email', 'ortu@budi.com')->first();

        if (! $ortu) {
            $ortu = User::create([
                'name' => 'Budi Santoso',
                'email' => 'ortu@budi.com',
                'password' => Hash::make('password'),
                'role' => 'ortu',
            ]);
            $this->command->info('✅ Akun ortu@budi.com berhasil dibuat');
        }

        // 2. PASTIKAN DATA ORANG TUA (tabel orang_tua) ADA
        $ortuData = OrangTua::where('user_id', $ortu->id)->first();

        if (! $ortuData) {
            $ortuData = OrangTua::create(['user_id' => $ortu->id]);
            $this->command->info('✅ Data orang tua di tabel orang_tua berhasil dibuat');
        }

        // 3. BUAT SISWA UNTUK ortu@budi.com (jika belum ada)
        $siswa = User::where('email', 'budi.jr@siswa.com')->first();

        if (! $siswa) {
            User::create([
                'name' => 'Budi Santoso Jr',
                'email' => 'budi.jr@siswa.com',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'kelas' => 'X PPLG',
                'ortu_id' => $ortuData->id,  // Hubungkan ke orang tua
            ]);
            $this->command->info('✅ Siswa Budi Santoso Jr berhasil dibuat dan dihubungkan ke ortu@budi.com');
        } else {
            $this->command->info('⚠️ Siswa budi.jr@siswa.com sudah ada, dilewati');
        }

        // 4. DAFTAR ORANG TUA YANG SUDAH PUNYA ANAK
        $this->command->info("\n📋 Daftar orang tua yang sudah punya anak:");
        $ortuWithAnak = User::whereHas('orangTua')->get();
        foreach ($ortuWithAnak as $o) {
            $this->command->line("- {$o->name} ({$o->email})");
        }
    }
}
