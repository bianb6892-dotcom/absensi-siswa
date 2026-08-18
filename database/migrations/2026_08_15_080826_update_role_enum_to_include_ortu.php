<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        // Kembalikan ke ENUM (hati-hati dengan data)
        Schema::table('users', function (Blueprint $table) {
            // Hanya bisa di-down jika tidak ada data yang conflict
            $table->enum('role', ['admin', 'siswa', 'ortu'])->default('siswa')->change();
        });
    }
};