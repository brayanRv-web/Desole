<?php
// app/Http/Controllers/RegisterController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Notifications\WelcomeClienteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('public.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clientes',
            'telefono' => 'required|string|max:20|unique:clientes',
            'password' => 'required|string|min:8|confirmed',
            'direccion' => 'nullable|string|max:500',
        ], [
            'nombre.required' => 'El nombre completo es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.unique' => 'Este correo ya está registrado',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.unique' => 'Este teléfono ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Crear cliente
        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'direccion' => $request->direccion,
            'tipo' => 'registrado',
            'ultima_visita' => now(),
        ]);


        // Autenticar al cliente usando el guard 'cliente'
        Auth::guard('cliente')->login($cliente);

        // Enviar correo de bienvenida mediante Job (no bloquear el request)
        try {
            if ($cliente->email) {
                \App\Jobs\SendWelcomeEmailJob::dispatch($cliente);
            }
        } catch (\Exception $e) {
            // No queremos bloquear el registro por un fallo en el despacho
            \Log::error('Error despachando job de bienvenida: ' . $e->getMessage());
        }

            return redirect()->route('home')
        ->with('success', '¡Bienvenido a DÉSOLÉ! Tu cuenta ha sido creada exitosamente.');
    }
}