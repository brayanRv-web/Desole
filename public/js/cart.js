// ===== CARRITO DE COMPRAS - COMPATIBILIDAD =====
// Este archivo asegura que el carrito funcione correctamente en todas las páginas

(function() {
    let intentos = 0;
    const maxIntentos = 15; // Aumentar de 10 a 15
    
    function setupCartButton() {
        // Si carrito.js ya cargó, window.carrito existirá
        if (window.carrito && typeof window.carrito.renderModal === 'function') {
            console.log('✅ Clase Carrito detectada, configurando botón');
            setupCartListener();
            return true;
        }
        
        // Si no está listo pero aún hay intentos, reintentar
        if (intentos < maxIntentos) {
            intentos++;
            console.log(`⏳ Esperando carrito (intento ${intentos}/${maxIntentos})`);
            setTimeout(setupCartButton, 100);
            return false;
        }
        
        // Si agotamos intentos, crear fallback
        console.warn('⚠️ Carrito no detectado después de intentos, usando fallback');
        window.carrito = window.carrito || {
            items: JSON.parse(localStorage.getItem('carrito') || '[]'),
            renderModal: () => {
                // Verificar si está autenticado buscando evidencia en la página
                const isAuthenticated = !!document.querySelector('#cartBtn') && 
                                       document.body.innerHTML.includes('Mis Pedidos');
                if (!isAuthenticated) {
                    alert('Por favor, inicia sesión para usar el carrito');
                } else {
                    alert('Carrito no disponible en este momento');
                }
            },
            cerrarModal: () => {},
        };
        setupCartListener();
        return true;
    }

    function setupCartListener() {
        const cartButton = document.getElementById('cartBtn');
        if (cartButton) {
            cartButton.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('🖱️ Click en carrito');
                if (window.carrito && typeof window.carrito.renderModal === 'function') {
                    try {
                        window.carrito.renderModal();
                        console.log('✅ renderModal() ejecutado');
                    } catch (err) {
                        console.error('❌ Error en renderModal():', err);
                    }
                }
            });
            console.log('✅ Botón del carrito configurado');
        }

        // Actualizar contador del carrito
        const updateCartCount = () => {
            if (window.carrito && window.carrito.items) {
                const total = window.carrito.items.reduce((sum, item) => sum + (item.cantidad || 0), 0);
                const countEl = document.getElementById('cart-count');
                if (countEl) countEl.textContent = total;
            }
        };
        updateCartCount();

        // Escuchar cambios en el carrito desde otros eventos
        window.addEventListener('carrito:cambio', updateCartCount);
    }
    
    // Iniciar el setup - ejecutar inmediatamente (no esperar DOMContentLoaded)
    setupCartButton();
})();

// Script para menú hamburguesa y otros comportamientos globales
document.addEventListener('DOMContentLoaded', function() {
    // Scroll suave para anclas
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Menú hamburguesa para móviles
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });

        // Cerrar menú al hacer clic en un enlace
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                hamburger.classList.remove('active');
            });
        });
    }
});
