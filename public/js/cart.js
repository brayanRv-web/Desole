document.addEventListener('DOMContentLoaded', function() {
    // ===== CARRITO DE COMPRAS =====
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    const updateCartCount = () => {
        const count = cart.reduce((t, i) => t + i.quantity, 0);
        // COMENTA ESTA LÍNEA:
        // document.getElementById('cart-count').textContent = count;
    };

    const showNotification = msg => {
        const existing = document.querySelector('.notification');
        if (existing) existing.remove();

        const n = document.createElement('div');
        n.className = 'notification';
        n.innerHTML = `<i class="fas fa-bell"></i> ${msg}`;
        document.body.appendChild(n);
        setTimeout(() => n.classList.add('show'), 100);
        setTimeout(() => { n.classList.remove('show'); n.remove(); }, 3200);
    };

    // Agregar productos al carrito
    document.querySelectorAll('.add-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const price = parseFloat(btn.dataset.price);
            const item = cart.find(i => i.id === id);
            item ? item.quantity++ : cart.push({ id, name, price, quantity: 1 });
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
            showNotification('✅ Producto agregado');
        });
    });

    // Pedido rápido por WhatsApp
    const quickOrder = document.getElementById('quick-order');
    if (quickOrder) {
        quickOrder.addEventListener('click', () => {
            if (!cart.length) return showNotification('🛒 Agrega productos primero');
            let msg = '¡Hola! Quiero hacer un pedido en DESOLE:\n\n';
            cart.forEach(i => msg += `• ${i.quantity}x ${i.name} - $${(i.price * i.quantity).toFixed(2)}\n`);
            msg += `\n💰 Total: $${cart.reduce((t, i) => t + i.price * i.quantity, 0).toFixed(2)}`;
            window.open(`https://wa.me/529614564697?text=${encodeURIComponent(msg)}`, '_blank');
        });
    }

    // Botones de promociones
    document.querySelectorAll('.aprovechar-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const promoId = this.dataset.promoId;
            const promoName = this.dataset.promoName;
            showNotification(`🎊 Promoción "${promoName}" aplicada a tu pedido`);
            
            // Aquí puedes agregar lógica adicional para aplicar la promoción
            // Por ejemplo, agregar un código de descuento al carrito
        });
    });

    // COMENTA ESTA LÍNEA:
    // updateCartCount();

    // Scroll suave
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // ===== MENÚ HAMBURGUESA PARA MÓVILES =====
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    const cartBtn = document.querySelector('.cart-btn');

    if (hamburger) {
        hamburger.addEventListener('click', function() {
            // Alternar menú
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
            
            // Ocultar carrito cuando el menú está abierto (opcional)
            if (navLinks.classList.contains('active')) {
                cartBtn.style.display = 'none';
            } else {
                cartBtn.style.display = 'flex';
            }
        });

        // Cerrar menú al hacer clic en un enlace
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                hamburger.classList.remove('active');
                cartBtn.style.display = 'flex';
            });
        });
    }

    // COMENTA ESTA LÍNEA:
    // loadCartItems();
});