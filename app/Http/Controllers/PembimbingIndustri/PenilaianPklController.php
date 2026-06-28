<?php

namespace App\Http\Controllers\PembimbingIndustri;

use App\Http\Controllers\Controller;
use App\Models\PembimbingIndustri;
use App\Models\PengajuanPkl;
use App\Models\PenilaianPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenilaianPklController extends Controller
{
    public function index()
    {
        $pembimbing = $this->getPembimbing();
        $selectedPeriodeId = \App\Models\PeriodePkl::getSelectedPeriodId();

        $pengajuan = PengajuanPkl::with(['siswa.user', 'penilaianPkl'])
            ->where('tempat_pkl_id', $pembimbing->tempat_pkl_id)
            ->where('periode_pkl_id', $selectedPeriodeId)
            ->whereIn('status', ['menunggu_penilaian', 'selesai'])
            ->latest()->paginate(10);

        return view('pembimbing.penilaian.index', compact('pengajuan'));
    }

    public function create(PengajuanPkl $pengajuanPkl)
    {
        $this->authorizeBimbingan($pengajuanPkl);

        if ($pengajuanPkl->status !== 'menunggu_penilaian') {
            return redirect()->route('pembimbing.penilaian.index')->withErrors(['msg' => 'Pengajuan tidak dalam status menunggu penilaian.']);
        }

        return view('pembimbing.penilaian.create', compact('pengajuanPkl'));
    }

    public function store(Request $request, PengajuanPkl $pengajuanPkl)
    {
        $this->authorizeBimbingan($pengajuanPkl);

        if ($pengajuanPkl->status !== 'menunggu_penilaian') {
            return redirect()->back()->withErrors(['msg' => 'Pengajuan tidak dalam status menunggu penilaian.']);
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
            'items.*.nilai' => 'required|integer|min:0|max:100',
            'catatan_evaluasi' => 'nullable|string',
        ]);

        $items = array_values($request->items);
        $totalNilai = array_sum(array_column($items, 'nilai'));
        $count = count($items);
        $nilaiAkhir = round($totalNilai / $count, 2);

        // Map first 3 values for backwards compatibility
        $nilaiSikap = isset($items[0]) ? $items[0]['nilai'] : 0;
        $nilaiKeterampilan = isset($items[1]) ? $items[1]['nilai'] : 0;
        $nilaiLaporan = isset($items[2]) ? $items[2]['nilai'] : 0;

        DB::transaction(function () use ($request, $pengajuanPkl, $items, $nilaiAkhir, $nilaiSikap, $nilaiKeterampilan, $nilaiLaporan) {
            PenilaianPkl::updateOrCreate(
                ['pengajuan_pkl_id' => $pengajuanPkl->id],
                [
                    'nilai_sikap' => $nilaiSikap,
                    'nilai_keterampilan' => $nilaiKeterampilan,
                    'nilai_laporan' => $nilaiLaporan,
                    'nilai_akhir' => $nilaiAkhir,
                    'catatan_evaluasi' => $request->catatan_evaluasi,
                    'detail_nilai' => $items,
                ]
            );
            $pengajuanPkl->update(['status' => 'selesai']);
        });

        return redirect()->route('pembimbing.penilaian.index')->with('success', 'Penilaian berhasil disimpan.');
    }

    public function edit(PenilaianPkl $penilaianPkl)
    {
        $pengajuanPkl = $penilaianPkl->pengajuanPkl;
        $this->authorizeBimbingan($pengajuanPkl);

        return view('pembimbing.penilaian.edit', compact('penilaianPkl', 'pengajuanPkl'));
    }

    public function update(Request $request, PenilaianPkl $penilaianPkl)
    {
        $pengajuanPkl = $penilaianPkl->pengajuanPkl;
        $this->authorizeBimbingan($pengajuanPkl);

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:255',
            'items.*.nilai' => 'required|integer|min:0|max:100',
            'catatan_evaluasi' => 'nullable|string',
        ]);

        $items = array_values($request->items);
        $totalNilai = array_sum(array_column($items, 'nilai'));
        $count = count($items);
        $nilaiAkhir = round($totalNilai / $count, 2);

        // Map first 3 values for backwards compatibility
        $nilaiSikap = isset($items[0]) ? $items[0]['nilai'] : 0;
        $nilaiKeterampilan = isset($items[1]) ? $items[1]['nilai'] : 0;
        $nilaiLaporan = isset($items[2]) ? $items[2]['nilai'] : 0;

        DB::transaction(function () use ($request, $penilaianPkl, $items, $nilaiAkhir, $nilaiSikap, $nilaiKeterampilan, $nilaiLaporan) {
            $penilaianPkl->update([
                'nilai_sikap' => $nilaiSikap,
                'nilai_keterampilan' => $nilaiKeterampilan,
                'nilai_laporan' => $nilaiLaporan,
                'nilai_akhir' => $nilaiAkhir,
                'catatan_evaluasi' => $request->catatan_evaluasi,
                'detail_nilai' => $items,
            ]);
        });

        return redirect()->route('pembimbing.penilaian.index')->with('success', 'Penilaian berhasil diperbarui.');
    }

    public function destroy(PenilaianPkl $penilaianPkl)
    {
        $pengajuanPkl = $penilaianPkl->pengajuanPkl;
        $this->authorizeBimbingan($pengajuanPkl);

        DB::transaction(function () use ($penilaianPkl, $pengajuanPkl) {
            $penilaianPkl->delete();
            $pengajuanPkl->update(['status' => 'menunggu_penilaian']);
        });

        return redirect()->route('pembimbing.penilaian.index')->with('success', 'Penilaian berhasil dihapus.');
    }

    private function getPembimbing(): PembimbingIndustri
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            abort(403, 'Profil pembimbing industri belum diatur.');
        }
        return $pembimbing;
    }

    private function authorizeBimbingan(PengajuanPkl $pengajuanPkl): void
    {
        $pembimbing = $this->getPembimbing();
        if ($pengajuanPkl->tempat_pkl_id !== $pembimbing->tempat_pkl_id) {
            abort(403);
        }
    }
}
