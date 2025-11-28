<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'colonia' => $this->colonia,
            'referencias' => $this->referencias,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'alergias' => $this->alergias,
            'preferencias' => $this->preferencias,
            'tipo' => $this->tipo,
            'primera_visita' => $this->primera_visita,
            'ultima_visita' => $this->ultima_visita,
            'pedidos_count' => $this->pedidos_count ?? $this->pedidos()->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}