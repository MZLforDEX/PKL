<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodePkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodePklController extends Controller
{
    public function index()
    {
        $periodes = PeriodePkl::orderByDesc('tanggal_mulai')->paginate(10);
        return view('admin.periode-pkl.index', compact('periodes'));
    }

    public function create()
    {
        return view('admin.periode-pkl.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status_aktif' => 'nullable|boolean',
        ]);

        $statusAktif = $request->has('status_aktif') && $request->status_aktif;

        DB::transaction(function () use ($request, $statusAktif) {
            if ($statusAktif) {
                PeriodePkl::query()->update(['status_aktif' => false]);
            }

            PeriodePkl::create([
                'nama_periode' => $request->nama_periode,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status_aktif' => $statusAktif,
            ]);
        });

        return redirect()->route('admin.periode-pkl.index')->with('success', 'Periode PKL berhasil ditambahkan.');
    }

    public function edit(PeriodePkl $periodePkl)
    {
        return view('admin.periode-pkl.edit', compact('periodePkl'));
    }

    public function update(Request $request, PeriodePkl $periodePkl)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status_aktif' => 'nullable|boolean',
        ]);

        $statusAktif = $request->has('status_aktif') && $request->status_aktif;

        DB::transaction(function () use ($request, $periodePkl, $statusAktif) {
            if ($statusAktif) {
                PeriodePkl::where('id', '!=', $periodePkl->id)->update(['status_aktif' => false]);
            }

            $periodePkl->update([
                'nama_periode' => $request->nama_periode,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status_aktif' => $statusAktif,
            ]);
        });

        return redirect()->route('admin.periode-pkl.index')->with('success', 'Periode PKL berhasil diperbarui.');
    }

    public function destroy(PeriodePkl $periodePkl)
    {
        if ($periodePkl->status_aktif) {
            return redirect()->back()->withErrors(['msg' => 'Tidak dapat menghapus periode yang sedang aktif.']);
        }

        if ($periodePkl->pengajuanPkl()->exists()) {
            return redirect()->back()->withErrors(['msg' => 'Tidak dapat menghapus periode yang memiliki data pengajuan PKL terkait.']);
        }

        $periodePkl->delete();

        // Clear session if the deleted period was the selected one
        if (session('selected_periode_id') == $periodePkl->id) {
            session()->forget('selected_periode_id');
        }

        return redirect()->route('admin.periode-pkl.index')->with('success', 'Periode PKL berhasil dihapus.');
    }
}
