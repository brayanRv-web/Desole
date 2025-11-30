document.addEventListener('DOMContentLoaded', () => {
    if (window.clienteCarritoInicializado) return;
    window.clienteCarritoInicializado = true;
    inicializarCarritoCliente();
});

function inicializarCarritoCliente() {
    // Obtener CSRF token de forma segura (puede no existir en todas las páginas)
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    const token = metaToken ? metaToken.getAttribute('content') : null;

    // Si no hay token, no inicializar (estamos en una página sin autenticación)
    if (!token) {
        return;
    }

    // Agregar al carrito
    const isAuth = !!document.querySelector('#cartBtn') || !!document.querySelector('.logout-btn');

    if (!isAuth) {
        // If not authenticated, bind clicks to prompt login instead of allowing adds
        document.querySelectorAll('.btn-agregar-carrito').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                mostrarAlerta('🔐 Debes iniciar sesión para agregar productos');
                setTimeout(() => { window.location.href = '/login-cliente'; }, 900);
            });
        });
        return;
    }

    if (window.carrito && typeof window.carrito.agregar === 'function') {
        // If the global client-side cart exists, avoid attaching per-button listeners to prevent double-adds
    } else {
        document.querySelectorAll('.btn-agregar-carrito').forEach(btn => {
            btn.addEventListener('click', function () {
                const productoId = this.dataset.productoId;

                // Fallback: attempt server-side add (may not exist on this project)
                fetch('/cliente/carrito/agregar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ producto_id: productoId, cantidad: 1 })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const el = document.getElementById('cart-count') || document.querySelector('.cart-count');
                            if (el) el.textContent = data.carrito_count;
                            mostrarAlerta('✅ Producto agregado al carrito');
                        }
                    });
            });
        });
    }

    // Agregar promoción al carrito
    const promoBtns = document.querySelectorAll('.btn-agregar-promocion');

    promoBtns.forEach(btn => {
        if (btn.dataset.listenerAttached) return; // Evitar duplicados
        btn.dataset.listenerAttached = 'true';

        btn.addEventListener('click', function () {
            const promocionId = this.dataset.promocionId;
            const btnOriginalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Agregando...';
            this.disabled = true;

            const baseUrl = window.APP_URL || '';
            // Asegurar que no haya doble slash si baseUrl termina en /
            const endpoint = `${baseUrl.replace(/\/$/, '')}/cliente/carrito/agregar-promocion`;

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ promocion_id: promocionId })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.producto) {
                        // Agregar el pack como un único ítem
                        if (window.carrito && typeof window.carrito.agregar === 'function') {
                            window.carrito.agregar(data.producto);
                        } else {
                            console.warn('Objeto carrito global no encontrado');
                        }

                        mostrarAlerta('✅ Pack agregado al carrito');

                    } else {
                        mostrarAlerta('⚠️ ' + (data.message || 'Error al agregar promoción'));
                    }
                })
                .catch(err => {
                    console.error('❌ Error en fetch:', err);
                    mostrarAlerta('❌ Error de conexión');
                })
                .finally(() => {
                    this.innerHTML = btnOriginalText;
                    this.disabled = false;
                });
        });
    });

    // Eliminar producto
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', function () {
            const productoId = this.dataset.productoId;

            fetch('/cliente/carrito/eliminar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ producto_id: productoId })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('🗑️ Producto eliminado');
                        setTimeout(() => location.reload(), 800);
                    }
                });
        });
    });

    // Finalizar compra
    const finalizarBtn = document.getElementById('btn-finalizar-compra');
    if (finalizarBtn) {
        finalizarBtn.addEventListener('click', () => {
            fetch('/cliente/carrito/finalizar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({})
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('🛍️ Pedido realizado con éxito');
                        setTimeout(() => location.href = '/cliente/carrito', 1200);
                    } else {
                        mostrarAlerta('⚠️ ' + data.message);
                    }
                });
        });
    }
}

// Función de alerta visible
function mostrarAlerta(mensaje) {
    let alerta = document.createElement('div');
    alerta.textContent = mensaje;
    alerta.className = 'fixed bottom-5 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
    document.body.appendChild(alerta);
    setTimeout(() => alerta.remove(), 2500);
}

document.addEventListener('DOMContentLoaded', inicializarCarritoCliente);
