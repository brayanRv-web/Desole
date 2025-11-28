<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clientes',
            'telefono' => 'required|string|max:20|unique:clientes',
            'password' => 'required|string|min:8|confirmed',
            'direccion' => 'required|string|max:500',
            'colonia' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'alergias' => 'nullable|string|max:500',
            'preferencias' => 'nullable|string|max:500',
            'referencias' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre completo es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.unique' => 'Este correo ya está registrado',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.unique' => 'Este teléfono ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'direccion.required' => 'La dirección es obligatoria',
            'colonia.required' => 'La colonia es obligatoria',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
        ];
    }
}