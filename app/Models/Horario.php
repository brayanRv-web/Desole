<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'dia_semana',
        'apertura',
        'cierre',
        'activo'
    ];

    protected $casts = [
        'apertura' => 'datetime',
        'cierre' => 'datetime',
        'activo' => 'boolean'
    ];

    public function getDiaSemanaCompletoAttribute()
    {
        $dias = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miercoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sabado' => 'Sábado',
            'domingo' => 'Domingo'
        ];

        return $dias[$this->dia_semana] ?? $this->dia_semana;
    }

    public function getHorarioFormateadoAttribute()
    {
        return $this->apertura->format('h:i A') . ' - ' . $this->cierre->format('h:i A');
    }

    public function estaAbierto()
    {
        if (!$this->activo) {
            return false;
        }

        $now = now();
        $horaActual = $now->format('H:i:s');
        
        return $horaActual >= $this->apertura->format('H:i:s') && 
               $horaActual <= $this->cierre->format('H:i:s');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeOrdenados($query)
    {
        $ordenDias = [
            'lunes' => 1,
            'martes' => 2,
            'miercoles' => 3,
            'jueves' => 4,
            'viernes' => 5,
            'sabado' => 6,
            'domingo' => 7
        ];

        return $query->orderByRaw(
            "FIELD(dia_semana, 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo')"
        );
    }
}