<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Mostrar listado de admins + empleados (allUsers) y clientes paginados (clientes)
     */
    public function index()
    {
        // Empleados (tabla users)
        $employees = User::latest()->get()->map(function ($user) {
            $user->user_type = 'system_user';
            return $user;
        });

        // Administradores (tabla admins)
        $admins = Admin::latest()->get()->map(function ($admin) {
            $admin->user_type = 'panel_admin';
            $admin->role = 'panel_admin';
            return $admin;
        });

        // Unir colecciones (admins + empleados)
        $allUsers = $employees->concat($admins);

        // Clientes (tabla clientes) - paginado
        $clientes = Cliente::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.usuarios.index', compact('allUsers', 'clientes'));
    }

    /**
     * Mostrar formulario de creación (admins o empleados)
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Guardar nuevo usuario (employee en users o admin en admins)
     */
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
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'employee',
                'is_active' => $request->boolean('is_active', true),
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        } else {
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

    /**
     * Mostrar formulario de edición (busca en users y en admins)
     */
    public function edit($id)
    {
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

    /**
     * Actualizar usuario (users o admins)
     */
    public function update(Request $request, $id)
    {
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

    /**
     * Eliminar usuario (users o admins)
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $userType = 'system_user';

        if (!$user) {
            $user = Admin::find($id);
            $userType = 'panel_admin';
        }

        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }

        // Evitar que un admin se elimine a sí mismo
        if (auth('admin')->check() && $user->email === auth('admin')->user()->email) {
            return redirect()->route('admin.usuarios.index')
                             ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()->route('admin.usuarios.index')
                         ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Actualiza el estado (is_active) de usuarios (users o admins)
     */
    public function updateStatus(Request $request, $id)
    {
        $user = User::find($id);
        $userType = 'system_user';

        if (!$user) {
            $user = Admin::find($id);
            $userType = 'panel_admin';
        }

        if (!$user) {
            abort(404, 'Usuario no encontrado');
        }

        // Prevenir que un admin modifique su propio estado
        if (auth('admin')->check() && $user->email === auth('admin')->user()->email) {
            return redirect()->route('admin.usuarios.index')
                             ->with('error', 'No puedes modificar tu propio estado.');
        }

        // Si desactivan un panel_admin, asegurar que quede al menos uno activo
        if ($userType === 'panel_admin' && !$request->boolean('is_active')) {
            $activeAdminsCount = Admin::where('is_active', true)->count();
            if ($activeAdminsCount <= 1 && $user->is_active) {
                return redirect()->route('admin.usuarios.index')
                                 ->with('error', 'No se puede desactivar el único administrador activo del sistema.');
            }
        }

        $isActive = $request->is_active === '1' || $request->is_active === 1 || $request->boolean('is_active');

        $user->is_active = (bool) $isActive;
        $user->save();

        $status = $user->is_active ? 'activado' : 'desactivado';
        return redirect()->route('admin.usuarios.index')
                         ->with('success', "Usuario {$status} exitosamente.");
    }

    /**
     * Activar/Desactivar cliente (se usa en la sección Clientes de la misma vista)
     */
    public function toggleCliente(Cliente $cliente)
    {
        $cliente->is_active = !$cliente->is_active;
        $cliente->save();

        return back()->with('success', 'Estado del cliente actualizado.');
    }

    /**
     * Eliminar cliente
     */
    public function destroyCliente(Cliente $cliente)
    {
        $cliente->delete();
        return back()->with('success', 'Cliente eliminado correctamente.');
    }
}
