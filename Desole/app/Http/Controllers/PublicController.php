<?php

namespace App\Http\Controllers;

use App\Models\HeroImage;
use Illuminate\Http\Request;
use App\Services\CatalogService;
use App\Models\Producto;

class PublicController extends Controller
{
    protected CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    protected function getActivePromociones()
    {
        return \App\Models\Promocion::query()
            ->activa()
            ->with('productosActivos')
            ->orderBy('fecha_inicio')
            ->get();
    }

    public function welcome()
    {
        $promociones = $this->getActivePromociones();

        // Obtener imágenes para el carrusel del hero
        // Usar columna 'activo' (boolean) en la tabla hero_images
        $heroImages = HeroImage::where('activo', 1)
            ->where('tipo', 'hero')
            ->orderBy('orden', 'asc')
            ->get();

        // Productos destacados (4 aleatorios)
        $productosDestacados = Producto::inRandomOrder()->limit(4)->get();

        return view('public.welcome', [
            'promociones' => $promociones,
            'heroImages' => $heroImages,
            'productosDestacados' => $productosDestacados,
            'whatsapp_number' => '9614564697',
            'telefono' => '+52 (961) 456-46-97',
            'email' => 'info@desole.com',
        ]);
    }

    public function contacto()
    {
        $data = [
            'whatsapp_number' => '9614564697', // Reemplaza con el número real
            'telefono' => '+52 (961) 456-46-97',
            'email' => 'info@desole.com',
            // Si tienes modelo Horario, lo puedes agregar después:
            // 'horario' => Horario::all(),
        ];
        
        return view('public.contacto', $data);
    }
    
    // Más adelante puedes agregar otros métodos aquí
    // public function menu() { ... }
    // public function contacto() { ... }
}