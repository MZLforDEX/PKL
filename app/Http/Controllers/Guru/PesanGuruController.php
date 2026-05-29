<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PesanGuru;
use App\Models\User;
use App\Notifications\PesanBaruDariGuru;
use Illuminate\Http\Request;

class PesanGuruController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        if (!$guru) {
            abort(403, 'Profil guru belum diatur.');
        }

        $pesan = PesanGuru::where('guru_id', $guru->id)
            ->latest()
            ->paginate(10);

        return view('guru.pesan.index', compact('pesan'));
    }

    public function create()
    {
        $guru = auth()->user()->guru;
        if (!$guru) {
            abort(403, 'Profil guru belum diatur.');
        }

        return view('guru.pesan.create');
    }

    public function store(Request $request)
    {
        $guru = auth()->user()->guru;
        if (!$guru) {
            abort(403, 'Profil guru belum diatur.');
        }

        $request->validate([
            'subjek' => 'required|string|max:255',
            'kategori' => 'required|string|in:penilaian,jurnal,laporan,teknis,lainnya',
            'pesan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = [
            'guru_id' => $guru->id,
            'subjek' => $request->subjek,
            'kategori' => $request->kategori,
            'pesan' => $request->pesan,
            'status' => 'menunggu_tanggapan',
        ];

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('pesan_lampiran', 'public');
        }

        $pesan = PesanGuru::create($data);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PesanBaruDariGuru($pesan));
        }

        return redirect()->route('guru.hubungi-admin.index')
            ->with('success', 'Pesan berhasil dikirim ke Admin sekolah.');
    }

    public function show(PesanGuru $hubungi_admin)
    {
        $guru = auth()->user()->guru;
        if (!$guru || $hubungi_admin->guru_id !== $guru->id) {
            abort(403);
        }

        return view('guru.pesan.show', [
            'pesan' => $hubungi_admin
        ]);
    }
}
