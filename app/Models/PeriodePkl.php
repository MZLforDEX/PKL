<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PeriodePkl extends Model
{
    protected $table = 'periode_pkl';

    protected $fillable = [
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_aktif',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'status_aktif' => 'boolean',
    ];

    public static function getSelectedPeriodId()
    {
        if (session()->has('selected_periode_id')) {
            $exists = self::where('id', session('selected_periode_id'))->exists();
            if ($exists) {
                return session('selected_periode_id');
            }
            session()->forget('selected_periode_id');
        }

        $active = self::where('status_aktif', true)->first();
        if ($active) {
            return $active->id;
        }

        return self::latest('id')->first()?->id;
    }

    public function pengajuanPkl(): HasMany
    {
        return $this->hasMany(PengajuanPkl::class, 'periode_pkl_id');
    }

    public function jurnalPkl(): HasManyThrough
    {
        return $this->hasManyThrough(JurnalPkl::class, PengajuanPkl::class, 'periode_pkl_id', 'pengajuan_pkl_id');
    }

    public function absensiPkl(): HasManyThrough
    {
        return $this->hasManyThrough(AbsensiPkl::class, PengajuanPkl::class, 'periode_pkl_id', 'pengajuan_pkl_id');
    }

    public function laporanPkl(): HasManyThrough
    {
        return $this->hasManyThrough(LaporanPkl::class, PengajuanPkl::class, 'periode_pkl_id', 'pengajuan_pkl_id');
    }

    public function penilaianPkl(): HasManyThrough
    {
        return $this->hasManyThrough(PenilaianPkl::class, PengajuanPkl::class, 'periode_pkl_id', 'pengajuan_pkl_id');
    }
}
