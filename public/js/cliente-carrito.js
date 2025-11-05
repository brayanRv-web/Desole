// resources/js/cliente-carrito.js
function inicializarCarritoCliente() {
    document.querySelectorAll('.btn-agregar-carrito').forEach(btn => {
        btn.addEventListener('click', function() {
            const productoId = this.dataset.productoId;
            console.log('Agregar producto:', productoId);
            
            // Lógica AJAX para agregar al carrito
            fetch('/cliente/carrito/agregar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    producto_id: productoId,
                    cantidad: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Actualizar contador del carrito
                    const cartCount = document.getElementById('cart-count');
                    if(cartCount) {
                        cartCount.textContent = data.carrito_count;
                    }
                    alert('Producto agregado al carrito');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al agregar producto al carrito');
            });
        });
    });
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializarCarritoCliente);
} else {
    inicializarCarritoCliente();
}