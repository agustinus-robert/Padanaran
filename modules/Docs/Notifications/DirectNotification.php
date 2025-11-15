<?php

namespace Modules\Docs\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Docs\Models\Document;

class DirectNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $doc;
    public $delay;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $doc, int $delay)
    {
        $this->doc = $doc;
        $this->delay = $delay;
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Dokumen baru telah diunggah di sistem, cek sekarang!',
            'icon'    => 'mdi mdi-note-plus-outline',
            'color'   => 'primary',
            'link'    => route('docs::manage.documents.show', ['document' => $this->doc->id])
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Dokumen baru telah diunggah di sistem, cek sekarang!')
            ->greeting('Dokumen baru telah diunggah di sistem, dengan judul ' . $this->doc->label)
            ->action('Periksa sekarang', route('docs::manage.documents.show', ['document' => $this->doc->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.');
    }

    /**
     * Determine the notification's delivery delay.
     */
    public function withDelay($notifiable)
    {
        return [
            'mail' => now()->addSeconds($this->delay),
            'database' => now(),
        ];
    }
}
