<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cafe': {
                            50: '#fdf8f3',
                            100: '#f7e8d9',
                            200: '#eed0b3',
                            300: '#e2af82',
                            400: '#d48952',
                            500: '#c86d36',
                            600: '#ba572c',
                            700: '#9b4426',
                            800: '#7d3825',
                            900: '#653021',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Admin button styles to ensure good contrast with the dark admin theme */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.8125rem; }

        .btn-primary { background-color: #16a34a; color: #ffffff; border-color: #16a34a; }
        .btn-primary:hover{ background-color: #138a3d; }

        .btn-success { background-color: #10b981; color: #ffffff; border-color: #10b981; }
        .btn-success:hover{ background-color: #0ea372; }

        .btn-warning { background-color: #f59e0b; color: #0f172a; border-color: #f59e0b; }
        .btn-warning:hover{ background-color: #d97706; }

        .btn-info { background-color: #3b82f6; color: #ffffff; border-color: #3b82f6; }
        .btn-info:hover{ background-color: #2563eb; }

        .btn-outline-light { background: transparent; color: #ffffff; border-color: rgba(255,255,255,0.18); }
        .btn-outline-light:hover { background: rgba(255,255,255,0.06); }

        .btn-danger { background-color: #ef4444; color: #ffffff; border-color: #ef4444; }
        .btn-danger:hover { background-color: #dc2626; }

        /* ensure links with btn classes inherit cursor and focus styles */
        a.btn { display: inline-flex; }

    </style>
</head>

<body class="bg-gray-950 text-gray-100 min-h-screen flex flex-col font-sans">

    <!-- Header Fijo -->
    <header class="bg-black text-green-400 px-6 py-4 flex justify-between items-center shadow-md border-b border-green-800 fixed top-0 left-0 right-0 z-30 h-16">
        <h1 class="text-2xl font-bold tracking-wide flex items-center gap-2">
            <i class=""></i> <span class="text-white">Désolé</span>
            <span class="text-green-500 font-semibold">Administración</span>
        </h1>

        <div class="flex items-center gap-5">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <span class="text-sm text-gray-300">Administrador</span>
            </div>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <button
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200 shadow-md hover:shadow-green-500/30 flex items-center gap-2"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-door-closed"></i> Cerrar sesión
            </button>
        </div>
    </header>

    <!-- Contenedor principal -->
    <div class="flex flex-1 pt-16"> <!-- pt-16 para compensar el header fijo -->

        <!-- Sidebar - Menú Principal Fijo -->
        <aside class="w-64 bg-gray-900 p-6 flex flex-col justify-between shadow-xl border-r border-green-800 fixed h-[calc(100vh-4rem)] top-16 z-20">
            <nav class="space-y-2">

                <h2 class="text-gray-400 uppercase text-xs font-semibold tracking-wider mb-3">Menú Principal</h2>

                <!-- Dashboard -->
                <a href="{{ url('admin/') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-lg bg-green-600/20 text-green-400 transition">
                    <i class="fas fa-tachometer-alt"></i> <span>Inicio</span>
                </a>

                <!-- Marketing y CRM -->
                <a href="{{ url('admin/crm') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-600/20 hover:text-green-400 transition">
                    <i class="fas fa-hands"></i> <span>CRM</span>
                </a>

                <!-- Productos -->
                <a href="{{ url('admin/productos') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-600/20 hover:text-green-400 transition relative">
                        <i class="fas fa-box w-5 text-center"></i> 
                        <span>Productos</span>
                        
                        <!-- Notificación de stock bajo -->
                        @php
                            // Obtener contador de stock bajo directamente
                            $stockBajoCount = \App\Models\Producto::where('stock', '<=', 5)
                                ->where('stock', '>', 0)
                                ->count();
                        @endphp
                        
                        @if($stockBajoCount > 0)
                        <div class="absolute -top-1 -right-1">
                            <div class="relative">
                                <div class="w-5 h-5 bg-red-500 rounded-full flex items-center justify-center animate-pulse">
                                    <span class="text-white text-xs font-bold">{{ $stockBajoCount }}</span>
                                </div>
                                <div class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-red-400 rounded-full animate-ping"></div>
                            </div>
                        </div>
                        @endif
                    </a>

                <!-- Pedidos -->
                <a href="{{ url('admin/pedidos') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-600/20 hover:text-green-400 transition">
                    <i class="fas fa-clipboard-list w-5 text-center"></i> <span>Pedidos</span>
                </a>

                <!-- Promociones -->
                <a href="{{ url('admin/promociones') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-600/20 hover:text-green-400 transition">
                    <i class="fas fa-tag w-5 text-center"></i> <span>Promociones</span>
                </a>

                <!-- Horarios -->
                <a href="{{ url('admin/horarios') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-600/20 hover:text-green-400 transition">
                    <i class="fas fa-clock w-5 text-center"></i> <span>Horarios</span>
                </a>

                <!-- Usuarios -->
                <a href="{{ url('admin/usuarios') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-600/20 hover:text-green-400 transition">
                    <i class="fas fa-users w-5 text-center"></i> <span>Usuarios</span>
                </a>
                <!-- Reportes -->
                <a href="{{ url('admin/reportes') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-600/20 hover:text-green-400 transition">
                    <i class="fas fa-chart-bar w-5 text-center"></i> <span>Reportes</span>
                </a>
                <!-- Config -->
                <a href="{{ url('admin/configuracion') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-600/20 hover:text-green-400 transition">
                    <i class="fas fa-gear"></i> <span>Configuración</span>
                </a>
                 <!-- Reseñas -->
                <a href="{{ url('admin/reseñas') }}"
                class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-600/20 hover:text-green-400 transition">
                    <i class="fas fa-comments"></i> <span>Reseñas</span>
                </a>
                </nav>


                

            <div class="mt-10 border-t border-green-800 pt-4 text-center text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} <span class="text-green-500">Désolé</span></p>
                <p>Panel de Administración</p>
            </div>
        </aside>

        <!-- Contenido principal - Solo este se desplaza -->
        <main class="flex-1 p-8 bg-gray-950 overflow-y-auto ml-64 mt-0">
            <div class="bg-gray-900 rounded-xl shadow-lg border border-green-700/40 p-6 min-h-[80vh]">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
    
    <!-- Global Audio Notification Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el último ID de pedido al cargar la página
            @php
                $latestOrder = \App\Models\Pedido::latest('id')->first();
                $initialLastId = $latestOrder ? $latestOrder->id : 0;
            @endphp
            let lastOrderId = {{ $initialLastId }};
            
            const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
            
            // Persistencia de Audio
            let audioEnabled = localStorage.getItem('admin_audio_enabled') === 'true';

            // Botón flotante
            const enableAudioBtn = document.createElement('button');
            enableAudioBtn.className = 'fixed bottom-4 right-4 bg-zinc-800 text-white p-3 rounded-full shadow-lg border border-zinc-700 hover:bg-zinc-700 transition-all z-50';
            enableAudioBtn.title = "Configuración de Sonido";
            
            function updateAudioButton() {
                if (audioEnabled) {
                    enableAudioBtn.innerHTML = '<i class="fas fa-volume-up text-green-400"></i>';
                    enableAudioBtn.classList.remove('animate-bounce');
                } else {
                    enableAudioBtn.innerHTML = '<i class="fas fa-volume-mute text-red-400"></i>';
                    enableAudioBtn.classList.add('animate-bounce');
                }
            }

            enableAudioBtn.onclick = () => {
                if (!audioEnabled) {
                    // Activar
                    audio.play().then(() => {
                        audio.pause();
                        audio.currentTime = 0;
                        audioEnabled = true;
                        localStorage.setItem('admin_audio_enabled', 'true');
                        updateAudioButton();
                        
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            background: '#27272a',
                            color: '#fff'
                        });
                        Toast.fire({ icon: 'success', title: 'Sonido activado' });
                    }).catch(e => console.error("No se pudo activar audio:", e));
                } else {
                    // Desactivar
                    audioEnabled = false;
                    localStorage.setItem('admin_audio_enabled', 'false');
                    updateAudioButton();
                }
            };
            document.body.appendChild(enableAudioBtn);

            // Intentar activar audio al cargar si estaba activado
            if (audioEnabled) {
                updateAudioButton();
                // Intentar reproducir silencio para "desbloquear" si el navegador lo permite
                audio.play().then(() => {
                    audio.pause();
                    audio.currentTime = 0;
                }).catch(() => {
                    console.log("Audio bloqueado por navegador, requiere interacción.");
                    // Revertir estado visual y lógico porque el navegador lo bloqueó
                    audioEnabled = false;
                    updateAudioButton();
                    
                    // Avisar al usuario
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        background: '#27272a',
                        color: '#fff'
                    });
                    Toast.fire({
                        icon: 'warning',
                        title: 'Acción requerida',
                        text: 'Haz clic en el icono de sonido para activar las alertas.'
                    });
                });
            } else {
                updateAudioButton();
            }

            // Polling Global (Cada 5 segundos)
            setInterval(() => {
                fetch("{{ route('admin.pedidos.check') }}?last_id=" + lastOrderId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.has_new) {
                            // Actualizar ID
                            lastOrderId = data.latest_id;

                            // Reproducir sonido
                            if (audioEnabled) {
                                audio.play().catch(e => {
                                    console.log('Audio bloqueado:', e);
                                    // Si falla aquí también, avisar de nuevo
                                    if(audioEnabled) {
                                        audioEnabled = false;
                                        updateAudioButton();
                                    }
                                });
                            }

                            // Mostrar Toast Global
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000,
                                background: '#27272a',
                                color: '#fff'
                            });
                            Toast.fire({
                                icon: 'info',
                                title: '¡Nuevo Pedido!',
                                text: 'Se ha recibido un nuevo pedido.'
                            });

                            // Disparar evento para que otras vistas se actualicen
                            document.dispatchEvent(new CustomEvent('new-order-received', { detail: data }));
                        }
                    })
                    .catch(err => console.error('Error polling:', err));
            }, 5000); // 5 segundos
        });
    </script>
</body>
</html>