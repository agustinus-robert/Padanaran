<?php

namespace Modules\Asset\Notifications\Inventory\Lease;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Core\Models\CompanyApprovable;
use Modules\Core\Models\CompanyBorrow;

class ApprovedNotification extends Notification
{
    use Queueable;

    public $manage;
    public $approvable;

    /**
     * Create a new notification instance.
     */
    public function __construct(CompanyBorrow $manage, CompanyApprovable $approvable)
    {
        $this->manage = $manage;
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
            ->subject('Selamat! Pengajuan peminjaman inventaris kamu disetujui')
            ->greeting('Selamat! Pengajuan peminjaman inventaris kamu disetujui')
            ->line('Pengajuan peminjaman inventaris kamu untuk keperluan ' . ($this->manage->meta?->for ?: '-') . ' telah disetujui oleh perusahaan, klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::asset.inventories.show', ['inventory' => $this->manage->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Selamat! Pengajuan peminjaman inventaris kamu untuk keperluam ' . ($this->manage->meta?->for ?: '-') . ' telah disetujui oleh perusahaan.',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'success',
            'link' => route('portal::asset.inventories.show', ['inventory' => $this->manage->id])
        ];
    }
}
