@extends('admin.layout')

@section('content')
@php
    // Calcular estado del local
    $dias = [
        0 => 'domingo',
        1 => 'lunes',
        2 => 'martes',
        3 => 'miercoles',
        4 => 'jueves',
        5 => 'viernes',
        6 => 'sabado'
    ];
    $diaActual = $dias[now('America/Mexico_City')->dayOfWeek];
    $horarioHoy = $horarios->firstWhere('dia_semana', $diaActual);
    $estaAbierto = $horarioHoy && $horarioHoy->activo && $horarioHoy->estaAbierto();
@endphp

<!-- Header Mejorado -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-bold text-green-400">
            <i class="fas fa-tachometer-alt mr-2"></i>Panel Principal
        </h2>
        <p class="text-gray-400 mt-1">Resumen operativo de Désolé</p>
    </div>
    <div class="text-right">
        <div class="text-sm text-gray-400">
            <i class="fas fa-calendar-alt mr-1"></i> {{ now()->format('d/m/Y') }}
        </div>
        <div class="text-xs text-{{ $estaAbierto ? 'green' : 'red' }}-400 mt-1">
            <i class="fas fa-circle mr-1"></i>
            {{ $estaAbierto ? 'Abierto ahora' : 'Cerrado' }}
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

    <!-- Card -->
    <a href="{{ route('admin.productos.create') }}"
       class="bg-gray-800 hover:bg-gray-750 p-8 rounded-2xl border border-green-600/40 hover:border-green-500
              shadow-xl hover:shadow-green-500/10 transition-all duration-300 
              flex items-center gap-6 group min-h-[150px]">

        <div class="w-16 h-16 rounded-2xl bg-green-600/20 flex items-center justify-center 
                    group-hover:bg-green-600/30 transition">
            <i class="fas fa-plus text-3xl text-green-400"></i>
        </div>

        <div>
            <p class="font-semibold text-xl text-white">Nuevo Producto</p>
            <p class="text-sm text-gray-400 mt-1">Agregar al menú</p>
        </div>
    </a>

    <!-- Card -->
    <a href="{{ route('admin.usuarios.create') }}"
       class="bg-gray-800 hover:bg-gray-750 p-8 rounded-2xl border border-purple-600/40 hover:border-purple-500
              shadow-xl hover:shadow-purple-500/10 transition-all duration-300 
              flex items-center gap-6 group min-h-[150px]">

        <div class="w-16 h-16 rounded-2xl bg-purple-600/20 flex items-center justify-center
                    group-hover:bg-purple-600/30 transition">
            <i class="fas fa-user-plus text-3xl text-purple-400"></i>
        </div>

        <div>
            <p class="font-semibold text-xl text-white">Nuevo Personal</p>
            <p class="text-sm text-gray-400 mt-1">Agregar usuario</p>
        </div>
    </a>

    <!-- Card -->
    <a href="{{ route('admin.promociones.create') }}"
       class="bg-gray-800 hover:bg-gray-750 p-8 rounded-2xl border border-yellow-600/40 hover:border-yellow-500
              shadow-xl hover:shadow-yellow-500/10 transition-all duration-300 
              flex items-center gap-6 group min-h-[150px]">

        <div class="w-16 h-16 rounded-2xl bg-yellow-600/20 flex items-center justify-center
                    group-hover:bg-yellow-600/30 transition">
            <i class="fas fa-tag text-3xl text-yellow-400"></i>
        </div>

        <div>
            <p class="font-semibold text-xl text-white">Nueva Promoción</p>
            <p class="text-sm text-gray-400 mt-1">Crear oferta</p>
        </div>
    </a>

    <!-- Card -->
    <a href="{{ route('admin.horarios.index') }}"
       class="bg-gray-800 hover:bg-gray-750 p-8 rounded-2xl border border-indigo-600/40 hover:border-indigo-500
              shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 
              flex items-center gap-6 group min-h-[150px]">

        <div class="w-16 h-16 rounded-2xl bg-indigo-600/20 flex items-center justify-center
                    group-hover:bg-indigo-600/30 transition">
            <i class="fas fa-clock text-3xl text-indigo-400"></i>
        </div>

        <div>
            <p class="font-semibold text-xl text-white">Horarios</p>
            <p class="text-sm text-gray-400 mt-1">Configurar</p>
        </div>
    </a>
</div>
{{-- ========================= --}}
{{--     SELECTOR DE VISTA     --}}
{{-- ========================= --}}

<div class="mt-12 mb-6 flex items-center gap-4">
    @php
        $view = request('view', 'mes'); // Vista predeterminada: mes
    @endphp

    <a href="?view=dia"
       class="px-4 py-2 rounded-lg text-sm font-semibold
       {{ $view == 'dia' ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
        Día
    </a>

    <a href="?view=semana"
       class="px-4 py-2 rounded-lg text-sm font-semibold
       {{ $view == 'semana' ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
        Semana
    </a>

    <a href="?view=mes"
       class="px-4 py-2 rounded-lg text-sm font-semibold
       {{ $view == 'mes' ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
        Mes
    </a>

    <a href="?view=anio"
       class="px-4 py-2 rounded-lg text-sm font-semibold
       {{ $view == 'anio' ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
        Año
    </a>
</div>



{{-- ========================= --}}
{{--       VISTA: MES          --}}
{{-- ========================= --}}
@if ($view == 'mes')

@php
    $monthNames = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    $currentYear = date('Y');
    $currentMonth = date('n');
    $today = date('j');
    $firstDayOfMonth = date('w', strtotime("$currentYear-$currentMonth-01"));
    $daysInMonth = date('t');
@endphp

<div class="bg-gray-800 border border-gray-700 rounded-2xl p-8 shadow-lg">

    <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
        <i class="fas fa-calendar-alt text-indigo-400"></i>
        {{ $monthNames[$currentMonth - 1] }} {{ $currentYear }}
    </h2>

    <!-- Encabezados -->
    <div class="grid grid-cols-7 text-center text-gray-300 font-semibold mb-4">
        <div>Dom</div>
        <div>Lun</div>
        <div>Mar</div>
        <div>Mié</div>
        <div>Jue</div>
        <div>Vie</div>
        <div>Sáb</div>
    </div>

    <!-- Días -->
    <div class="grid grid-cols-7 gap-2 text-center">

        {{-- Espacios vacíos antes del día 1 --}}
        @for ($i = 0; $i < $firstDayOfMonth; $i++)
            <div></div>
        @endfor

        {{-- Días reales --}}
        @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
                $isToday = ($day == $today);
            @endphp

            <div class="p-4 rounded-xl border select-none transition-all cursor-pointer
                        {{ $isToday
                            ? 'bg-indigo-600 text-white border-indigo-400 shadow-lg'
                            : 'bg-gray-750 text-gray-200 border-gray-700 hover:bg-indigo-600 hover:border-indigo-400 hover:text-white' }}">
                {{ $day }}
            </div>
        @endfor

    </div>

</div>

@endif



{{-- ========================= --}}
{{--       VISTA: DÍA          --}}
{{-- ========================= --}}
@if ($view == 'dia')

@php
    $fecha = date('d/m/Y');
@endphp

<div class="bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-700">

    <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
        <i class="fas fa-sun text-yellow-400"></i>
        Hoy - {{ $fecha }}
    </h2>

</div>

@endif



{{-- ========================= --}}
{{--      VISTA: SEMANA        --}}
{{-- ========================= --}}
@if ($view == 'semana')

@php
    $inicioSemana = date('d/m/Y', strtotime('monday this week'));
    $finSemana = date('d/m/Y', strtotime('sunday this week'));
@endphp

<div class="bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-700">

    <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
        <i class="fas fa-calendar-week text-green-400"></i>
        Semana del {{ $inicioSemana }} al {{ $finSemana }}
    </h2>

</div>

@endif



{{-- ========================= --}}
{{--        VISTA: AÑO         --}}
{{-- ========================= --}}
@if ($view == 'anio')

@php
    $year = date('Y');
@endphp

<div class="bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-700">

    <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
        <i class="fas fa-calendar text-blue-400"></i>
        Año {{ $year }}
    </h2>

</div>

@endif

@endsection