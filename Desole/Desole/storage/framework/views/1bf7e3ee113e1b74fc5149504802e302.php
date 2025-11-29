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
  <link rel="stylesheet" href="<?php echo e(asset('css/desole.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/hero.css')); ?>">
  <link rel="icon" href="<?php echo e(asset('assets/favicon.ico')); ?>">

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
      background: #2a2a2a;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
      overflow: hidden;
      width: 100%;
      max-width: 500px;
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
      color: #f8f8f8;
    }

    .form-control {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #444;
      border-radius: 10px;
      font-size: 16px;
      background-color: #1f1f1f;
      color: #fff;
    }

    .form-control::placeholder {
      color: #aaa;
    }

    .form-control:focus {
      outline: none;
      border-color: #65CF72;
      background-color: #252525;
      box-shadow: none; /* 🔹 Sin brillo */
    }

    /* Íconos de ver/ocultar */
    .toggle-password {
      position: absolute;
      right: 15px;
      top: 45px;
      cursor: pointer;
      color: #bbb;
    }

    .toggle-password:hover {
      color: #65CF72;
    }

    .btn-register {
      width: 100%;
      padding: 14px;
      background: #65CF72;
      color: #1c1c1c;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
    }

    .btn-register:hover {
      background: #55b863;
    }

    .login-link {
      text-align: center;
      margin-top: 20px;
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

<body data-theme="dark">
  <section class="register-section">
    <div class="register-container">
      <div class="register-header">
        <h1><i class="fas fa-coffee"></i> Únete a DÉSOLÉ</h1>
        <p>Comunidad cafetera con beneficios exclusivos</p>
      </div>

      <form class="register-form" method="POST" action="<?php echo e(route('register')); ?>">
        <?php echo csrf_field(); ?>

        <div class="form-group">
          <label for="nombre"><i class="fas fa-user"></i> Nombre completo</label>
          <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo e(old('nombre')); ?>" required autofocus>
          <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="email"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="form-group">
            <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
            <input type="tel" id="telefono" name="telefono" class="form-control" value="<?php echo e(old('telefono')); ?>" required>
            <?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
        </div>

        <div class="form-group">
          <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección completa</label>
          <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo e(old('direccion')); ?>" required>
          <?php $__errorArgs = ['direccion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="colonia"><i class="fas fa-location-dot"></i> Colonia</label>
            <input type="text" id="colonia" name="colonia" class="form-control" value="<?php echo e(old('colonia')); ?>" required>
            <?php $__errorArgs = ['colonia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="form-group">
            <label for="fecha_nacimiento"><i class="fas fa-cake-candles"></i> Fecha de nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="<?php echo e(old('fecha_nacimiento')); ?>" required>
            <?php $__errorArgs = ['fecha_nacimiento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
        </div>

        <div class="form-group">
          <label for="referencias"><i class="fas fa-map-pin"></i> Referencias de domicilio</label>
          <input type="text" id="referencias" name="referencias" class="form-control" value="<?php echo e(old('referencias')); ?>" placeholder="Ej: Casa color azul, junto a...">
          <?php $__errorArgs = ['referencias'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
          <label for="alergias"><i class="fas fa-heart"></i> Alergias alimenticias</label>
          <input type="text" id="alergias" name="alergias" class="form-control" value="<?php echo e(old('alergias')); ?>" placeholder="Ej: Sin gluten, alergia a nueces...">
          <?php $__errorArgs = ['alergias'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
          <label for="preferencias"><i class="fas fa-star"></i> Preferencias alimenticias</label>
          <input type="text" id="preferencias" name="preferencias" class="form-control" value="<?php echo e(old('preferencias')); ?>" placeholder="Ej: Vegano, sin azúcar, etc.">
          <?php $__errorArgs = ['preferencias'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <span id="togglePassword" class="toggle-password"><i class="fas fa-eye"></i></span>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-message"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="form-group">
            <label for="password_confirmation"><i class="fas fa-lock"></i> Confirmar contraseña</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            <span id="togglePasswordConfirm" class="toggle-password"><i class="fas fa-eye"></i></span>
          </div>
        </div>

        <button type="submit" class="btn-register">
          <i class="fas fa-user-plus"></i> Crear mi cuenta
        </button>

        <div class="login-link">
          ¿Ya tienes cuenta? <a href="<?php echo e(route('login.cliente')); ?>">Inicia sesión aquí</a>
        </div>
      </form>
    </div>
  </section>

  <!-- Script mostrar/ocultar contraseña -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      function toggleVisibility(buttonId, inputId) {
        const toggle = document.getElementById(buttonId);
        const input = document.getElementById(inputId);
        const icon = toggle.querySelector('i');

        toggle.addEventListener('click', function() {
          const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
          input.setAttribute('type', type);
          icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
        });
      }

      toggleVisibility('togglePassword', 'password');
      toggleVisibility('togglePasswordConfirm', 'password_confirmation');
    });
  </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\Desole\resources\views/public/register.blade.php ENDPATH**/ ?>