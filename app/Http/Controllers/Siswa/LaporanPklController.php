<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaporanPklRequest;
use App\Models\LaporanPkl;
use App\Models\PengajuanPkl;
use Illuminate\Support\Facades\Storage;

class LaporanPklController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $laporan = LaporanPkl::whereHas('pengajuanPkl', fn($q) => $q->where('siswa_id', $siswa->id))
            ->latest()->paginate(10);
        return view('siswa.laporan.index', compact('laporan'));
    }

    public function create()
    {
        $siswa = auth()->user()->siswa;
        $pengajuanAktif = PengajuanPkl::where('siswa_id', $siswa->id)
            ->whereIn('status', ['disetujui', 'sedang_pkl'])
            ->whereDoesntHave('laporanPkl')
            ->first();

        if (!$pengajuanAktif) {
            return redirect()->route('siswa.laporan.index')->withErrors(['msg' => 'Tidak ada pengajuan aktif atau laporan sudah ada.']);
        }

        return view('siswa.laporan.create', compact('pengajuanAktif'));
    }

    public function store(StoreLaporanPklRequest $request)
    {
        $siswa = auth()->user()->siswa;
        $pengajuan = PengajuanPkl::where('siswa_id', $siswa->id)
            ->whereIn('status', ['disetujui', 'sedang_pkl'])
            ->whereDoesntHave('laporanPkl')
            ->firstOrFail();

        $data = $request->validated();
        $data['pengajuan_pkl_id'] = $pengajuan->id;
        $data['status'] = 'menunggu_review';
        $data['file_laporan'] = $request->file('file_laporan')->store('laporan', 'public');

        LaporanPkl::create($data);
        return redirect()->route('siswa.laporan.index')->with('success', 'Laporan berhasil diunggah.');
    }
}
