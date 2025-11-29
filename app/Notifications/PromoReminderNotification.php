<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PromoReminderNotification extends Notification
{
    use Queueable;

    protected $titulo;
    protected $mensaje;

    public function __construct(string $titulo, string $mensaje)
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->titulo)
                    ->greeting('Hola ' . ($notifiable->nombre ?? ''))
                    ->line($this->mensaje)
                    ->action('Ver promociones', url('/'))
                    ->line('Si no deseas recibir estas notificaciones puedes actualizar tus preferencias en tu perfil.');
    }
}
