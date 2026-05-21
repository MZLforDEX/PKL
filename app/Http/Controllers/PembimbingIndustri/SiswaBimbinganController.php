<?php

namespace App\Http\Controllers\PembimbingIndustri;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;

class SiswaBimbinganController extends Controller
{
    public function index()
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        $siswa = PengajuanPkl::with(['siswa.user', 'guru.user'])
            ->where('tempat_pkl_id', $pembimbing->tempat_pkl_id)
            ->whereIn('status', ['disetujui', 'sedang_pkl', 'menunggu_penilaian', 'selesai'])
            ->latest()->paginate(10);

        return view('pembimbing.siswa.index', compact('siswa'));
    }

    public function show(PengajuanPkl $pengajuanPkl)
    {
        $this->authorizeBimbingan($pengajuanPkl);
        $pengajuanPkl->load(['siswa.user', 'guru.user', 'penilaianPkl']);

        return view('pembimbing.siswa.show', compact('pengajuanPkl'));
    }

    private function authorizeBimbingan(PengajuanPkl $pengajuanPkl): void
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if ($pengajuanPkl->tempat_pkl_id !== $pembimbing->tempat_pkl_id) {
            abort(403);
        }
    }
}
