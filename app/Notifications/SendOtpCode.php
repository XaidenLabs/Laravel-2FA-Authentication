<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOtpCode extends Notification
{
    use Queueable;

    public function __construct(public string $code)
    {

    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Authentication Code')
            ->greeting('Hello!')
            ->line('Your one-time password (OTP) is: ' . $this->code)
            ->line('This code will expire in 5 minutes.')
            ->line('If you did not request this code, please ignore this email.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'code' => $this->code,
            'message' => 'Your SMS OTP is: ' . $this->code,
        ];
    }
}
