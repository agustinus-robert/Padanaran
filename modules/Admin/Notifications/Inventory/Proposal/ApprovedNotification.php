<?php

namespace Modules\Asset\Notifications\Inventory\Proposal;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Core\Models\CompanyInventoryProposal;
use Modules\Core\Models\CompanyApprovable;

class ApprovedNotification extends Notification
{
    use Queueable;

    public $proposal;
    public $approvable;

    /**
     * Create a new notification instance.
     */
    public function __construct(CompanyInventoryProposal $proposal, CompanyApprovable $approvable)
    {
        $this->proposal = $proposal;
        $this->approvable = $approvable;
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
            ->subject('Selamat! Pengajuan pengadaan inventaris kamu disetujui')
            ->greeting('Selamat! Pengajuan pengadaan inventaris kamu disetujui')
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
            'message' => 'Selamat! Pengajuan pembelian inventaris ' . $this->proposal->name . ' kamu telah disetujui oleh ' . $this->approvable->userable->position->level->label() . '.',
            'icon'    => 'mdi mdi-calendar-minus',
            'color'   => 'info',
            'link'    => route('asset::inventories.proposals.show', ['proposal' => $this->proposal->id])
        ];
    }
}
