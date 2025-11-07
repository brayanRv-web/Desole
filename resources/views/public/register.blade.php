<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registro - DÉSOLÉ Cafetería nocturna</title>
  <meta name="description" content="Regístrate en DÉSOLÉ - Cafetería nocturna. Alitas, pizzadogs, frappés y promociones nocturnas." />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Estilos -->
  <link rel="stylesheet" href="{{ asset('css/desole.css') }}">
  <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
  <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
  
  <style>
    .register-section {
      padding: 80px 20px;
      min-height: 100vh;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .register-container {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(101, 207, 114, 0.15);
      overflow: hidden;
      width: 100%;
      max-width: 500px;
      margin-top: 20px;
    }
    
    .register-header {
      background: #65CF72;
      color: white;
      padding: 30px;
      text-align: center;
    }
    
    .register-header h1 {
      font-size: 28px;
      margin-bottom: 10px;
    }
    
    .register-header p {
      opacity: 0.9;
      font-size: 16px;
    }
    
    .register-form {
      padding: 30px;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #2E2E2E;
    }
    
    .form-control {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      font-size: 16px;
      transition: all 0.3s ease;
    }
    
    .form-control:focus {
      outline: none;
      border-color: #65CF72;
      box-shadow: 0 0 0 3px rgba(101, 207, 114, 0.1);
    }
    
    .btn-register {
      width: 100%;
      padding: 14px;
      background: #65CF72;
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .btn-register:hover {
      background: #55b863;
      transform: translateY(-2px);
    }
    
    .login-link {
      text-align: center;
      margin-top: 20px;
      color: #2E2E2E;
    }
    
    .login-link a {
      color: #65CF72;
      text-decoration: none;
      font-weight: 600;
    }
    
    .login-link a:hover {
      text-decoration: underline;
    }
    
    .error-message {
      color: #dc3545;
      font-size: 14px;
      margin-top: 5px;
    }
    
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }
    
    @media (max-width: 480px) {
      .form-row {
        grid-template-columns: 1fr;
      }
      
      .register-container {
        margin: 10px;
      }
    }
  </style>
</head>

<body data-theme="default">
  <!-- INCLUIR EL NAVBAR -->
  @include('public.secciones._navbar')

  <section class="register-section">
    <div class="register-container">
      <div class="register-header">
        <h1><i class="fas fa-coffee"></i> Únete a DÉSOLÉ</h1>
        <p>Comunidad cafetera con beneficios exclusivos</p>
      </div>
      
      <form class="register-form" method="POST" action="{{ route('register') }}">
        @csrf
        
        <div class="form-group">
          <label for="nombre"><i class="fas fa-user"></i> Nombre completo</label>
          <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required autofocus>
          @error('nombre')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="email"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            @error('email')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="form-group">
            <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
            <input type="tel" id="telefono" name="telefono" class="form-control" value="{{ old('telefono') }}" required>
            @error('telefono')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
        </div>
        
        <div class="form-group">
          <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección completa</label>
          <input type="text" id="direccion" name="direccion" class="form-control" value="{{ old('direccion') }}" required>
          @error('direccion')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="colonia"><i class="fas fa-location-dot"></i> Colonia</label>
            <input type="text" id="colonia" name="colonia" class="form-control" value="{{ old('colonia') }}" required>
            @error('colonia')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="form-group">
            <label for="fecha_nacimiento"><i class="fas fa-cake-candles"></i> Fecha de nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}" required>
            @error('fecha_nacimiento')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="form-group">
          <label for="referencias"><i class="fas fa-map-pin"></i> Referencias de domicilio</label>
          <input type="text" id="referencias" name="referencias" class="form-control" value="{{ old('referencias') }}" placeholder="Ej: Casa color azul, junto a...">
          @error('referencias')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="alergias"><i class="fas fa-heart"></i> Alergias alimenticias</label>
          <input type="text" id="alergias" name="alergias" class="form-control" value="{{ old('alergias') }}" placeholder="Ej: Sin gluten, alergia a nueces...">
          @error('alergias')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group">
          <label for="preferencias"><i class="fas fa-star"></i> Preferencias alimenticias</label>
          <input type="text" id="preferencias" name="preferencias" class="form-control" value="{{ old('preferencias') }}" placeholder="Ej: Vegano, sin azúcar, etc.">
          @error('preferencias')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" required>
            @error('password')
              <div class="error-message">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="form-group">
            <label for="password_confirmation"><i class="fas fa-lock"></i> Confirmar contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
          </div>
        </div>
        
        <button type="submit" class="btn-register">
          <i class="fas fa-user-plus"></i> Crear mi cuenta
        </button>
        
        <div class="login-link">
          ¿Ya tienes cuenta? <a href="{{ route('login.cliente') }}">Inicia sesión aquí</a>
        </div>
      </form>
    </div>
  </section>
</body>
</html>