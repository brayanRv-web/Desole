<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Promocion;

class NewPromotionNotification extends Notification
{
    public Promocion $promo;

    public function __construct(Promocion $promo)
    {
        $this->promo = $promo;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva promoción en DÉSOLÉ ☕')
            ->view('emails.newpromotion', [
                'cliente' => $notifiable,
                'promo' => $this->promo
            ]);
    }
}
