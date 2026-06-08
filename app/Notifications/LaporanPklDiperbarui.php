<?php

namespace App\Notifications;

use App\Models\LaporanPkl;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LaporanPklDiperbarui extends Notification
{
    use Queueable;

    protected $laporan;
    protected $action;

    public function __construct(LaporanPkl $laporan, string $action)
    {
        $this->laporan = $laporan;
        $this->action = $action;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $actionLabel = $this->action === 'diterima' ? 'diterima' : 'diminta revisi';
        return (new MailMessage)
            ->subject('Laporan PKL ' . $actionLabel)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Laporan akhir PKL Anda telah ' . $actionLabel . '.')
            ->action('Lihat Laporan', route('siswa.laporan.index'))
            ->line('Terima kasih.');
    }

    public function toArray(object $notifiable): array
    {
        $actionLabel = $this->action === 'diterima' ? 'diterima' : 'diminta revisi';
        return [
            'laporan_id' => $this->laporan->id,
            'pengajuan_id' => $this->laporan->pengajuan_pkl_id,
            'status' => $this->laporan->status,
            'message' => 'Laporan akhir PKL Anda telah ' . $actionLabel . '.',
            'title' => 'Laporan PKL ' . $actionLabel,
        ];
    }
}
