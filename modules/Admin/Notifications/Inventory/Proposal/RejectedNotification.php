<?php

namespace Modules\Asset\Notifications\Inventory\Proposal;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Core\Models\CompanyInventoryProposal;
use Modules\Core\Models\CompanyApprovable;

class RejectedNotification extends Notification
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
        $mail = (new MailMessage);

        if ($ccs = $this->approvable->load('modelable.approvables.userable.employee.user')->modelable->approvables->filter(fn ($a) => $a->level < $this->approvable->level)) {
            $mail->bcc($ccs->pluck('userable.employee.user.email_address')->filter());
        }

        return $mail->subject('Maaf ' . $this->proposal->user->name . '! Pengajuan pembelian inventaris kamu belum disetujui')
            ->greeting('Maaf! Pengajuan pembelian inventaris kamu belum disetujui')
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
            'message' => 'Maaf! Pengajuan pembelian inventaris ' . $this->proposal->name . ' kamu belum disetujui oleh ' . $this->approvable->userable->position->level->label() . '.',
            'icon'    => 'mdi mdi-calendar-minus',
            'color'   => 'info',
            'link'    => route('asset::inventories.proposals.show', ['proposal' => $this->proposal->id])
        ];
    }
}
