# Tema WordPress Rory

Rory es un tema de WordPress personalizado, ligero y centrado en la estética, diseñado para "ZuKy's Art". Enfatiza una experiencia limpia y centrada en la lectura, con soporte para varios formatos de publicación y una jerarquía visual distinta adecuada para historias, poemas y contenido multimedia.

## Características

*   **Diseño Responsivo:** Construido con un enfoque "mobile-first" pero optimizado para todos los tamaños de pantalla.
*   **Formatos de Publicación Personalizados:** Diseños y estilos especializados para:
    *   Entradas Estándar
    *   Minientradas (Asides)
    *   Galerías con comportamiento personalizado de slider/lightbox
    *   Dibujos (Imágenes)
    *   Videos
    *   Citas
    *   Artículos externos (Enlaces)
*   **Migas de Pan Dinámicas (Breadcrumbs):** Navegación de migas de pan personalizada que maneja jerarquías profundas de categorías y archivos de formatos de publicación personalizados.
*   **Contenido Relacionado:** Sugerencia automática de publicaciones relacionadas basada en etiquetas y categorías al final de las entradas individuales.
*   **Sistema de Diseño:**
    *   Uso de **Variables CSS** para una tematización consistente (colores, espaciado, fuentes).
    *   Integración con `theme.json` para soporte del editor de bloques Gutenberg.
    *   Página de Error 404 con estilo personalizado y carga dinámica de recursos.
    *   Efectos de Glassmorphism y animaciones sutiles (`animate-in`).
*   **Rendimiento:**
    *   Iconos SVG utilizados directamente en PHP para una carga óptima (sin librerías de fuentes de iconos externas).
    *   Carga condicional de recursos (los archivos CSS/JS solo se cargan en las páginas donde son necesarios).
*   **Accesibilidad:** Estructura semántica HTML5 (header, main, footer, article, nav).

## Instalación

1.  Sube la carpeta `rory` a tu directorio `/wp-content/themes/`.
2.  Activa el tema a través del menú 'Apariencia' en WordPress.
3.  Asegúrate de que tus enlaces permanentes estén configurados como 'Nombre de la entrada' para estructuras de URL óptimas.

## Desarrollo

### Estructura de Directorios

*   `assets/`: Contiene todos los recursos estáticos (CSS, JS, Iconos, Imágenes).
*   `template-parts/`: Partes de plantilla reutilizables (encabezado, pie de página, bucles).
    *   `loop/`: Plantillas de contenido para diferentes formatos de publicación.
    *   `page/`: Plantillas de contenido para páginas.
    *   `single/`: Plantillas de contenido para entradas individuales.
*   `templates/`: Plantillas específicas para funcionalidades como publicaciones relacionadas o listas de etiquetas.
*   `functions.php`: Lógica del tema, encolado de scripts/estilos y soporte de funcionalidades.
*   `theme.json`: Configuraciones globales y estilos para el Editor de Bloques.

### Personalización

*   **Colores y Fuentes:** Modifica `theme.json` o `assets/css/wp-root.css` para ajustar los tokens de diseño principales.
*   **Iconos:** Todos los iconos se gestionan a través de la función `rory_get_icon()` en `functions.php`.

## Créditos

*   **Autor:** Chanovera Dev
*   **Versión:** 1.0.0
