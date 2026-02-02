<?php
/**
 * Template Name: Homepage Custom Filter
 * Template Post Type: page
 * 
 * Plantilla de inicio con filtros AJAX por categoría y toggle NSFW.
 */

get_header(); 

// Obtener todas las categorías ordenadas
$categories = get_categories([
    'orderby' => 'name',
    'order'   => 'ASC',
    'hide_empty' => true
]);
?>

<main id="main" class="site-main homepage-wrapper" role="main">
    <!-- SECCIÓN DE FILTROS -->
    <section class="block filters-section">
        <div class="content">
            <div class="home-filters-control">
                
                <!-- 1. Filtro Categorías -->
                <div class="categories-filter-wrapper">
                    <ul class="cat-filters-list">
                        <li>
                            <button type="button" class="cat-filter-btn active" data-cat-id="0">
                                <?php esc_html_e('Todos', 'rory'); ?>
                            </button>
                        </li>
                        <?php foreach($categories as $cat): ?>
                            <li>
                                <button type="button" class="cat-filter-btn" data-cat-id="<?php echo esc_attr($cat->term_id); ?>">
                                    <?php echo esc_html($cat->name); ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- 2. Toggle NSFW -->
                <div class="nsfw-filter-wrapper">
                    <div class="nsfw-toggle-wrapper">
                        <label class="toggle-switch">
                            <input type="checkbox" id="nsfw-toggle-input">
                            <span class="slider"></span>
                        </label>
                        <label for="nsfw-toggle-input" class="nsfw-toggle-label">
                            <?php esc_html_e('NSFW', 'rory'); ?>
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- GRID DE RESULTADOS -->
    <section class="block posts--body">
        <div class="content">
            <div id="ajax-posts-container">
                <?php
                // Query Inicial (Solo Posts normales, Status publish)
                // Reproducimos el estado inicial "Todos + No NSFW"
                $initial_args = [
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'posts_per_page' => 12, // Forzamos 12 posts
                    'orderby'        => 'date',
                    'order'          => 'DESC'
                ];
                $initial_query = new WP_Query($initial_args);

                if ($initial_query->have_posts()) :
                    while ($initial_query->have_posts()) :
                        $initial_query->the_post();
                        // Importante: El wrapper ahora está dentro del template part
                        get_template_part('template-parts/loop/content', 'ajax');
                    endwhile;
                else :
                    echo '<div class="no-results">' . esc_html__('No hay contenido para mostrar.', 'rory') . '</div>';
                endif;
                
                // Guardar max_pages para pasarlo al botón inicial
                $max_pages = $initial_query->max_num_pages;
                wp_reset_postdata();
                ?>
            </div>

            <!-- Botón de Paginación -->
            <div class="pagination-wrapper" style="text-align: center; margin-top: 2rem;">
                <button id="load-more-btn" class="btn" 
                    data-page="1" 
                    data-max-pages="<?php echo esc_attr($max_pages); ?>"
                    style="<?php echo ($max_pages <= 1) ? 'display: none;' : ''; ?>">
                    <span class="btn-text"><?php esc_html_e('Cargar más', 'rory'); ?></span>
                    <span class="btn-loader spinner" style="display: none;"></span>
                </button>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>