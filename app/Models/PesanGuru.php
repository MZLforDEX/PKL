<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesanGuru extends Model
{
    protected $table = 'pesan_guru';

    protected $fillable = [
        'guru_id',
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

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function dibalasOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibalas_oleh_id');
    }

    public function replies(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PesanGuruReply::class, 'pesan_guru_id');
    }
}

