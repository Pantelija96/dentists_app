<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserWaitingApproval extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New user waiting for approval')
            ->greeting('Hello Admin,')
            ->line('A new user has completed registration and is waiting for approval.')
            ->line('Email: ' . $this->user->email)
            ->action('Review user', url('/admin/dashboard/'))
            ->line('Thank you.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'email'   => $this->user->email,
            'region'  => $this->user->regions_id,
            'message' => 'New user waiting for approval',
        ];
    }
}
