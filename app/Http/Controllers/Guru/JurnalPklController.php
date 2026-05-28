<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JurnalPkl;
use Illuminate\Http\Request;

class JurnalPklController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $jurnal = JurnalPkl::with(['pengajuanPkl.siswa.user'])
            ->whereHas('pengajuanPkl', fn($q) => $q->where('guru_id', $guru->id))
            ->latest()->paginate(10);
        return view('guru.jurnal.index', compact('jurnal'));
    }

    public function show(JurnalPkl $jurnalPkl)
    {
        $this->authorizeBimbingan($jurnalPkl);
        $jurnalPkl->load(['pengajuanPkl.siswa.user']);
        return view('guru.jurnal.show', compact('jurnalPkl'));
    }

    public function valid(Request $request, JurnalPkl $jurnalPkl)
    {
        $this->authorizeBimbingan($jurnalPkl);
        if ($jurnalPkl->status !== 'menunggu_validasi') {
            return redirect()->back()->withErrors(['msg' => 'Jurnal tidak dalam status menunggu validasi.']);
        }
        $jurnalPkl->update(['status' => 'valid', 'catatan_guru' => $request->catatan_guru]);
        return redirect()->back()->with('success', 'Jurnal telah divalidasi.');
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

    private function authorizeBimbingan(JurnalPkl $jurnalPkl): void
    {
        $guru = auth()->user()->guru;
        if ($jurnalPkl->pengajuanPkl->guru_id !== $guru->id) {
            abort(403);
        }
    }
}
