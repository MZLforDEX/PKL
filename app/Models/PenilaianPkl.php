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

    protected $casts = [
        'nilai_sikap' => 'float',
        'nilai_keterampilan' => 'float',
        'nilai_laporan' => 'float',
        'nilai_akhir' => 'float',
    ];

    public function pengajuanPkl(): BelongsTo
    {
        return $this->belongsTo(PengajuanPkl::class);
    }

    public function getPredikatAttribute(): string
    {
        if ($this->nilai_akhir === null) return 'Belum Dinilai';
        if ($this->nilai_akhir >= 90) return 'Sangat Baik';
        if ($this->nilai_akhir >= 80) return 'Baik';
        if ($this->nilai_akhir >= 70) return 'Cukup';
        return 'Kurang';
    }
}
