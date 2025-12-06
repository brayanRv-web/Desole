<?php
// app/Models/Cliente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cliente extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'password',
        'direccion',
        'fecha_nacimiento',
        'recibir_promociones',
        'recibir_cumpleanos',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_nacimiento' => 'date',
        'ultima_visita' => 'date',
        'primera_visita' => 'date',
        'recibir_promociones' => 'boolean',
        'recibir_cumpleanos' => 'boolean',
    ];

    /**
     * Relación con pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(\App\Models\Pedido::class);
    }

    // Scopes útiles para CRM
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
