@extends('cliente.layout.cliente')  {{-- ← Ruta corregida --}}

@section('title', 'Mi Perfil - DÉSOLÉ')

@section('content')
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
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Dirección</label>
                                <input type="text" class="form-control profile-input" name="direccion" value="{{ $cliente->direccion }}" placeholder="Calle, número, colonia...">
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
@endsection