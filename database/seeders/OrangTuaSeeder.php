<?php

namespace Database\Seeders;

use App\Models\OrangTua;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrangTuaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua akun dengan role ortu (jangan ubah role user lain)
        $ortuUsers = User::where('role', 'ortu')->get();

        foreach ($ortuUsers as $ortuUser) {
            // Lengkapi data orang tua untuk akun ortu yang belum punya
            OrangTua::updateOrCreate(
                ['user_id' => $ortuUser->id],
                [
                    'no_wa' => '0812345678'.rand(10, 99),
                    'alamat' => 'Jl. Contoh No. '.rand(1, 100),
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
