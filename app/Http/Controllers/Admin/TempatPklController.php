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
        $tempatPkl = TempatPkl::paginate(10);
        return view('admin.tempat-pkl.index', compact('tempatPkl'));
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
        $pengajuanCount = PengajuanPkl::where('tempat_pkl_id', $tempatPkl->id)->count();
        $pembimbingCount = PembimbingIndustri::where('tempat_pkl_id', $tempatPkl->id)->count();

        if ($pengajuanCount > 0 || $pembimbingCount > 0) {
            return redirect()->route('admin.tempat-pkl.index')->with('error', 'Tempat PKL tidak dapat dihapus karena masih memiliki data terkait.');
        }

        $tempatPkl->delete();
        return redirect()->route('admin.tempat-pkl.index')->with('success', 'Tempat PKL berhasil dihapus.');
    }
}
