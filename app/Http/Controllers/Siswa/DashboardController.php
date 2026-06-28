<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JurnalPkl;
use App\Models\PengajuanPkl;
use App\Models\AbsensiPkl;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');

        $activePeriodId = \App\Models\PeriodePkl::where('status_aktif', true)->first()?->id;

        $pengajuan = null;
        if ($activePeriodId) {
            $pengajuan = PengajuanPkl::with(['tempatPkl', 'guru.user', 'penilaianPkl', 'laporanPkl'])
                ->where('siswa_id', $siswa->id)
                ->where('periode_pkl_id', $activePeriodId)
                ->first();
        }

        if (!$pengajuan) {
            $pengajuan = PengajuanPkl::with(['tempatPkl', 'guru.user', 'penilaianPkl', 'laporanPkl'])
                ->where('siswa_id', $siswa->id)
                ->latest()
                ->first();
        }
        
        $jmlJurnal = 0;
        $jmlValidJurnal = 0;
        $totalHari = 50; // default target
        $periode = '-';
        $progressPersen = 0;
        $jurnalTerbaru = collect();
        $absensiTerbaru = collect();
        $absensiHariIni = null;

        if ($pengajuan) {
            $jmlJurnal = JurnalPkl::where('pengajuan_pkl_id', $pengajuan->id)->count();
            $jmlValidJurnal = JurnalPkl::where('pengajuan_pkl_id', $pengajuan->id)
                ->where('status', 'valid')
                ->count();
            
            $jurnalTerbaru = JurnalPkl::where('pengajuan_pkl_id', $pengajuan->id)
                ->latest()
                ->take(5)
                ->get();

            $absensiTerbaru = AbsensiPkl::where('pengajuan_pkl_id', $pengajuan->id)
                ->latest()
                ->take(5)
                ->get();

            $absensiHariIni = AbsensiPkl::where('pengajuan_pkl_id', $pengajuan->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();
            
            if ($pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai) {
                try {
                    $mulai = Carbon::parse($pengajuan->tanggal_mulai);
                    $selesai = Carbon::parse($pengajuan->tanggal_selesai);
                    
                    // Format period (e.g. "Agt 2023 - Nov 2023")
                    $periode = $mulai->translatedFormat('M Y') . ' - ' . $selesai->translatedFormat('M Y');
                    
                    // Count total weekdays (Monday - Friday) as working days
                    $diff = $mulai->diffInDaysFiltered(function (Carbon $date) {
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

        return view('siswa.dashboard', compact(
            'siswa',
            'pengajuan',
            'jmlJurnal',
            'jmlValidJurnal',
            'totalHari',
            'periode',
            'progressPersen',
            'jurnalTerbaru',
            'absensiTerbaru',
            'absensiHariIni'
        ));
    }
}

