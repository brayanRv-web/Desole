<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = Horario::ordenados()->get();
        
        // Si no existen horarios, crear unos por defecto
        if ($horarios->isEmpty()) {
            $horarios = $this->crearHorariosPorDefecto();
        }

        return view('admin.horarios.index', compact('horarios'));
    }

    public function edit(Horario $horario)
    {
        return view('admin.horarios.edit', compact('horario'));
    }

    public function update(Request $request, Horario $horario)
    {
        // Modificar para usar sesión en lugar de user('admin')
        if (!session('admin_role') || session('admin_role') !== 'Administrador') {
            return redirect()->route('admin.horarios.index')->with('error', 'No tienes permiso para modificar horarios.');
        }

        $request->validate([
            'apertura' => 'required|date_format:H:i',
            'cierre' => 'required|date_format:H:i|after:apertura',
            'activo' => 'sometimes|boolean'
        ]);

        try {
            DB::beginTransaction();

            $horario->update([
                'apertura' => $request->apertura,
                'cierre' => $request->cierre,
                'activo' => $request->has('activo')
            ]);

            DB::commit();

            return redirect()->route('admin.horarios.index')
                ->with('success', 'Horario actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el horario: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Horario $horario)
    {
        // Modificar para usar sesión en lugar de user('admin')
        if (!session('admin_role') || session('admin_role') !== 'Administrador') {
            return redirect()->route('admin.horarios.index')->with('error', 'No tienes permiso para cambiar el estado del horario.');
        }

        try {
            $horario->update([
                'activo' => !$horario->activo
            ]);

            $estado = $horario->activo ? 'activado' : 'desactivado';

            return redirect()->route('admin.horarios.index')
                ->with('success', "Horario {$estado} correctamente");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al cambiar el estado del horario');
        }
    }

    public function updateMultiple(Request $request)
    {
        // Modificar para usar sesión en lugar de user('admin')
        if (!session('admin_role') || session('admin_role') !== 'Administrador') {
            return redirect()->route('admin.horarios.index')->with('error', 'No tienes permiso para modificar horarios.');
        }

        $request->validate([
            'horarios' => 'required|array',
            'horarios.*.apertura' => 'required|date_format:H:i',
            'horarios.*.cierre' => 'required|date_format:H:i|after:horarios.*.apertura',
            'horarios.*.activo' => 'sometimes|boolean'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->horarios as $id => $datos) {
                $horario = Horario::findOrFail($id);
                $horario->update([
                    'apertura' => $datos['apertura'],
                    'cierre' => $datos['cierre'],
                    'activo' => isset($datos['activo'])
                ]);
            }

            DB::commit();

            return redirect()->route('admin.horarios.index')
                ->with('success', 'Horarios actualizados correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar los horarios: ' . $e->getMessage());
        }
    }

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