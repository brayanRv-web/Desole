<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Producto;

$productosDestacados = Producto::inRandomOrder()->limit(4)->get();

$html = view('public.welcome', [
    'promociones' => App\Models\Promocion::activa()->with('productosActivos')->orderBy('fecha_inicio')->get(),
    'heroImages' => App\Models\HeroImage::where('activo',1)->where('tipo','hero')->orderBy('orden','asc')->get(),
    'productosDestacados' => $productosDestacados,
    'whatsapp_number' => '9614564697',
    'telefono' => '+52 (961) 456-46-97',
    'email' => 'info@desole.com',
])->render();

file_put_contents(__DIR__.'/welcome_render.html', $html);
echo "Rendered to scripts/welcome_render.html\n";