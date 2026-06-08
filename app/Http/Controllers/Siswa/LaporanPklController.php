<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLaporanPklRequest;
use App\Http\Requests\UpdateLaporanPklRequest;
use App\Models\LaporanPkl;
use App\Models\PengajuanPkl;
use Illuminate\Support\Facades\Storage;

class LaporanPklController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        $laporan = LaporanPkl::with('pengajuanPkl.tempatPkl')
            ->whereHas('pengajuanPkl', fn($q) => $q->where('siswa_id', $siswa->id))
            ->latest()->paginate(10);

        $canUpload = PengajuanPkl::where('siswa_id', $siswa->id)
            ->whereIn('status', ['disetujui', 'sedang_pkl'])
            ->whereDoesntHave('laporanPkl')
            ->exists();

        return view('siswa.laporan.index', compact('laporan', 'canUpload'));
    }

    public function create()
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        $pengajuanAktif = PengajuanPkl::where('siswa_id', $siswa->id)
            ->whereIn('status', ['disetujui', 'sedang_pkl'])
            ->whereDoesntHave('laporanPkl')
            ->first();

        if (!$pengajuanAktif) {
            return redirect()->route('siswa.laporan.index')->withErrors(['msg' => 'Tidak ada pengajuan aktif atau laporan sudah ada.']);
        }

        return view('siswa.laporan.create', compact('pengajuanAktif'));
    }

    public function store(StoreLaporanPklRequest $request)
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        $pengajuan = PengajuanPkl::where('siswa_id', $siswa->id)
            ->whereIn('status', ['disetujui', 'sedang_pkl'])
            ->whereDoesntHave('laporanPkl')
            ->first();

        if (!$pengajuan) {
            return redirect()->route('siswa.laporan.index')->withErrors(['msg' => 'Tidak ada pengajuan aktif atau laporan sudah diunggah.']);
        }

        $data = $request->validated();
        $data['pengajuan_pkl_id'] = $pengajuan->id;
        $data['status'] = 'menunggu_review';
        $data['file_laporan'] = $request->file('file_laporan')->store('laporan', 'public');

        $laporan = LaporanPkl::create($data);

        $this->notifyGuruLaporan($pengajuan, $laporan);

        return redirect()->route('siswa.laporan.index')->with('success', 'Laporan berhasil diunggah.');
    }

    public function edit(LaporanPkl $laporanPkl)
    {
        $this->authorizeOwner($laporanPkl);

        if ($laporanPkl->status !== 'revisi') {
            return redirect()->route('siswa.laporan.index')->withErrors(['msg' => 'Laporan tidak memerlukan perbaikan.']);
        }

        $laporanPkl->load('pengajuanPkl.tempatPkl');

        return view('siswa.laporan.edit', compact('laporanPkl'));
    }

    public function update(UpdateLaporanPklRequest $request, LaporanPkl $laporanPkl)
    {
        $this->authorizeOwner($laporanPkl);

        $pengajuan = $laporanPkl->pengajuanPkl;
        if (!in_array($pengajuan->status, ['disetujui', 'sedang_pkl'])) {
            return redirect()->route('siswa.laporan.index')->withErrors(['msg' => 'PKL tidak aktif, laporan tidak dapat diperbarui.']);
        }

        if ($laporanPkl->status !== 'revisi') {
            return redirect()->route('siswa.laporan.index')->withErrors(['msg' => 'Laporan tidak dapat diperbarui.']);
        }

        $path = $request->file('file_laporan')->store('laporan', 'public');

        if ($laporanPkl->file_laporan) {
            Storage::disk('public')->delete($laporanPkl->file_laporan);
        }

        $laporanPkl->update([
            'file_laporan' => $path,
            'status' => 'menunggu_review',
        ]);

        $this->notifyGuruLaporan($pengajuan, $laporanPkl->fresh());

        return redirect()->route('siswa.laporan.index')->with('success', 'Laporan berhasil diperbaiki dan dikirim ulang.');
    }

    private function authorizeOwner(LaporanPkl $laporanPkl): void
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        if ($laporanPkl->pengajuanPkl->siswa_id !== $siswa->id) {
            abort(403);
        }
    }

    private function notifyGuruLaporan(PengajuanPkl $pengajuan, LaporanPkl $laporan): void
    {
        if ($pengajuan->guru && $pengajuan->guru->user) {
            $pengajuan->guru->user->notify(new \App\Notifications\SiswaUploadLaporan($laporan));
        }
    }
}
