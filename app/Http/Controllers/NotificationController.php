<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        $data = $notification->data;
        $role = auth()->user()->role;
        if (isset($data['pesan_id'])) {
            $routes = [
                'admin' => isset($data['guru_name']) || (isset($data['title']) && str_contains($data['title'], 'Guru'))
                    ? route('admin.pesan-guru.show', $data['pesan_id'])
                    : route('admin.pesan.show', $data['pesan_id']),
                'guru' => isset($data['title']) && str_contains($data['title'], 'Admin')
                    ? route('guru.hubungi-admin.show', $data['pesan_id'])
                    : route('guru.pesan.show', $data['pesan_id']),
                'pembimbing_industri' => route('pembimbing.hubungi-sekolah.show', $data['pesan_id']),
            ];
            if (isset($routes[$role])) {
                return redirect()->to($routes[$role]);
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
                'guru' => 'guru.laporan.show',
                'siswa' => 'siswa.laporan.index',
                default => null,
            };
            if ($routeName) {
                if ($routeName === 'guru.laporan.show') {
                    return redirect()->route($routeName, $data['laporan_id']);
                }
                return redirect()->route($routeName);
            }
        }
        return redirect()->back()->with('success', 'Notifikasi ditandai telah dibaca.');
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }
}
