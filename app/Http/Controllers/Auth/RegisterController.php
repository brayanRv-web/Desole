<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Contracts\ClienteServiceInterface;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    protected ClienteServiceInterface $clienteService;

    public function __construct(ClienteServiceInterface $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    public function showRegistrationForm()
    {
        return view('public.register');
    }

    public function register(RegisterRequest $request)
    {
        try {
            $cliente = $this->clienteService->register($request->validated());
            
            Auth::guard('cliente')->login($cliente);

            return redirect()->route('home')
                ->with('success', '¡Bienvenido a DÉSOLÉ! Tu cuenta ha sido creada exitosamente.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'error' => 'Error al crear la cuenta: ' . $e->getMessage()
            ]);
        }
    }
}