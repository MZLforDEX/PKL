<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function security(Request $request): View
    {
        return view('profile.security', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->only('name', 'email'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        if ($user->role === 'siswa' && $user->siswa) {
            $user->siswa->update($request->only('nis', 'kelas', 'jurusan', 'alamat', 'no_hp'));
        }

        if ($user->role === 'guru' && $user->guru) {
            $user->guru->update($request->only('nip', 'alamat', 'no_hp'));
        }

        if ($user->role === 'pembimbing_industri' && $user->pembimbingIndustri) {
            $data = $request->only('jabatan', 'no_hp');
            if ($request->hasFile('tanda_tangan')) {
                if ($user->pembimbingIndustri->tanda_tangan) {
                    Storage::disk('public')->delete($user->pembimbingIndustri->tanda_tangan);
                }
                $data['tanda_tangan'] = $request->file('tanda_tangan')->store('signatures', 'public');
            }
            if ($request->hasFile('logo')) {
                if ($user->pembimbingIndustri->logo) {
                    Storage::disk('public')->delete($user->pembimbingIndustri->logo);
                }
                $data['logo'] = $request->file('logo')->store('logos', 'public');
            }
            $user->pembimbingIndustri->update($data);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        DB::transaction(function () use ($user) {
            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
