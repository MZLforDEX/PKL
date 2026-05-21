<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianPkl extends Model
{
    protected $table = 'penilaian_pkl';

    protected $fillable = [
        'pengajuan_pkl_id', 'nilai_sikap', 'nilai_keterampilan',
        'nilai_laporan', 'nilai_akhir', 'catatan_evaluasi',
    ];

    public function pengajuanPkl(): BelongsTo
    {
        return $this->belongsTo(PengajuanPkl::class);
    }
}
