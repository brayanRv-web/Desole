// public/js/carrito.js
class Carrito {
  constructor() {
    console.log('✅ Carrito inicializado');
    console.log('📍 Ubicación:', window.location.pathname);
    console.log('🔐 Autenticado:', !!document.querySelector('#cartBtn'));
    this.items = this._load();
    this._renderCount();
    this._setupListeners();
  }

  /* ---------------------------
     Cargar / guardar datos
  ---------------------------- */
  _load() {
    try {
      const data = localStorage.getItem('carrito');
      if (!data) return [];
      const parsed = JSON.parse(data);
      return Array.isArray(parsed)
        ? parsed.map(i => this._normalizeItem(i)).filter(Boolean)
        : [];
    } catch (e) {
      console.error('Error leyendo carrito:', e);
      return [];
    }
  }

  _save() {
    localStorage.setItem('carrito', JSON.stringify(this.items));
    this._renderCount();
    window.dispatchEvent(new CustomEvent('carrito:cambio', { detail: this.items }));
  }

  _normalizeItem(item) {
    if (!item || typeof item !== 'object') return null;
    const id = Number(item.id);
    if (!Number.isFinite(id)) return null;
    return {
      id,
      nombre: String(item.nombre || 'Producto'),
      precio: Number(item.precio) || 0,
      stock: Number(item.stock) || 0,
      cantidad: Math.max(1, Math.min(Number(item.cantidad) || 1, Number(item.stock) || Infinity)),
      imagen: item.imagen || ''
    };
  }

  /* ---------------------------
     Render visual
  ---------------------------- */
  _renderCount() {
    const total = this.items.reduce((acc, p) => acc + (p.cantidad || 0), 0);
    const el = document.querySelector('.cart-count');
    if (el) el.textContent = total;
  }

  renderModal() {
    this.cerrarModal();

    const modal = document.createElement('div');
    modal.id = 'modal-carrito';
    modal.className = 'modal-carrito';
    modal.innerHTML = `
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="m-0"><i class="fas fa-shopping-cart me-2"></i> Tu carrito</h5>
          <button type="button" class="cerrar-modal">&times;</button>
        </div>
        <div class="modal-body" id="carrito-body">
          ${this.items.length === 0
        ? '<p class="text-center text-secondary">Tu carrito está vacío.</p>'
        : this.items.map(p => `
              <div class="cart-item" data-id="${p.id}">
                <div>
                  <strong>${p.nombre}</strong>
                  <div><small>$${p.precio.toFixed(2)}</small></div>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <button class="quantity-btn restar">−</button>
                  <span>${p.cantidad}</span>
                  <button class="quantity-btn sumar">+</button>
                  <button class="btn-remove-carrito eliminar" title="Eliminar">🗑️</button>
                </div>
              </div>
            `).join('')}
        </div>
        <div class="modal-footer">
          <div class="cart-total">
            <span>Total</span>
            <strong>$${this.calcularTotal().toFixed(2)}</strong>
          </div>

          <!-- Footer actions: Finalizar full-width like the menu page -->
          <div class="cart-summary-actions">
            <button class="btn-procesar checkout" type="button">Finalizar compra</button>
            <button class="btn-limpiar limpiar" type="button">Vaciar</button>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(modal);

    // Escuchar acciones del modal
    modal.querySelector('.cerrar-modal').addEventListener('click', () => this.cerrarModal());
    modal.querySelectorAll('.restar').forEach(b => b.addEventListener('click', e => this._modificarCantidad(e, -1)));
    modal.querySelectorAll('.sumar').forEach(b => b.addEventListener('click', e => this._modificarCantidad(e, 1)));
    modal.querySelectorAll('.eliminar').forEach(b => b.addEventListener('click', e => this._eliminarItem(e)));
    modal.querySelector('.limpiar').addEventListener('click', () => this.limpiar());
    modal.querySelector('.checkout').addEventListener('click', () => this.procesar());
  }

  cerrarModal() {
    const modal = document.getElementById('modal-carrito');
    if (modal) modal.remove();
  }

  /* ---------------------------
     Acciones del carrito
  ---------------------------- */
  agregar(productoRaw) {
    // Only allow adding when the user is authenticated (client sessions)
    if (!this._isAuthenticated()) {
      this._notify('🔐 Debes iniciar sesión para agregar productos al carrito', 'error');
      // optional: redirect to login page after short delay
      setTimeout(() => { window.location.href = '/login-cliente'; }, 900);
      return;
    }

    const p = this._normalizeItem(productoRaw);
    if (!p) return;

    const existente = this.items.find(i => i.id === p.id);
    if (existente) {
      if (existente.stock && existente.cantidad >= existente.stock) {
        return this._notify('Stock máximo alcanzado', 'error');
      }
      existente.cantidad++;
    } else {
      this.items.push(p);
    }
    this._save();
    this._notify('Producto agregado al carrito', 'success');
  }

  _isAuthenticated() {
    // Determine authentication by checking for elements rendered only for logged-in clientes
    // (cart button or logout button are only present when auth:cliente)
    return !!document.querySelector('#cartBtn') || !!document.querySelector('.logout-btn');
  }

  _modificarCantidad(e, delta) {
    const id = Number(e.target.closest('.cart-item').dataset.id);
    const item = this.items.find(i => i.id === id);
    if (!item) return;
    item.cantidad = Math.max(1, item.cantidad + delta);
    if (item.stock && item.cantidad > item.stock) item.cantidad = item.stock;
    this._save();
    this.renderModal();
  }

  _eliminarItem(e) {
    const id = Number(e.target.closest('.cart-item').dataset.id);
    this.eliminar(id);
    this.renderModal();
  }

  eliminar(id) {
    this.items = this.items.filter(i => i.id !== id);
    this._save();
    this._notify('Producto eliminado', 'success');
  }

  limpiar() {
    this.items = [];
    localStorage.removeItem('carrito');
    this._renderCount();
    this.cerrarModal();
    this._notify('Carrito vacío', 'info');
  }

  calcularTotal() {
    return this.items.reduce((acc, p) => acc + p.precio * p.cantidad, 0);
  }

  procesar() {
    if (this.items.length === 0) {
      this._notify('El carrito está vacío', 'error');
      return;
    }

    console.log('🔵 Redirigiendo a checkout desde carrito.js');

    // Usar APP_URL global si está definida (inyectada en layout)
    if (window.APP_URL) {
      const baseUrl = window.APP_URL.replace(/\/$/, '');
      window.location.href = `${baseUrl}/cliente/carrito/checkout`;
      return;
    }

    // Fallback: intentar deducir base path si APP_URL no existe
    const pathname = window.location.pathname;
    // Si estamos en /Desole/public/ o similar, tratar de conservar el prefijo
    // Buscamos 'public' o el nombre del proyecto si es común
    let checkoutUrl = '/cliente/carrito/checkout';

    // Si la URL actual contiene 'public', asumimos que es parte del base path
    const publicIndex = pathname.indexOf('/public/');
    if (publicIndex !== -1) {
      const base = pathname.substring(0, publicIndex + 8); // incluir /public/
      checkoutUrl = base + 'cliente/carrito/checkout';
    } else if (pathname.indexOf('/cliente/') !== -1) {
      const clienteIndex = pathname.indexOf('/cliente/');
      const base = pathname.substring(0, clienteIndex);
      checkoutUrl = base + '/cliente/carrito/checkout';
    }

    console.log('🔄 Redirigiendo a (fallback):', checkoutUrl);
    window.location.href = checkoutUrl;
  }

  /* ---------------------------
     Inicialización UI
  ---------------------------- */
  _setupListeners() {
    // Botón global del carrito
    const btn = document.querySelector('.cart-btn, .cart-button');
    if (btn) {
      btn.addEventListener('click', () => this.renderModal());
    }

    // Botones agregar producto
    document.addEventListener('click', (e) => {
      const btnAdd = e.target.closest('.btn-agregar-carrito, .add-btn');
      if (btnAdd) {
        e.preventDefault();
        const card = btnAdd.closest('[data-producto-id]');
        if (!card) return;

        const producto = {
          id: card.dataset.productoId,
          nombre: card.dataset.nombre,
          precio: card.dataset.precio,
          stock: card.dataset.stock || 0,
          imagen: card.dataset.imagen || ''
        };
        this.agregar(producto);
      }
    });
  }

  /* ---------------------------
     Notificaciones simples
  ---------------------------- */
  _notify(msg, tipo = 'info') {
    const cont = document.getElementById('notificaciones-container') || this._crearNotificaciones();
    const div = document.createElement('div');
    div.className = `alert alert-${tipo === 'error' ? 'danger' : tipo === 'success' ? 'success' : 'secondary'} notification-slide`;
    div.textContent = msg;
    cont.appendChild(div);
    setTimeout(() => div.remove(), 2000);
  }

  _crearNotificaciones() {
    const cont = document.createElement('div');
    cont.id = 'notificaciones-container';
    document.body.appendChild(cont);
    return cont;
  }
}

// Inicializar carrito global
window.carrito = new Carrito();
