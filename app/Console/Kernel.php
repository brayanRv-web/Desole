<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Verificar stock bajo cada hora
        $schedule->command('stock:verificar')->hourly();
        // Enviar recordatorios/promociones CRM semanalmente (puedes cambiar a daily()/monthly())
        $schedule->command('crm:enviar-recordatorios')->weekly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}