<?php

namespace App\Notifications;

use App\Models\Producto;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockBajoNotification extends Notification
{
    use Queueable;

    public $producto;
    public $tipoAlerta;

    public function __construct(Producto $producto, $tipoAlerta = 'bajo')
    {
        $this->producto = $producto;
        $this->tipoAlerta = $tipoAlerta;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->tipoAlerta == 'agotado' 
            ? '🚨 PRODUCTO AGOTADO - ' . $this->producto->nombre
            : '⚠️ STOCK BAJO - ' . $this->producto->nombre;

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hola Administrador,')
            ->line($this->tipoAlerta == 'agotado' 
                ? 'El producto **' . $this->producto->nombre . '** se ha agotado.'
                : 'El producto **' . $this->producto->nombre . '** tiene stock bajo.'
            )
            ->line('**Detalles del producto:**')
            ->line('- Producto: ' . $this->producto->nombre)
            ->line('- Stock actual: ' . $this->producto->stock . ' unidades')
            ->line('- Categoría: ' . ($this->producto->categoria->nombre ?? 'Sin categoría'))
            ->line('- Precio: $' . number_format($this->producto->precio, 2))
            ->action('Ver Producto', url('/admin/productos/' . $this->producto->id . '/edit'))
            ->line('Por favor, actualiza el stock para continuar con las ventas.')
            ->salutation('Saludos,  
            Sistema de Notificaciones Désolé');
    }
}