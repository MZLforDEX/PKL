<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_pkl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_pkl_id')->constrained('pengajuan_pkl')->cascadeOnDelete();
            $table->integer('nilai_sikap');
            $table->integer('nilai_keterampilan');
            $table->integer('nilai_laporan');
            $table->decimal('nilai_akhir', 5, 2);
            $table->text('catatan_evaluasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_pkl');
    }
};
