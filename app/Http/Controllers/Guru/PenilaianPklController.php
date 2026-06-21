<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;

class PenilaianPklController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        if (!$guru) {
            abort(403, 'Profil guru belum diatur.');
        }

        $pengajuan = PengajuanPkl::with(['siswa.user', 'penilaianPkl'])
            ->where('guru_id', $guru->id)
            ->whereIn('status', ['menunggu_penilaian', 'selesai'])
            ->latest()
            ->paginate(10);

        return view('guru.penilaian.index', compact('pengajuan'));
    }
}
