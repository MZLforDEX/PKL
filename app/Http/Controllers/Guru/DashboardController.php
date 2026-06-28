<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JurnalPkl;
use App\Models\LaporanPkl;
use App\Models\PengajuanPkl;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        if (!$guru) abort(403, 'Profil guru belum diatur.');

        $selectedPeriodeId = \App\Models\PeriodePkl::getSelectedPeriodId();

        $totalBimbingan = PengajuanPkl::where('guru_id', $guru->id)->where('periode_pkl_id', $selectedPeriodeId)->count();
        $menungguValidasi = PengajuanPkl::where('guru_id', $guru->id)->where('periode_pkl_id', $selectedPeriodeId)->where('status', 'menunggu_persetujuan')->count();
        $jurnalMenunggu = JurnalPkl::whereHas('pengajuanPkl', fn($q) => $q->where('guru_id', $guru->id)->where('periode_pkl_id', $selectedPeriodeId))
            ->where('status', 'menunggu_validasi')->count();
        $laporanMenunggu = LaporanPkl::whereHas('pengajuanPkl', fn($q) => $q->where('guru_id', $guru->id)->where('periode_pkl_id', $selectedPeriodeId))
            ->where('status', 'menunggu_review')->count();
        $penilaianMenunggu = PengajuanPkl::where('guru_id', $guru->id)->where('periode_pkl_id', $selectedPeriodeId)
            ->where('status', 'menunggu_penilaian')->count();

        return view('guru.dashboard', compact('totalBimbingan', 'menungguValidasi', 'jurnalMenunggu', 'laporanMenunggu', 'penilaianMenunggu'));
    }
}
