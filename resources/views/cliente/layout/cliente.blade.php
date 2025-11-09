<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DÉSOLÉ - Cafetería Nocturna')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/desole.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
    <style>
    /* Estilos del carrito */
    .cart-btn {
        @apply relative bg-green-600 hover:bg-green-700 text-white p-3 rounded-full transition-all duration-300 ease-in-out;
        width: 48px;
        height: 48px;
    }

    .cart-count {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 10px;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    /* Estilos para el modal del carrito */
    .modal-carrito {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 50;
        display: flex;
        align-items: start;
        justify-content: center;
        padding-top: 2.5rem;
    }

    .modal-content {
        background: #27272a;
        border-radius: 0.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 32rem;
        width: 100%;
        margin: 0 auto;
        overflow: hidden;
    }

    .modal-header {
        padding: 1rem;
        border-bottom: 1px solid #3f3f46;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body {
        padding: 1rem;
        max-height: 80vh;
        overflow-y: auto;
    }

    .cart-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #3f3f46;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .quantity-btn {
        padding: 0.25rem;
        border-radius: 0.25rem;
        background: #3f3f46;
        color: white;
        transition: background-color 0.2s;
    }

    .quantity-btn:hover:not(:disabled) {
        background: #52525b;
    }

    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Notificaciones */
    #notificaciones-container {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 50;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .notification-slide {
        animation: slideInRight 0.3s ease-out forwards;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar Específico para Cliente -->
    @include('public.secciones._navbar', ['isProfilePage' => true])

    <main class="cliente-main">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/carrito.js') }}"></script>
    @stack('scripts')
</body>
</html>