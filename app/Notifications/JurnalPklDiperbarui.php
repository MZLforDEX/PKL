<?php

namespace App\Notifications;

use App\Models\JurnalPkl;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JurnalPklDiperbarui extends Notification
{
    use Queueable;

    protected $jurnal;
    protected $action;

    public function __construct(JurnalPkl $jurnal, string $action)
    {
        $this->jurnal = $jurnal;
        $this->action = $action;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $actionLabel = $this->action === 'valid' ? 'divalidasi' : 'diminta revisi';
        return (new MailMessage)
            ->subject('Jurnal PKL ' . $actionLabel)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Jurnal PKL Anda pada tanggal ' . \Carbon\Carbon::parse($this->jurnal->tanggal)->translatedFormat('d F Y') . ' telah ' . $actionLabel . '.')
            ->action('Lihat Jurnal', route('siswa.jurnal.edit', $this->jurnal->id))
            ->line('Terima kasih.');
    }

    public function toArray(object $notifiable): array
    {
        $actionLabel = $this->action === 'valid' ? 'divalidasi' : 'diminta revisi';
        return [
            'jurnal_id' => $this->jurnal->id,
            'tanggal' => $this->jurnal->tanggal,
            'status' => $this->jurnal->status,
            'message' => 'Jurnal PKL Anda pada tanggal ' . \Carbon\Carbon::parse($this->jurnal->tanggal)->translatedFormat('d F Y') . ' telah ' . $actionLabel . '.',
            'title' => 'Jurnal PKL ' . $actionLabel,
        ];
    }
}
