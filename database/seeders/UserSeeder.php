<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@absensi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Buat siswa
        $siswa = [
            ['name' => 'Budi Santoso', 'email' => 'budi@siswa.com'],
            ['name' => 'Ani Rahayu', 'email' => 'ani@siswa.com'],
            ['name' => 'Citra Dewi', 'email' => 'citra@siswa.com'],
            ['name' => 'Dedi Firmansyah', 'email' => 'dedi@siswa.com'],
            ['name' => 'Eka Putri', 'email' => 'eka@siswa.com'],
        ];

        foreach ($siswa as $s) {
            User::create([
                'name' => $s['name'],
                'email' => $s['email'],
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]);
        }
    }
}