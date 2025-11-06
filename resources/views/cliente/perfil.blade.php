@php
    $isProfilePage = true;
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - DÉSOLÉ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/desole.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
</head>
<body>
    <!-- Navbar Específico para Perfil -->
    @include('public.secciones._navbar', ['isProfilePage' => true])

    <section class="profile-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <!-- Formulario de Perfil -->
                    <div class="profile-form-card">
                        <div class="form-header">
                            <h4><i class="fas fa-user-edit me-2"></i>Editar Mi Perfil</h4>
                        </div>
                        <div class="form-body">
                            @if(session('success'))
                                <div class="success-message">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('cliente.perfil.update') }}" method="POST">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Nombre Completo</label>
                                            <input type="text" class="form-control profile-input" name="nombre" value="{{ $cliente->nombre }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control profile-input" value="{{ $cliente->email }}" disabled>
                                            <small class="text-muted">El email no se puede modificar</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Teléfono</label>
                                            <input type="text" class="form-control profile-input" name="telefono" value="{{ $cliente->telefono }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control profile-input" name="fecha_nacimiento" value="{{ $cliente->fecha_nacimiento }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" class="form-control profile-input" name="direccion" value="{{ $cliente->direccion }}" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Colonia</label>
                                            <input type="text" class="form-control profile-input" name="colonia" value="{{ $cliente->colonia }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Referencias</label>
                                            <input type="text" class="form-control profile-input" name="referencias" value="{{ $cliente->referencias }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Alergias</label>
                                    <textarea class="form-control profile-input" name="alergias" rows="3" placeholder="Ej: Alérgico a los mariscos, lactosa...">{{ $cliente->alergias }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Preferencias Alimenticias</label>
                                    <textarea class="form-control profile-input" name="preferencias" rows="3" placeholder="Ej: Vegetariano, sin azúcar...">{{ $cliente->preferencias }}</textarea>
                                </div>
                                
                                <div class="form-actions">
                                    <a href="{{ route('cliente.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Actualizar Perfil
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>