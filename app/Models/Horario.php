<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'dia_semana',
        'apertura',
        'cierre',
        'activo',
    ];

    protected $casts = [
        'apertura' => 'string',
        'cierre'   => 'string',
        'activo'   => 'boolean',
    ];

    /**
     * ✅ Devuelve el nombre completo del día (con acentos).
     */
    public function getDiaSemanaCompletoAttribute()
    {
        $dias = [
            'lunes'     => 'Lunes',
            'martes'    => 'Martes',
            'miercoles' => 'Miércoles',
            'miércoles' => 'Miércoles',
            'jueves'    => 'Jueves',
            'viernes'   => 'Viernes',
            'sabado'    => 'Sábado',
            'sábado'    => 'Sábado',
            'domingo'   => 'Domingo',
        ];

        return $dias[strtolower($this->dia_semana)] ?? ucfirst($this->dia_semana);
    }

    /**
     * ✅ Devuelve el horario formateado en formato legible (ej: 08:00 AM - 05:00 PM).
     */
    public function getHorarioFormateadoAttribute()
    {
        $apertura = $this->apertura ? date('h:i A', strtotime($this->apertura)) : '--:--';
        $cierre   = $this->cierre ? date('h:i A', strtotime($this->cierre)) : '--:--';

        return "$apertura - $cierre";
    }

    /**
     * ✅ Indica si el negocio está actualmente abierto.
     */
    public function estaAbierto()
    {
        if (!$this->activo || !$this->apertura || !$this->cierre) {
            return false;
        }

        $ahora = Carbon::now('America/Mexico_City');
        $horaActual = $ahora->format('H:i');

        try {
            $apertura = Carbon::createFromFormat('H:i:s', $this->apertura)->format('H:i');
            $cierre   = Carbon::createFromFormat('H:i:s', $this->cierre)->format('H:i');
        } catch (\Exception $e) {
            // Si el formato no es válido
            return false;
        }

        // Caso 1: horario normal (ej. 08:00–18:00)
        if ($cierre > $apertura) {
            return $horaActual >= $apertura && $horaActual <= $cierre;
        }

        // Caso 2: horario que cruza medianoche (ej. 22:00–02:00)
        return $horaActual >= $apertura || $horaActual <= $cierre;
    }

    /**
     * ✅ Scope para obtener solo los horarios activos.
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * ✅ Scope para ordenar los días de la semana en orden lógico.
     */
    public function scopeOrdenados($query)
    {
        return $query->orderByRaw("
            FIELD(
                dia_semana, 
                'lunes', 'martes', 'miercoles', 'miércoles', 
                'jueves', 'viernes', 'sabado', 'sábado', 'domingo'
            )
        ");
    }
}
    