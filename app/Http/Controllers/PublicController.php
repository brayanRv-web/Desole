<?php

namespace App\Http\Controllers;

use App\Models\Promocion;
use App\Models\HeroImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PublicController extends Controller
{
     public function welcome()
    {
        $promociones = Promocion::where('activa', 1)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        // Obtener imágenes para el carrusel del hero (verificar existencia de la tabla)
        if (Schema::hasTable('hero_images')) {
            $heroImages = HeroImage::where('activo', true)
                ->where('tipo', 'hero')
                ->orderBy('orden', 'asc')
                ->get();
        } else {
            // Evitar excepción en entornos donde la migración no se ha ejecutado
            $heroImages = collect();
        }

        return view('public.welcome', [
            'promociones' => $promociones,
            'heroImages' => $heroImages,
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