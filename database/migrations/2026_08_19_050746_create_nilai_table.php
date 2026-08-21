<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('kelas')->nullable();
            $table->string('tahun_ajaran', 20);
            $table->enum('periode', ['setengah_semester', 'akhir_semester']);
            $table->string('mata_pelajaran', 100);
            $table->decimal('nilai', 5, 2);
            $table->timestamps();
            $table->unique(['user_id', 'tahun_ajaran', 'periode', 'mata_pelajaran'], 'nilai_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
