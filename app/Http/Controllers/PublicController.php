<?php

namespace App\Http\Controllers;

use App\Models\Promocion;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function welcome()
    {
        // ✅ La lógica que tenías en la vista ahora está aquí
        $promociones = Promocion::where('activa', 1)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        // ✅ Pasamos las variables a la vista
        return view('public.welcome', [
            'promociones' => $promociones,
            'whatsapp_number' => '9614564697', // Agregar esta línea
            'telefono' => '+52 (961) 456-46-97',    // Agregar esta línea
            'email' => 'info@desole.com',        // Agregar esta línea
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