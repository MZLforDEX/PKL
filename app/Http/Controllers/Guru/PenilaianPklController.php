<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;

class PenilaianPklController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        if (!$guru) {
            abort(403, 'Profil guru belum diatur.');
        }

        $selectedPeriodeId = \App\Models\PeriodePkl::getSelectedPeriodId();

        $pengajuan = PengajuanPkl::with(['siswa.user', 'penilaianPkl'])
            ->where('guru_id', $guru->id)
            ->where('periode_pkl_id', $selectedPeriodeId)
            ->whereIn('status', ['menunggu_penilaian', 'selesai'])
            ->latest()
            ->paginate(10);

        return view('guru.penilaian.index', compact('pengajuan'));
    }
}
