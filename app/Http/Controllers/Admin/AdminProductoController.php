<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminProductoController extends Controller
{
    public function create()
{
    $categorias = Categoria::all(); // obtiene todas las categorías
    return view('admin.productos.create', compact('categorias'));
}
// En AdminController, ProductoController, etc.
public function __construct()
{
    $this->middleware('auth');
}

}
