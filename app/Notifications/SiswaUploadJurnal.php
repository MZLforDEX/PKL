<?php

namespace App\Notifications;

use App\Models\JurnalPkl;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SiswaUploadJurnal extends Notification
{
    use Queueable;

    protected $jurnal;

    public function __construct(JurnalPkl $jurnal)
    {
        $this->jurnal = $jurnal;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $siswaName = $this->jurnal->pengajuanPkl->siswa->user->name;
        return (new MailMessage)
            ->subject('Jurnal Baru Diunggah: ' . $siswaName)
            ->greeting('Halo, Bapak/Ibu ' . $notifiable->name . '!')
            ->line('Siswa bimbingan Anda, **' . $siswaName . '**, telah mengunggah jurnal kegiatan harian baru pada tanggal ' . \Carbon\Carbon::parse($this->jurnal->tanggal)->translatedFormat('d F Y') . '.')
            ->line('Kegiatan: "' . $this->jurnal->kegiatan . '"')
            ->action('Validasi Jurnal', route('guru.jurnal.index'))
            ->line('Terima kasih telah membimbing siswa kami.');
    }

    public function toArray(object $notifiable): array
    {
        $siswaName = $this->jurnal->pengajuanPkl->siswa->user->name;
        return [
            'jurnal_id' => $this->jurnal->id,
            'siswa_name' => $siswaName,
            'tanggal' => $this->jurnal->tanggal,
            'kegiatan' => $this->jurnal->kegiatan,
            'message' => 'Siswa bimbingan Anda, ' . $siswaName . ', telah mengunggah jurnal harian baru.',
            'title' => 'Jurnal Baru Diunggah',
        ];
    }
}
