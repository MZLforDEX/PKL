<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jurnal_pkl', function (Blueprint $table) {
            $table->text('catatan_pembimbing')->nullable()->after('catatan_guru');
        });
    }

    public function down(): void
    {
        Schema::table('jurnal_pkl', function (Blueprint $table) {
            $table->dropColumn('catatan_pembimbing');
        });
    }
};
