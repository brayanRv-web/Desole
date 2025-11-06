<?php
// app/Http/Controllers/Admin/CRMController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Http\Request;

class CRMController extends Controller
{
    public function index()
    {
        // ✅ DATOS REALES
        $estadisticas = [
            'totalClientes' => Cliente::count(),
            'clientesNuevosMes' => Cliente::where('created_at', '>=', now()->subMonth())->count(),
            'pedidosRecurrentes' => Cliente::where('total_pedidos', '>=', 2)->count(),
            'clientesFrecuentes' => Cliente::where('total_pedidos', '>=', 5)->count()
        ];

        $clientesRecientes = Cliente::latest()->take(5)->get();

        return view('admin.crm.index', compact('estadisticas', 'clientesRecientes'));
    }

    public function clientes()
    {
        // LISTA REAL DE CLIENTES
        $clientes = Cliente::withCount('pedidos')
                          ->orderBy('total_pedidos', 'desc')
                          ->paginate(20);
        
        return view('admin.crm.clientes', compact('clientes'));
    }

    public function verCliente($id)
    {
        //  CLIENTE REAL CON HISTORIAL
        $cliente = Cliente::with(['pedidos' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        return view('admin.crm.ver-cliente', compact('cliente'));
    }

    public function campanas()
    {
        $estadisticas = [
            'totalClientes' => Cliente::count(),
            
            // CLIENTES INACTIVOS (no han visitado en más de 1 mes)
            'clientesInactivos' => Cliente::where('ultima_visita', '<', now()->subMonth())->count(),
            
            'inactivosConWhatsApp' => Cliente::where('ultima_visita', '<', now()->subMonth())
                                            ->where('recibir_whatsapp', true)
                                            ->where('recibir_promociones', true)
                                            ->whereNotNull('telefono')
                                            ->count(),

            // CUMPLEAÑOS (clientes que cumplen este mes)
            'cumpleanerosMes' => Cliente::whereMonth('fecha_nacimiento', now()->month)->count(),
            
            'cumpleanerosConWhatsApp' => Cliente::whereMonth('fecha_nacimiento', now()->month)
                                            ->where('recibir_whatsapp', true)
                                            ->where('recibir_cumpleanos', true)
                                            ->whereNotNull('telefono')
                                            ->count(),

            // CLIENTES FRECUENTES (5+ pedidos)
            'clientesFrecuentes' => Cliente::where('total_pedidos', '>=', 5)->count(),
            
            'frecuentesConWhatsApp' => Cliente::where('total_pedidos', '>=', 5)
                                            ->where('recibir_whatsapp', true)
                                            ->where('recibir_promociones', true)
                                            ->whereNotNull('telefono')
                                            ->count(),

            // PROMOCIONES (clientes que aceptan promociones)
            'clientesRecibirPromociones' => Cliente::where('recibir_promociones', true)
                                                ->where('recibir_whatsapp', true)
                                                ->whereNotNull('telefono')
                                                ->count(),
        ];

        return view('admin.crm.campanas', compact('estadisticas'));
    }    


    public function fidelidad()
    {
        $clientes = Cliente::orderBy('puntos_fidelidad', 'desc')->paginate(20);
        return view('admin.crm.fidelidad', compact('clientes'));
    }
}