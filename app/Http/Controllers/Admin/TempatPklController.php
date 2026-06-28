<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTempatPklRequest;
use App\Http\Requests\UpdateTempatPklRequest;
use App\Models\PembimbingIndustri;
use App\Models\PengajuanPkl;
use App\Models\TempatPkl;

class TempatPklController extends Controller
{
    public function index()
    {
        $selectedPeriodeId = request()->has('periode_id') ? request('periode_id') : \App\Models\PeriodePkl::getSelectedPeriodId();
        $search = request('search');
        $tempatPkl = TempatPkl::when($selectedPeriodeId, function ($query, $selectedPeriodeId) {
                $query->whereHas('pengajuanPkl', function ($q) use ($selectedPeriodeId) {
                    $q->where('periode_pkl_id', $selectedPeriodeId);
                });
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_tempat', 'like', "%{$search}%")
                        ->orWhere('alamat', 'like', "%{$search}%")
                        ->orWhere('bidang_usaha', 'like', "%{$search}%");
                });
            })
            ->paginate(10)->withQueryString();

        $periodes = \App\Models\PeriodePkl::orderByDesc('tanggal_mulai')->get();

        return view('admin.tempat-pkl.index', compact('tempatPkl', 'periodes', 'selectedPeriodeId'));
    }

    public function create()
    {
        return view('admin.tempat-pkl.create');
    }

    public function store(StoreTempatPklRequest $request)
    {
        TempatPkl::create($request->validated());
        return redirect()->route('admin.tempat-pkl.index')->with('success', 'Tempat PKL berhasil ditambahkan.');
    }

    public function edit(TempatPkl $tempatPkl)
    {
        return view('admin.tempat-pkl.edit', compact('tempatPkl'));
    }

    public function update(UpdateTempatPklRequest $request, TempatPkl $tempatPkl)
    {
        $tempatPkl->update($request->validated());
        return redirect()->route('admin.tempat-pkl.index')->with('success', 'Tempat PKL berhasil diperbarui.');
    }

    public function destroy(TempatPkl $tempatPkl)
    {
        $activePengajuanCount = PengajuanPkl::where('tempat_pkl_id', $tempatPkl->id)
            ->whereNotIn('status', ['ditolak', 'selesai'])
            ->count();
        $pembimbingCount = PembimbingIndustri::where('tempat_pkl_id', $tempatPkl->id)->count();

        if ($activePengajuanCount > 0 || $pembimbingCount > 0) {
            return redirect()->route('admin.tempat-pkl.index')->with('error', 'Tempat PKL tidak dapat dihapus karena masih memiliki data aktif terkait.');
        }

        $tempatPkl->delete();
        return redirect()->route('admin.tempat-pkl.index')->with('success', 'Tempat PKL berhasil dihapus.');
    }
}
