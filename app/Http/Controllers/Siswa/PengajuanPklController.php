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
        $pengajuan = PengajuanPkl::with(['tempatPkl', 'guru.user'])
            ->where('siswa_id', $siswa->id)->latest()->paginate(10);
        return view('siswa.pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        $tempatPkl = TempatPkl::all();
        return view('siswa.pengajuan.create', compact('tempatPkl'));
    }

    public function store(StorePengajuanPklRequest $request)
    {
        $siswa = auth()->user()->siswa;

        $hasActive = PengajuanPkl::where('siswa_id', $siswa->id)
            ->whereNotIn('status', ['ditolak', 'selesai'])
            ->exists();

        if ($hasActive) {
            return redirect()->back()->withErrors(['msg' => 'Anda masih memiliki pengajuan aktif.']);
        }

        $data = $request->validated();
        $data['siswa_id'] = $siswa->id;
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
        $tempatPkl = TempatPkl::all();
        return view('siswa.pengajuan.edit', compact('pengajuan', 'tempatPkl'));
    }

    public function update(UpdatePengajuanPklRequest $request, PengajuanPkl $pengajuan)
    {
        $this->authorizeOwner($pengajuan);
        if (!in_array($pengajuan->status, ['draft', 'revisi'])) {
            return redirect()->back()->withErrors(['msg' => 'Pengajuan tidak dapat diubah.']);
        }

        $data = $request->validated();

        if ($request->hasFile('file_dokumen')) {
            if ($pengajuan->file_dokumen) {
                Storage::disk('public')->delete($pengajuan->file_dokumen);
            }
            $data['file_dokumen'] = $request->file('file_dokumen')->store('pengajuan', 'public');
        }

        $pengajuan->update($data);
        return redirect()->route('siswa.pengajuan.index')->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function ajukan(PengajuanPkl $pengajuan)
    {
        $this->authorizeOwner($pengajuan);
        if (!in_array($pengajuan->status, ['draft', 'revisi'])) {
            return redirect()->back()->withErrors(['msg' => 'Pengajuan tidak dapat diajukan.']);
        }
        $pengajuan->update(['status' => 'menunggu_persetujuan']);
        return redirect()->route('siswa.pengajuan.index')->with('success', 'Pengajuan berhasil diajukan.');
    }

    public function destroy(PengajuanPkl $pengajuan)
    {
        $this->authorizeOwner($pengajuan);
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
        if ($pengajuan->siswa_id !== $siswa->id) {
            abort(403);
        }
    }
}
