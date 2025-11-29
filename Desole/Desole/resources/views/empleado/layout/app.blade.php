<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Panel de Empleado</title>

    <!-- Estilos y Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>
<body class="bg-gray-100">
    <div x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 bg-primary text-white w-64 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out z-10">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-primary-dark">
                <span class="text-xl font-bold">{{ config('app.name') }}</span>
            </div>

            <!-- Navigation -->
            <nav class="mt-5">
                <a href="{{ route('empleado.dashboard') }}" class="flex items-center px-6 py-3 text-white hover:bg-primary-dark {{ request()->routeIs('empleado.dashboard') ? 'bg-primary-dark' : '' }}">
                    <i class="fas fa-home w-6"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('empleado.pedidos.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-primary-dark {{ request()->routeIs('empleado.pedidos.*') ? 'bg-primary-dark' : '' }}">
                    <i class="fas fa-list w-6"></i>
                    <span>Pedidos</span>
                </a>

                <a href="{{ route('empleado.productos.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-primary-dark {{ request()->routeIs('empleado.productos.*') ? 'bg-primary-dark' : '' }}">
                    <i class="fas fa-box w-6"></i>
                    <span>Productos</span>
                </a>
            </nav>
        </aside>

        <!-- Content area -->
        <div class="md:ml-64 min-h-screen">
            <!-- Top bar -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between h-16 px-4">
                    <!-- Mobile menu button -->
                    <button 
                        @click="sidebarOpen = !sidebarOpen" 
                        class="md:hidden text-gray-500 hover:text-gray-600"
                    >
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- User menu -->
                    <div class="flex items-center">
                        <span class="mr-4 text-gray-700">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-600">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Mobile sidebar -->
            <div 
                x-show="sidebarOpen" 
                @click="sidebarOpen = false" 
                class="fixed inset-0 bg-black bg-opacity-50 md:hidden z-20"
            ></div>

            <aside 
                x-show="sidebarOpen" 
                class="fixed inset-y-0 left-0 bg-primary text-white w-64 transform transition-transform duration-200 ease-in-out z-30"
            >
                <!-- Mobile sidebar content (same as desktop sidebar) -->
                <div class="flex items-center justify-between h-16 bg-primary-dark px-6">
                    <span class="text-xl font-bold">{{ config('app.name') }}</span>
                    <button @click="sidebarOpen = false" class="text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <nav class="mt-5">
                    <a href="{{ route('empleado.dashboard') }}" class="flex items-center px-6 py-3 text-white hover:bg-primary-dark">
                        <i class="fas fa-home w-6"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('empleado.pedidos.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-primary-dark">
                        <i class="fas fa-list w-6"></i>
                        <span>Pedidos</span>
                    </a>

                    <a href="{{ route('empleado.productos.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-primary-dark">
                        <i class="fas fa-box w-6"></i>
                        <span>Productos</span>
                    </a>
                </nav>
            </aside>

            <!-- Main content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

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