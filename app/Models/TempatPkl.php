<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TempatPkl extends Model
{
    protected $table = 'tempat_pkl';

    protected $fillable = [
        'nama_tempat', 'alamat', 'bidang_usaha', 'kontak_person',
        'no_hp', 'email', 'kuota', 'keterangan',
    ];

    public function pengajuanPkl(): HasMany
    {
        return $this->hasMany(PengajuanPkl::class);
    }
}
