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
        Schema::create('pesan_pembimbing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembimbing_industri_id')->constrained('pembimbing_industri')->onDelete('cascade');
            $table->string('subjek');
            $table->string('kategori'); // administrasi, kendala_siswa, teknis, lainnya
            $table->text('pesan');
            $table->string('lampiran')->nullable();
            $table->string('status')->default('menunggu_tanggapan'); // menunggu_tanggapan, proses, selesai
            $table->text('tanggapan')->nullable();
            $table->foreignId('dibalas_oleh_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('dibalas_pada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan_pembimbing');
    }
};
