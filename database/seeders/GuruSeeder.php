<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        // Guru (dulu siswa)
        $guru = [
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@siswa.com', 'kelas' => 'X PPLG'],
            ['name' => 'Budi Raharjo', 'email' => 'budi.r@siswa.com', 'kelas' => 'X PPLG'],
            ['name' => 'Citra Lestari', 'email' => 'citra@siswa.com', 'kelas' => 'X PPLG'],
            ['name' => 'Dewi Anggraini', 'email' => 'dewi@siswa.com', 'kelas' => 'X PPLG'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko@siswa.com', 'kelas' => 'X PPLG'],
            ['name' => 'Fajar Nugroho', 'email' => 'fajar@siswa.com', 'kelas' => 'XI TJKT'],
            ['name' => 'Gita Permata', 'email' => 'gita@siswa.com', 'kelas' => 'XI TJKT'],
            ['name' => 'Hendra Gunawan', 'email' => 'hendra@siswa.com', 'kelas' => 'XI TJKT'],
            ['name' => 'Indah Sari', 'email' => 'indah@siswa.com', 'kelas' => 'XI TJKT'],
            ['name' => 'Joko Susilo', 'email' => 'joko@siswa.com', 'kelas' => 'XI TJKT'],
            ['name' => 'Kartika Dewi', 'email' => 'kartika@siswa.com', 'kelas' => 'XII AKL'],
            ['name' => 'Lukman Hakim', 'email' => 'lukman@siswa.com', 'kelas' => 'XII AKL'],
            ['name' => 'Maya Sari', 'email' => 'maya@siswa.com', 'kelas' => 'XII AKL'],
            ['name' => 'Nugroho Wicaksono', 'email' => 'nugroho@siswa.com', 'kelas' => 'XII AKL'],
            ['name' => 'Oktavia Putri', 'email' => 'oktavia@siswa.com', 'kelas' => 'XII AKL'],
        ];

        foreach ($guru as $g) {
            User::create([
                'name' => $g['name'],
                'email' => $g['email'],
                'password' => Hash::make('password'),
                'role' => 'guru',
                'kelas' => $g['kelas'],
            ]);
        }
    }
}