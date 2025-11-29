<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;

class VerificarStockBajo extends Command
{
    protected $signature = 'stock:verificar';
    protected $description = 'Verificar productos con stock bajo y enviar notificaciones';

    public function handle()
    {
        $this->info('Verificando stock bajo...');
        
        Producto::verificarStockBajo();
        
        $this->info('Verificación completada.');
        
        return Command::SUCCESS;
    }
}