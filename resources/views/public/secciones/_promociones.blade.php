@extends('public.layout')

@section('content')
@php
    use Illuminate\Support\Str;
    use App\Models\Promocion;

    // Trae solo las promociones activas (activa = 1)
    $promociones = Promocion::where('activa', 1)
        ->where('fecha_inicio', '<=', now())
        ->where('fecha_fin', '>=', now())
        ->orderBy('fecha_inicio', 'desc')
        ->get();
@endphp

<div class="bg-gray-100 py-12 px-6 min-h-screen">
    <h2 class="text-3xl font-bold text-center mb-10 text-green-700">
        🎉 Promociones Activas
    </h2>

    @if ($promociones->isEmpty())
        <p class="text-center text-gray-600 text-lg">No hay promociones disponibles en este momento.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($promociones as $promo)
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition duration-300">
                    <h3 class="text-xl font-semibold text-green-600 mb-2">
                        {{ $promo->nombre }}
                    </h3>
                    <p class="text-gray-700 mb-3">
                        {{ Str::limit($promo->descripcion, 120) }}
                    </p>

                    <p class="mb-2">
                        <span class="font-semibold text-gray-800">Tipo de Descuento:</span>
                        <span class="text-gray-600">
                            {{ $promo->tipo_descuento === 'porcentaje' ? $promo->valor_descuento . '%' : '$' . number_format($promo->valor_descuento, 2) }}
                        </span>
                    </p>

                    <p class="text-sm text-gray-500">
                        <span class="font-semibold">Válido del:</span>
                        {{ \Carbon\Carbon::parse($promo->fecha_inicio)->format('d/m/Y') }}
                        <span class="font-semibold">al</span>
                        {{ \Carbon\Carbon::parse($promo->fecha_fin)->format('d/m/Y') }}
                    </p>

                    <div class="mt-4 flex justify-center">
                        <button class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition duration-300">
                            ¡Aprovechar!
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
