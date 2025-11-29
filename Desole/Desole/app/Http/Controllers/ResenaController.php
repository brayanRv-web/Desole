<?php

namespace App\Http\Controllers;

use App\Models\Resena;
use Illuminate\Http\Request;

class ResenaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email',
            'calificacion' => 'required|integer|between:1,5',
            'comentario' => 'required|string|min:10|max:1000'
        ]);

        Resena::create($request->all());

        return redirect()->back()->with('success', '¡Gracias por tu reseña! Ya está publicada.');
    }
}