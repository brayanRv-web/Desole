<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Producto;

class NewProductNotification extends Notification
{
    public Producto $producto;

    public function __construct(Producto $producto)
    {
        $this->producto = $producto;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nuevo producto disponible en DÉSOLÉ')
            ->view('emails.newproduct', [
                'cliente' => $notifiable,
                'producto' => $this->producto
            ]);
    }
}

