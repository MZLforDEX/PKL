<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PesanGuru;
use App\Models\User;
use App\Notifications\PesanBaruDariGuru;
use Illuminate\Http\Request;

class PesanGuruController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        if (!$guru) {
            abort(403, 'Profil guru belum diatur.');
        }

        $pesan = PesanGuru::where('guru_id', $guru->id)
            ->latest()
            ->first();

        if (!$pesan) {
            return redirect()->route('guru.hubungi-admin.create');
        }

        return redirect()->route('guru.hubungi-admin.show', $pesan->id);
    }

    public function create()
    {
        $guru = auth()->user()->guru;
        if (!$guru) {
            abort(403, 'Profil guru belum diatur.');
        }

        return view('guru.pesan.create');
    }

    public function store(Request $request)
    {
        $guru = auth()->user()->guru;
        if (!$guru) {
            abort(403, 'Profil guru belum diatur.');
        }

        $request->validate([
            'subjek' => 'required|string|max:255',
            'kategori' => 'required|string|in:penilaian,jurnal,laporan,teknis,lainnya',
            'pesan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = [
            'guru_id' => $guru->id,
            'subjek' => $request->subjek,
            'kategori' => $request->kategori,
            'pesan' => $request->pesan,
            'status' => 'menunggu_tanggapan',
        ];

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('pesan_lampiran', 'public');
        }

        $pesan = PesanGuru::create($data);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PesanBaruDariGuru($pesan));
        }

        return redirect()->route('guru.hubungi-admin.index')
            ->with('success', 'Pesan berhasil dikirim ke Admin sekolah.');
    }

    public function show(PesanGuru $hubungi_admin)
    {
        $guru = auth()->user()->guru;
        if (!$guru || $hubungi_admin->guru_id !== $guru->id) {
            abort(403);
        }

        // Get all conversations for the sidebar
        $conversations = PesanGuru::where('guru_id', $guru->id)
            ->latest()
            ->get();

        return view('guru.pesan.show', [
            'pesan' => $hubungi_admin,
            'conversations' => $conversations
        ]);
    }

    public function messages($id)
    {
        $guru = auth()->user()->guru;
        if (!$guru) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $pesan = PesanGuru::with(['replies.sender', 'guru.user'])->findOrFail($id);
        if ($pesan->guru_id !== $guru->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = [];
        
        // Add initial message
        $messages[] = [
            'id' => 0,
            'sender_id' => $pesan->guru->user_id,
            'sender_name' => $pesan->guru->user->name,
            'pesan' => $pesan->pesan,
            'lampiran' => $pesan->lampiran ? asset('storage/' . $pesan->lampiran) : null,
            'lampiran_name' => $pesan->lampiran ? basename($pesan->lampiran) : null,
            'is_image' => $pesan->lampiran && in_array(strtolower(pathinfo($pesan->lampiran, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png']),
            'created_at' => $pesan->created_at->format('d M Y, H:i'),
            'time' => $pesan->created_at->format('H:i'),
            'is_me' => $pesan->guru->user_id === auth()->id(),
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
        $guru = auth()->user()->guru;
        if (!$guru) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $pesan = PesanGuru::findOrFail($id);
        if ($pesan->guru_id !== $guru->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'pesan' => 'required|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $replyData = [
            'pesan_guru_id' => $pesan->id,
            'sender_id' => auth()->id(),
            'pesan' => $request->pesan,
        ];

        if ($request->hasFile('lampiran')) {
            $replyData['lampiran'] = $request->file('lampiran')->store('pesan_lampiran', 'public');
        }

        $reply = \App\Models\PesanGuruReply::create($replyData);

        // Update parent status to awaiting response
        $pesan->update([
            'status' => 'menunggu_tanggapan'
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new PesanBaruDariGuru($pesan));
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
