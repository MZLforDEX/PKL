<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesanPembimbing;
use App\Notifications\PesanTelahDibalasSekolah;
use Illuminate\Http\Request;

class AduanPembimbingController extends Controller
{
    private function checkAccess(PesanPembimbing $pesan)
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'guru') {
            $guru = $user->guru;
            if (!$guru) {
                return false;
            }

            // Check if this guru supervises any student at the sender's tempat PKL
            $tempatPklId = $pesan->pembimbingIndustri?->tempat_pkl_id;
            if (!$tempatPklId) {
                return false;
            }

            $hasAccess = \App\Models\PengajuanPkl::where('guru_id', $guru->id)
                ->where('tempat_pkl_id', $tempatPklId)
                ->exists();

            return $hasAccess;
        }

        return false;
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            // Auto-create chat threads for all pembimbing if not exist
            $pembimbings = \App\Models\PembimbingIndustri::all();
            foreach ($pembimbings as $p) {
                $exists = PesanPembimbing::where('pembimbing_industri_id', $p->id)->exists();
                if (!$exists) {
                    PesanPembimbing::create([
                        'pembimbing_industri_id' => $p->id,
                        'subjek' => 'Chat Hubungi Sekolah',
                        'kategori' => 'lainnya',
                        'pesan' => 'Halo, ini adalah sesi chat Hubungi Sekolah Anda.',
                        'status' => 'menunggu_tanggapan',
                    ]);
                }
            }

            $latest = PesanPembimbing::latest()->first();
            if ($latest) {
                return redirect()->route('admin.pesan.show', $latest->id);
            }
            $conversations = PesanPembimbing::with(['pembimbingIndustri.tempatPkl', 'pembimbingIndustri.user'])
                ->latest()
                ->get();
        } else if ($user->role === 'guru') {
            $guru = $user->guru;
            if (!$guru) {
                abort(403, 'Profil guru belum diatur.');
            }

            $tempatPklIds = \App\Models\PengajuanPkl::where('guru_id', $guru->id)
                ->pluck('tempat_pkl_id')
                ->unique();

            // Auto-create chat threads for all pembimbings under this guru's supervision
            $pembimbings = \App\Models\PembimbingIndustri::whereIn('tempat_pkl_id', $tempatPklIds)->get();
            foreach ($pembimbings as $p) {
                $exists = PesanPembimbing::where('pembimbing_industri_id', $p->id)->exists();
                if (!$exists) {
                    PesanPembimbing::create([
                        'pembimbing_industri_id' => $p->id,
                        'subjek' => 'Chat Hubungi Sekolah',
                        'kategori' => 'lainnya',
                        'pesan' => 'Halo, ini adalah sesi chat Hubungi Sekolah Anda.',
                        'status' => 'menunggu_tanggapan',
                    ]);
                }
            }

            $latest = PesanPembimbing::whereHas('pembimbingIndustri', fn($q) => $q->whereIn('tempat_pkl_id', $tempatPklIds))
                ->latest()
                ->first();
            if ($latest) {
                return redirect()->route('guru.pesan.show', $latest->id);
            }

            $conversations = PesanPembimbing::whereHas('pembimbingIndustri', fn($q) => $q->whereIn('tempat_pkl_id', $tempatPklIds))
                ->with(['pembimbingIndustri.tempatPkl', 'pembimbingIndustri.user'])
                ->latest()
                ->get();
        } else {
            abort(403);
        }

        return view('admin.pesan.index', compact('conversations'));
    }

    public function show($id)
    {
        $pesan = PesanPembimbing::with(['pembimbingIndustri.tempatPkl', 'pembimbingIndustri.user', 'dibalasOleh'])->findOrFail($id);

        if (!$this->checkAccess($pesan)) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat pesan aduan ini.');
        }

        $user = auth()->user();
        if ($user->role === 'admin') {
            $conversations = PesanPembimbing::with(['pembimbingIndustri.tempatPkl', 'pembimbingIndustri.user'])
                ->latest()
                ->get();
        } else if ($user->role === 'guru') {
            $guru = $user->guru;
            $tempatPklIds = \App\Models\PengajuanPkl::where('guru_id', $guru->id)
                ->pluck('tempat_pkl_id')
                ->unique();

            $conversations = PesanPembimbing::whereHas('pembimbingIndustri', fn($q) => $q->whereIn('tempat_pkl_id', $tempatPklIds))
                ->with(['pembimbingIndustri.tempatPkl', 'pembimbingIndustri.user'])
                ->latest()
                ->get();
        } else {
            $conversations = collect();
        }

        return view('admin.pesan.show', compact('pesan', 'conversations'));
    }

    public function messages($id)
    {
        $pesan = PesanPembimbing::with(['replies.sender', 'pembimbingIndustri.user'])->findOrFail($id);

        if (!$this->checkAccess($pesan)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = [];
        
        // Add initial message
        $messages[] = [
            'id' => 0,
            'sender_id' => $pesan->pembimbingIndustri->user_id,
            'sender_name' => $pesan->pembimbingIndustri->user->name,
            'pesan' => $pesan->pesan,
            'lampiran' => $pesan->lampiran ? asset('storage/' . $pesan->lampiran) : null,
            'lampiran_name' => $pesan->lampiran ? basename($pesan->lampiran) : null,
            'is_image' => $pesan->lampiran && in_array(strtolower(pathinfo($pesan->lampiran, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']),
            'created_at' => $pesan->created_at->format('d M Y, H:i'),
            'time' => $pesan->created_at->format('H:i'),
            'is_me' => $pesan->pembimbingIndustri->user_id === auth()->id(),
        ];

        // Add replies
        foreach ($pesan->replies as $reply) {
            $messages[] = [
                'id' => $reply->id,
                'sender_id' => $reply->sender_id,
                'sender_name' => $reply->sender->name,
                'pesan' => $reply->pesan,
                'lampiran' => $reply->lampiran ? asset('storage/' . $reply->lampiran) : null,
                'lampiran_name' => $reply->lampiran ? basename($reply->lampiran) : null,
                'is_image' => $reply->lampiran && in_array(strtolower(pathinfo($reply->lampiran, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']),
                'created_at' => $reply->created_at->format('d M Y, H:i'),
                'time' => $reply->created_at->format('H:i'),
                'is_me' => $reply->sender_id === auth()->id(),
            ];
        }

        return response()->json([
            'status' => $pesan->status,
            'messages' => $messages
        ]);
    }

    public function reply(Request $request, $id)
    {
        $pesan = PesanPembimbing::findOrFail($id);

        if (!$this->checkAccess($pesan)) {
            abort(403, 'Anda tidak memiliki hak akses untuk membalas aduan ini.');
        }

        $request->validate([
            'tanggapan' => 'required|string',
            'status' => 'required|in:proses,selesai',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $replyData = [
            'pesan_pembimbing_id' => $pesan->id,
            'sender_id' => auth()->id(),
            'pesan' => $request->tanggapan,
        ];

        if ($request->hasFile('lampiran')) {
            $replyData['lampiran'] = $request->file('lampiran')->store('pesan_lampiran', 'public');
        }

        $reply = \App\Models\PesanPembimbingReply::create($replyData);

        $pesan->update([
            'tanggapan' => $request->tanggapan,
            'status' => $request->status,
            'dibalas_oleh_id' => auth()->id(),
            'dibalas_pada' => now(),
        ]);

        if ($pesan->pembimbingIndustri && $pesan->pembimbingIndustri->user) {
            $pesan->pembimbingIndustri->user->notify(new PesanTelahDibalasSekolah($pesan));
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $reply->id,
                    'sender_id' => $reply->sender_id,
                    'sender_name' => auth()->user()->name,
                    'pesan' => $reply->pesan,
                    'lampiran' => $reply->lampiran ? asset('storage/' . $reply->lampiran) : null,
                    'lampiran_name' => $reply->lampiran ? basename($reply->lampiran) : null,
                    'is_image' => $reply->lampiran && in_array(strtolower(pathinfo($reply->lampiran, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']),
                    'created_at' => $reply->created_at->format('d M Y, H:i'),
                    'time' => $reply->created_at->format('H:i'),
                    'is_me' => true,
                ]
            ]);
        }

        $role = auth()->user()->role;
        $route = $role === 'admin' ? 'admin.pesan.show' : 'guru.pesan.show';

        return redirect()->route($route, $pesan->id)
            ->with('success', 'Tanggapan berhasil dikirim ke pembimbing industri.');
    }
}
