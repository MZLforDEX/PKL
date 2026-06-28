<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengajuanPklRequest;
use App\Http\Requests\UpdatePengajuanPklRequest;
use App\Models\PengajuanPkl;
use App\Models\TempatPkl;
use Illuminate\Support\Facades\Storage;

class PengajuanPklController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        $pengajuan = PengajuanPkl::with(['tempatPkl', 'guru.user'])
            ->where('siswa_id', $siswa->id)->latest()->paginate(10);
        return view('siswa.pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        $tempatPkl = TempatPkl::all();
        return view('siswa.pengajuan.create', compact('tempatPkl'));
    }

    public function store(StorePengajuanPklRequest $request)
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');

        $activePeriodId = \App\Models\PeriodePkl::where('status_aktif', true)->first()?->id;
        if (!$activePeriodId) {
            return redirect()->back()->withInput()->withErrors(['msg' => 'Pendaftaran PKL belum dibuka oleh Admin (tidak ada periode aktif).']);
        }

        $hasActive = PengajuanPkl::where('siswa_id', $siswa->id)
            ->where('periode_pkl_id', $activePeriodId)
            ->whereNotIn('status', ['ditolak', 'selesai'])
            ->exists();

        if ($hasActive) {
            return redirect()->back()->withErrors(['msg' => 'Anda masih memiliki pengajuan aktif di periode ini.']);
        }

        $tempatPkl = TempatPkl::findOrFail($request->tempat_pkl_id);
        if ($tempatPkl->is_penuh) {
            return redirect()->back()->withErrors(['msg' => 'Tempat PKL ini sudah penuh (kuota habis).']);
        }

        $data = $request->validated();
        $data['siswa_id'] = $siswa->id;
        $data['periode_pkl_id'] = $activePeriodId;
        $data['status'] = 'draft';

        if ($request->hasFile('file_dokumen')) {
            $data['file_dokumen'] = $request->file('file_dokumen')->store('pengajuan', 'public');
        }

        PengajuanPkl::create($data);

        return redirect()->route('siswa.pengajuan.index')->with('success', 'Pengajuan PKL berhasil dibuat.');
    }

    public function edit(PengajuanPkl $pengajuan)
    {
        $this->authorizeOwner($pengajuan);
        if (!$pengajuan->periodePkl || !$pengajuan->periodePkl->status_aktif) {
            return redirect()->back()->withErrors(['msg' => 'Aksi tidak diizinkan pada periode PKL yang sudah diarsipkan.']);
        }
        if (!in_array($pengajuan->status, ['draft', 'revisi'])) {
            return redirect()->back()->withErrors(['msg' => 'Pengajuan tidak dapat diubah.']);
        }
        $tempatPkl = TempatPkl::all();
        return view('siswa.pengajuan.edit', compact('pengajuan', 'tempatPkl'));
    }

    public function update(UpdatePengajuanPklRequest $request, PengajuanPkl $pengajuan)
    {
        $this->authorizeOwner($pengajuan);
        if (!$pengajuan->periodePkl || !$pengajuan->periodePkl->status_aktif) {
            return redirect()->back()->withErrors(['msg' => 'Aksi tidak diizinkan pada periode PKL yang sudah diarsipkan.']);
        }
        if (!in_array($pengajuan->status, ['draft', 'revisi'])) {
            return redirect()->back()->withErrors(['msg' => 'Pengajuan tidak dapat diubah.']);
        }

        $tempatPkl = TempatPkl::findOrFail($request->tempat_pkl_id);
        if ($request->tempat_pkl_id != $pengajuan->tempat_pkl_id && $tempatPkl->is_penuh) {
            return redirect()->back()->withErrors(['msg' => 'Tempat PKL tujuan sudah penuh (kuota habis).']);
        }

        $data = $request->validated();

        if ($request->hasFile('file_dokumen')) {
            $path = $request->file('file_dokumen')->store('pengajuan', 'public');
            if ($pengajuan->file_dokumen) {
                Storage::disk('public')->delete($pengajuan->file_dokumen);
            }
            $data['file_dokumen'] = $path;
        }

        $pengajuan->update($data);
        return redirect()->route('siswa.pengajuan.index')->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function ajukan(PengajuanPkl $pengajuan)
    {
        $this->authorizeOwner($pengajuan);
        if (!$pengajuan->periodePkl || !$pengajuan->periodePkl->status_aktif) {
            return redirect()->back()->withErrors(['msg' => 'Aksi tidak diizinkan pada periode PKL yang sudah diarsipkan.']);
        }
        if (empty($pengajuan->tempat_pkl_id) || empty($pengajuan->tanggal_mulai) || empty($pengajuan->tanggal_selesai) || empty($pengajuan->alasan)) {
            return redirect()->back()->withErrors(['msg' => 'Lengkapi data pengajuan terlebih dahulu.']);
        }
        if (!in_array($pengajuan->status, ['draft', 'revisi'])) {
            return redirect()->back()->withErrors(['msg' => 'Pengajuan tidak dapat diajukan.']);
        }

        if ($pengajuan->tempatPkl->is_penuh) {
            return redirect()->back()->withErrors(['msg' => 'Tidak dapat diajukan karena kuota Tempat PKL ini sudah penuh.']);
        }

        $pengajuan->update(['status' => 'menunggu_persetujuan']);
        return redirect()->route('siswa.pengajuan.index')->with('success', 'Pengajuan berhasil diajukan.');
    }

    public function destroy(PengajuanPkl $pengajuan)
    {
        $this->authorizeOwner($pengajuan);
        if (!$pengajuan->periodePkl || !$pengajuan->periodePkl->status_aktif) {
            return redirect()->back()->withErrors(['msg' => 'Aksi tidak diizinkan pada periode PKL yang sudah diarsipkan.']);
        }
        if (!in_array($pengajuan->status, ['draft', 'ditolak'])) {
            return redirect()->back()->withErrors(['msg' => 'Hanya pengajuan draft/ditolak yang dapat dihapus.']);
        }
        if ($pengajuan->file_dokumen) {
            Storage::disk('public')->delete($pengajuan->file_dokumen);
        }
        $pengajuan->delete();
        return redirect()->route('siswa.pengajuan.index')->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function show(PengajuanPkl $pengajuan)
    {
        $this->authorizeOwner($pengajuan);
        $pengajuan->load(['tempatPkl', 'guru.user', 'jurnalPkl', 'laporanPkl', 'penilaianPkl']);
        return view('siswa.pengajuan.show', compact('pengajuan'));
    }

    public function cetakSertifikat(PengajuanPkl $pengajuan)
    {
        $this->authorizeOwner($pengajuan);
        if ($pengajuan->status !== 'selesai') {
            return redirect()->back()->withErrors(['msg' => 'Sertifikat belum tersedia.']);
        }
        $pengajuan->load(['siswa.user', 'tempatPkl', 'guru.user', 'penilaianPkl']);
        return view('siswa.sertifikat.print', compact('pengajuan'));
    }

    private function authorizeOwner(PengajuanPkl $pengajuan): void
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        if ($pengajuan->siswa_id !== $siswa->id) {
            abort(403);
        }
    }
}
