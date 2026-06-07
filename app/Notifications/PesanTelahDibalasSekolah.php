<?php

namespace App\Notifications;

use App\Models\PesanPembimbing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PesanTelahDibalasSekolah extends Notification
{
    use Queueable;

    protected $pesan;

    public function __construct(PesanPembimbing $pesan)
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
            ->subject('Tanggapan Pesan SiPKL: ' . $this->pesan->subjek)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Pihak sekolah melalui **' . $namaPenjawab . '** telah memberikan tanggapan atas laporan/aduan Anda.')
            ->line('Subjek Pesan: **' . $this->pesan->subjek . '**')
            ->line('Tanggapan: "' . $this->pesan->tanggapan . '"')
            ->action('Lihat Detail Tiket', route('pembimbing.hubungi-sekolah.show', $this->pesan->id))
            ->line('Terima kasih atas kerja samanya dalam membimbing siswa PKL.');
    }

    public function toArray(object $notifiable): array
    {
        $namaPenjawab = $this->pesan->dibalasOleh?->name ?? 'Admin Sekolah';

        return [
            'pesan_id' => $this->pesan->id,
            'dibalas_oleh' => $namaPenjawab,
            'subjek' => $this->pesan->subjek,
            'message' => 'Pesan Anda tentang "' . $this->pesan->subjek . '" telah dibalas oleh sekolah.',
            'title' => 'Pesan Ditanggapi Sekolah',
        ];
    }
}
