<?php
// app/Models/Cliente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cliente extends Authenticatable
{
    use HasFactory;

    // Agregar estos campos al $fillable para completar:
    protected $fillable = [
    'nombre', 'telefono', 'email', 'password', 'tipo',
    'direccion', 'fecha_nacimiento', 'alergias', 'preferencias',
    'puntos_fidelidad', 'nivel_fidelidad', 
    'recibir_promociones', 'recibir_cumpleanos',
    'recibir_whatsapp', 'recibir_email', 'recibir_sms', // ✅ NUEVOS
    'consentimiento_notificaciones' // ✅ NUEVO
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_nacimiento' => 'date',
        'ultima_visita' => 'date',
        'primera_visita' => 'date',
    ];

    // ✅ RELACIÓN CON PEDIDOS
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    // ✅ SCOPES ÚTILES
    public function scopeRegistrados($query)
    {
        return $query->where('tipo', 'registrado');
    }

    public function scopeFrecuentes($query)
    {
        return $query->where('total_pedidos', '>=', 5);
    }

    public function scopeInactivos($query)
    {
        return $query->where('ultima_visita', '<', now()->subMonth());
    }
}