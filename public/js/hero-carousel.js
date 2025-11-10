// Carrusel Hero para DÉSOLÉ Cafetería Nocturna
class HeroCarousel {
    constructor() {
        this.currentSlide = 0;
        this.slides = document.querySelectorAll('.carousel-slide');
        this.indicators = document.querySelectorAll('.indicator');
        this.totalSlides = this.slides.length;
        this.autoPlayInterval = null;
        this.autoPlayDelay = 5000; // 5 segundos
        
        this.init();
    }

    init() {
        this.addEventListeners();
        this.startAutoPlay();
        this.showSlide(this.currentSlide);
    }

    addEventListeners() {
        // Controles de navegación
        document.querySelector('.carousel-control.prev').addEventListener('click', () => {
            this.prevSlide();
            this.resetAutoPlay();
        });

        document.querySelector('.carousel-control.next').addEventListener('click', () => {
            this.nextSlide();
            this.resetAutoPlay();
        });

        // Indicadores
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                this.goToSlide(index);
                this.resetAutoPlay();
            });
        });

        // Pausar auto-play al hacer hover
        const carousel = document.querySelector('.hero-carousel');
        carousel.addEventListener('mouseenter', () => this.stopAutoPlay());
        carousel.addEventListener('mouseleave', () => this.startAutoPlay());

        // Pausar auto-play al interactuar con touch
        carousel.addEventListener('touchstart', () => this.stopAutoPlay());
    }

    showSlide(index) {
        // Remover clase active de todos los slides e indicadores
        this.slides.forEach(slide => slide.classList.remove('active'));
        this.indicators.forEach(indicator => indicator.classList.remove('active'));

        // Agregar clase active al slide e indicador actual
        this.slides[index].classList.add('active');
        this.indicators[index].classList.add('active');
        
        this.currentSlide = index;
    }

    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.totalSlides;
        this.showSlide(nextIndex);
    }

    prevSlide() {
        const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        this.showSlide(prevIndex);
    }

    goToSlide(index) {
        this.showSlide(index);
    }

    startAutoPlay() {
        this.stopAutoPlay(); // Limpiar intervalo existente
        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, this.autoPlayDelay);
    }

    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }

    resetAutoPlay() {
        this.stopAutoPlay();
        this.startAutoPlay();
    }
}

// Inicializar carrusel cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    new HeroCarousel();
});