<?php

namespace App\Http\Controllers\PembimbingIndustri;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;

class AbsensiPklController extends Controller
{
    public function index()
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        $absensi = AbsensiPkl::with(['pengajuanPkl.siswa.user'])
            ->whereHas('pengajuanPkl', fn($q) => $q->where('tempat_pkl_id', $pembimbing->tempat_pkl_id))
            ->latest()
            ->paginate(15);

        return view('pembimbing.absensi.index', compact('absensi'));
    }
}
