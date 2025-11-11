// ===== CONFIGURACIÓN DE RUTA BASE =====
// Detectar la ruta base del sitio (ej: /Desole/ o /)
(function() {
    // Calcular base path automáticamente
    const pathname = window.location.pathname;
    let basePath = '/';
    
    // Si el pathname contiene /cliente/, /admin/, /empleado/, etc.
    // La base es todo antes de eso
    const match = pathname.match(/^(.*?)\/(cliente|admin|empleado|public)\//);
    if (match) {
        basePath = match[1] + '/';
    }
    
    // Guardar en window.config para que otros scripts accedan
    window.BASE_PATH = basePath;
    window.API_BASE = basePath;
    
    console.log('🔧 Configuración de base path:', {
        pathname,
        basePath,
        apiBase: window.API_BASE
    });
})();
