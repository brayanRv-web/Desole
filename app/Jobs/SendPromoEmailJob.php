<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Cliente;
use App\Notifications\PromoReminderNotification;
use Illuminate\Support\Facades\Log;

class SendPromoEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $cliente;
    public $titulo;
    public $mensaje;

    public $tries = 3;
    public $backoff = 120; // segundos

    public function __construct(Cliente $cliente, string $titulo, string $mensaje)
    {
        $this->cliente = $cliente;
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->onQueue('emails');
    }

    public function handle()
    {
        try {
            if ($this->cliente && $this->cliente->email) {
                $this->cliente->notify(new PromoReminderNotification($this->titulo, $this->mensaje));
            }
        } catch (\Exception $e) {
            Log::error('Error en SendPromoEmailJob para cliente ' . ($this->cliente->id ?? 'n/a') . ': ' . $e->getMessage());
            throw $e;
        }
    }
}
