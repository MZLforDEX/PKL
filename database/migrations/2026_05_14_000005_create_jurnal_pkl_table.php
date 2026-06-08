<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnal_pkl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_pkl_id')->constrained('pengajuan_pkl')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('kegiatan');
            $table->text('kendala')->nullable();
            $table->string('dokumentasi')->nullable();
            $table->string('status')->default('menunggu_validasi');
            $table->text('catatan_guru')->nullable();
            $table->timestamps();
            $table->unique(['pengajuan_pkl_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnal_pkl');
    }
};
