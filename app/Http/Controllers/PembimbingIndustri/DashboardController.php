<?php

namespace App\Http\Controllers\PembimbingIndustri;

use App\Http\Controllers\Controller;
use App\Models\JurnalPkl;
use App\Models\PengajuanPkl;

class DashboardController extends Controller
{
    public function index()
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            abort(403, 'Profil pembimbing industri belum diatur.');
        }

        $tempatPklId = $pembimbing->tempat_pkl_id;

        $totalBimbingan = PengajuanPkl::where('tempat_pkl_id', $tempatPklId)
            ->whereIn('status', ['disetujui', 'sedang_pkl', 'menunggu_penilaian', 'selesai'])
            ->count();

        $jurnalMenunggu = JurnalPkl::whereHas('pengajuanPkl', fn($q) => $q->where('tempat_pkl_id', $tempatPklId))
            ->where('status', 'menunggu_validasi')
            ->count();

        return view('pembimbing.dashboard', compact('totalBimbingan', 'jurnalMenunggu', 'pembimbing'));
    }
}
