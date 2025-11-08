<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\User;
use App\Models\Admin;
use App\Models\Promocion;
use App\Models\Horario;
use Illuminate\Http\Request;

class AdminController extends Controller
{
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
        $promocionesActivadas = Promocion::where('activa', true)->count();
        
        // Calcular promociones válidas
        $totalPromocionesValidas = Promocion::where('activa', true)
            ->get()
            ->filter(function($promocion) {
                return $promocion->esValida();
            })
            ->count();
            
        // Calcular promociones con problemas
        $promocionesConProblemas = Promocion::where('activa', true)
            ->get()
            ->filter(function($promocion) {
                return !$promocion->esValida();
            })
            ->count();

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
}