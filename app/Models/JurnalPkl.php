<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JurnalPkl extends Model
{
    protected $table = 'jurnal_pkl';

    protected $fillable = [
        'pengajuan_pkl_id', 'tanggal', 'kegiatan',
        'kendala', 'dokumentasi', 'status', 'catatan_guru', 'catatan_pembimbing',
    ];

    protected static function booted(): void
    {
        static::deleting(function ($jurnal) {
            if ($jurnal->dokumentasi) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($jurnal->dokumentasi);
            }
        });
    }

    public function pengajuanPkl(): BelongsTo
    {
        return $this->belongsTo(PengajuanPkl::class);
    }
}
