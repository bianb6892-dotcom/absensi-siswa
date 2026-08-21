<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah ENUM: hapus 'siswa', tambah 'guru'
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('ortu')->change();
        });

        // Update data: siswa → guru
        DB::statement("UPDATE users SET role = 'guru' WHERE role = 'siswa'");

        // Hapus data siswa yang tidak perlu (opsional)
        // DB::statement("DELETE FROM users WHERE role = 'siswa'");
    }

    public function down(): void
    {
        // Update data dulu sebelum ubah tipe kolom, agar tidak ada nilai yang terpotong
        DB::statement("UPDATE users SET role = 'siswa' WHERE role = 'guru'");

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'siswa', 'ortu'])->default('siswa')->change();
        });
    }
};
