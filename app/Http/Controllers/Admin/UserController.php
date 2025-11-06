<?php
// app/Http\Controllers\Admin\UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        // Obtener empleados de la tabla users
        $employees = User::latest()->get()->map(function ($user) {
            $user->user_type = 'system_user';
            return $user;
        });
        
        // Obtener administradores de la tabla admins
        $admins = Admin::latest()->get()->map(function ($admin) {
            $admin->user_type = 'panel_admin';
            $admin->role = 'panel_admin'; // Para consistencia en la vista
            return $admin;
        });
        
        // Combinar ambas colecciones
        $allUsers = $employees->concat($admins);
        
        return view('admin.usuarios.index', compact('allUsers'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email|unique:admins,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => 'required|in:system_user,panel_admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        if ($request->user_type === 'system_user') {
            // Crear empleado en tabla users
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'employee', // Siempre será empleado
                'is_active' => $request->boolean('is_active', true),
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        } else {
            // Crear administrador en tabla admins
            Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => $request->boolean('is_active', true),
            ]);
        }

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function edit($id)
    {
        // Buscar en ambas tablas
        $user = User::find($id);
        $userType = 'system_user';
        
        if (!$user) {
            $user = Admin::find($id);
            $userType = 'panel_admin';
        }

        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }

        return view('admin.usuarios.edit', compact('user', 'userType'));
    }

    public function update(Request $request, $id)
    {
        // Determinar en qué tabla está el usuario
        $user = User::find($id);
        $userType = 'system_user';
        
        if (!$user) {
            $user = Admin::find($id);
            $userType = 'panel_admin';
        }

        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }

        $emailRules = 'required|string|email|max:255';
        
        if ($userType === 'system_user') {
            $emailRules .= '|unique:users,email,' . $id;
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => $emailRules,
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ];
        } else {
            $emailRules .= '|unique:admins,email,' . $id;
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => $emailRules,
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            ]);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        // Buscar en ambas tablas
        $user = User::find($id);
        $userType = 'system_user';
        
        if (!$user) {
            $user = Admin::find($id);
            $userType = 'panel_admin';
        }

        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }

        // Prevenir que un admin se elimine a sí mismo
        if (auth('admin')->check() && $user->email === auth('admin')->user()->email) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    // Método para actualizar el estado de cualquier usuario
    public function updateStatus(Request $request, $id)
    {
        // Log para debug
        \Log::info('UpdateStatus called', [
            'user_id' => $id,
            'is_active' => $request->is_active,
            'all_data' => $request->all()
        ]);

        $user = User::find($id);
        $userType = 'system_user';
        
        if (!$user) {
            $user = Admin::find($id);
            $userType = 'panel_admin';
        }

        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }

        // Prevenir que un usuario modifique su propio estado
        if (auth('admin')->check() && $user->email === auth('admin')->user()->email) {
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'No puedes modificar tu propio estado.');
        }

        // Si se está desactivando a un administrador, verificar que quede al menos uno activo
        if ($userType === 'panel_admin' && !$request->boolean('is_active')) {
            $activeAdminsCount = Admin::where('is_active', true)->count();
            
            // Si este es el único administrador activo, prevenir la desactivación
            if ($activeAdminsCount <= 1 && $user->is_active) {
                return redirect()->route('admin.usuarios.index')
                    ->with('error', 'No se puede desactivar el único administrador activo del sistema.');
            }
        }

        // Forzar el valor booleano correctamente
        $isActive = $request->is_active === '1' || $request->is_active === 1 || $request->is_active === true;

        \Log::info('Updating user status', [
            'user_id' => $user->id,
            'old_status' => $user->is_active,
            'new_status' => $isActive,
            'user_type' => $userType
        ]);

        // Actualizar usando save() en lugar de update() para asegurar que se disparen los eventos
        $user->is_active = $isActive;
        $saved = $user->save();

        if ($saved) {
            $status = $isActive ? 'activado' : 'desactivado';
            \Log::info('User status updated successfully', [
                'user_id' => $user->id,
                'new_status' => $user->is_active
            ]);
            return redirect()->route('admin.usuarios.index')
                ->with('success', "Usuario {$status} exitosamente.");
        } else {
            \Log::error('Failed to update user status', [
                'user_id' => $user->id
            ]);
            return redirect()->route('admin.usuarios.index')
                ->with('error', 'Error al actualizar el estado del usuario.');
        }
    }
}