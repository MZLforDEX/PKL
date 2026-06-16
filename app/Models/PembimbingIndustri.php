<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembimbingIndustri extends Model
{
    protected $table = 'pembimbing_industri';

    protected $fillable = [
        'user_id',
        'tempat_pkl_id',
        'no_hp',
        'jabatan',
        'tanda_tangan',
        'logo',
    ];

    protected static function booted(): void
    {
        static::deleting(function ($pembimbing) {
            if ($pembimbing->tanda_tangan) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($pembimbing->tanda_tangan);
            }
            if ($pembimbing->logo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($pembimbing->logo);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tempatPkl(): BelongsTo
    {
        return $this->belongsTo(TempatPkl::class);
    }
}
