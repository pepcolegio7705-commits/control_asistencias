# Directiva: Landing Page (Control de Asistencias)

Esta directiva define los lineamientos de diseño visual, arquitectura HTML y estilos CSS para la página de aterrizaje del módulo de Asistencias.

## El Bucle Central de Desarrollo (Mis Reglas de Agente)
1. **Consultar:** Revisar esta directiva antes de realizar modificaciones visuales o de estructura en la landing.
2. **Ejecutar:** Aplicar cambios en `landing/index.html` asegurando mantener el estándar Premium (WOW factor).
3. **Observar y Aprender:** Si se rompe el diseño responsivo o hay conflictos de estilos, corregir e incorporar aquí la regla preventiva.

## Módulo: Landing Page (UI/UX)
### Objetivo
Brindar una primera impresión de alto nivel (Premium), mostrando de forma rápida y atractiva las bondades del sistema de Asistencias para instituciones modernas.

### Lógica y Estructura Visual
- **Colores:**
  - Primario: Azul índigo (`#2563eb` a `#1e40af` en gradientes).
  - Secundario / Acentos: Turquesa vibrante (`#06b6d4`) o Naranja suave.
  - Fondos: Blanco y grises ultra claros (`#f8fafc`) para maximizar contraste y limpieza.
- **Tipografía:**
  - Principal: `Inter` (importada de Google Fonts), con variaciones de peso (400, 600, 800) para crear jerarquías claras.
- **Efectos:**
  - **Glassmorphism:** En el Navbar, usando `backdrop-filter: blur(10px)` con fondo semi-transparente.
  - **Micro-animaciones:** Efectos `hover` suaves (`transition: all 0.3s ease`) en botones y tarjetas que las eleven ligeramente (`transform: translateY(-5px)`).

### Restricciones / Casos Borde
- **Recursos Externos:** Utilizar CDN para Bootstrap (CSS/JS), FontAwesome y Google Fonts para evitar cargar el repositorio con assets estáticos pesados.
- **Rutas Relativas:** 
  - Las imágenes (como el logo `sintek_logo.png`) deben enlazarse correctamente hacia el directorio raíz (`../sintek_logo.png`).
  - El botón de probar Demo debe apuntar a la raíz de la app (`../index.php`).
  - El enlace de volver al hub principal debe ser `../../sintek_landing_page/index.html` (manteniendo la compatibilidad del entorno local del usuario).
- **Responsividad:** 
  - Todo grid debe colapsar correctamente en móviles (usando clases de Bootstrap como `col-12 col-md-6 col-lg-3`).
  - Las animaciones en tarjetas no deben causar scroll horizontal o rebalse oculto (overflow).
- **Semántica:** Evitar usar divs para todo; aprovechar secciones (`<section>`), encabezados (`<header>`) y pies de página (`<footer>`).
