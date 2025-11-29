@props(['categorias', 'categoriaActiva' => null])

<div class="flex flex-wrap gap-2">
    <button type="button" 
            class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200
                   {{ !$categoriaActiva ? 'bg-green-500/20 text-green-400 border-2 border-green-500/30' : 'bg-gray-800/50 text-gray-400 border border-gray-700 hover:border-green-500/30 hover:text-green-400' }}"
            onclick="window.location.href = '{{ route('public.menu') }}'">
        Todos
    </button>

    @foreach($categorias as $categoria)
        <button type="button"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200
                       {{ $categoriaActiva === $categoria->id ? 'border-2' : 'border' }}
                       {{ $categoriaActiva === $categoria->id 
                           ? "bg-{$categoria->color}/20 text-{$categoria->color} border-{$categoria->color}/30"
                           : 'bg-gray-800/50 text-gray-400 border-gray-700 hover:border-green-500/30 hover:text-green-400' }}"
                onclick="window.location.href = '{{ route('public.menu', ['categoria' => $categoria->id]) }}'">
            @if($categoria->icono)
                <i class="fas fa-{{ $categoria->icono }} mr-1"></i>
            @endif
            {{ $categoria->nombre }}
        </button>
    @endforeach
</div>