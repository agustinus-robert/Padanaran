<?php

namespace Modules\Asset\Notifications\Inventory\Lease;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Core\Models\CompanyBorrow;

class SubmissionNotification extends Notification
{
    use Queueable;

    public $borrow;

    /**
     * Create a new notification instance.
     */
    public function __construct(CompanyBorrow $borrow)
    {
        $this->borrow = $borrow;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Seseorang mengajukan peminjaman inventaris')
            ->greeting('Seseorang mengajukan peminjaman inventaris')
            ->line($this->borrow->receiver->name . ' mengajukan peminjaman ' . $this->borrow->items->count() . ' inventaris , klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('asset::inventories.lease.manages.show', ['manage' => $this->borrow->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->borrow->receiver->name . ' mengajukan peminjaman inventaris, cek sekarang!',
            'icon'    => 'mdi mdi-calendar-minus',
            'color'   => 'info',
            'link'    => route('asset::inventories.lease.manages.show', ['manage' => $this->borrow->id])
        ];
    }
}
