<?php

namespace App\Notifications;

use App\Models\PesanGuru;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PesanBaruDariGuru extends Notification
{
    use Queueable;

    protected $pesan;

    public function __construct(PesanGuru $pesan)
    {
        $this->pesan = $pesan;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $namaGuru = $this->pesan->guru?->user?->name ?? 'Guru Pembimbing';

        return (new MailMessage)
            ->subject('Pesan Baru dari Guru Pembimbing: ' . $namaGuru)
            ->greeting('Halo, Bapak/Ibu ' . $notifiable->name . '!')
            ->line('Anda menerima aduan/pesan bantuan baru dari guru pembimbing **' . $namaGuru . '**.')
            ->line('Subjek: **' . $this->pesan->subjek . '**')
            ->line('Kategori: **' . ucfirst($this->pesan->kategori) . '**')
            ->line('Pesan: "' . $this->pesan->pesan . '"')
            ->action('Lihat Pesan Masuk', route('admin.pesan-guru.show', $this->pesan->id))
            ->line('Mohon segera memberikan tanggapan.');
    }

    public function toArray(object $notifiable): array
    {
        $namaGuru = $this->pesan->guru?->user?->name ?? 'Guru Pembimbing';

        return [
            'pesan_id' => $this->pesan->id,
            'guru_name' => $namaGuru,
            'subjek' => $this->pesan->subjek,
            'kategori' => $this->pesan->kategori,
            'message' => 'Pesan baru dari guru ' . $namaGuru . ': ' . $this->pesan->subjek,
            'title' => 'Pesan Baru dari Guru',
        ];
    }
}
