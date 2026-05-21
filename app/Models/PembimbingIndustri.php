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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tempatPkl(): BelongsTo
    {
        return $this->belongsTo(TempatPkl::class);
    }
}
