<?php

namespace App\Notifications\Auth_notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;


class VerifyEmailApi extends Notification implements ShouldQueue
{

    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
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



        return (new MailMessage)
            ->subject('Please Verify Your Email Address') // عنوان الرسالة
            ->greeting('Hello ' . $notifiable->name . ',') // تحية مخصصة بالاسم
            ->line('Thank you for registering with us! To complete your registration, please verify your email address by clicking the button below.')
            ->action('Verify Email Now', $this->verificationUrl)
            ->line('This verification link will expire in 60 minutes.')
            ->line('If you did not create an account, no further action is required.')
            ->salutation('Regards, ' . config('app.name')); // توقيع البريد
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
