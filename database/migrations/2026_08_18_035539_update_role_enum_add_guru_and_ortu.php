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
            $table->string('role')->default('ortu')->change();
        });

        // Update role yang sudah ada
        DB::statement("UPDATE users SET role = 'ortu' WHERE role = 'siswa'");
        DB::statement("UPDATE users SET role = 'admin' WHERE role = 'admin'");
    }

    public function down(): void
    {
        // Update data dulu agar tidak ada nilai di luar enum saat tipe kolom dikembalikan
        DB::statement("UPDATE users SET role = 'ortu' WHERE role NOT IN ('admin', 'guru', 'ortu')");

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'guru', 'ortu'])->default('ortu')->change();
        });
    }
};
