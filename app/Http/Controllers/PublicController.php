<?php

namespace App\Http\Controllers;

use App\Models\Promocion;
use App\Models\HeroImage;
use App\Models\Producto; // ✅ AGREGAR ESTA LÍNEA
use Illuminate\Http\Request;

class PublicController extends Controller
{
     public function welcome()
    {
        $promociones = Promocion::where('activa', 1)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        // Obtener imágenes para el carrusel del hero
        $heroImages = HeroImage::where('estado', true)
            ->where('tipo', 'hero')
            ->orderBy('orden', 'asc')
            ->get();

        // ✅ AGREGAR ESTA CONSULTA PARA LOS PRODUCTOS TEASER
        $productosTeaser = Producto::with('categoria')
            ->where('estado', 'activo')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('public.welcome', [
            'promociones' => $promociones,
            'heroImages' => $heroImages,
            'productosTeaser' => $productosTeaser, // ✅ AGREGAR ESTA LÍNEA
            'whatsapp_number' => '9614564697',
            'telefono' => '+52 (961) 456-46-97',
            'email' => 'info@desole.com',
        ]);
    }

    public function contacto()
    {
        $data = [
            'whatsapp_number' => '9614564697',
            'telefono' => '+52 (961) 456-46-97',
            'email' => 'info@desole.com',
        ];
        
        return view('public.contacto', $data);
    }
}