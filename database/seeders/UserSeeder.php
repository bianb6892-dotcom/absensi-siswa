<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat siswa
        $siswa = [
            ['name' => 'Budi Santoso', 'email' => 'budi@siswa.com', 'kelas' => 'X PPLG'],
            ['name' => 'Ani Rahayu', 'email' => 'ani@siswa.com', 'kelas' => 'X PPLG'],
            ['name' => 'Citra Dewi', 'email' => 'citra@siswa.com', 'kelas' => 'X TJKT'],
            ['name' => 'Dedi Firmansyah', 'email' => 'dedi@siswa.com', 'kelas' => 'X TJKT'],
            ['name' => 'Eka Putri', 'email' => 'eka@siswa.com', 'kelas' => 'X PPLG'],
        ];

        foreach ($siswa as $s) {
            User::updateOrCreate(
                ['email' => $s['email']],
                [
                    'name' => $s['name'],
                    'password' => Hash::make('password'),
                    'role' => 'siswa',
                    'kelas' => $s['kelas'],
                ]
            );
        }
    }
}
