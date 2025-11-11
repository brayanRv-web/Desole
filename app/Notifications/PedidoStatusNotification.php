<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PedidoStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected array $pedido,
        protected string $oldStatus,
        protected string $newStatus
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Actualización de Estado de Pedido #' . $this->pedido['id'])
            ->greeting('Hola ' . $this->pedido['cliente_nombre'])
            ->line('El estado de tu pedido ha sido actualizado.')
            ->line('Estado anterior: ' . ucfirst($this->oldStatus))
            ->line('Nuevo estado: ' . ucfirst($this->newStatus))
            ->action('Ver Pedido', route('cliente.pedidos.show', $this->pedido['id']))
            ->line('Gracias por confiar en Désolé!');
    }

    public function toArray($notifiable): array
    {
        return [
            'pedido_id' => $this->pedido['id'],
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => "El pedido #{$this->pedido['id']} cambió de estado {$this->oldStatus} a {$this->newStatus}"
        ];
    }
}