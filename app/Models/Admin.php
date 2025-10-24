<?php
// app/Models/Admin.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
    
    /**
     * Convenience helper to check role
     */
    public function hasRole(string $role): bool
    {
        return isset($this->role) && strcasecmp($this->role, $role) === 0;
    }

    // Método para determinar si es admin del panel
    public function isPanelAdmin(): bool
    {
        return true;
    }
}