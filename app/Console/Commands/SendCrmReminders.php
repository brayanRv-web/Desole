<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cliente;
use App\Jobs\SendPromoEmailJob;
use Illuminate\Support\Facades\Log;

class SendCrmReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:enviar-recordatorios {--mensaje= : Mensaje personalizado para el correo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorios/promociones por correo a clientes que aceptaron recibir promociones';

    public function handle()
    {
        $this->info('Buscando clientes para enviar recordatorios...');

        $mensaje = $this->option('mensaje') ?? 'Tenemos nuevas promociones en DÉSOLÉ, revisa nuestro menú y aprovecha descuentos especiales.';
        $titulo = 'Novedades y promociones en DÉSOLÉ';

        Cliente::where('recibir_promociones', true)
            ->whereNotNull('email')
            ->chunk(100, function($chunk) use ($titulo, $mensaje) {
                foreach ($chunk as $cliente) {
                    try {
                        SendPromoEmailJob::dispatch($cliente, $titulo, $mensaje);
                    } catch (\Exception $e) {
                        Log::error('Error despachando promo job para cliente '.$cliente->id.': '.$e->getMessage());
                    }
                }
            });

        $this->info('Envio finalizado.');
        return 0;
    }
}
