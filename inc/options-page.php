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
 * Returns the available color themes.
 */
function rory_get_color_themes()
{
    return array(
        'default' => array(
            'name' => __('Clásico', 'rory'),
            'colors' => array(
                'base' => '#FFFFFF',
                'contrast' => '#655731',
                'primary' => '#feda7c',
                'secondary' => '#fff4d7',
                'tertiary' => '#fff8e7',
                'background' => '#fffbf0',
                'button' => '#AF3E4D',
                'footer-background' => '#3F0D12',
                'focus' => '#F90093',
                'bullet-active' => '#cbae63',
            ),
        ),
        'dark' => array(
            'name' => __('Oscuro', 'rory'),
            'colors' => array(
                'base' => '#1a1a1a',
                'contrast' => '#ffffff',
                'primary' => '#feda7c',
                'secondary' => '#333333',
                'tertiary' => '#2d2d2d',
                'background' => '#121212',
                'button' => '#FF5252',
                'footer-background' => '#000000',
                'focus' => '#F90093',
                'bullet-active' => '#feda7c',
            ),
        ),
        'ocean' => array(
            'name' => __('Océano', 'rory'),
            'colors' => array(
                'base' => '#f0f8ff',
                'contrast' => '#003366',
                'primary' => '#0077be',
                'secondary' => '#e1f5fe',
                'tertiary' => '#b3e5fc',
                'background' => '#e0f7fa',
                'button' => '#01579b',
                'footer-background' => '#00254d',
                'focus' => '#00bcd4',
                'bullet-active' => '#0077be',
            ),
        ),
        'sakura' => array(
            'name' => __('Sakura', 'rory'),
            'colors' => array(
                'base' => '#fff5f7',
                'contrast' => '#5d3b3e',
                'primary' => '#ffb7c5',
                'secondary' => '#ffe4e8',
                'tertiary' => '#ffd1dc',
                'background' => '#fff0f3',
                'button' => '#d85d6b',
                'footer-background' => '#4a2c2e',
                'focus' => '#ff69b4',
                'bullet-active' => '#ffb7c5',
            ),
        ),
        'forest' => array(
            'name' => __('Bosque', 'rory'),
            'colors' => array(
                'base' => '#f1f8e9',
                'contrast' => '#1b5e20',
                'primary' => '#8bc34a',
                'secondary' => '#dcedc8',
                'tertiary' => '#c5e1a5',
                'background' => '#f9fbe7',
                'button' => '#388e3c',
                'footer-background' => '#1b3320',
                'focus' => '#4caf50',
                'bullet-active' => '#8bc34a',
            ),
        ),
    );
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
 * Enqueue Media Uploader scripts for the options page.
 */
function rory_options_media_scripts($hook) {
    if ('toplevel_page_rory-options' !== $hook) {
        return;
    }
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'rory_options_media_scripts');

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

    register_setting('rory_options_group', 'rory_home_featured_image', array(
        'type' => 'string',
        'sanitize_callback' => 'esc_url_raw',
    ));

    // Color Settings
    $themes = rory_get_color_themes();
    $default_colors = $themes['default']['colors'];

    foreach ($default_colors as $color_id => $default_value) {
        register_setting('rory_options_group', 'rory_color_' . $color_id, array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_hex_color',
            'default' => $default_value,
        ));
    }

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

    add_settings_field(
        'rory_home_featured_image',
        __('Imagen destacada Home (URL)', 'rory'),
        'rory_home_featured_image_render',
        'rory-options',
        'rory_site_data_section'
    );

    // Color Section
    add_settings_section(
        'rory_colors_section',
        __('Colores del Tema', 'rory'),
        'rory_colors_section_callback',
        'rory-options'
    );

    add_settings_field(
        'rory_theme_preset',
        __('Preajustes de Tema', 'rory'),
        'rory_theme_preset_render',
        'rory-options',
        'rory_colors_section'
    );

    $color_labels = array(
        'base' => __('Color Base (Blanco/Claro)', 'rory'),
        'contrast' => __('Color de Contraste (Texto)', 'rory'),
        'primary' => __('Color Primario', 'rory'),
        'secondary' => __('Color Secundario', 'rory'),
        'tertiary' => __('Color Terciario', 'rory'),
        'background' => __('Fondo del Sitio', 'rory'),
        'button' => __('Color de Botón', 'rory'),
        'footer-background' => __('Fondo del Pie de Página', 'rory'),
        'focus' => __('Color de Enfoque (Focus)', 'rory'),
        'bullet-active' => __('Indicador Activo (Bullets)', 'rory'),
    );

    foreach ($color_labels as $color_id => $label) {
        add_settings_field(
            'rory_color_' . $color_id,
            $label,
            'rory_color_render',
            'rory-options',
            'rory_colors_section',
            array('id' => $color_id)
        );
    }
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
 * Render the featured image field.
 */
function rory_home_featured_image_render()
{
    $value = get_option('rory_home_featured_image');
    ?>
    <div class="rory-media-uploader">
        <input type="text" name="rory_home_featured_image" id="rory_home_featured_image" value="<?php echo esc_attr($value); ?>" class="large-text" style="display: none;">
        <div class="rory-media-preview" style="margin-bottom: 10px;">
            <?php if ($value) : ?>
                <img src="<?php echo esc_url($value); ?>" style="max-width: 200px; height: auto; border: 1px solid #ccc; display: block;">
            <?php endif; ?>
        </div>
        <button type="button" class="button rory-upload-button" id="rory_upload_btn"><?php _e('Seleccionar imagen', 'rory'); ?></button>
        <button type="button" class="button rory-remove-button" id="rory_remove_btn" style="<?php echo $value ? '' : 'display:none;'; ?>"><?php _e('Quitar imagen', 'rory'); ?></button>
        <p class="description"><?php _e('Selecciona una imagen de la biblioteca de medios.', 'rory'); ?></p>
    </div>
    <?php
}

/**
 * Colors section callback.
 */
function rory_colors_section_callback()
{
    echo '<p>' . __('Selecciona un preajuste o personaliza cada color individualmente.', 'rory') . '</p>';
}

/**
 * Render Theme Preset selector.
 */
function rory_theme_preset_render()
{
    $themes = rory_get_color_themes();
    echo '<select id="rory_theme_selector">';
    echo '<option value="">' . __('Seleccionar preajuste...', 'rory') . '</option>';
    foreach ($themes as $id => $theme) {
        echo '<option value="' . esc_attr($id) . '" data-colors="' . esc_attr(json_encode($theme['colors'])) . '">' . esc_html($theme['name']) . '</option>';
    }
    echo '</select>';
    echo '<p class="description">' . __('Al seleccionar uno, se actualizarán los selectores de abajo.', 'rory') . '</p>';
}

/**
 * Render individual color picker.
 */
function rory_color_render($args)
{
    $id = $args['id'];
    $themes = rory_get_color_themes();
    $default = $themes['default']['colors'][$id] ?? '#000000';
    $value = get_option('rory_color_' . $id, $default);

    echo '<div class="rory-color-picker-wrapper" style="display:flex; align-items:center; gap:10px;">';
    echo '<input type="color" name="rory_color_' . $id . '" id="rory_color_' . $id . '" value="' . esc_attr($value) . '">';
    echo ' <code>' . esc_html($value) . '</code>';
    echo '<button type="button" class="button rory-reset-color" data-id="' . esc_attr($id) . '" data-default="' . esc_attr($default) . '">' . __('Resetear', 'rory') . '</button>';
    echo '</div>';
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
    <script>
        document.getElementById('rory_theme_selector').addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            if (!selected.value) return;

            var colors = JSON.parse(selected.getAttribute('data-colors'));
            for (var id in colors) {
                var input = document.getElementById('rory_color_' + id);
                if (input) {
                    input.value = colors[id];
                    // Tambien actualizar el label de texto si existe
                    var code = input.nextElementSibling;
                    if (code && code.tagName === 'CODE') {
                        code.textContent = colors[id];
                    }
                }
            }
        });

        // Lógica para los botones de resetear
        document.querySelectorAll('.rory-reset-color').forEach(function(button) {
            button.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var defaultValue = this.getAttribute('data-default');
                var input = document.getElementById('rory_color_' + id);
                if (input) {
                    input.value = defaultValue;
                    var code = input.nextElementSibling;
                    if (code && code.tagName === 'CODE') {
                        code.textContent = defaultValue;
                    }
                }
            });
        });

        // Actualizar el texto del hex cuando cambia el color picker manualmente
        document.querySelectorAll('input[type="color"]').forEach(function(picker) {
            picker.addEventListener('input', function() {
                var code = this.nextElementSibling;
                if (code && code.tagName === 'CODE') {
                    code.textContent = this.value;
                }
            });
        });

        // --- Lógica del Media Uploader ---
        jQuery(document).ready(function($) {
            var mediaUploader;
            $('#rory_upload_btn').click(function(e) {
                e.preventDefault();
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                mediaUploader = wp.media({
                    title: '<?php _e("Seleccionar Imagen", "rory"); ?>',
                    button: { text: '<?php _e("Usar esta imagen", "rory"); ?>' },
                    multiple: false
                });
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#rory_home_featured_image').val(attachment.url);
                    $('.rory-media-preview').html('<img src="' + attachment.url + '" style="max-width: 200px; height: auto; border: 1px solid #ccc; display: block;">');
                    $('#rory_remove_btn').show();
                });
                mediaUploader.open();
            });

            $('#rory_remove_btn').click(function(e) {
                e.preventDefault();
                $('#rory_home_featured_image').val('');
                $('.rory-media-preview').empty();
                $(this).hide();
            });
        });
    </script>
    <?php
}
