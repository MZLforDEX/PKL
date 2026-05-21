<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tempat_pkl', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tempat');
            $table->text('alamat');
            $table->string('bidang_usaha');
            $table->string('kontak_person')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->integer('kuota')->default(1);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tempat_pkl');
    }
};
