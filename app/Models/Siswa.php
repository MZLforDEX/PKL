<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'nis',
        'kelas',
        'jurusan',
        'alamat',
        'no_hp',
    ];

    protected static function booted(): void
    {
        static::deleting(function ($siswa) {
            foreach ($siswa->pengajuanPkl as $pengajuan) {
                $pengajuan->delete();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pengajuanPkl(): HasMany
    {
        return $this->hasMany(PengajuanPkl::class);
    }
}
