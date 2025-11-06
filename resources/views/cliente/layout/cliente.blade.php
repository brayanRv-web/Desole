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
    @stack('styles')
</head>
<body>
    @php
        $isProfilePage = true; // Esto hará que el navbar muestre las opciones de cliente
    @endphp
    
    <!-- Navbar Específico para Cliente -->
    @include('public.secciones._navbar', ['isProfilePage' => true])

    <main class="cliente-main">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>