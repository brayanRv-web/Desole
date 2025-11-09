<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('public.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::guard('cliente')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            Auth::guard('cliente')->user()->update(['ultima_visita' => now()]);

            return redirect()->intended(route('cliente.dashboard'))
                ->with('success', '¡Bienvenido de nuevo!');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('cliente')->logout();
        
        $clienteSessionKeys = [
            'user_id', 
            'cliente_id', 
            'cliente_nombre',
            'cliente_email',
            'cart',
            'pedido_actual'
        ];
        
        foreach ($clienteSessionKeys as $key) {
            $request->session()->forget($key);
        }
        
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Has cerrado sesión exitosamente.');
    }
}