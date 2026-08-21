<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $admin = User::where('email', 'admin@absensi.com')->first();
        if (! $admin) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@absensi.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
            $this->command->info('✅ Admin berhasil dibuat');
        } else {
            $this->command->info('⚠️ Admin sudah ada, dilewati');
        }

        // Cek apakah guru sudah ada
        $guru = User::where('email', 'guru@absensi.com')->first();
        if (! $guru) {
            User::create([
                'name' => 'Guru PPLG',
                'email' => 'guru@absensi.com',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'kelas' => 'X PPLG',
            ]);
            $this->command->info('✅ Guru berhasil dibuat');
        } else {
            $this->command->info('⚠️ Guru sudah ada, dilewati');
        }
    }
}
