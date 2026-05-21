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
        $pengajuan = PengajuanPkl::with(['siswa.user', 'tempatPkl'])
            ->where('guru_id', $guru->id)
            ->latest()->paginate(10);
        return view('guru.pengajuan.index', compact('pengajuan'));
    }

    public function show(PengajuanPkl $pengajuanPkl)
    {
        $this->authorizeBimbingan($pengajuanPkl);
        $pengajuanPkl->load(['siswa.user', 'tempatPkl']);
        return view('guru.pengajuan.show', compact('pengajuanPkl'));
    }

    public function setujui(Request $request, PengajuanPkl $pengajuanPkl)
    {
        $this->authorizeBimbingan($pengajuanPkl);
        
        if ($pengajuanPkl->status !== 'menunggu_persetujuan') {
            return redirect()->back()->withErrors(['msg' => 'Pengajuan tidak sedang menunggu persetujuan.']);
        }

        if ($pengajuanPkl->tempatPkl->is_penuh) {
            return redirect()->back()->withErrors(['msg' => 'Tidak dapat menyetujui pengajuan karena kuota Tempat PKL ini sudah penuh.']);
        }

        $pengajuanPkl->update(['status' => 'disetujui', 'catatan' => $request->catatan]);
        
        $pengajuanPkl->siswa->user->notify(new \App\Notifications\PengajuanPklStatusChanged($pengajuanPkl));
        
        return redirect()->back()->with('success', 'Pengajuan telah disetujui.');
    }

    public function tolak(Request $request, PengajuanPkl $pengajuanPkl)
    {
        $this->authorizeBimbingan($pengajuanPkl);

        if ($pengajuanPkl->status !== 'menunggu_persetujuan') {
            return redirect()->back()->withErrors(['msg' => 'Pengajuan tidak sedang menunggu persetujuan.']);
        }

        $request->validate(['catatan' => 'required|string']);
        $pengajuanPkl->update(['status' => 'ditolak', 'catatan' => $request->catatan]);
        
        $pengajuanPkl->siswa->user->notify(new \App\Notifications\PengajuanPklStatusChanged($pengajuanPkl));
        
        return redirect()->back()->with('success', 'Pengajuan telah ditolak.');
    }

    public function mintaRevisi(Request $request, PengajuanPkl $pengajuanPkl)
    {
        $this->authorizeBimbingan($pengajuanPkl);

        if ($pengajuanPkl->status !== 'menunggu_persetujuan') {
            return redirect()->back()->withErrors(['msg' => 'Pengajuan tidak sedang menunggu persetujuan.']);
        }

        $request->validate(['catatan' => 'required|string']);
        $pengajuanPkl->update(['status' => 'revisi', 'catatan' => $request->catatan]);
        
        $pengajuanPkl->siswa->user->notify(new \App\Notifications\PengajuanPklStatusChanged($pengajuanPkl));
        
        return redirect()->back()->with('success', 'Revisi telah diminta.');
    }

    private function authorizeBimbingan(PengajuanPkl $pengajuanPkl): void
    {
        $guru = auth()->user()->guru;
        if ($pengajuanPkl->guru_id !== $guru->id) {
            abort(403);
        }
    }
}
