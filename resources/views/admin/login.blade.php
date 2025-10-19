<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Désolé</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-900">

    <div class="w-full max-w-md">
        <form action="{{ route('admin.authenticate') }}" method="POST" class="bg-gray-800 p-8 rounded-2xl shadow-2xl border border-green-600/30">
            @csrf
            
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-green-400 mb-2 flex items-center justify-center gap-2">
                    <i class="fas fa-shield-alt"></i>
                    Désolé Admin
                </h2>
                <p class="text-gray-400">Sistema de administración</p>
            </div>

            <!-- Mensajes de éxito -->
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Mensajes de error -->
            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg mb-4">
                    @foreach($errors->all() as $error)
                        <p class="flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            <!-- Campos del formulario -->
            <div class="mb-4">
                <label class="block text-gray-200 font-medium mb-2" for="email">
                    <i class="fas fa-envelope mr-2 text-green-400"></i>
                    Email
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" 
                       placeholder="tu@email.com" 
                       class="w-full px-4 py-3 bg-gray-700/50 text-white border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                       required autofocus>
            </div>

            <div class="mb-6">
                <label class="block text-gray-200 font-medium mb-2" for="password">
                    <i class="fas fa-lock mr-2 text-green-400"></i>
                    Contraseña
                </label>
                <input id="password" type="password" name="password" 
                       placeholder="••••••••" 
                       class="w-full px-4 py-3 bg-gray-700/50 text-white border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                       required>
            </div>

            <button type="submit" 
                    class="w-full py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 group">
                <i class="fas fa-sign-in-alt group-hover:scale-110 transition-transform"></i>
                Ingresar al Sistema
            </button>

            <!-- Footer -->
            <div class="mt-6 pt-4 border-t border-gray-700/50">
                <p class="text-sm text-center text-gray-400">
                    <i class="fas fa-info-circle mr-1"></i>
                    Acceso restringido al personal autorizado
                </p>
            </div>
        </form>
    </div>

</body>
</html>