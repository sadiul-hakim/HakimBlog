<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        // $notifiable is the model that receives the notification. In this case, it is User

        // 1.
        return (new MailMessage)
            ->subject('Your password was changed')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your password has been successfully updated.')
            ->line('If this was not you, please contact support immediately.')
            ->salutation('Regards, ' . config('app.name'));

        // 2.
        // return (new MailMessage)
        //     ->subject('Password Changed')
        //     ->view('mail.password-reset-success', [
        //         'user' => $notifiable
        //     ]);

        // 3.
        // return (new PasswordResetSuccessMail($notifiable))
        //     ->to($notifiable->email);
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
