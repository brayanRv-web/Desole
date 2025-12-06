<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;   // ← falta esto
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeClienteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
    }

   public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bienvenido a DÉSOLÉ')
            ->view('emails.bienvenida', [
                'cliente' => $notifiable
            ]);
    }

}
