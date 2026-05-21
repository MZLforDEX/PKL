<?php

namespace App\Notifications;

use App\Models\LaporanPkl;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SiswaUploadLaporan extends Notification
{
    use Queueable;

    protected $laporan;

    public function __construct(LaporanPkl $laporan)
    {
        $this->laporan = $laporan;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $siswaName = $this->laporan->pengajuanPkl->siswa->user->name;
        return (new MailMessage)
            ->subject('Laporan Akhir Diunggah: ' . $siswaName)
            ->greeting('Halo, Bapak/Ibu ' . $notifiable->name . '!')
            ->line('Siswa bimbingan Anda, **' . $siswaName . '**, telah mengunggah laporan akhir PKL baru.')
            ->line('Judul Laporan: "' . $this->laporan->judul . '"')
            ->action('Review Laporan', route('guru.laporan.index'))
            ->line('Terima kasih telah membimbing siswa kami.');
    }

    public function toArray(object $notifiable): array
    {
        $siswaName = $this->laporan->pengajuanPkl->siswa->user->name;
        return [
            'laporan_id' => $this->laporan->id,
            'siswa_name' => $siswaName,
            'judul' => $this->laporan->judul,
            'message' => 'Siswa bimbingan Anda, ' . $siswaName . ', telah mengunggah laporan akhir PKL.',
            'title' => 'Laporan Akhir Diunggah',
        ];
    }
}
