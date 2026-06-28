<?php

namespace App\Http\Controllers\PembimbingIndustri;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;
use App\Models\PembimbingIndustri;
use App\Models\Siswa;

class AbsensiPklController extends Controller
{
    public function index()
    {
        $pembimbing = $this->getPembimbing();
        $selectedPeriodeId = request()->has('periode_id') ? request('periode_id') : \App\Models\PeriodePkl::getSelectedPeriodId();

        $absensi = AbsensiPkl::with(['pengajuanPkl.siswa.user'])
            ->whereHas('pengajuanPkl', function ($q) use ($pembimbing) {
                $q->where('tempat_pkl_id', $pembimbing->tempat_pkl_id);
            })
            ->when($selectedPeriodeId, function ($query, $selectedPeriodeId) {
                $query->whereHas('pengajuanPkl', function ($q) use ($selectedPeriodeId) {
                    $q->where('periode_pkl_id', $selectedPeriodeId);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $periodes = \App\Models\PeriodePkl::orderByDesc('tanggal_mulai')->get();

        return view('pembimbing.absensi.index', compact('absensi', 'periodes', 'selectedPeriodeId'));
    }

    public function export(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'bulan' => 'nullable|integer|between:1,12',
            'tahun' => 'nullable|integer|min:2000|max:2100',
        ]);

        $pembimbing = $this->getPembimbing();
        $selectedPeriodeId = request()->has('periode_id') ? request('periode_id') : \App\Models\PeriodePkl::getSelectedPeriodId();
        
        $periode = $selectedPeriodeId ? \App\Models\PeriodePkl::find($selectedPeriodeId) : null;
        $periodeName = $periode ? $periode->nama_periode : 'Semua Periode';

        $month = (int) $request->input('bulan', date('n'));
        $year = (int) $request->input('tahun', date('Y'));

        // Get all students magang for this industry partner
        $students = Siswa::whereHas('pengajuanPkl', function ($q) use ($pembimbing, $selectedPeriodeId) {
            $q->where('tempat_pkl_id', $pembimbing->tempat_pkl_id)
              ->whereIn('status', ['disetujui', 'sedang_pkl', 'menunggu_penilaian', 'selesai']);
            if ($selectedPeriodeId) {
                $q->where('periode_pkl_id', $selectedPeriodeId);
            }
        })->with(['user', 'pengajuanPkl' => function ($q) use ($selectedPeriodeId) {
            if ($selectedPeriodeId) {
                $q->where('periode_pkl_id', $selectedPeriodeId);
            }
        }])->get();

        // Calculate days in the selected month
        $daysInMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->daysInMonth;

        $indonesianMonths = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulanNama = $indonesianMonths[$month];

        $daysMap = [
            0 => 'Su',
            1 => 'Mo',
            2 => 'Tu',
            3 => 'We',
            4 => 'Th',
            5 => 'Fr',
            6 => 'Sa',
        ];

        // Fetch all attendance for these students in the selected month/year
        $pengajuanIds = $students->pluck('pengajuanPkl')->flatten()->pluck('id');
        $allAbsensi = AbsensiPkl::whereIn('pengajuan_pkl_id', $pengajuanIds)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get()
            ->groupBy('pengajuan_pkl_id');

        // Transform students data to include attendance mapped by day number
        $studentsData = $students->map(function ($siswa) use ($allAbsensi, $year, $month, $daysInMonth) {
            $pengajuan = $siswa->pengajuanPkl->first();
            $pengajuanId = $pengajuan?->id;
            
            $studentAbsensi = isset($allAbsensi[$pengajuanId]) ? $allAbsensi[$pengajuanId]->keyBy(function($a) {
                return (int) \Carbon\Carbon::parse($a->tanggal)->format('j');
            }) : collect();

            return [
                'nama' => $siswa->user->name,
                'nis' => $siswa->nis,
                'absensi' => $studentAbsensi,
                'tanggal_mulai' => $pengajuan?->tanggal_mulai,
                'tanggal_selesai' => $pengajuan?->tanggal_selesai,
            ];
        });

        $view = view('exports.absensi', [
            'periode' => $periode,
            'periodeName' => $periodeName,
            'bulan' => $month,
            'tahun' => $year,
            'bulanNama' => $bulanNama,
            'daysInMonth' => $daysInMonth,
            'daysMap' => $daysMap,
            'students' => $studentsData,
        ]);

        $filename = "Rekap_Absensi_Siswa_Magang_" . str_replace([' ', '/'], '_', $periodeName) . "_{$bulanNama}_{$year}.xls";

        return response($view)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function getPembimbing(): PembimbingIndustri
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            abort(403, 'Profil pembimbing industri belum diatur.');
        }
        return $pembimbing;
    }
}
