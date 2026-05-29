<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesanGuru;
use App\Notifications\PesanGuruDibalas;
use Illuminate\Http\Request;

class AduanGuruController extends Controller
{
    public function index()
    {
        $pesan = PesanGuru::with(['guru.user'])
            ->latest()
            ->paginate(10);

        return view('admin.pesan-guru.index', compact('pesan'));
    }

    public function show($id)
    {
        $pesan = PesanGuru::with(['guru.user', 'dibalasOleh'])->findOrFail($id);

        return view('admin.pesan-guru.show', compact('pesan'));
    }

    public function reply(Request $request, $id)
    {
        $pesan = PesanGuru::findOrFail($id);

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

        if ($pesan->guru && $pesan->guru->user) {
            $pesan->guru->user->notify(new PesanGuruDibalas($pesan));
        }

        return redirect()->route('admin.pesan-guru.show', $pesan->id)
            ->with('success', 'Tanggapan aduan guru berhasil disimpan dan dikirim.');
    }
}
