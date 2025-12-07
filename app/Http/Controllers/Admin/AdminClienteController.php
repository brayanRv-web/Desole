<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;

class AdminClienteController extends Controller
{
    // Mostrar lista de clientes
    public function index()
    {
        $clientes = Cliente::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.clientes.index', compact('clientes'));
    }

    // Mostrar detalles de un cliente
    public function show(Cliente $cliente)
    {
        return view('admin.clientes.show', compact('cliente'));
    }

    // Eliminar cliente
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return back()->with('success', 'Cliente eliminado correctamente.');
    }
}
