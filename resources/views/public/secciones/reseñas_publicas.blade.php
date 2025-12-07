@extends('layouts.public')

@section('content')
<div class="container mx-auto py-8 px-4">

    <h2 class="text-3xl font-bold text-green-500 text-center mb-8">Todas las Reseñas</h2>

    @if($reseñas->count() > 0)

        <div class="space-y-6">
            @foreach ($reseñas as $reseña)
                <div class="bg-gray-800/50 p-6 rounded-2xl shadow-lg border border-gray-700 transition hover:bg-gray-800/70">
                    <div class="flex justify-between items-center mb-3">
                        <h5 class="text-lg font-semibold text-green-400">{{ $reseña->nombre }}</h5>
                        <span class="text-gray-400 text-sm">{{ $reseña->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div class="mb-2 text-yellow-400">
                        @for ($i = 1; $i <= $reseña->calificacion; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                    </div>

                    <p class="text-gray-200">{{ $reseña->comentario }}</p>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="flex justify-center mt-8">
            {{ $reseñas->links() }}
        </div>

    @else
        <div class="bg-blue-800/50 border border-blue-600 text-center text-blue-200 p-4 rounded-xl">
            No hay reseñas disponibles por ahora.
        </div>
    @endif

</div>
@endsection
