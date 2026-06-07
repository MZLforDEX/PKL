<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesanGuru;
use App\Notifications\PesanGuruDibalas;
use Illuminate\Http\Request;

class AduanGuruController extends Controller
{
    public function index()
    {
        // Auto-create chat threads for all gurus if not exist
        $gurus = \App\Models\Guru::all();
        foreach ($gurus as $g) {
            $exists = PesanGuru::where('guru_id', $g->id)->exists();
            if (!$exists) {
                PesanGuru::create([
                    'guru_id' => $g->id,
                    'subjek' => 'Chat Hubungi Admin',
                    'kategori' => 'lainnya',
                    'pesan' => 'Halo, ini adalah sesi chat Hubungi Admin Anda.',
                    'status' => 'menunggu_tanggapan',
                ]);
            }
        }

        $latest = PesanGuru::latest()->first();
        if ($latest) {
            return redirect()->route('admin.pesan-guru.show', $latest->id);
        }

        $conversations = PesanGuru::with(['guru.user'])
            ->latest()
            ->get();

        return view('admin.pesan-guru.index', compact('conversations'));
    }

    public function show($id)
    {
        $pesan = PesanGuru::with(['guru.user', 'dibalasOleh'])->findOrFail($id);
        $conversations = PesanGuru::with(['guru.user'])->latest()->get();

        return view('admin.pesan-guru.show', compact('pesan', 'conversations'));
    }

    public function messages($id)
    {
        $pesan = PesanGuru::with(['replies.sender', 'guru.user'])->findOrFail($id);

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
        $pesan = PesanGuru::findOrFail($id);

        $request->validate([
            'tanggapan' => 'required|string',
            'status' => 'required|in:proses,selesai',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $replyData = [
            'pesan_guru_id' => $pesan->id,
            'sender_id' => auth()->id(),
            'pesan' => $request->tanggapan,
        ];

        if ($request->hasFile('lampiran')) {
            $replyData['lampiran'] = $request->file('lampiran')->store('pesan_lampiran', 'public');
        }

        $reply = \App\Models\PesanGuruReply::create($replyData);

        $pesan->update([
            'tanggapan' => $request->tanggapan,
            'status' => $request->status,
            'dibalas_oleh_id' => auth()->id(),
            'dibalas_pada' => now(),
        ]);

        if ($pesan->guru && $pesan->guru->user) {
            $pesan->guru->user->notify(new PesanGuruDibalas($pesan));
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

        return redirect()->route('admin.pesan-guru.show', $pesan->id)
            ->with('success', 'Tanggapan aduan guru berhasil disimpan dan dikirim.');
    }
}
