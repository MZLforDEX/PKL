<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;

class PengajuanPklController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        if (!$guru) abort(403, 'Profil guru belum diatur.');
        
        $selectedPeriodeId = \App\Models\PeriodePkl::getSelectedPeriodId();

        $pengajuan = PengajuanPkl::with(['siswa.user', 'tempatPkl'])
            ->where('guru_id', $guru->id)
            ->where('periode_pkl_id', $selectedPeriodeId)
            ->latest()->paginate(10);
        return view('guru.pengajuan.index', compact('pengajuan'));
    }

    public function show(PengajuanPkl $pengajuanPkl)
    {
        $this->authorizeBimbingan($pengajuanPkl);
        $pengajuanPkl->load(['siswa.user', 'tempatPkl']);
        return view('guru.pengajuan.show', compact('pengajuanPkl'));
    }


    private function authorizeBimbingan(PengajuanPkl $pengajuanPkl): void
    {
        $guru = auth()->user()->guru;
        if (!$guru) abort(403, 'Profil guru belum diatur.');
        if ($pengajuanPkl->guru_id !== $guru->id) {
            abort(403);
        }
    }
}
