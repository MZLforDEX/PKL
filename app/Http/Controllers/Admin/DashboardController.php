<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\PengajuanPkl;
use App\Models\Siswa;
use App\Models\TempatPkl;
use App\Models\User;

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
        $pengajuanTerbaru = PengajuanPkl::with(['siswa.user', 'tempatPkl'])
            ->latest()->take(5)->get();

        // Additional cool stats
        $unapprovedUsersCount = User::where('is_approved', false)->count();
        
        $tempatPklList = TempatPkl::withCount(['pengajuanPkl' => function ($query) {
            $query->whereIn('status', ['disetujui', 'sedang_pkl', 'menunggu_penilaian']);
        }])->orderByDesc('pengajuan_pkl_count')->take(4)->get();

        $bidangUsahaStats = TempatPkl::selectRaw('bidang_usaha, count(*) as total')
            ->groupBy('bidang_usaha')
            ->orderByDesc('total')
            ->take(4)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalTempatPkl',
            'totalPengajuan', 'totalMenunggu', 'totalSelesai', 'pengajuanTerbaru',
            'unapprovedUsersCount', 'tempatPklList', 'bidangUsahaStats'
        ));
    }
}
