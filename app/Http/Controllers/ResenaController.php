<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resena;
use App\Models\Cliente; // Asegúrate de importar el modelo Cliente

class ResenaController extends Controller
{   

     public function index()
    {
        // Obtener solo reseñas de clientes registrados
        $resenas = Resena::where('tipo_cliente', 'registrado')
                         ->orderBy('created_at', 'desc')
                         ->get();

        return view('layouts.public', compact('resenas'));
    }

    /**
     * Guardar reseña de cliente registrado
     */
    public function store(Request $request)
    {
        // Obtener cliente autenticado
        /** @var Cliente|null $cliente */
        $cliente = auth()->guard('cliente')->user();

        if (!$cliente) {
            return redirect()->route('login.cliente')
                ->with('error', 'Debes iniciar sesión para dejar una reseña.');
        }

        // Validación
        $request->validate([
            'calificacion' => 'required|integer|between:1,5',
            'comentario' => 'required|string|min:10|max:1000',
        ]);

        // Crear reseña usando la relación resenas()
        $cliente->resenas()->create([
            'nombre' => $cliente->nombre,
            'email' => $cliente->email,
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
            'tipo_cliente' => 'registrado',
        ]);

        return redirect()->back()->with('success', '¡Gracias por tu reseña! Ya está publicada.');
    }
}
