<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tunggakan_spp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('kelas')->nullable();
            // Tanggal 1 dari bulan tunggakan, contoh: 2026-06-01 untuk "Juni 2026"
            $table->date('bulan');
            $table->decimal('jumlah', 12, 2);
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'bulan'], 'tunggakan_spp_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tunggakan_spp');
    }
};
