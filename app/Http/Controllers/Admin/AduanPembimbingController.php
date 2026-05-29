<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesanPembimbing;
use App\Notifications\PesanTelahDibalasSekolah;
use Illuminate\Http\Request;

class AduanPembimbingController extends Controller
{
    private function checkAccess(PesanPembimbing $pesan)
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'guru') {
            $guru = $user->guru;
            if (!$guru) {
                return false;
            }

            // Check if this guru supervises any student at the sender's tempat PKL
            $tempatPklId = $pesan->pembimbingIndustri?->tempat_pkl_id;
            if (!$tempatPklId) {
                return false;
            }

            $hasAccess = \App\Models\PengajuanPkl::where('guru_id', $guru->id)
                ->where('tempat_pkl_id', $tempatPklId)
                ->exists();

            return $hasAccess;
        }

        return false;
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $pesan = PesanPembimbing::with(['pembimbingIndustri.tempatPkl', 'pembimbingIndustri.user'])
                ->latest()
                ->paginate(10);
        } else if ($user->role === 'guru') {
            $guru = $user->guru;
            if (!$guru) {
                abort(403, 'Profil guru belum diatur.');
            }

            $tempatPklIds = \App\Models\PengajuanPkl::where('guru_id', $guru->id)
                ->pluck('tempat_pkl_id')
                ->unique();

            $pesan = PesanPembimbing::whereHas('pembimbingIndustri', fn($q) => $q->whereIn('tempat_pkl_id', $tempatPklIds))
                ->with(['pembimbingIndustri.tempatPkl', 'pembimbingIndustri.user'])
                ->latest()
                ->paginate(10);
        } else {
            abort(403);
        }

        return view('admin.pesan.index', compact('pesan'));
    }

    public function show($id)
    {
        $pesan = PesanPembimbing::with(['pembimbingIndustri.tempatPkl', 'pembimbingIndustri.user', 'dibalasOleh'])->findOrFail($id);

        if (!$this->checkAccess($pesan)) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat pesan aduan ini.');
        }

        return view('admin.pesan.show', compact('pesan'));
    }

    public function reply(Request $request, $id)
    {
        $pesan = PesanPembimbing::findOrFail($id);

        if (!$this->checkAccess($pesan)) {
            abort(403, 'Anda tidak memiliki hak akses untuk membalas aduan ini.');
        }

        $request->validate([
            'tanggapan' => 'required|string',
            'status' => 'required|in:proses,selesai',
        ]);

        $pesan->update([
            'tanggapan' => $request->tanggapan,
            'status' => $request->status,
            'dibalas_oleh_id' => auth()->id(),
            'dibalas_pada' => now(),
        ]);

        if ($pesan->pembimbingIndustri && $pesan->pembimbingIndustri->user) {
            $pesan->pembimbingIndustri->user->notify(new PesanTelahDibalasSekolah($pesan));
        }

        $role = auth()->user()->role;
        $route = $role === 'admin' ? 'admin.pesan.show' : 'guru.pesan.show';

        return redirect()->route($route, $pesan->id)
            ->with('success', 'Tanggapan berhasil dikirim ke pembimbing industri.');
    }
}
