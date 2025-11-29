<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Contracts\ClienteServiceInterface;
use App\Http\Requests\Cliente\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected ClienteServiceInterface $clienteService;

    public function __construct(ClienteServiceInterface $clienteService)
    {
        $this->clienteService = $clienteService;
    }

    public function show()
    {
        $cliente = Auth::guard('cliente')->user();
        $promociones = $this->clienteService->getActivePromotions();
        $pedidosRecientes = $this->clienteService->getOrderHistory($cliente);

        return view('cliente.perfil', compact('cliente', 'promociones', 'pedidosRecientes'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $cliente = Auth::guard('cliente')->user();
        
        try {
            $cliente = $this->clienteService->updateProfile($cliente, $request->validated());
            return redirect()->route('cliente.perfil')
                ->with('success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        $cliente = Auth::guard('cliente')->user();
        $pedidosRecientes = $this->clienteService->getOrderHistory($cliente);
        $promociones = $this->clienteService->getActivePromotions();

        $contactInfo = [
            'whatsapp_number' => "529614564697",
            'telefono' => "529614564697",
            'email' => "info@desole.com"
        ];

        return view('cliente.dashboard', compact(
            'cliente',
            'pedidosRecientes',
            'promociones',
            'contactInfo'
        ));
    }
}