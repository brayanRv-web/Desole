<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Desole') }}</title>

    <!-- Scripts y Estilos -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-2xl font-bold text-primary">
                    {{ config('app.name', 'Desole') }}
                </a>

                <!-- Menú de navegación -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('cliente.menu') }}" class="text-gray-600 hover:text-primary">Menú</a>
                    
                    @auth('cliente')
                        <a href="{{ route('cliente.dashboard') }}" class="text-gray-600 hover:text-primary">Dashboard</a>
                        <a href="{{ route('cliente.pedidos') }}" class="text-gray-600 hover:text-primary">Mis Pedidos</a>
                        <a href="{{ route('cliente.perfil') }}" class="text-gray-600 hover:text-primary">Mi Perfil</a>
                    @else
                        <a href="{{ route('login.cliente') }}" class="text-gray-600 hover:text-primary">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="text-primary hover:text-primary-dark">Registrarse</a>
                    @endauth

                    <!-- Carrito -->
                    <a href="{{ route('cliente.carrito.ver') }}" class="relative text-gray-600 hover:text-primary">
                        <i class="fas fa-shopping-cart"></i>
                        @if(session()->has('carrito') && count(session('carrito')) > 0)
                            <span id="cart-count" 
                                  class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                {{ array_sum(array_column(session('carrito'), 'cantidad')) }}
                            </span>
                        @else
                            <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">
                                0
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Menú móvil -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-600">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Menú móvil expandible -->
            <div id="mobile-menu" class="md:hidden hidden mt-4 space-y-4">
                <a href="{{ route('cliente.menu') }}" class="block text-gray-600 hover:text-primary">Menú</a>
                
                @auth('cliente')
                    <a href="{{ route('cliente.dashboard') }}" class="block text-gray-600 hover:text-primary">Dashboard</a>
                    <a href="{{ route('cliente.pedidos') }}" class="block text-gray-600 hover:text-primary">Mis Pedidos</a>
                    <a href="{{ route('cliente.perfil') }}" class="block text-gray-600 hover:text-primary">Mi Perfil</a>
                    <form action="{{ route('logout.cliente') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-primary">Cerrar Sesión</button>
                    </form>
                @else
                    <a href="{{ route('login.cliente') }}" class="block text-gray-600 hover:text-primary">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="block text-primary hover:text-primary-dark">Registrarse</a>
                @endauth
                
                <a href="{{ route('cliente.carrito.ver') }}" class="block text-gray-600 hover:text-primary">
                    Carrito
                    @if(session()->has('carrito') && count(session('carrito')) > 0)
                        <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                            {{ array_sum(array_column(session('carrito'), 'cantidad')) }}
                        </span>
                    @endif
                </a>
            </div>
        </nav>
    </header>

    <!-- Contenido principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Información de contacto -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contacto</h3>
                    <ul class="space-y-2">
                        <li>
                            <i class="fas fa-phone mr-2"></i>
                            <a href="tel:+529614564697">961 456 4697</a>
                        </li>
                        <li>
                            <i class="fas fa-envelope mr-2"></i>
                            <a href="mailto:info@desole.com">info@desole.com</a>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Tuxtla Gutiérrez, Chiapas
                        </li>
                    </ul>
                </div>

                <!-- Enlaces rápidos -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="hover:text-gray-300">Inicio</a></li>
                        <li><a href="{{ route('cliente.menu') }}" class="hover:text-gray-300">Menú</a></li>
                        <li><a href="{{ route('contacto') }}" class="hover:text-gray-300">Contacto</a></li>
                    </ul>
                </div>

                <!-- Horarios -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Horarios</h3>
                    <ul class="space-y-2">
                        <li>Lunes a Viernes: 8:00 AM - 8:00 PM</li>
                        <li>Sábado: 9:00 AM - 6:00 PM</li>
                        <li>Domingo: Cerrado</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-700 text-center">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Desole') }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
    // Toast notification setup
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    // Flash messages
    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}'
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: '{{ session('error') }}'
        });
    @endif
    </script>

    @stack('scripts')
</body>
</html>