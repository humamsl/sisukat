<?php

namespace App\Notifications;

use App\Models\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUploadSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Upload $upload) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Dokumen Baru Masuk - SISUKAT')
            ->greeting("Halo {$notifiable->name},")
            ->line('Ada dokumen baru yang dikirim melalui halaman Upload Dokumen SISUKAT.')
            ->line("Pengirim: {$this->upload->user?->name} ({$this->upload->user?->school})")
            ->line("File: {$this->upload->original_filename}")
            ->action('Lihat Dokumen Masuk', route('admin.uploads.show', $this->upload))
            ->line('Terima kasih.');
    }
}
