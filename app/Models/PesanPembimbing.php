<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesanPembimbing extends Model
{
    protected $table = 'pesan_pembimbing';

    protected $fillable = [
        'pembimbing_industri_id',
        'subjek',
        'kategori',
        'pesan',
        'lampiran',
        'status',
        'tanggapan',
        'dibalas_oleh_id',
        'dibalas_pada',
    ];

    protected $casts = [
        'dibalas_pada' => 'datetime',
    ];

    public function pembimbingIndustri(): BelongsTo
    {
        return $this->belongsTo(PembimbingIndustri::class);
    }

    public function dibalasOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibalas_oleh_id');
    }
}
