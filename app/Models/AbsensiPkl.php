<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiPkl extends Model
{
    use HasFactory;

    protected $table = 'absensi_pkl';

    protected $fillable = [
        'pengajuan_pkl_id',
        'tanggal',
        'jam_masuk',
        'latitude',
        'longitude',
        'foto_selfie',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pengajuanPkl(): BelongsTo
    {
        return $this->belongsTo(PengajuanPkl::class, 'pengajuan_pkl_id');
    }
}
