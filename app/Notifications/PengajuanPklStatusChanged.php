<?php

namespace App\Notifications;

use App\Models\PengajuanPkl;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanPklStatusChanged extends Notification
{
    use Queueable;

    protected $pengajuan;

    public function __construct(PengajuanPkl $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusStr = str_replace('_', ' ', ucwords($this->pengajuan->status));
        $mail = (new MailMessage)
            ->subject('Status Pengajuan PKL: ' . $statusStr)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Status pengajuan PKL Anda di ' . $this->pengajuan->tempatPkl->nama_tempat . ' telah diperbarui menjadi: **' . $statusStr . '**.');
            
        if ($this->pengajuan->catatan) {
            $mail->line('Catatan: "' . $this->pengajuan->catatan . '"');
        }

        return $mail->action('Lihat Detail Pengajuan', route('siswa.pengajuan.show', $this->pengajuan->id))
            ->line('Terima kasih telah menggunakan sistem informasi SPARTA.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'tempat_pkl_nama' => $this->pengajuan->tempatPkl->nama_tempat,
            'status' => $this->pengajuan->status,
            'catatan' => $this->pengajuan->catatan,
            'message' => 'Status pengajuan PKL Anda di ' . $this->pengajuan->tempatPkl->nama_tempat . ' telah diperbarui menjadi ' . str_replace('_', ' ', ucwords($this->pengajuan->status)) . '.',
            'title' => 'Pembaruan Pengajuan PKL',
        ];
    }
}
