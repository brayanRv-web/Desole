@props(['estado'])

@php
$classes = match ($estado) {
    'pendiente' => 'bg-yellow-200 text-yellow-800',
    'en_preparacion' => 'bg-blue-200 text-blue-800',
    'listo' => 'bg-green-200 text-green-800',
    'entregado' => 'bg-gray-200 text-gray-800',
    'cancelado' => 'bg-red-200 text-red-800',
    default => 'bg-gray-200 text-gray-800'
};
@endphp

<span {{ $attributes->merge(['class' => "px-2 inline-flex text-xs leading-5 font-semibold rounded-full $classes"]) }}>
    {{ ucfirst($estado) }}
</span>