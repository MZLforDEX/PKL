<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;

class PengajuanPklController extends Controller
{
    public function index()
    {
        $pengajuan = PengajuanPkl::with(['siswa.user', 'tempatPkl', 'guru.user'])->latest()->paginate(10);
        return view('admin.pengajuan.index', compact('pengajuan'));
    }

    public function show(PengajuanPkl $pengajuanPkl)
    {
        $pengajuanPkl->load(['siswa.user', 'tempatPkl', 'guru.user']);
        $guru = Guru::with('user')->get();
        return view('admin.pengajuan.show', compact('pengajuanPkl', 'guru'));
    }

    public function assignGuru(Request $request, PengajuanPkl $pengajuanPkl)
    {
        if (!in_array($pengajuanPkl->status, ['menunggu_persetujuan', 'disetujui', 'revisi', 'sedang_pkl', 'menunggu_penilaian'])) {
            return redirect()->back()->withErrors(['msg' => 'Guru hanya dapat ditugaskan pada pengajuan aktif.']);
        }

        $request->validate(['guru_id' => 'required|exists:guru,id']);
        $pengajuanPkl->update(['guru_id' => $request->guru_id]);
        return redirect()->back()->with('success', 'Guru pendamping berhasil ditentukan.');
    }
}
