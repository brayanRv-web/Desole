@extends('cliente.layout.cliente')

@section('title', 'Menú Completo')

@section('content')

<section class="container py-5" style="min-height: 100vh;">

    <h2 class="text-center text-light mb-4 fw-bold">Nuestro Menú</h2>
    <p class="text-center text-secondary mb-5">Explora nuestra selección de platillos y bebidas.</p>

    @foreach($categorias as $categoria)
        @if($categoria->productos->count() > 0)

        <!-- Nombre de la categoría -->
        <h3 class="text-light fw-semibold mb-3">{{ $categoria->nombre }}</h3>

        <div class="row g-4 mb-5">

            @foreach($categoria->productos as $producto)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

                <div class="card h-100 bg-dark border-0 shadow-sm">

                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" class="card-img-top" style="height: 180px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="fa-solid fa-mug-saucer text-white-50 fs-1"></i>
                        </div>
                    @endif

                    <div class="card-body text-light d-flex flex-column">

                        <h5 class="card-title fw-bold">{{ $producto->nombre }}</h5>
                        <p class="card-text text-secondary small">{{ $producto->descripcion }}</p>

                        <div class="mt-auto">
                            <p class="mb-2 text-light fw-semibold">${{ number_format($producto->precio, 2) }}</p>

                            <button class="btn btn-success w-100 add-btn"
                                data-id="{{ $producto->id }}"
                                data-name="{{ $producto->nombre }}"
                                data-price="{{ $producto->precio }}"
                                data-stock="{{ $producto->stock }}"
                                data-imagen="{{ $producto->imagen ?? '' }}">
                                <i class="fa-solid fa-cart-plus me-2"></i>Agregar al carrito
                            </button>

                        </div>

                    </div>

                </div>

            </div>
            @endforeach

        </div>

        @endif
    @endforeach

</section>

<script>
document.addEventListener("click", function(e) {
    const btn = e.target.closest(".add-btn");
    if (!btn) return;
    const item = {
        id: Number(btn.dataset.id),
        nombre: btn.dataset.name,
        precio: Number(btn.dataset.price),
        stock: Number(btn.dataset.stock || 0),
        imagen: btn.dataset.imagen || ''
    };
    if (window.carrito && typeof window.carrito.agregar === 'function') {
        window.carrito.agregar(item);
    } else {
        console.warn('carrito no inicializado al intentar agregar:', item);
    }
});

</script>

@endsection
