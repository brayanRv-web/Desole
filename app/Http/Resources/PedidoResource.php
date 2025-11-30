<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cliente' => [
                'id' => $this->cliente_id,
                'nombre' => $this->cliente_nombre,
                'telefono' => $this->cliente_telefono,
            ],
            'estado' => $this->estado,
            'total' => $this->total,
            'items' => $this->detalles->map(function ($detalle) {
                return [
                    'producto_id' => $detalle->producto_id,
                    'nombre' => $detalle->producto->nombre ?? 'Producto eliminado',
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio,
                    'subtotal' => $detalle->cantidad * $detalle->precio,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}