<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;

class ProfileController extends Controller
{
    // Mostrar perfil
    public function show()
    {
        $cliente = Auth::guard('cliente')->user();
        return view('cliente.perfil', compact('cliente'));
    }

    // Actualizar perfil
    public function update(Request $request)
    {
        /** @var Cliente $cliente */
        $cliente = Auth::guard('cliente')->user();

        // Validación básica
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        try {
            // Actualizar datos del cliente
            $cliente->update($data);

            return redirect()->route('cliente.perfil')
                             ->with('success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    // Dashboard cliente
    public function dashboard()
    {
        $cliente = Auth::guard('cliente')->user();
        return view('cliente.dashboard', compact('cliente'));
    }
}
