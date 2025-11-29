@extends('empleado.layout')

@section('content')
    <div class="max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold text-green-400 mb-4">Editar Producto (Empleado)</h2>

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('empleado.productos.update', $producto) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm text-gray-300">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" class="w-full mt-1 px-3 py-2 rounded bg-gray-800 border border-gray-700 text-white" required>
            </div>

            <div>
                <label class="block text-sm text-gray-300">Categoría</label>
                <select name="categoria_id" class="w-full mt-1 px-3 py-2 rounded bg-gray-800 border border-gray-700 text-white">
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ (old('categoria_id', $producto->categoria_id) == $cat->id) ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-300">Precio</label>
                <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" class="w-full mt-1 px-3 py-2 rounded bg-gray-800 border border-gray-700 text-white" required>
            </div>

            <div>
                <label class="block text-sm text-gray-300">Descripción</label>
                <textarea name="descripcion" class="w-full mt-1 px-3 py-2 rounded bg-gray-800 border border-gray-700 text-white" rows="4">{{ old('descripcion', $producto->descripcion) }}</textarea>
            </div>

            <div>
                <label class="block text-sm text-gray-300">Imagen (opcional)</label>
                <input type="file" name="imagen" accept="image/*" class="mt-1 text-sm text-gray-300">
                @if($producto->imagen)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-32 h-24 object-cover rounded">
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-green-600 px-4 py-2 rounded text-white">Guardar cambios</button>
                <a href="{{ route('empleado.productos.index') }}" class="text-sm text-gray-400">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
