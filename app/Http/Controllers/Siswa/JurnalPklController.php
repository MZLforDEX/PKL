<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJurnalPklRequest;
use App\Http\Requests\UpdateJurnalPklRequest;
use App\Models\JurnalPkl;
use App\Models\PengajuanPkl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JurnalPklController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        $jurnal = JurnalPkl::whereHas('pengajuanPkl', fn($q) => $q->where('siswa_id', $siswa->id))
            ->latest()->paginate(10);
        return view('siswa.jurnal.index', compact('jurnal'));
    }

    public function create()
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        $pengajuan = PengajuanPkl::where('siswa_id', $siswa->id)
            ->whereIn('status', ['disetujui', 'sedang_pkl'])->first();

        if (!$pengajuan) {
            return redirect()->route('siswa.jurnal.index')->withErrors(['msg' => 'Belum ada pengajuan disetujui.']);
        }

        return view('siswa.jurnal.create', compact('pengajuan'));
    }

    public function store(StoreJurnalPklRequest $request)
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        $pengajuan = PengajuanPkl::where('siswa_id', $siswa->id)
            ->whereIn('status', ['disetujui', 'sedang_pkl'])->first();

        if (!$pengajuan) {
            return redirect()->route('siswa.jurnal.index')->withErrors(['msg' => 'Belum ada pengajuan disetujui.']);
        }

        $data = $request->validated();
        $data['pengajuan_pkl_id'] = $pengajuan->id;
        $data['status'] = 'menunggu_validasi';

        if ($request->hasFile('dokumentasi')) {
            $data['dokumentasi'] = $request->file('dokumentasi')->store('jurnal', 'public');
        }

        $jurnal = DB::transaction(function () use ($pengajuan, $data) {
            if ($pengajuan->status === 'disetujui') {
                $pengajuan->update(['status' => 'sedang_pkl']);
            }

            return JurnalPkl::create($data);
        });

        if ($pengajuan->guru && $pengajuan->guru->user) {
            $pengajuan->guru->user->notify(new \App\Notifications\SiswaUploadJurnal($jurnal));
        }

        return redirect()->route('siswa.jurnal.index')->with('success', 'Jurnal berhasil ditambahkan.');
    }

    public function edit(JurnalPkl $jurnalPkl)
    {
        $this->authorizeOwner($jurnalPkl);
        if ($jurnalPkl->status === 'valid') {
            return redirect()->route('siswa.jurnal.index')->withErrors(['msg' => 'Jurnal sudah divalidasi, tidak dapat diubah.']);
        }
        $pengajuan = $jurnalPkl->pengajuanPkl;
        if (!in_array($pengajuan->status, ['disetujui', 'sedang_pkl'])) {
            return redirect()->route('siswa.jurnal.index')->withErrors(['msg' => 'PKL tidak aktif, jurnal tidak dapat diubah.']);
        }
        return view('siswa.jurnal.edit', compact('jurnalPkl'));
    }

    public function update(UpdateJurnalPklRequest $request, JurnalPkl $jurnalPkl)
    {
        $this->authorizeOwner($jurnalPkl);
        $pengajuan = $jurnalPkl->pengajuanPkl;
        if (!in_array($pengajuan->status, ['disetujui', 'sedang_pkl'])) {
            return redirect()->back()->withErrors(['msg' => 'PKL tidak aktif, jurnal tidak dapat diubah.']);
        }
        if ($jurnalPkl->status === 'valid') {
            return redirect()->back()->withErrors(['msg' => 'Jurnal sudah divalidasi, tidak dapat diubah.']);
        }

        $data = $request->validated();
        $data['status'] = 'menunggu_validasi';
        if ($request->hasFile('dokumentasi')) {
            if ($jurnalPkl->dokumentasi) {
                Storage::disk('public')->delete($jurnalPkl->dokumentasi);
            }
            $data['dokumentasi'] = $request->file('dokumentasi')->store('jurnal', 'public');
        }

        $jurnalPkl->update($data);
        return redirect()->route('siswa.jurnal.index')->with('success', 'Jurnal berhasil diperbarui.');
    }

    private function authorizeOwner(JurnalPkl $jurnalPkl): void
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) abort(403, 'Profil siswa belum diatur.');
        if ($jurnalPkl->pengajuanPkl->siswa_id !== $siswa->id) {
            abort(403);
        }
    }
}
