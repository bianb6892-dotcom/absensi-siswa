<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom role dari ENUM menjadi VARCHAR dulu
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('siswa')->change();
        });

        // Update data yang ada
        DB::statement("UPDATE users SET role = 'siswa' WHERE role NOT IN ('admin', 'siswa', 'ortu')");
    }

    public function down(): void
    {
        // Update data dulu agar tidak ada nilai di luar enum saat tipe kolom dikembalikan
        DB::statement("UPDATE users SET role = 'siswa' WHERE role NOT IN ('admin', 'siswa')");

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'siswa'])->default('siswa')->change();
        });
    }
};
