<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JurnalPkl;
use App\Models\PengajuanPkl;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $pengajuan = PengajuanPkl::with(['tempatPkl', 'guru.user', 'penilaianPkl'])
            ->where('siswa_id', $siswa->id)->latest()->first();
        $jmlJurnal = JurnalPkl::whereHas('pengajuanPkl', fn($q) => $q->where('siswa_id', $siswa->id))->count();

        return view('siswa.dashboard', compact('pengajuan', 'jmlJurnal'));
    }
}
