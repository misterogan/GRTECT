<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Employee;

class NewEmployeeNotification extends Notification
{
    use Queueable;
    protected $employee;

    /**
     * Create a new notification instance.
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Employee Added')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new employee has been added to your company.')
            ->line('Name: ' . $this->employee->first_name . ' ' . $this->employee->last_name)
            ->line('Email: ' . ($this->employee->email ?? 'N/A'))
            ->line('Phone: ' . $this->employee->phone)
            ->action('View Employees', url('/employees'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
