<?php

namespace App\Http\Controllers\PembimbingIndustri;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;
use App\Models\PembimbingIndustri;

class AbsensiPklController extends Controller
{
    public function index()
    {
        $pembimbing = $this->getPembimbing();
        $absensi = AbsensiPkl::with(['pengajuanPkl.siswa.user'])
            ->whereHas('pengajuanPkl', fn($q) => $q->where('tempat_pkl_id', $pembimbing->tempat_pkl_id))
            ->latest()
            ->paginate(15);

        return view('pembimbing.absensi.index', compact('absensi'));
    }

    private function getPembimbing(): PembimbingIndustri
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            abort(403, 'Profil pembimbing industri belum diatur.');
        }
        return $pembimbing;
    }
}
