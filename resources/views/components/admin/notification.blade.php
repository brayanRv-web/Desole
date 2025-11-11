@props(['type' => 'info', 'message'])

@php
$classes = match ($type) {
    'success' => 'bg-green-600/20 text-green-400 border-green-600',
    'error' => 'bg-red-600/20 text-red-400 border-red-600',
    'warning' => 'bg-yellow-600/20 text-yellow-400 border-yellow-600',
    default => 'bg-blue-600/20 text-blue-400 border-blue-600',
};

$icon = match ($type) {
    'success' => 'fa-check-circle',
    'error' => 'fa-exclamation-circle',
    'warning' => 'fa-exclamation-triangle',
    default => 'fa-info-circle',
};
@endphp

<div class="rounded-lg border {{ $classes }} p-4 mb-4" 
     x-data="{ show: true }" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform -translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform -translate-y-2">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <i class="fas {{ $icon }} mr-3"></i>
            <p>{{ $message }}</p>
        </div>
        <button @click="show = false" class="text-current hover:text-white transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>