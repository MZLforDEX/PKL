<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $data = $notification->data;
        $role = auth()->user()->role;

        // Redirect based on notification payload data
        if (isset($data['pesan_id'])) {
            if ($role === 'admin') {
                if (isset($data['guru_name']) || (isset($data['title']) && str_contains($data['title'], 'Guru'))) {
                    return redirect()->route('admin.pesan-guru.show', $data['pesan_id']);
                }
                return redirect()->route('admin.pesan.show', $data['pesan_id']);
            } elseif ($role === 'guru') {
                if (isset($data['title']) && str_contains($data['title'], 'Admin')) {
                    return redirect()->route('guru.hubungi-admin.show', $data['pesan_id']);
                }
                return redirect()->route('guru.pesan.show', $data['pesan_id']);
            } elseif ($role === 'pembimbing_industri') {
                return redirect()->route('pembimbing.hubungi-sekolah.show', $data['pesan_id']);
            }
        }

        if (isset($data['pengajuan_id'])) {
            $routeName = match($role) {
                'admin' => 'admin.pengajuan.show',
                'guru' => 'guru.pengajuan.show',
                'siswa' => 'siswa.pengajuan.show',
                'pembimbing_industri' => 'pembimbing.siswa.show',
                default => null,
            };
            if ($routeName) {
                return redirect()->route($routeName, $data['pengajuan_id']);
            }
        }

        if (isset($data['jurnal_id'])) {
            $routeName = match($role) {
                'guru' => 'guru.jurnal.index',
                'pembimbing_industri' => 'pembimbing.jurnal.show',
                'siswa' => 'siswa.jurnal.index',
                default => null,
            };
            if ($routeName) {
                return $routeName === 'pembimbing.jurnal.show'
                    ? redirect()->route($routeName, $data['jurnal_id'])
                    : redirect()->route($routeName);
            }
        }

        if (isset($data['laporan_id'])) {
            $routeName = match($role) {
                'guru' => 'guru.laporan.index',
                'siswa' => 'siswa.laporan.index',
                default => null,
            };
            if ($routeName) {
                return redirect()->route($routeName);
            }
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai telah dibaca.');
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }
}
