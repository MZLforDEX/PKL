<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;

class AbsensiPklController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $absensi = AbsensiPkl::with(['pengajuanPkl.siswa.user', 'pengajuanPkl.tempatPkl'])
            ->whereHas('pengajuanPkl', fn($q) => $q->where('guru_id', $guru->id))
            ->latest()
            ->paginate(15);

        return view('guru.absensi.index', compact('absensi'));
    }
}
