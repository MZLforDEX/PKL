<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesanPembimbingReply extends Model
{
    protected $table = 'pesan_pembimbing_replies';

    protected $fillable = [
        'pesan_pembimbing_id',
        'sender_id',
        'pesan',
        'lampiran',
    ];

    public function pesanPembimbing(): BelongsTo
    {
        return $this->belongsTo(PesanPembimbing::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
