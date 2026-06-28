<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_pkl', function (Blueprint $table) {
            $table->foreignId('periode_pkl_id')->nullable()->after('siswa_id')->constrained('periode_pkl')->nullOnDelete();
        });

        // Insert default period for existing data
        $defaultPeriodId = DB::table('periode_pkl')->insertGetId([
            'nama_periode' => 'PKL 2025/2026',
            'tanggal_mulai' => '2025-07-01',
            'tanggal_selesai' => '2025-12-31',
            'status_aktif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update all existing records to use this default period
        DB::table('pengajuan_pkl')->update(['periode_pkl_id' => $defaultPeriodId]);
    }

    public function down(): void
    {
        Schema::table('pengajuan_pkl', function (Blueprint $table) {
            $table->dropForeign(['periode_pkl_id']);
            $table->dropColumn('periode_pkl_id');
        });
    }
};
