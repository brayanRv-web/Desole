<?php

use Carbon\Carbon;
use App\Models\Horario;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$diaHoy = strtolower(now()->locale('es')->isoFormat('dddd'));
echo "Dia de hoy (Carbon): " . $diaHoy . "\n";

// Normalizar
$diaHoy = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $diaHoy);
echo "Dia normalizado: " . $diaHoy . "\n";

$horarios = Horario::all();
echo "Horarios en BD:\n";
foreach ($horarios as $h) {
    echo "ID: {$h->id}, Dia: {$h->dia_semana}, Activo: {$h->activo}\n";
}

$match = $horarios->firstWhere('dia_semana', $diaHoy);
echo "Match encontrado: " . ($match ? 'SI' : 'NO') . "\n";
