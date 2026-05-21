<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JurnalPkl;
use App\Models\PengajuanPkl;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $pengajuan = PengajuanPkl::with(['tempatPkl', 'guru.user', 'penilaianPkl'])
            ->where('siswa_id', $siswa->id)->latest()->first();
        $jmlJurnal = JurnalPkl::whereHas('pengajuanPkl', fn($q) => $q->where('siswa_id', $siswa->id))->count();

        $jmlValidJurnal = 0;
        $totalHari = 50; // default target
        $periode = '-';
        $progressPersen = 0;

        if ($pengajuan) {
            $jmlValidJurnal = JurnalPkl::where('pengajuan_pkl_id', $pengajuan->id)
                ->where('status', 'valid')
                ->count();
            
            if ($pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai) {
                try {
                    $mulai = \Carbon\Carbon::parse($pengajuan->tanggal_mulai);
                    $selesai = \Carbon\Carbon::parse($pengajuan->tanggal_selesai);
                    
                    // Format period (e.g. "Agt 2023 - Nov 2023")
                    $periode = $mulai->translatedFormat('M Y') . ' - ' . $selesai->translatedFormat('M Y');
                    
                    // Count total weekdays (Monday - Friday) as working days
                    $diff = $mulai->diffInDaysFiltered(function (\Carbon\Carbon $date) {
                        return !$date->isWeekend();
                    }, $selesai);
                    
                    $totalHari = $diff > 0 ? $diff : 1;
                } catch (\Exception $e) {
                    $totalHari = 50;
                }
            }
            
            // Progress based on valid journals / total working days
            $progressPersen = $totalHari > 0 ? min(100, round(($jmlValidJurnal / $totalHari) * 100)) : 0;
        }

        return view('siswa.dashboard', compact('pengajuan', 'jmlJurnal', 'jmlValidJurnal', 'totalHari', 'periode', 'progressPersen'));
    }
}
