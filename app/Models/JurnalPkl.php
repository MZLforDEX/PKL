<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JurnalPkl extends Model
{
    protected $table = 'jurnal_pkl';

    protected $fillable = [
        'pengajuan_pkl_id', 'tanggal', 'kegiatan',
        'kendala', 'dokumentasi', 'status', 'catatan_guru',
    ];

    public function pengajuanPkl(): BelongsTo
    {
        return $this->belongsTo(PengajuanPkl::class);
    }
}
