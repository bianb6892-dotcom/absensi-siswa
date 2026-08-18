<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\OrangTua;
use Illuminate\Support\Facades\Hash;

class OrangTuaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua user dengan email @siswa.com
        $siswaList = User::where('email', 'like', '%@siswa.com')->get();

        foreach ($siswaList as $siswa) {
            // Update role menjadi 'ortu'
            $siswa->update([
                'role' => 'ortu',
                'name' => str_replace(' ', ' ', 'Bapak/Ibu ' . $siswa->name),
            ]);

            // Buat data orang tua
            OrangTua::updateOrCreate(
                ['user_id' => $siswa->id],
                [
                    'no_wa' => '0812345678' . rand(10, 99),
                    'alamat' => 'Jl. Contoh No. ' . rand(1, 100),
                    'pekerjaan' => 'Wiraswasta',
                ]
            );
        }

        // Buat orang tua untuk user ortu@budi.com (kalau belum)
        $ortuUser = User::where('email', 'ortu@budi.com')->first();
        if ($ortuUser) {
            OrangTua::updateOrCreate(
                ['user_id' => $ortuUser->id],
                [
                    'no_wa' => '081234567890',
                    'alamat' => 'Jl. Merdeka No. 123',
                    'pekerjaan' => 'Wiraswasta',
                ]
            );
        }
    }
}