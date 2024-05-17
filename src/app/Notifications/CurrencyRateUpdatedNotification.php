<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CurrencyRateUpdatedNotification extends Notification
{
    use Queueable;

    protected $rate;

    /**
     * Create a new notification instance.
     */
    public function __construct(float $rate)
    {
        $this->rate = $rate;
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
            ->subject('USD Rate Notification')
            ->line('You are receiving this email because you have previously subscribed to daily USD rate notification.')
            ->line('Current USD rate: ' . $this->rate);
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
