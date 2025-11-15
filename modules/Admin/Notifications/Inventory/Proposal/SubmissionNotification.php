<?php

namespace Modules\Asset\Notifications\Inventory\Proposal;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Core\Models\CompanyInventoryProposal;

class SubmissionNotification extends Notification
{
    use Queueable;

    public $proposal;

    /**
     * Create a new notification instance.
     */
    public function __construct(CompanyInventoryProposal $proposal)
    {
        $this->proposal = $proposal;
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
            ->subject('Seseorang mengajukan proposal pengadaan inventaris')
            ->greeting('Seseorang mengajukan proposal pengadaan inventaris')
            ->line($this->proposal->user->name . ' mengajukan pembelian ' . $this->proposal->items->count() . ' inventaris baru , klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('asset::inventories.proposals.show', ['proposal' => $this->proposal->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->proposal->user->name . ' mengajukan proposal pengadaan inventaris, cek sekarang!',
            'icon'    => 'mdi mdi-calendar-minus',
            'color'   => 'info',
            'link'    => route('asset::inventories.proposals.show', ['proposal' => $this->proposal->id])
        ];
    }
}
