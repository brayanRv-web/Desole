<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registro</title>
  <meta name="description" content="Regístrate en DÉSOLÉ - Cafetería nocturna. Alitas, pizzadogs, frappés y promociones nocturnas." />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Estilos -->
  <link rel="stylesheet" href="{{ asset('css/desole.css') }}">
  <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
  <link rel="icon" href="{{ asset('assets/favicon.ico') }}">

  <style>
    body {
      background-color: #000;
      color: #f8f8f8;
      font-family: 'Poppins', sans-serif;
    }

    .register-section {
      padding: 80px 20px;
      min-height: 100vh;
      background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .register-container {
      background: #1a1a1a;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
      overflow: hidden;
      width: 100%;
      max-width: 520px;
      margin-top: 20px;
    }

    .register-header {
      background: #65CF72;
      color: #fff;
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
      padding: 35px;
    }

    .form-group {
      margin-bottom: 22px;
      position: relative;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #f8f8f8;
    }

    .form-control {
      width: 100%;
      padding: 14px 18px;
      border: 2px solid #444;
      border-radius: 10px;
      font-size: 17px;
      background-color: #1a1a1a;
      color: #fff;
      transition: border-color 0.3s ease;
    }

    .form-control::placeholder {
      color: #c0c0c0;
      font-size: 15px;
    }

    .form-control:focus {
      outline: none;
      border-color: #65CF72;
      background-color: #1a1a1a;
    }

    .form-control.error {
      border-color: #ff6b6b !important;
    }

    .toggle-password {
      position: absolute;
      right: 15px;
      top: 48px;
      cursor: pointer;
      color: #bbb;
    }

    .toggle-password:hover {
      color: #65CF72;
    }

    .btn-register {
      width: 100%;
      padding: 15px;
      background: #65CF72;
      color: #1c1c1c;
      border: none;
      border-radius: 10px;
      font-size: 17px;
      font-weight: 700;
      cursor: pointer;
    }

    .btn-register:hover {
      background: #55b863;
    }

    .login-link {
      text-align: center;
      margin-top: 25px;
      color: #ccc;
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
      color: #ff6b6b;
      font-size: 14px;
      margin-top: 5px;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
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

<body data-theme="dark">
  <section class="register-section">
    <div class="register-container">
      <div class="register-header">
        <h1><i class="fas fa-coffee"></i> Únete a DÉSOLÉ</h1>
        <p>Comunidad cafetera con beneficios exclusivos</p>
      </div>

      <!-- 🔹 Desactivamos la validación HTML5 -->
      <form class="register-form" method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <div class="form-group">
          <label for="nombre"><i class="fas fa-user"></i> Nombre completo</label>
          <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" placeholder="Ej: Juan Pérez López">
          @error('nombre') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="email"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Ej: juanperez@gmail.com">
            @error('email') <div class="error-message">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
            <input type="tel" id="telefono" name="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="Ej: 9617654321">
            @error('telefono') <div class="error-message">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección completa</label>
          <input type="text" id="direccion" name="direccion" class="form-control" value="{{ old('direccion') }}" placeholder="Ej: Calle Reforma 123, CDMX">
          @error('direccion') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="colonia"><i class="fas fa-location-dot"></i> Colonia</label>
            <input type="text" id="colonia" name="colonia" class="form-control" value="{{ old('colonia') }}" placeholder="Ej: Roma Norte">
            @error('colonia') <div class="error-message">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="fecha_nacimiento"><i class="fas fa-cake-candles"></i> Fecha de nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}">
            @error('fecha_nacimiento') <div class="error-message">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="form-group">
          <label for="referencias"><i class="fas fa-map-pin"></i> Referencias de domicilio</label>
          <input type="text" id="referencias" name="referencias" class="form-control" value="{{ old('referencias') }}" placeholder="Ej: Casa azul, frente a tienda Oxxo">
          @error('referencias') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label for="alergias"><i class="fas fa-heart"></i> Alergias alimenticias</label>
          <input type="text" id="alergias" name="alergias" class="form-control" value="{{ old('alergias') }}" placeholder="Ej: Alergia a nueces, sin gluten, etc.">
          @error('alergias') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label for="preferencias"><i class="fas fa-star"></i> Preferencias alimenticias</label>
          <input type="text" id="preferencias" name="preferencias" class="form-control" value="{{ old('preferencias') }}" placeholder="Ej: Vegano, sin azúcar, bajo en grasa...">
          @error('preferencias') <div class="error-message">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres">
            <span id="togglePassword" class="toggle-password"><i class="fas fa-eye"></i></span>
            @error('password') <div class="error-message">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label for="password_confirmation"><i class="fas fa-lock"></i> Confirmar contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repite tu contraseña">
            <span id="togglePasswordConfirm" class="toggle-password"><i class="fas fa-eye"></i></span>
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

  <script>
    document.addEventListener('DOMContentLoaded', function () {

      // --- Mostrar / ocultar contraseña ---
      function toggleVisibility(buttonId, inputId) {
        const toggle = document.getElementById(buttonId);
        const input = document.getElementById(inputId);
        const icon = toggle.querySelector('i');
        toggle.addEventListener('click', function () {
          const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
          input.setAttribute('type', type);
          icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        });
      }

      toggleVisibility('togglePassword', 'password');
      toggleVisibility('togglePasswordConfirm', 'password_confirmation');

      // --- Validaciones personalizadas ---
      const form = document.querySelector('.register-form');

      form.addEventListener('submit', function (e) {
        e.preventDefault();
        let valido = true;

        // Eliminar errores previos
        form.querySelectorAll('.error-message.js').forEach(el => el.remove());
        form.querySelectorAll('.form-control').forEach(el => el.classList.remove('error'));

        const inputs = form.querySelectorAll('.form-control');
        inputs.forEach(input => {
          const valor = input.value.trim();
          if (!valor) {
            valido = false;
            mostrarError(input, 'No puedes dejar vacío este campo');
            input.classList.add('error');
          }
        });

        // Validar contraseña
        const password = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        const regexPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

        if (password.value.trim() && !regexPassword.test(password.value)) {
          valido = false;
          mostrarError(password, 'Debe contener al menos una mayúscula, una minúscula, un número y un símbolo');
          password.classList.add('error');
        }

        if (password.value !== confirm.value) {
          valido = false;
          mostrarError(confirm, 'Las contraseñas no coinciden');
          confirm.classList.add('error');
        }

        // Autocompletar campos vacíos de alergias/preferencias
        const alergias = document.getElementById('alergias');
        const preferencias = document.getElementById('preferencias');
        if (alergias.value.trim() === '') alergias.value = 'NINGUNA';
        if (preferencias.value.trim() === '') preferencias.value = 'NINGUNA';

        if (valido) form.submit();
      });

      function mostrarError(input, mensaje) {
        const error = document.createElement('div');
        error.classList.add('error-message', 'js');
        error.textContent = mensaje;
        input.insertAdjacentElement('afterend', error);
      }
    });
  </script>
</body>
</html>
