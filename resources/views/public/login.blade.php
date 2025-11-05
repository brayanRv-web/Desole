<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Iniciar Sesión - DÉSOLÉ Cafetería nocturna</title>
  <meta name="description" content="Inicia sesión en DÉSOLÉ - Cafetería nocturna. Alitas, pizzadogs, frappés y promociones nocturnas." />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Estilos -->
  <link rel="stylesheet" href="{{ asset('css/desole.css') }}">
  <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
  <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
  
  <style>
    .login-section {
      padding: 80px 20px;
      min-height: 100vh;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .login-container {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(101, 207, 114, 0.15);
      overflow: hidden;
      width: 100%;
      max-width: 400px;
      margin-top: 20px;
    }
    
    .login-header {
      background: #65CF72;
      color: white;
      padding: 30px;
      text-align: center;
    }
    
    .login-header h1 {
      font-size: 28px;
      margin-bottom: 10px;
    }
    
    .login-header p {
      opacity: 0.9;
      font-size: 16px;
    }
    
    .login-form {
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
    
    .btn-login {
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
    
    .btn-login:hover {
      background: #55b863;
      transform: translateY(-2px);
    }
    
    .register-link {
      text-align: center;
      margin-top: 20px;
      color: #2E2E2E;
    }
    
    .register-link a {
      color: #65CF72;
      text-decoration: none;
      font-weight: 600;
    }
    
    .register-link a:hover {
      text-decoration: underline;
    }
    
    .error-message {
      color: #dc3545;
      font-size: 14px;
      margin-top: 5px;
    }
    
    .remember-forgot {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    
    .remember-me {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .success-message {
      background: #d4edda;
      color: #155724;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      text-align: center;
    }
  </style>
</head>

<body data-theme="default">
  <!-- INCLUIR EL NAVBAR -->
  @include('public.secciones._navbar')

  <section class="login-section">
    <div class="login-container">
      <div class="login-header">
        <h1><i class="fas fa-coffee"></i> Bienvenido</h1>
        <p>Ingresa a tu cuenta DÉSOLÉ</p>
      </div>
      
      <form class="login-form" method="POST" action="{{ route('login.cliente') }}">
        @csrf
        
        @if(session('success'))
          <div class="success-message">
            {{ session('success') }}
          </div>
        @endif
        
        <div class="form-group">
          <label for="email"><i class="fas fa-envelope"></i> Email</label>
          <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
          @error('email')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
          <input type="password" id="password" name="password" class="form-control" required>
          @error('password')
            <div class="error-message">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="remember-forgot">
          <label class="remember-me">
            <input type="checkbox" name="remember" id="remember">
            Recordarme
          </label>
        </div>
        
        <button type="submit" class="btn-login">
          <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
        </button>
        
        <div class="register-link">
          ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a>
        </div>
      </form>
    </div>
  </section>
</body>
</html>