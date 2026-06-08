<?php

namespace App\Http\Controllers\PembimbingIndustri;

use App\Http\Controllers\Controller;
use App\Models\PesanPembimbing;
use App\Models\User;
use App\Notifications\PesanBaruDariIndustri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PesanPembimbingController extends Controller
{
    public function index()
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            abort(403, 'Profil pembimbing industri belum diatur.');
        }

        $pesan = PesanPembimbing::where('pembimbing_industri_id', $pembimbing->id)
            ->latest()
            ->first();

        if (!$pesan) {
            $pesan = PesanPembimbing::create([
                'pembimbing_industri_id' => $pembimbing->id,
                'subjek' => 'Chat Hubungi Sekolah',
                'kategori' => 'lainnya',
                'pesan' => 'Halo, ini adalah sesi chat Hubungi Sekolah Anda.',
                'status' => 'menunggu_tanggapan',
            ]);
        }

        return redirect()->route('pembimbing.hubungi-sekolah.show', $pesan->id);
    }

    public function create()
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            abort(403, 'Profil pembimbing industri belum diatur.');
        }

        return view('pembimbing.pesan.create');
    }

    public function store(Request $request)
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            abort(403, 'Profil pembimbing industri belum diatur.');
        }

        $request->validate([
            'subjek' => 'required|string|max:255',
            'kategori' => 'required|string|in:administrasi,kendala_siswa,teknis,lainnya',
            'pesan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = [
            'pembimbing_industri_id' => $pembimbing->id,
            'subjek' => $request->subjek,
            'kategori' => $request->kategori,
            'pesan' => $request->pesan,
            'status' => 'menunggu_tanggapan',
        ];

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('pesan_lampiran', 'public');
        }

        $pesan = PesanPembimbing::create($data);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PesanBaruDariIndustri($pesan));
        }

        // Notify Gurus who have students at this tempat_pkl
        $tempatPklId = $pembimbing->tempat_pkl_id;
        $gurus = \App\Models\Guru::whereHas('pengajuanPkl', fn($q) => $q->where('tempat_pkl_id', $tempatPklId))->with('user')->get();
        foreach ($gurus as $guru) {
            if ($guru->user) {
                $guru->user->notify(new PesanBaruDariIndustri($pesan));
            }
        }

        return redirect()->route('pembimbing.hubungi-sekolah.index')
            ->with('success', 'Pesan berhasil dikirim ke sekolah.');
    }

    public function show(PesanPembimbing $hubungi_sekolah)
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing || $hubungi_sekolah->pembimbing_industri_id !== $pembimbing->id) {
            abort(403);
        }

        // Get all related chats/messages to this pembimbing
        $conversations = PesanPembimbing::where('pembimbing_industri_id', $pembimbing->id)
            ->latest()
            ->get();

        return view('pembimbing.pesan.show', [
            'pesan' => $hubungi_sekolah,
            'conversations' => $conversations
        ]);
    }

    public function messages($id)
    {
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $pesan = PesanPembimbing::with(['replies.sender', 'pembimbingIndustri.user'])->findOrFail($id);
        if ($pesan->pembimbing_industri_id !== $pembimbing->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = [];
        
        // Add initial message
        $messages[] = [
            'id' => 0,
            'sender_id' => optional($pesan->pembimbingIndustri)->user_id,
            'sender_name' => optional(optional($pesan->pembimbingIndustri)->user)->name,
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
                'sender_name' => optional($reply->sender)->name,
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
        $pembimbing = auth()->user()->pembimbingIndustri;
        if (!$pembimbing) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $pesan = PesanPembimbing::findOrFail($id);
        if ($pesan->pembimbing_industri_id !== $pembimbing->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'pesan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $replyData = [
            'pesan_pembimbing_id' => $pesan->id,
            'sender_id' => auth()->id(),
            'pesan' => $request->pesan,
        ];

        if ($request->hasFile('lampiran')) {
            $replyData['lampiran'] = $request->file('lampiran')->store('pesan_lampiran', 'public');
        }

        $reply = \App\Models\PesanPembimbingReply::create($replyData);

        // Update parent status to awaiting response
        $pesan->update([
            'status' => 'menunggu_tanggapan'
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PesanBaruDariIndustri($pesan));
        }

        // Notify Gurus who have students at this tempat_pkl
        $pembimbing = auth()->user()->pembimbingIndustri;
        $tempatPklId = $pembimbing->tempat_pkl_id;
        $gurus = \App\Models\Guru::whereHas('pengajuanPkl', fn($q) => $q->where('tempat_pkl_id', $tempatPklId))->with('user')->get();
        foreach ($gurus as $guru) {
            if ($guru->user) {
                $guru->user->notify(new PesanBaruDariIndustri($pesan));
            }
        }

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
}
