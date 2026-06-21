<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\PengajuanPklStatusChanged;

class PengajuanPklController extends Controller
{
    public function index()
    {
        $search = request('search');
        $pengajuan = PengajuanPkl::with(['siswa.user', 'tempatPkl', 'guru.user'])
            ->when($search, function ($query, $search) {
                $query->where('status', 'like', "%{$search}%")
                    ->orWhereHas('siswa.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('tempatPkl', function ($q) use ($search) {
                        $q->where('nama_tempat', 'like', "%{$search}%");
                    })
                    ->orWhereHas('guru.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)->withQueryString();
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

    public function setujui(Request $request, PengajuanPkl $pengajuanPkl)
    {
        if ($pengajuanPkl->status !== 'menunggu_persetujuan') {
            return redirect()->back()->withErrors(['msg' => 'Pengajuan tidak sedang menunggu persetujuan.']);
        }

        $request->validate(['catatan' => 'nullable|string']);

        try {
            DB::transaction(function () use ($pengajuanPkl, $request) {
                // Lock the TempatPkl row to prevent concurrent modifications
                $tempatPkl = \App\Models\TempatPkl::where('id', $pengajuanPkl->tempat_pkl_id)->lockForUpdate()->firstOrFail();

                if ($tempatPkl->is_penuh) {
                    throw new \Exception('Tidak dapat menyetujui pengajuan karena kuota Tempat PKL ini sudah penuh.');
                }

                $pengajuanPkl->update(['status' => 'disetujui', 'catatan' => $request->catatan]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
        }
        
        if ($pengajuanPkl->siswa && $pengajuanPkl->siswa->user) {
            $pengajuanPkl->siswa->user->notify(new PengajuanPklStatusChanged($pengajuanPkl));
        }
        
        return redirect()->back()->with('success', 'Pengajuan telah disetujui.');
    }

    public function tolak(Request $request, PengajuanPkl $pengajuanPkl)
    {
        if ($pengajuanPkl->status !== 'menunggu_persetujuan') {
            return redirect()->back()->withErrors(['msg' => 'Pengajuan tidak sedang menunggu persetujuan.']);
        }

        $request->validate(['catatan' => 'required|string']);
        $pengajuanPkl->update(['status' => 'ditolak', 'catatan' => $request->catatan]);
        
        if ($pengajuanPkl->siswa && $pengajuanPkl->siswa->user) {
            $pengajuanPkl->siswa->user->notify(new PengajuanPklStatusChanged($pengajuanPkl));
        }
        
        return redirect()->back()->with('success', 'Pengajuan telah ditolak.');
    }

    public function mintaRevisi(Request $request, PengajuanPkl $pengajuanPkl)
    {
        if ($pengajuanPkl->status !== 'menunggu_persetujuan') {
            return redirect()->back()->withErrors(['msg' => 'Pengajuan tidak sedang menunggu persetujuan.']);
        }

        $request->validate(['catatan' => 'required|string']);
        $pengajuanPkl->update(['status' => 'revisi', 'catatan' => $request->catatan]);
        
        if ($pengajuanPkl->siswa && $pengajuanPkl->siswa->user) {
            $pengajuanPkl->siswa->user->notify(new PengajuanPklStatusChanged($pengajuanPkl));
        }
        
        return redirect()->back()->with('success', 'Revisi telah diminta.');
    }
}
