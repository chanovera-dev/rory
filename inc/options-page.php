<?php
/**
 * Theme Options Page
 *
 * Implements a custom settings page in the WordPress admin panel
 * to manage theme-specific configurations.
 *
 * @package Rory
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the Theme Options page in the admin menu.
 */
function rory_add_options_page()
{
    add_menu_page(
        __('Datos del tema', 'rory'), // Page title
        __('Datos del tema', 'rory'), // Menu title
        'manage_options',                // Capability
        'rory-options',                  // Menu slug
        'rory_render_options_page',      // Callback function
        'dashicons-admin-generic',       // Icon
        60                               // Position
    );
}
add_action('admin_menu', 'rory_add_options_page');

/**
 * Register settings, sections, and fields.
 */
function rory_register_settings()
{
    register_setting('rory_options_group', 'rory_ga_id', array(
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => 'G-0000000000',
    ));

    register_setting('rory_options_group', 'rory_bio', array(
        'type' => 'string',
        'sanitize_callback' => 'wp_kses_post',
        'default' => __('Estudiante y fanatico de la cultura y estilo de arte asiatico estilizado, me gusta crear cosas que se vean lindas o cool.', 'rory'),
    ));

    add_settings_section(
        'rory_site_data_section',
        __('Datos del Sitio', 'rory'),
        'rory_section_callback',
        'rory-options'
    );

    add_settings_field(
        'rory_ga_id',
        __('Google Analytics ID', 'rory'),
        'rory_ga_id_render',
        'rory-options',
        'rory_site_data_section'
    );

    add_settings_field(
        'rory_bio',
        __('Biografía Corta', 'rory'),
        'rory_bio_render',
        'rory-options',
        'rory_site_data_section'
    );
}
add_action('admin_init', 'rory_register_settings');

/**
 * Section callback.
 */
function rory_section_callback()
{
    echo '<p>' . __('Define la información básica de tu sitio web.', 'rory') . '</p>';
}

/**
 * Render the Bio field.
 */
function rory_bio_render()
{
    $default = __('Relatos y Cartas es un espacio dedicado a la creatividad y la expresión a través de las palabras. Aquí encontrarás cuentos, microcuentos, poemas e historias que buscan inspirar, emocionar y conectar con los lectores.', 'rory');
    
    // Try to get from option first, then from theme_mod, finally use default.
    $value = get_option('rory_bio');
    if (false === $value) {
        $value = get_theme_mod('rory_bio', $default);
    }

    echo '<textarea name="rory_bio" rows="5" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
    echo '<p class="description">' . __('Este texto aparecerá en el pie de página.', 'rory') . '</p>';
}

/**
 * Render the GA ID field.
 */
function rory_ga_id_render()
{
    $default = 'G-7XNN23WGQT';
    
    // Try to get from option first, then from theme_mod, finally use default.
    $value = get_option('rory_ga_id');
    if (false === $value) {
        $value = get_theme_mod('stories_ga_id', $default);
    }

    echo '<input type="text" name="rory_ga_id" value="' . esc_attr($value) . '" class="regular-text" placeholder="G-XXXXXXXXXX">';
    echo '<p class="description">' . __('Ingresa tu ID de Google Analytics (ej. G-XXXXXXXXXX).', 'rory') . '</p>';
}

/**
 * Render the options page HTML.
 */
function rory_render_options_page()
{
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('rory_options_group');
            do_settings_sections('rory-options');
            submit_button(__('Guardar Cambios', 'rory'));
            ?>
        </form>
    </div>
    <?php
}
