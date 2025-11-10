<section id="menu" class="menu-section">
  <header class="section-header">
    <h2><i class="fas fa-utensils"></i> Menú Principal</h2>
    <p>
      @guest('cliente')
        Prueba lo mejor de DÉSOLÉ - Regístrate para ver el menú completo y ordenar
      @else
        Haz clic para agregar tus favoritos al carrito.
      @endguest
    </p>
  </header>

  @guest('cliente')
    {{-- ========== VISITANTES: PRODUCTOS EN CARDS ========== --}}
    @php
      $productos = \App\Models\Producto::where('estado', 'activo')->get();
    @endphp

    @if($productos->count() > 0)
      <div class="productos-teaser-grid" data-productos-count="{{ $productos->count() }}">
        @foreach($productos as $producto)
        <div class="producto-teaser-card">
          <div class="producto-img-container">
           <img src="{{ asset($producto->imagen ?? 'assets/placeholder-food.jpg') }}" 
                alt="{{ $producto->nombre }}" 
                class="producto-img">
            <div class="producto-categoria">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</div>
            @if($producto->stock <= 5 && $producto->stock > 0)
              <div class="card-stock-low">¡Últimas {{ $producto->stock }} unidades!</div>
            @elseif($producto->stock == 0)
              <div class="card-stock-out">Agotado</div>
            @endif
          </div>
          
          <div class="producto-info">
            <h3 class="producto-nombre">{{ $producto->nombre }}</h3>
            <p class="producto-descripcion">{{ $producto->descripcion ?? 'Delicioso producto de nuestra cafetería' }}</p>
            
            <div class="producto-details">
              <div class="producto-precio">${{ number_format($producto->precio, 2) }} MXN</div>
              <div class="producto-stock">
                <small>
                  @if($producto->stock > 0)
                    <i class="fas fa-check-circle text-success"></i> Disponible
                  @else
                    <i class="fas fa-times-circle text-danger"></i> Agotado
                  @endif
                </small>
              </div>
            </div>
            
            <button class="btn-login-required" onclick="mostrarModalAuth()">
              <i class="fas fa-shopping-cart"></i> Agregar al carrito
            </button>
          </div>
        </div>
        @endforeach
      </div>

    @else
      <div class="estado-vacio">
        <div class="empty-state">
          <i class="fas fa-utensils"></i>
          <h3>No hay productos disponibles</h3>
          <p>Vuelve pronto para ver nuestro menú</p>
        </div>
      </div>
    @endif

  @else
    {{-- ========== CLIENTES REGISTRADOS: MENÚ COMPLETO EN CARDS ========== --}}
    @php
      $productos = \App\Models\Producto::where('estado', 'activo')->get();
    @endphp

    @if($productos->count() > 0)
      <div class="productos-teaser-grid" data-productos-count="{{ $productos->count() }}">
        @foreach($productos as $producto)
        <div class="producto-teaser-card">
          <div class="producto-img-container">
            <img src="{{ asset($producto->imagen ?? 'assets/placeholder-food.jpg') }}" 
                alt="{{ $producto->nombre }}" 
                class="producto-img">
            <div class="producto-categoria">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</div>
            @if($producto->stock <= 5 && $producto->stock > 0)
              <div class="card-stock-low">¡Últimas {{ $producto->stock }} unidades!</div>
            @elseif($producto->stock == 0)
              <div class="card-stock-out">Agotado</div>
            @endif
          </div>
          
          <div class="producto-info">
            <h3 class="producto-nombre">{{ $producto->nombre }}</h3>
            <p class="producto-descripcion">{{ $producto->descripcion ?? 'Delicioso producto de nuestra cafetería' }}</p>
            
            <div class="producto-details">
              <div class="producto-precio">${{ number_format($producto->precio, 2) }} MXN</div>
              <div class="producto-stock">
                <small>
                  @if($producto->stock > 0)
                    <i class="fas fa-check-circle text-success"></i> Disponible
                  @else
                    <i class="fas fa-times-circle text-danger"></i> Agotado
                  @endif
                </small>
              </div>
            </div>
            
            <button class="btn-agregar-carrito" 
                    data-id="{{ $producto->id }}" 
                    data-name="{{ $producto->nombre }}" 
                    data-price="{{ $producto->precio }}"
                    {{ $producto->stock == 0 ? 'disabled' : '' }}>
              <i class="fas fa-cart-plus"></i> 
              {{ $producto->stock == 0 ? 'Agotado' : 'Agregar al Carrito' }}
            </button>
          </div>
        </div>
        @endforeach
      </div>
    @else
      <div class="estado-vacio">
        <div class="empty-state">
          <i class="fas fa-utensils"></i>
          <h3>No hay productos disponibles</h3>
          <p>Vuelve pronto para ver nuestro menú</p>
        </div>
      </div>
    @endif
  @endguest
</section>