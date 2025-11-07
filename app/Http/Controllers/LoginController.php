<?php
// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('public.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El formato del correo no es válido',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        // Intentar autenticar como cliente
        if (Auth::guard('cliente')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Actualizar última visita
            Auth::guard('cliente')->user()->update(['ultima_visita' => now()]);

            return redirect()->route('home') // redirige a la página principal
            ->with('success', '¡Bienvenido de nuevo!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('cliente')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Has cerrado sesión exitosamente.');
    }
}