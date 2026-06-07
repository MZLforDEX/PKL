<?php

namespace App\Notifications;

use App\Models\PesanGuru;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PesanGuruDibalas extends Notification
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
        $namaPenjawab = $this->pesan->dibalasOleh?->name ?? 'Admin Sekolah';

        return (new MailMessage)
            ->subject('Tanggapan Bantuan SiPKL: ' . $this->pesan->subjek)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Admin sekolah melalui **' . $namaPenjawab . '** telah memberikan tanggapan atas pertanyaan/kendala Anda.')
            ->line('Subjek Pesan: **' . $this->pesan->subjek . '**')
            ->line('Tanggapan: "' . $this->pesan->tanggapan . '"')
            ->action('Lihat Detail Tiket', route('guru.hubungi-admin.show', $this->pesan->id))
            ->line('Semoga membantu.');
    }

    public function toArray(object $notifiable): array
    {
        $namaPenjawab = $this->pesan->dibalasOleh?->name ?? 'Admin Sekolah';

        return [
            'pesan_id' => $this->pesan->id,
            'dibalas_oleh' => $namaPenjawab,
            'subjek' => $this->pesan->subjek,
            'message' => 'Pertanyaan Anda tentang "' . $this->pesan->subjek . '" telah ditanggapi oleh admin.',
            'title' => 'Pertanyaan Ditanggapi Admin',
        ];
    }
}
