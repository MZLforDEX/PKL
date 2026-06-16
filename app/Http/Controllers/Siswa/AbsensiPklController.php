<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPkl;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AbsensiPklController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        $pengajuan = PengajuanPkl::where('siswa_id', $siswa->id)
            ->whereIn('status', ['disetujui', 'sedang_pkl', 'menunggu_penilaian', 'selesai'])
            ->first();

        $absensi = collect();
        $sudahAbsenHariIni = false;

        if ($pengajuan) {
            $absensi = AbsensiPkl::where('pengajuan_pkl_id', $pengajuan->id)
                ->latest()
                ->paginate(10);

            $sudahAbsenHariIni = AbsensiPkl::where('pengajuan_pkl_id', $pengajuan->id)
                ->whereDate('tanggal', Carbon::today())
                ->exists();
        }

        return view('siswa.absensi.index', compact('pengajuan', 'absensi', 'sudahAbsenHariIni'));
    }

    public function store(Request $request)
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        $pengajuan = PengajuanPkl::where('siswa_id', $siswa->id)
            ->whereIn('status', ['disetujui', 'sedang_pkl'])
            ->first();

        if (!$pengajuan) {
            return redirect()->back()->withErrors(['msg' => 'Anda tidak memiliki status PKL aktif untuk melakukan absensi.']);
        }

        $today = Carbon::today();
        if ($today->lt($pengajuan->tanggal_mulai) || $today->gt($pengajuan->tanggal_selesai)) {
            return redirect()->back()->withErrors(['msg' => 'Di luar periode PKL.']);
        }

        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto_selfie' => 'required|string|max:3145728',
        ]);

        $now = Carbon::now();
        $jamMasuk = $now->toTimeString();
        $tanggal = $now->toDateString();

        // 08:00 AM check-in limit
        $status = $now->gt($now->copy()->setTime(8, 0, 0)) ? 'terlambat' : 'hadir';

        $fotoPath = '';
        $fotoData = $request->input('foto_selfie');

        if (preg_match('/^data:image\/(jpeg|png|jpg|webp);base64,/', $fotoData)) {
            $imageInfo = explode(";base64,", $fotoData);
            $imageDecoded = base64_decode($imageInfo[1], true);

            if ($imageDecoded === false || strlen($imageDecoded) === 0) {
                return redirect()->back()->withErrors(['msg' => 'Data foto kosong.']);
            }

            $imgInfo = @getimagesizefromstring($imageDecoded);
            if ($imgInfo === false) {
                return redirect()->back()->withErrors(['msg' => 'Foto tidak valid.']);
            }

            preg_match('/^data:image\/(jpeg|png|jpg|webp);base64,/', $fotoData, $mimeMatch);
            $ext = isset($mimeMatch[1]) ? match ($mimeMatch[1]) {
                'jpeg', 'jpg' => 'jpg',
                'png' => 'png',
                'webp' => 'webp',
                default => 'jpg',
            } : 'jpg';

            $fileName = 'selfie_' . uniqid() . '.' . $ext;
            $fotoPath = 'absensi/' . $fileName;
            Storage::disk('public')->put($fotoPath, $imageDecoded);
        } else {
            return redirect()->back()->withErrors(['msg' => 'Format foto tidak valid.']);
        }

        DB::beginTransaction();
        try {
            $exists = AbsensiPkl::where('pengajuan_pkl_id', $pengajuan->id)
                ->whereDate('tanggal', Carbon::today())
                ->exists();
            if ($exists) {
                DB::rollBack();
                return redirect()->back()->withErrors(['msg' => 'Anda sudah melakukan absensi hari ini.']);
            }

            AbsensiPkl::create([
                'pengajuan_pkl_id' => $pengajuan->id,
                'tanggal' => $tanggal,
                'jam_masuk' => $jamMasuk,
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'foto_selfie' => $fotoPath,
                'status' => $status,
            ]);

            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->getCode() == 23000 || (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062)) {
                return redirect()->back()->withErrors(['msg' => 'Anda sudah melakukan absensi hari ini.']);
            }
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('siswa.absensi.index')->with('success', 'Absensi berhasil disimpan!');
    }
}
