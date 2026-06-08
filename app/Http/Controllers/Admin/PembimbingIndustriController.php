<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePembimbingIndustriRequest;
use App\Http\Requests\UpdatePembimbingIndustriRequest;
use App\Models\PembimbingIndustri;
use App\Models\TempatPkl;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PembimbingIndustriController extends Controller
{
    public function index()
    {
        $pembimbing = PembimbingIndustri::with(['user', 'tempatPkl'])->paginate(10);
        return view('admin.pembimbing.index', compact('pembimbing'));
    }

    public function create()
    {
        $tempatPkl = TempatPkl::all();
        return view('admin.pembimbing.create', compact('tempatPkl'));
    }

    public function store(StorePembimbingIndustriRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->forceFill(['role' => 'pembimbing_industri', 'is_approved' => true])->save();

            PembimbingIndustri::create([
                'user_id' => $user->id,
                'tempat_pkl_id' => $request->tempat_pkl_id,
                'no_hp' => $request->no_hp,
                'jabatan' => $request->jabatan,
            ]);
        });

        return redirect()->route('admin.pembimbing-industri.index')->with('success', 'Pembimbing Industri berhasil ditambahkan.');
    }

    public function edit(PembimbingIndustri $pembimbingIndustri)
    {
        $tempatPkl = TempatPkl::all();
        return view('admin.pembimbing.edit', compact('pembimbingIndustri', 'tempatPkl'));
    }

    public function update(UpdatePembimbingIndustriRequest $request, PembimbingIndustri $pembimbingIndustri)
    {
        optional($pembimbingIndustri->user)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            optional($pembimbingIndustri->user)->update(['password' => bcrypt($request->password)]);
        }

        $pembimbingIndustri->update([
            'tempat_pkl_id' => $request->tempat_pkl_id,
            'no_hp' => $request->no_hp,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('admin.pembimbing-industri.index')->with('success', 'Pembimbing Industri berhasil diperbarui.');
    }

    public function destroy(PembimbingIndustri $pembimbingIndustri)
    {
        DB::transaction(function () use ($pembimbingIndustri) {
            optional($pembimbingIndustri->user)->delete();
            $pembimbingIndustri->delete();
        });

        return redirect()->route('admin.pembimbing-industri.index')->with('success', 'Pembimbing Industri berhasil dihapus.');
    }
}
