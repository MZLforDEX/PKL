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
        $selectedPeriodeId = \App\Models\PeriodePkl::getSelectedPeriodId();

        $totalSiswa = Siswa::whereHas('pengajuanPkl', function ($q) use ($selectedPeriodeId) {
            $q->where('periode_pkl_id', $selectedPeriodeId);
        })->count();
        $totalGuru = Guru::count();
        $totalTempatPkl = TempatPkl::count();
        $totalPengajuan = PengajuanPkl::where('periode_pkl_id', $selectedPeriodeId)->count();
        $totalMenunggu = PengajuanPkl::where('periode_pkl_id', $selectedPeriodeId)->where('status', 'menunggu_persetujuan')->count();
        $totalSelesai = PengajuanPkl::where('periode_pkl_id', $selectedPeriodeId)->where('status', 'selesai')->count();
        
        $pengajuanTerbaru = PengajuanPkl::where('periode_pkl_id', $selectedPeriodeId)
            ->with(['siswa.user', 'tempatPkl'])
            ->latest()->take(5)->get();

        // Additional cool stats
        $unapprovedUsersCount = User::where('is_approved', false)->count();
        
        $tempatPklList = TempatPkl::withCount(['pengajuanPkl' => function ($query) use ($selectedPeriodeId) {
            $query->where('periode_pkl_id', $selectedPeriodeId)
                  ->whereIn('status', ['disetujui', 'sedang_pkl', 'menunggu_penilaian']);
        }])->orderByDesc('pengajuan_pkl_count')->take(4)->get();

        // Query historical statistics per period for comparison
        $periodeStats = \App\Models\PeriodePkl::withCount([
            'pengajuanPkl as total_pengajuan',
            'pengajuanPkl as total_selesai' => function ($query) {
                $query->where('status', 'selesai');
            }
        ])->orderBy('tanggal_mulai')->get();

        // Query active period industrial partners and their quotas
        $mitraQuotaStats = TempatPkl::withCount(['pengajuanPkl' => function ($query) use ($selectedPeriodeId) {
            $query->where('periode_pkl_id', $selectedPeriodeId)
                  ->whereIn('status', ['disetujui', 'sedang_pkl', 'menunggu_penilaian']);
        }])->orderByDesc('kuota')->take(6)->get();

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalGuru', 'totalTempatPkl',
            'totalPengajuan', 'totalMenunggu', 'totalSelesai', 'pengajuanTerbaru',
            'unapprovedUsersCount', 'tempatPklList',
            'periodeStats', 'mitraQuotaStats'
        ));
    }
}
