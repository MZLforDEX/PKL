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
        $search = request('search');
        $guru = Guru::with('user')
            ->when($search, function ($query, $search) {
                $query->where('nip', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->paginate(10)->withQueryString();
        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(StoreGuruRequest $request)
    {
        DB::transaction(function () use ($request) {
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
        });

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
            if ($guru->user) {
                $guru->user->delete();
            } else {
                $guru->delete();
            }
        });

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}
