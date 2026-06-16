<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengajuanPkl extends Model
{
    protected $table = 'pengajuan_pkl';

    protected $fillable = [
        'siswa_id', 'tempat_pkl_id', 'guru_id',
        'tanggal_mulai', 'tanggal_selesai', 'alasan',
        'file_dokumen', 'status', 'catatan',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    protected static function booted(): void
    {
        static::deleting(function ($pengajuan) {
            if ($pengajuan->file_dokumen) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($pengajuan->file_dokumen);
            }

            foreach ($pengajuan->jurnalPkl as $jurnal) {
                $jurnal->delete();
            }

            if ($pengajuan->laporanPkl) {
                $pengajuan->laporanPkl->delete();
            }

            if ($pengajuan->penilaianPkl) {
                $pengajuan->penilaianPkl->delete();
            }

            foreach ($pengajuan->absensiPkl as $absensi) {
                $absensi->delete();
            }
        });
    }

    public function tempatPkl(): BelongsTo
    {
        return $this->belongsTo(TempatPkl::class);
    }

    public function jurnalPkl(): HasMany
    {
        return $this->hasMany(JurnalPkl::class);
    }

    public function laporanPkl(): HasOne
    {
        return $this->hasOne(LaporanPkl::class);
    }

    public function penilaianPkl(): HasOne
    {
        return $this->hasOne(PenilaianPkl::class);
    }

    public function absensiPkl(): HasMany
    {
        return $this->hasMany(AbsensiPkl::class, 'pengajuan_pkl_id');
    }
}
