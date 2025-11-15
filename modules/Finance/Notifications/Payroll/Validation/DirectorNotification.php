<?php

namespace Modules\Finance\Notifications\Payroll\Validation;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DirectorNotification extends Notification
{
    use Queueable;

    public $start_at;
    public $end_at;

    /**
     * Create a new notification instance.
     */
    public function __construct(Carbon $start_at, Carbon $end_at)
    {
        $this->start_at = $start_at;
        $this->end_at = $end_at;
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
            ->subject('Permohonan pengajuan gaji periode ' . $this->end_at->isoFormat('DD MMMM YYYY'))
            ->greeting('Permohonan pengajuan gaji periode ' . $this->end_at->isoFormat('DD MMMM YYYY'))
            ->line('Departemen Keuangan mengajukan permohonan persetujuan gaji untuk periode ' . $this->end_at->isoFormat('DD MMMM YYYY') . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('hrms::payroll.approvals.index', ['start_at' => $this->start_at->format('Y-m-d'), 'end_at' => $this->end_at->format('Y-m-d')]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Permohonan pengajuan gaji periode ' . $this->end_at->isoFormat('DD MMMM YYYY'),
            'icon' => 'mdi mdi-safe',
            'color' => 'danger',
            'link' => route('hrms::payroll.approvals.index', ['start_at' => $this->start_at->format('Y-m-d'), 'end_at' => $this->end_at->format('Y-m-d')])
        ];
    }
}
