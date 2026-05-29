<?php

namespace App\Http\Controllers\PembimbingIndustri;

use App\Http\Controllers\Controller;
use App\Models\PesanPembimbing;
use App\Models\User;
use App\Notifications\PesanBaruDariIndustri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PesanPembimbingController extends Controller
{
    public function index()
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            abort(403, 'Profil pembimbing industri belum diatur.');
        }

        $pesan = PesanPembimbing::where('pembimbing_industri_id', $pembimbing->id)
            ->latest()
            ->paginate(10);

        return view('pembimbing.pesan.index', compact('pesan'));
    }

    public function create()
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            abort(403, 'Profil pembimbing industri belum diatur.');
        }

        return view('pembimbing.pesan.create');
    }

    public function store(Request $request)
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            abort(403, 'Profil pembimbing industri belum diatur.');
        }

        $request->validate([
            'subjek' => 'required|string|max:255',
            'kategori' => 'required|string|in:administrasi,kendala_siswa,teknis,lainnya',
            'pesan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = [
            'pembimbing_industri_id' => $pembimbing->id,
            'subjek' => $request->subjek,
            'kategori' => $request->kategori,
            'pesan' => $request->pesan,
            'status' => 'menunggu_tanggapan',
        ];

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('pesan_lampiran', 'public');
        }

        $pesan = PesanPembimbing::create($data);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PesanBaruDariIndustri($pesan));
        }

        return redirect()->route('pembimbing.hubungi-sekolah.index')
            ->with('success', 'Pesan berhasil dikirim ke sekolah.');
    }

    public function show(PesanPembimbing $hubungi_sekolah)
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing || $hubungi_sekolah->pembimbing_industri_id !== $pembimbing->id) {
            abort(403);
        }

        return view('pembimbing.pesan.show', [
            'pesan' => $hubungi_sekolah
        ]);
    }
}
