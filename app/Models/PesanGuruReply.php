<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesanGuruReply extends Model
{
    protected $table = 'pesan_guru_replies';

    protected $fillable = [
        'pesan_guru_id',
        'sender_id',
        'pesan',
        'lampiran',
    ];

    public function pesanGuru(): BelongsTo
    {
        return $this->belongsTo(PesanGuru::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
