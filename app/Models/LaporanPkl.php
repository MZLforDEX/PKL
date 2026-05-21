<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanPkl extends Model
{
    protected $table = 'laporan_pkl';

    protected $fillable = [
        'pengajuan_pkl_id', 'file_laporan', 'status', 'catatan_guru',
    ];

    public function pengajuanPkl(): BelongsTo
    {
        return $this->belongsTo(PengajuanPkl::class);
    }
}
