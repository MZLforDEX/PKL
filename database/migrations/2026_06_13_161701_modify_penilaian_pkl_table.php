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
        Schema::table('penilaian_pkl', function (Blueprint $table) {
            $table->integer('nilai_sikap')->nullable()->change();
            $table->integer('nilai_keterampilan')->nullable()->change();
            $table->integer('nilai_laporan')->nullable()->change();
            $table->json('detail_nilai')->nullable()->after('catatan_evaluasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_pkl', function (Blueprint $table) {
            $table->integer('nilai_sikap')->nullable(false)->change();
            $table->integer('nilai_keterampilan')->nullable(false)->change();
            $table->integer('nilai_laporan')->nullable(false)->change();
            $table->dropColumn('detail_nilai');
        });
    }
};
