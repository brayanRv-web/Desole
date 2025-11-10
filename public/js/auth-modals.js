// Gestión de Modales de Autenticación
class AuthModals {
    constructor() {
        this.authModal = document.getElementById('authModal');
        this.loginRequiredButtons = document.querySelectorAll('.btn-login-required');
        this.closeModal = document.querySelector('.close-modal');
        
        this.init();
    }

    init() {
        // Event listeners para botones que requieren login
        this.loginRequiredButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.showAuthModal();
            });
        });

        // Cerrar modal
        this.closeModal.addEventListener('click', () => {
            this.hideAuthModal();
        });

        // Cerrar modal al hacer click fuera
        window.addEventListener('click', (e) => {
            if (e.target === this.authModal) {
                this.hideAuthModal();
            }
        });

        // Cerrar modal con ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.authModal.style.display === 'block') {
                this.hideAuthModal();
            }
        });
    }

    showAuthModal() {
        this.authModal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevenir scroll
    }

    hideAuthModal() {
        this.authModal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restaurar scroll
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    new AuthModals();
});