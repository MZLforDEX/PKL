<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePenilaianPklRequest;
use App\Models\PengajuanPkl;
use App\Models\PenilaianPkl;

class PenilaianPklController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $pengajuan = PengajuanPkl::with(['siswa.user', 'tempatPkl', 'penilaianPkl'])
            ->where('guru_id', $guru->id)
            ->whereIn('status', ['menunggu_penilaian', 'selesai'])
            ->latest()->paginate(10);
        return view('guru.penilaian.index', compact('pengajuan'));
    }

    public function create(PengajuanPkl $pengajuanPkl)
    {
        $this->authorizeBimbingan($pengajuanPkl);
        return view('guru.penilaian.create', compact('pengajuanPkl'));
    }

    public function store(StorePenilaianPklRequest $request, PengajuanPkl $pengajuanPkl)
    {
        $this->authorizeBimbingan($pengajuanPkl);
        $nilaiAkhir = round(($request->nilai_sikap + $request->nilai_keterampilan + $request->nilai_laporan) / 3, 2);

        PenilaianPkl::updateOrCreate(
            ['pengajuan_pkl_id' => $pengajuanPkl->id],
            [
                'nilai_sikap' => $request->nilai_sikap,
                'nilai_keterampilan' => $request->nilai_keterampilan,
                'nilai_laporan' => $request->nilai_laporan,
                'nilai_akhir' => $nilaiAkhir,
                'catatan_evaluasi' => $request->catatan_evaluasi,
            ]
        );

        $pengajuanPkl->update(['status' => 'selesai']);

        return redirect()->route('guru.penilaian.index')->with('success', 'Penilaian berhasil disimpan.');
    }

    private function authorizeBimbingan(PengajuanPkl $pengajuanPkl): void
    {
        $guru = auth()->user()->guru;
        if ($pengajuanPkl->guru_id !== $guru->id) {
            abort(403);
        }
    }
}
