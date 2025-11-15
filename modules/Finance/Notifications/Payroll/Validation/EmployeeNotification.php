<?php

namespace Modules\Finance\Notifications\Payroll\Validation;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\HRMS\Models\EmployeeSalary;

class EmployeeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $salary;
    public $delay;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeSalary $salary, int $delay)
    {
        $this->salary = $salary;
        $this->$delay = $delay;
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
     * Determine the notification's delivery delay.
     */
    public function withDelay($notifiable)
    {
        return [
            'database' => now(),
            'mail' => now()->addSeconds($this->delay),
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Horeeee! ' . $this->salary->name . ' Kamu sudah rilis!',
            'icon' => 'mdi mdi-safe',
            'color' => 'danger',
            'link' => route('portal::salary.slips.index')
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Horeeee gajian!')
            ->greeting($this->salary->name . ' kamu sudah rilis, cek sekarang!')
            ->action('Lihat gaji', route('portal::salary.slips.index'))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.');
    }
}
