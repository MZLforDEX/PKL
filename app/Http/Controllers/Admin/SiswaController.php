<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index()
    {
        $selectedPeriodeId = request()->has('periode_id') ? request('periode_id') : \App\Models\PeriodePkl::getSelectedPeriodId();
        $search = request('search');
        $siswa = Siswa::with('user')
            ->when($selectedPeriodeId, function ($query, $selectedPeriodeId) {
                $query->whereHas('pengajuanPkl', function ($q) use ($selectedPeriodeId) {
                    $q->where('periode_pkl_id', $selectedPeriodeId);
                });
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nis', 'like', "%{$search}%")
                        ->orWhere('kelas', 'like', "%{$search}%")
                        ->orWhere('jurusan', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->paginate(10)->withQueryString();

        $periodes = \App\Models\PeriodePkl::orderByDesc('tanggal_mulai')->get();

        return view('admin.siswa.index', compact('siswa', 'periodes', 'selectedPeriodeId'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(StoreSiswaRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->forceFill(['role' => 'siswa', 'is_approved' => true])->save();

            Siswa::create([
                'user_id' => $user->id,
                'nis' => $request->nis,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa)
    {
        optional($siswa->user)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            optional($siswa->user)->update(['password' => bcrypt($request->password)]);
        }

        $siswa->update([
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        DB::transaction(function () use ($siswa) {
            if ($siswa->user) {
                $siswa->user->delete();
            } else {
                $siswa->delete();
            }
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
