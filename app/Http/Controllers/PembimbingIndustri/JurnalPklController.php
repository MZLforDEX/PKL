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
        $selectedPeriodeId = \App\Models\PeriodePkl::getSelectedPeriodId();

        $jurnal = JurnalPkl::with(['pengajuanPkl.siswa.user'])
            ->whereHas('pengajuanPkl', fn($q) => $q->where('tempat_pkl_id', $pembimbing->tempat_pkl_id)->where('periode_pkl_id', $selectedPeriodeId))
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
        if ($jurnalPkl->status !== 'menunggu_validasi' && ($jurnalPkl->status !== 'valid' || !is_null($jurnalPkl->catatan_pembimbing))) {
            return redirect()->back()->withErrors(['msg' => 'Jurnal sudah divalidasi oleh Anda atau tidak dapat divalidasi.']);
        }
        $request->validate(['catatan_pembimbing' => 'nullable|string|max:5000']);
        $jurnalPkl->update([
            'status' => 'valid', 
            'catatan_pembimbing' => $request->filled('catatan_pembimbing') ? $request->catatan_pembimbing : ($jurnalPkl->catatan_pembimbing ?? '-')
        ]);
        if ($jurnalPkl->pengajuanPkl->siswa && $jurnalPkl->pengajuanPkl->siswa->user) {
            $jurnalPkl->pengajuanPkl->siswa->user->notify(new \App\Notifications\JurnalPklDiperbarui($jurnalPkl, 'valid'));
        }
        return redirect()->back()->with('success', 'Jurnal telah divalidasi oleh industri.');
    }

    public function mintaRevisi(Request $request, JurnalPkl $jurnalPkl)
    {
        $this->authorizeBimbingan($jurnalPkl);
        if ($jurnalPkl->status !== 'menunggu_validasi' && ($jurnalPkl->status !== 'valid' || !is_null($jurnalPkl->catatan_pembimbing))) {
            return redirect()->back()->withErrors(['msg' => 'Jurnal sudah divalidasi oleh Anda atau tidak dapat divalidasi.']);
        }
        $request->validate(['catatan_pembimbing' => 'required|string']);
        $jurnalPkl->update([
            'status' => 'revisi', 
            'catatan_pembimbing' => $request->catatan_pembimbing
        ]);
        if ($jurnalPkl->pengajuanPkl->siswa && $jurnalPkl->pengajuanPkl->siswa->user) {
            $jurnalPkl->pengajuanPkl->siswa->user->notify(new \App\Notifications\JurnalPklDiperbarui($jurnalPkl, 'revisi'));
        }
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
