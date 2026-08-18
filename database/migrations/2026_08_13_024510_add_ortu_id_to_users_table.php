<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'ortu_id')) {
                $table->foreignId('ortu_id')->nullable()->constrained('orang_tua')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'nis')) {
                $table->string('nis')->nullable()->unique()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'ortu_id')) {
                $table->dropForeign(['ortu_id']);
                $table->dropColumn('ortu_id');
            }
            if (Schema::hasColumn('users', 'nis')) {
                $table->dropColumn('nis');
            }
        });
    }
};