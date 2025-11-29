<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeClienteNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // datos adicionales si se necesitan
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Bienvenido a DÉSOLÉ')
                    ->greeting('¡Hola ' . ($notifiable->nombre ?? '') . '!')
                    ->line('Gracias por registrarte en DÉSOLÉ. Estamos felices de tenerte con nosotros.')
                    ->line('Como nuevo cliente, empezarás a acumular puntos por tus pedidos y recibirás promociones exclusivas.')
                    ->action('Visita DÉSOLÉ', url('/'))
                    ->line('Si necesitas ayuda, contáctanos en info@desole.com');
    }
}
