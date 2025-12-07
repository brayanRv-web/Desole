<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\User;
use App\Models\Admin;
use App\Models\Promocion;
use App\Models\Horario;
use App\Services\CatalogService;
use Illuminate\Http\Request;
use App\Models\Pedido;

class AdminController extends Controller
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService) 
    {
        $this->catalogService = $catalogService;
    }

    /**
     * Verificar que el admin esté autenticado
     
    private function checkAdminAuth()
    {
        if (!session('admin_id')) {
            return redirect()->route('admin.login')->send();
        }
    }
    */

    public function dashboard()
    {
        // ✅ Verificar autenticación
        //$this->checkAdminAuth();

        // Estadísticas de productos
        $totalProductos = Producto::count();
        $productosActivos = Producto::where('status', 'activo')->count();
        $productosRecientes = Producto::latest()->take(5)->get();

        // Estadísticas de usuarios
        $totalAdmins = Admin::count();
        $adminsActivos = Admin::where('is_active', true)->count();
        
        $totalEmpleados = User::count();
        $empleadosActivos = User::where('is_active', true)->count();
        
        $totalUsuarios = $totalAdmins + $totalEmpleados;
        $usuariosActivos = $adminsActivos + $empleadosActivos;

        // Estadísticas de promociones
        $totalPromociones = Promocion::count();
        $promocionesActivadas = $this->catalogService->getActivePromocionesCount();

        // Calcular promociones válidas
        $totalPromocionesValidas = $this->catalogService->getValidActivePromocionesCount();

        // Calcular promociones con problemas 
        $promocionesConProblemas = $this->catalogService->getActivePromocionesCount() - $this->catalogService->getValidActivePromocionesCount();

        // Estadísticas de horarios
        $horarios = Horario::ordenados()->get();
        
        // Si no existen horarios, crear unos por defecto
        if ($horarios->isEmpty()) {
            $horarios = $this->crearHorariosPorDefecto();
        }

        return view('admin.dashboard', compact(
            'totalProductos',
            'productosActivos',
            'productosRecientes',
            'totalAdmins',
            'adminsActivos',
            'totalEmpleados',
            'empleadosActivos',
            'totalUsuarios',
            'usuariosActivos',
            'totalPromociones',
            'promocionesActivadas',
            'totalPromocionesValidas',
            'promocionesConProblemas',
            'horarios'
        ));
    }

    /**
     * Crear horarios por defecto si la tabla está vacía
     */
    private function crearHorariosPorDefecto()
    {
        $horariosPorDefecto = [
            ['dia_semana' => 'lunes', 'apertura' => '08:00', 'cierre' => '22:00', 'activo' => true],
            ['dia_semana' => 'martes', 'apertura' => '08:00', 'cierre' => '22:00', 'activo' => true],
            ['dia_semana' => 'miercoles', 'apertura' => '08:00', 'cierre' => '22:00', 'activo' => true],
            ['dia_semana' => 'jueves', 'apertura' => '08:00', 'cierre' => '22:00', 'activo' => true],
            ['dia_semana' => 'viernes', 'apertura' => '08:00', 'cierre' => '23:00', 'activo' => true],
            ['dia_semana' => 'sabado', 'apertura' => '09:00', 'cierre' => '23:00', 'activo' => true],
            ['dia_semana' => 'domingo', 'apertura' => '09:00', 'cierre' => '21:00', 'activo' => false],
        ];

        foreach ($horariosPorDefecto as $horario) {
            Horario::create($horario);
        }

        return Horario::ordenados()->get();
    }

    public function verPedido($id)
    {
        // Cargar pedido con la relación cliente
        $pedido = Pedido::with('cliente')->findOrFail($id);

        // Pasar a la vista
        return view('admin.pedidos.ver', compact('pedido'));
    }
}