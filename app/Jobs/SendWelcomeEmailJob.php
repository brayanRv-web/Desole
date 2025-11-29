<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Cliente;
use App\Notifications\WelcomeClienteNotification;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $cliente;

    public $tries = 3;
    public $backoff = 60; // segundos

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
        // enviar a la cola 'emails' por defecto
        $this->onQueue('emails');
    }

    public function handle()
    {
        try {
            if ($this->cliente && $this->cliente->email) {
                $this->cliente->notify(new WelcomeClienteNotification());
            }
        } catch (\Exception $e) {
            Log::error('Error en SendWelcomeEmailJob para cliente ' . ($this->cliente->id ?? 'n/a') . ': ' . $e->getMessage());
            throw $e; // reintentar según $tries
        }
    }
}
