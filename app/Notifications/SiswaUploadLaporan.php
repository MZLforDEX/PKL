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

    private function getSiswaName(): string
    {
        return $this->laporan->pengajuanPkl?->siswa?->user?->name ?? 'Siswa';
    }

    public function toMail(object $notifiable): MailMessage
    {
        $siswaName = $this->getSiswaName();
        return (new MailMessage)
            ->subject('Laporan Akhir Diunggah: ' . $siswaName)
            ->greeting('Halo, Bapak/Ibu ' . $notifiable->name . '!')
            ->line('Siswa bimbingan Anda, **' . $siswaName . '**, telah mengunggah laporan akhir PKL baru.')
            ->line('Silakan review laporan yang telah diunggah.')
            ->action('Review Laporan', route('guru.laporan.index'))
            ->line('Terima kasih telah membimbing siswa kami.');
    }

    public function toArray(object $notifiable): array
    {
        $siswaName = $this->getSiswaName();
        return [
            'laporan_id' => $this->laporan->id,
            'siswa_name' => $siswaName,
            'pengajuan_id' => $this->laporan->pengajuan_pkl_id,
            'message' => 'Siswa bimbingan Anda, ' . $siswaName . ', telah mengunggah laporan akhir PKL.',
            'title' => 'Laporan Akhir Diunggah',
        ];
    }
}
