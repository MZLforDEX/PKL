<?php

namespace App\Notifications;

use App\Models\PesanPembimbing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PesanBaruDariIndustri extends Notification
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
        $namaPengirim = $this->pesan->pembimbingIndustri?->user?->name ?? 'Pembimbing Industri';
        $namaPerusahaan = $this->pesan->pembimbingIndustri?->tempatPkl?->nama_tempat ?? 'Mitra Industri';

        $url = $notifiable->role === 'guru'
            ? route('guru.pesan.show', $this->pesan->id)
            : route('admin.pesan.show', $this->pesan->id);

        return (new MailMessage)
            ->subject('Pesan Baru dari Mitra Industri: ' . $namaPerusahaan)
            ->greeting('Halo, Bapak/Ibu ' . $notifiable->name . '!')
            ->line('Anda menerima aduan/pesan baru dari pembimbing industri **' . $namaPengirim . '** (' . $namaPerusahaan . ').')
            ->line('Subjek: **' . $this->pesan->subjek . '**')
            ->line('Kategori: **' . ucfirst($this->pesan->kategori) . '**')
            ->line('Pesan: "' . $this->pesan->pesan . '"')
            ->action('Lihat Pesan Masuk', $url)
            ->line('Mohon segera ditindaklanjuti.');
    }

    public function toArray(object $notifiable): array
    {
        $namaPengirim = $this->pesan->pembimbingIndustri?->user?->name ?? 'Pembimbing Industri';
        $namaPerusahaan = $this->pesan->pembimbingIndustri?->tempatPkl?->nama_tempat ?? 'Mitra Industri';

        return [
            'pesan_id' => $this->pesan->id,
            'pembimbing_name' => $namaPengirim,
            'perusahaan' => $namaPerusahaan,
            'subjek' => $this->pesan->subjek,
            'kategori' => $this->pesan->kategori,
            'message' => 'Pesan baru dari ' . $namaPengirim . ' (' . $namaPerusahaan . '): ' . $this->pesan->subjek,
            'title' => 'Pesan Baru dari Industri',
        ];
    }
}
