<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function unapproved()
    {
        $users = User::where('is_approved', false)->latest()->paginate(10);
        return view('admin.users.unapproved', compact('users'));
    }

    public function approve(User $user)
    {
        $user->forceFill(['is_approved' => true])->save();

        // If the user's role is 'siswa' and they don't have a siswa record, create an empty one
        if ($user->role === 'siswa' && !$user->siswa) {
            Siswa::create([
                'user_id' => $user->id,
                'nis' => '-', // Default placeholder, admin or siswa can update later
                'kelas' => '-',
                'jurusan' => '-',
                'alamat' => '-',
                'no_hp' => '-',
            ]);
        }

        return back()->with('success', 'Akun ' . $user->name . ' berhasil disetujui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            abort(403, 'Tidak dapat menghapus akun sendiri.');
        }

        DB::transaction(function () use ($user) {
            $user->delete();
        });

        return back()->with('success', 'Akun pendaftar berhasil ditolak dan dihapus.');
    }
}
