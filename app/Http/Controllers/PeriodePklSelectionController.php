<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeriodePklSelectionController extends Controller
{
    public function select(Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'guru', 'pembimbing_industri'])) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'periode_pkl_id' => 'required|exists:periode_pkl,id',
        ]);

        session(['selected_periode_id' => $request->periode_pkl_id]);

        return redirect()->back()->with('success', 'Periode PKL berhasil dipilih.');
    }
}
