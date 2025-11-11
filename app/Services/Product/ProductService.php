<?php

namespace App\Services;

use App\Models\Producto;
use App\Contracts\Services\ProductServiceInterface;
use Illuminate\Support\Facades\Storage;

class ProductService implements ProductServiceInterface
{
    public function __construct(protected Producto $producto)
    {}

    public function get(int $id): array
    {
        $producto = $this->producto->findOrFail($id);
        return $producto->toArray();
    }

    public function getAll(array $filters = []): array
    {
        $query = $this->producto->query();

        if (isset($filters['categoria_id'])) {
            $query->where('categoria_id', $filters['categoria_id']);
        }

        if (isset($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        if (isset($filters['search'])) {
            $query->where('nombre', 'like', "%{$filters['search']}%");
        }

        return $query->get()->toArray();
    }

    public function create(array $data): array
    {
        // Manejar la imagen si se proporciona
        if (isset($data['imagen']) && $data['imagen']) {
            $data['imagen'] = $this->handleImageUpload($data['imagen']);
        }

        $producto = $this->producto->create($data);
        return $producto->toArray();
    }

    public function update(int $id, array $data): array
    {
        $producto = $this->producto->findOrFail($id);

        // Manejar la imagen si se proporciona una nueva
        if (isset($data['imagen']) && $data['imagen']) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $this->handleImageUpload($data['imagen']);
        }

        $producto->update($data);
        return $producto->fresh()->toArray();
    }

    public function delete(int $id): bool
    {
        $producto = $this->producto->findOrFail($id);
        
        // Eliminar imagen si existe
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        return $producto->delete();
    }

    public function updateStatus(int $id, string $status): bool
    {
        return $this->producto->findOrFail($id)->update(['estado' => $status]);
    }

    public function updateStock(int $id, int $quantity): bool
    {
        $producto = $this->producto->findOrFail($id);
        $newStock = $producto->stock + $quantity;
        
        if ($newStock < 0) {
            return false;
        }

        return $producto->update(['stock' => $newStock]);
    }

    protected function handleImageUpload($image): string
    {
        $path = $image->store('productos', 'public');
        return $path;
    }
}