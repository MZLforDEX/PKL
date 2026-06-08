<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGuruRequest;
use App\Http\Requests\UpdateGuruRequest;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::with('user')->paginate(10);
        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(StoreGuruRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->forceFill(['role' => 'guru', 'is_approved' => true])->save();

        Guru::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(UpdateGuruRequest $request, Guru $guru)
    {
        optional($guru->user)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            optional($guru->user)->update(['password' => bcrypt($request->password)]);
        }

        $guru->update([
            'nip' => $request->nip,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        DB::transaction(function () use ($guru) {
            $guru->pengajuanPkl->each(function ($p) { $p->delete(); });
            optional($guru->user)->delete();
            $guru->delete();
        });

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}
