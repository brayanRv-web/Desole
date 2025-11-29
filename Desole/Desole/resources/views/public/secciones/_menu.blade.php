<section id="menu" class="menu-section">
  <header class="section-header">
    <h2><i class="fas fa-utensils"></i> Menú Principal</h2>
    <p>Haz clic para agregar tus favoritos al carrito.</p>
  </header>

  @php
    $productos = \App\Models\Producto::where('status', 'activo')->get();
    $categorias = [
      'alitas' => $productos->filter(fn($p) => stripos($p->nombre, 'alita') !== false),
      'boneless' => $productos->filter(fn($p) => stripos($p->nombre, 'boneless') !== false),
      'frappes' => $productos->filter(fn($p) => stripos($p->nombre, 'frappé') !== false || stripos($p->nombre, 'frappe') !== false),
      'hamburguesas' => $productos->filter(fn($p) => stripos($p->nombre, 'hamburguesa') !== false),
      'hotdogs' => $productos->filter(fn($p) => stripos($p->nombre, 'hotdog') !== false || stripos($p->nombre, 'pizzadog') !== false),
      'otros' => $productos->filter(fn($p) =>
        stripos($p->nombre, 'alita') === false &&
        stripos($p->nombre, 'boneless') === false &&
        stripos($p->nombre, 'frappé') === false &&
        stripos($p->nombre, 'frappe') === false &&
        stripos($p->nombre, 'hamburguesa') === false &&
        stripos($p->nombre, 'hotdog') === false &&
        stripos($p->nombre, 'pizzadog') === false
      ),
    ];
  @endphp

  <div class="menu-grid">
    @foreach($categorias as $categoria => $productosCategoria)
      @if($productosCategoria->count() > 0)
        <article class="menu-category">
          <h3>
            @switch($categoria)
              @case('alitas') <i class="fas fa-drumstick-bite"></i> Alitas @break
              @case('boneless') <i class="fas fa-utensils"></i> Boneless @break
              @case('frappes') <i class="fas fa-glass-whiskey"></i> Frappés @break
              @case('hamburguesas') <i class="fas fa-hamburger"></i> Hamburguesas @break
              @case('hotdogs') <i class="fas fa-hotdog"></i> Hotdogs @break
              @default <i class="fas fa-pizza-slice"></i> Otros
            @endswitch
          </h3>
          <ul class="menu-list">
            @foreach($productosCategoria as $producto)
              <li class="menu-item">
                <div class="menu-item-content">
                  <strong>{{ $producto->nombre }}</strong>
                  @if($producto->descripcion)
                    <br><small class="text-muted">{{ $producto->descripcion }}</small>
                  @endif
                </div>
                <div class="menu-item-price">
                  ${{ number_format($producto->precio, 2) }}
                  <button class="add-btn" 
                          data-id="{{ $producto->id }}" 
                          data-name="{{ $producto->nombre }}" 
                          data-price="{{ $producto->precio }}">
                    <i class="fas fa-plus"></i> Agregar
                  </button>
                </div>
              </li>
            @endforeach
          </ul>
        </article>
      @endif
    @endforeach
  </div>
</section>
