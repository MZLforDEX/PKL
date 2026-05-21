<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\PengajuanPkl;
use App\Models\Siswa;
use App\Models\TempatPkl;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalTempatPkl = TempatPkl::count();
        $totalPengajuan = PengajuanPkl::count();
        $totalMenunggu = PengajuanPkl::where('status', 'menunggu_persetujuan')->count();
        $totalSelesai = PengajuanPkl::where('status', 'selesai')->count();

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalTempatPkl',
            'totalPengajuan', 'totalMenunggu', 'totalSelesai'
        ));
    }
}
