<?php

namespace App\Http\Controllers\PembimbingIndustri;

use App\Http\Controllers\Controller;
use App\Models\JurnalPkl;
use App\Models\PembimbingIndustri;
use Illuminate\Http\Request;

class JurnalPklController extends Controller
{
    public function index()
    {
        $pembimbing = $this->getPembimbing();
        $jurnal = JurnalPkl::with(['pengajuanPkl.siswa.user'])
            ->whereHas('pengajuanPkl', fn($q) => $q->where('tempat_pkl_id', $pembimbing->tempat_pkl_id))
            ->latest()->paginate(10);

        return view('pembimbing.jurnal.index', compact('jurnal'));
    }

    public function show(JurnalPkl $jurnalPkl)
    {
        $this->authorizeBimbingan($jurnalPkl);
        $jurnalPkl->load(['pengajuanPkl.siswa.user']);
        return view('pembimbing.jurnal.show', compact('jurnalPkl'));
    }

    public function valid(Request $request, JurnalPkl $jurnalPkl)
    {
        $this->authorizeBimbingan($jurnalPkl);
        if ($jurnalPkl->status !== 'menunggu_validasi') {
            return redirect()->back()->withErrors(['msg' => 'Jurnal tidak dalam status menunggu validasi.']);
        }
        $jurnalPkl->update(['status' => 'valid', 'catatan_guru' => $request->filled('catatan_guru') ? $request->catatan_guru : $jurnalPkl->catatan_guru]);
        return redirect()->back()->with('success', 'Jurnal telah divalidasi oleh industri.');
    }

    public function mintaRevisi(Request $request, JurnalPkl $jurnalPkl)
    {
        $this->authorizeBimbingan($jurnalPkl);
        if ($jurnalPkl->status !== 'menunggu_validasi') {
            return redirect()->back()->withErrors(['msg' => 'Jurnal tidak dalam status menunggu validasi.']);
        }
        $request->validate(['catatan_guru' => 'required|string']);
        $jurnalPkl->update(['status' => 'revisi', 'catatan_guru' => $request->catatan_guru]);
        return redirect()->back()->with('success', 'Revisi jurnal telah diminta.');
    }

    private function getPembimbing(): PembimbingIndustri
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            abort(403, 'Profil pembimbing industri belum diatur.');
        }
        return $pembimbing;
    }

    private function authorizeBimbingan(JurnalPkl $jurnalPkl): void
    {
        $pembimbing = $this->getPembimbing();
        if ($jurnalPkl->pengajuanPkl->tempat_pkl_id !== $pembimbing->tempat_pkl_id) {
            abort(403);
        }
    }
}
