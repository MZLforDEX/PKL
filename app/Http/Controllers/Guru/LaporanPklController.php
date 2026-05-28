<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\LaporanPkl;
use Illuminate\Http\Request;

class LaporanPklController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $laporan = LaporanPkl::with(['pengajuanPkl.siswa.user'])
            ->whereHas('pengajuanPkl', fn($q) => $q->where('guru_id', $guru->id))
            ->latest()->paginate(10);
        return view('guru.laporan.index', compact('laporan'));
    }

    public function show(LaporanPkl $laporanPkl)
    {
        $this->authorizeBimbingan($laporanPkl);
        $laporanPkl->load(['pengajuanPkl.siswa.user']);
        return view('guru.laporan.show', compact('laporanPkl'));
    }

    public function terima(Request $request, LaporanPkl $laporanPkl)
    {
        $this->authorizeBimbingan($laporanPkl);
        if ($laporanPkl->status !== 'menunggu_review') {
            return redirect()->back()->withErrors(['msg' => 'Laporan tidak dalam status menunggu review.']);
        }

        $laporanPkl->update(['status' => 'diterima', 'catatan_guru' => $request->catatan_guru]);
        $laporanPkl->pengajuanPkl->update(['status' => 'menunggu_penilaian']);
        return redirect()->back()->with('success', 'Laporan telah diterima.');
    }

    public function mintaRevisi(Request $request, LaporanPkl $laporanPkl)
    {
        $this->authorizeBimbingan($laporanPkl);
        if (!in_array($laporanPkl->status, ['menunggu_review', 'revisi'])) {
            return redirect()->back()->withErrors(['msg' => 'Laporan tidak dapat direvisi.']);
        }

        $request->validate(['catatan_guru' => 'required|string']);
        $laporanPkl->update(['status' => 'revisi', 'catatan_guru' => $request->catatan_guru]);
        return redirect()->back()->with('success', 'Revisi laporan telah diminta.');
    }

    private function authorizeBimbingan(LaporanPkl $laporanPkl): void
    {
        $guru = auth()->user()->guru;
        if ($laporanPkl->pengajuanPkl->guru_id !== $guru->id) {
            abort(403);
        }
    }
}
