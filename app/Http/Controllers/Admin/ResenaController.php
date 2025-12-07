<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resena;
use Illuminate\Http\Request;

class ResenaController extends Controller
{
    public function index()
    {
        // ✅ CARGAR LA RELACIÓN CON CLIENTE
        $reseñas = Resena::with('cliente')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.resenas.index', compact('reseñas'));
    }

    public function destroy($id)
    {
        $reseña = Resena::findOrFail($id);
        $reseña->delete();

        return redirect()->route('admin.reseñas.index')
            ->with('success', 'Reseña eliminada correctamente.');
    }
}