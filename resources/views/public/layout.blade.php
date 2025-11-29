<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desole - Promociones</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
</head>
<body class="bg-gray-50 text-gray-900">
    <!-- Header -->
    <header class="bg-green-700 text-white py-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-6">
            <h1 class="text-2xl font-bold">Désolé</h1>
            <nav class="space-x-4">
                <a href="{{ url('/') }}" class="hover:underline">Inicio</a>
                <a href="{{ url('/promociones') }}" class="hover:underline">Promociones</a>
                <a href="{{ url('/contacto') }}" class="hover:underline">Contacto</a>
            </nav>
        </div>
    </header>

    <!-- Contenido dinámico -->
    <main class="container mx-auto mt-10 px-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 mt-12 py-6 text-center text-sm">
        © {{ date('Y') }} Désole. Todos los derechos reservados.
    </footer>
</body>
</html>
