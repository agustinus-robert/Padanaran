<?php

namespace Modules\Portal\Notifications\Package;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Core\Models\CompanyApprovable;
use Modules\HRMS\Models\EmployeeOvertimeAddional;
use Modules\Academic\Models\StudentsPackage;

class PackageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $package;

    /**
     * Create a new notification instance.
     */
    public function __construct(StudentsPackage $package)
    {
        $this->package = $package;
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
            ->subject('Paket Datang!!')
            ->greeting('Selamat! Ada paket datang untuk kamu')
            ->line('Paket anda telah telah datang '.$this->package->name)
            // ->action('Periksa sekarang', route('portal::additional.submission.show', ['additional' => $this->additional->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Selamat! Paket Anda telah datang. Nama paket: '.$this->package->name,
            'icon' => ' bx bxs-gift',
            'color' => 'warning'
        ];
    }

    /**
     * Determine the notification's delivery delay.
     */
    public function withDelay($notifiable)
    {
        return [
            'mail' => now()->addSeconds(5),
            'database' => now(),
        ];
    }
}
