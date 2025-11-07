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
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

  :root {
    --color-bg: #0d0d0d;
    --color-bg-alt: #161616;
    --color-primary: #65cf72;
    --color-secondary: #222;
    --color-text: #f5f5f5;
    --color-muted: #bbbbbb;
    --transition: all 0.3s ease-in-out;
  }

  body {
    font-family: 'Poppins', sans-serif;
    background-color: var(--color-bg);
    color: var(--color-text);
  }

  /* ==============================
     SECCIÓN LOGIN
  ============================== */
  .login-section {
    padding: 80px 20px;
    min-height: 100vh;
    background: linear-gradient(135deg, #0d0d0d 0%, #1a1a1a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .login-container {
    background: var(--color-bg-alt);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
    overflow: hidden;
    width: 100%;
    max-width: 400px;
    margin-top: 20px;
    animation: fadeInUp 0.8s ease-out both;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ==============================
     ENCABEZADO
  ============================== */
  .login-header {
    background: var(--color-primary);
    color: #fff;
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

  /* ==============================
     FORMULARIO
  ============================== */
  .login-form {
    padding: 30px;
  }

  .form-group {
    margin-bottom: 20px;
    position: relative;
  }

  .form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--color-text);
  }

  .form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #333;
    border-radius: 10px;
    font-size: 16px;
    background-color: #1a1a1a;
    color: var(--color-text);
    transition: var(--transition);
  }

  .form-control:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(101, 207, 114, 0.2);
  }

  /* ==============================
     ÍCONO MOSTRAR CONTRASEÑA
  ============================== */
  #togglePassword {
    position: absolute;
    right: 15px;
    top: 45px;
    cursor: pointer;
    color: var(--color-muted);
    transition: var(--transition);
  }

  #togglePassword:hover {
    color: var(--color-primary);
  }

  /* ==============================
     BOTÓN LOGIN
  ============================== */
  .btn-login {
    width: 100%;
    padding: 14px;
    background: var(--color-primary);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
  }

  .btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(101, 207, 114, 0.3);
  }

  /* ==============================
     LINKS Y OTROS
  ============================== */
  .register-link {
    text-align: center;
    margin-top: 20px;
    color: var(--color-muted);
  }

  .register-link a {
    color: var(--color-primary);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
  }

  .register-link a:hover {
    text-decoration: underline;
  }

  .error-message {
    color: #ff7373;
    font-size: 14px;
    margin-top: 5px;
  }

  .remember-forgot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    color: var(--color-muted);
  }

  .remember-me {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .success-message {
    background: rgba(101, 207, 114, 0.15);
    color: var(--color-primary);
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
  }
</style>

</head>

<body data-theme="default">
  <!-- INCLUIR EL NAVBAR -->
 

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
        
        <div class="form-group" style="position: relative;">
  <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
  <input type="password" id="password" name="password" class="form-control" required>
  
  <!-- Ícono para mostrar/ocultar -->
  <span id="togglePassword" 
        style="position: absolute; right: 15px; top: 45px; cursor: pointer; color: #888;">
    <i class="fas fa-eye"></i>
  </span>

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
<script>
  document.addEventListener('DOMContentLoaded', function() {
      const togglePassword = document.getElementById('togglePassword');
      const password = document.getElementById('password');
      const icon = togglePassword.querySelector('i');

      togglePassword.addEventListener('click', function() {
          const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
          password.setAttribute('type', type);
          
          icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
      });
  });
</script>

</html>