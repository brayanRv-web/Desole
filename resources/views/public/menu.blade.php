@extends('layouts.public')

@section('title', 'Menú')

@section('hide-footer')
@endsection

@push('styles')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/desole.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/carrito.css') }}">
    <style>
        /* Make Menu page background match Home hero background */
        body {
            background: linear-gradient(135deg, #0f0f0f, #191919) !important;
            /* keep text color consistent */
            color: var(--color-text);
        }
        /* If layout applies a min-height to main, ensure contrast similar to home */
        main { background: transparent; }
    </style>
@endpush

@section('content')

        {{-- No mostrar el hero del Home en la página de menú --}}

        @auth('cliente')
        <!-- INFORMACIÓN DEL CLIENTE EN MENÚ -->
        <section class="cliente-hero">
            <div class="container mx-auto">
                <div class="cliente-welcome" data-aos="fade-up">
                    <h2>¡Hola, {{ Auth::guard('cliente')->user()->nombre }}! 👋</h2>
                    <p>Bienvenido de nuevo, echa un vistazo a nuestro menú completo</p>
                    <div class="cliente-stats">
                        <div class="stat-card">
                            <i class="fas fa-gift"></i>
                            <span class="stat-number">{{ $promociones->count() ?? 0 }}</span>
                            <span class="stat-label">Promociones activas</span>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-star"></i>
                            <span class="stat-number">{{ Auth::guard('cliente')->user()->puntos_fidelidad ?? 0 }}</span>
                            <span class="stat-label">Puntos fidelidad</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endauth

        <main class="container mx-auto px-4 py-8">

            <!-- Productos (solo listado simple) -->
        @if($productos->isNotEmpty())
            <div class="productos-grid">
                @foreach($productos as $producto)
                    <div class="producto-card" data-producto-id="{{ $producto->id }}"
                             data-nombre="{{ htmlspecialchars($producto->nombre, ENT_QUOTES) }}"
                             data-precio="{{ number_format($producto->precio, 2, '.', '') }}"
                             data-stock="{{ $producto->stock ?? 0 }}"
                             data-imagen="{{ $producto->imagen ? asset($producto->imagen) : asset('assets/placeholder.svg') }}">
                        <div class="producto-img">
                            <img src="{{ $producto->imagen ? asset($producto->imagen) : asset('assets/placeholder.svg') }}" alt="{{ $producto->nombre }}">
                        </div>
                        <div class="producto-info">
                            <h3>{{ $producto->nombre }}</h3>
                            <p class="producto-desc">{{ Str::limit($producto->descripcion, 120) }}</p>
                            <div class="producto-precio">${{ number_format($producto->precio, 2) }}</div>
                            <div class="producto-actions">
                                <button class="btn-agregar-carrito" type="button">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800/50 mb-4">
                    <i class="fas fa-utensils text-2xl text-gray-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No hay productos disponibles</h3>
                <p class="text-gray-400">No se encontraron productos en esta categoría.</p>
            </div>
        @endif

        <!-- Paginación -->
        @if($productos->hasPages())
            <div class="mt-8">
                {{ $productos->links() }}
            </div>
        @endif

    </main>

@endsection

@push('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.AOS && typeof window.AOS.init === 'function') {
                AOS.init({ once: true, duration: 600 });
            }
        });
    </script>
    <script>
        // If the products grid contains only one product, add a class to constrain its width
        document.addEventListener('DOMContentLoaded', function () {
            try {
                var grid = document.querySelector('.productos-grid');
                if (grid) {
                    var cards = grid.querySelectorAll('.producto-card');
                    if (cards.length === 1) {
                        grid.classList.add('single-item');
                    } else {
                        grid.classList.remove('single-item');
                    }
                }
            } catch (e) {
                // fail silently
                console.error('productos-grid check error', e);
            }
        });
    </script>
    <script src="{{ asset('js/base-config.js') }}"></script>
    <script src="{{ asset('js/carrito.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/cliente-carrito.js') }}"></script>
@endpush