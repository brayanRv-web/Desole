function inicializarCarritoCliente() {
    // Obtener CSRF token de forma segura (puede no existir en todas las páginas)
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    const token = metaToken ? metaToken.getAttribute('content') : null;

    // Si no hay token, no inicializar (estamos en una página sin autenticación)
    if (!token) {
        console.log('ℹ️ No hay meta CSRF-token, cliente-carrito deshabilitado en esta página');
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
        console.log('ℹ️ Cliente-carrito: usuario no autenticado, se solicita login en clics de Agregar');
        return;
    }

    if (window.carrito && typeof window.carrito.agregar === 'function') {
        // If the global client-side cart exists, avoid attaching per-button listeners to prevent double-adds
        console.log('ℹ️ Cliente-carrito: usando carrito cliente, no se adjuntan listeners individuales de "Agregar"');
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
