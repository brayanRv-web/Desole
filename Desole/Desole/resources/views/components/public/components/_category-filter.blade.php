@props(['categorias', 'categoriaActiva' => null])

<div class="flex flex-wrap justify-center gap-3">
    <a href="{{ route('menu') }}" 
       class="px-4 py-2 rounded-full {{ !$categoriaActiva ? 'bg-green-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }} transition-colors duration-200">
        <i class="fas fa-utensils mr-2"></i>
        Todos
    </a>
    
    @foreach($categorias as $categoria)
        <a href="{{ route('menu', ['categoria' => $categoria->id]) }}"
           class="px-4 py-2 rounded-full {{ $categoriaActiva == $categoria->id ? 'bg-green-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }} transition-colors duration-200">
            <i class="{{ $categoria->icono ?? 'fas fa-tag' }} mr-2"></i>
            {{ $categoria->nombre }}
        </a>
    @endforeach
</div>