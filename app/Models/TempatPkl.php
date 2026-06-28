<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TempatPkl extends Model
{
    protected $table = 'tempat_pkl';

    protected $fillable = [
        'nama_tempat',
        'alamat',
        'bidang_usaha',
        'kontak_person',
        'no_hp',
        'email',
        'kuota',
        'keterangan',
    ];

    public function pengajuanPkl(): HasMany
    {
        return $this->hasMany(PengajuanPkl::class);
    }

    public function pembimbingIndustri(): HasMany
    {
        return $this->hasMany(PembimbingIndustri::class);
    }

    public function getSisaKuotaAttribute(): int
    {
        $activePeriodId = \App\Models\PeriodePkl::where('status_aktif', true)->first()?->id;
        if (!$activePeriodId) {
            return $this->kuota;
        }

        $occupied = $this->pengajuanPkl()
            ->where('periode_pkl_id', $activePeriodId)
            ->whereIn('status', ['disetujui', 'sedang_pkl', 'menunggu_penilaian'])
            ->count();
            
        return max(0, $this->kuota - $occupied);
    }

    public function getIsPenuhAttribute(): bool
    {
        return $this->sisa_kuota <= 0;
    }
}
