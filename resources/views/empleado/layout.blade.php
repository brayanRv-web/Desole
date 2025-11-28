<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Désolé | Panel Empleado</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col font-sans">

    <header class="bg-black text-green-400 px-6 py-4 flex justify-between items-center shadow-md border-b border-green-800 fixed top-0 left-0 right-0 z-30 h-16">
        <h1 class="text-2xl font-bold tracking-wide flex items-center gap-2">
            <i class="fas fa-coffee"></i> <span class="text-white">Désolé</span>
            <span class="text-green-500 font-semibold">Empleado</span>
        </h1>

        <div class="flex items-center gap-5">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                @php $u = auth()->user(); @endphp
                <span class="text-sm text-gray-300">{{ $u?->name ?? 'Empleado' }} </span>
            </div>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200 shadow-md hover:shadow-green-500/30 flex items-center gap-2">
                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
            </button>
        </div>
    </header>

    <div class="flex flex-1 pt-16">
        <aside class="w-64 bg-gray-900 p-6 flex flex-col justify-between shadow-xl border-r border-green-800 fixed h-[calc(100vh-4rem)] top-16 z-20">
            <nav class="space-y-2">
                <h2 class="text-gray-400 uppercase text-xs font-semibold tracking-wider mb-3">Menú Empleado</h2>

                <a href="{{ url('empleado/') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-green-600/20 text-green-400 transition">
                    <i class="fas fa-clipboard-list w-5 text-center"></i> <span>Pedidos</span>
                </a>

                <a href="{{ url('empleado/productos') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-600/20 hover:text-green-400 transition">
                    <i class="fas fa-box w-5 text-center"></i> <span>Actualizar Menú</span>
                </a>
            </nav>

            <div class="mt-10 border-t border-green-800 pt-4 text-center text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} <span class="text-green-500">Désolé Café</span></p>
                <p>Panel Empleado</p>
            </div>
        </aside>

        <main class="flex-1 p-8 bg-gray-950 overflow-y-auto ml-64 mt-0">
            <div class="bg-gray-900 rounded-xl shadow-lg border border-green-700/40 p-6 min-h-[80vh]">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
