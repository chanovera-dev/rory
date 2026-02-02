<?php
/**
 * Template part for displaying posts in a Justified Grid
 *
 * @package Rory
 * @since 1.1.0
 */

// Calcular Aspect Ratio
$ratio = 1; // Default cuadrado
$width = 300;
$height = 300;

if (has_post_thumbnail()) {
    $img_data = wp_get_attachment_image_src(get_post_thumbnail_id(), 'medium_large');
    if ($img_data) {
        $width = $img_data[1];
        $height = $img_data[2];
        // Evitar división por cero
        if ($height > 0) {
            $ratio = $width / $height;
        }
    }
}
?>

<!-- Wrapper con Aspect Ratio calculado -->
<div class="ajax-item-wrapper" 
    style="flex-grow: <?php echo esc_attr($ratio * 100); ?>; flex-basis: calc( var(--row-height, 250px) * <?php echo esc_attr($ratio); ?> );" 
    data-ratio="<?php echo esc_attr($ratio); ?>"
    data-year="<?php echo get_the_date('Y'); ?>">
    
    <article id="post-<?php the_ID(); ?>" <?php post_class('justified-post'); ?> style="padding-bottom: <?php echo esc_attr((1 / $ratio) * 100); ?>%;">
        <a class="post--permalink btn-pagination small-pagination glass-backdrop" href="<?php the_permalink(); ?>" style="position: absolute; top: 0; right: 0; z-index: 2;"
            aria-label="Ver la galería de <?= esc_attr(the_title()); ?>">
            <?= rory_get_icon('permalink'); ?>
        </a>
            <?php
            // Lógica condicional: O es Galería O es Imagen estática
            if ( get_post_format() === 'gallery' ) :
                
                // Incluir helper si no existe
                if (!function_exists('rory_extract_gallery_images')) {
                    require_once get_template_directory() . '/templates/helpers/extract-gallery-images.php';
                }
                $ids = rory_extract_gallery_images(get_the_ID());
                ?>

                <div class="gallery-wrapper" style="width: 100%; height: 100%; position: absolute; inset: 0;">
                    <div class="gallery" style="display: flex;">
                        <?php if (!empty($ids)) : foreach ($ids as $id) : ?>
                            <div class="slide" style="width: 100%; height: 100%;">
                                <?php echo wp_get_attachment_image($id, 'large', false, ['style' => 'width: 100%; height: 100%; object-fit: cover; display: block; position: absolute; inset: 0;']); ?>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                    
                    <div class="gallery-navigation" style="display: flex; align-items: center; position: absolute; bottom: 10px; left: 10px; z-index: 10; width: calc(100% - 20px);">
                        <button class="gallery-prev btn-pagination small-pagination glass-backdrop" aria-label="Anterior"><?= rory_get_icon('backward'); ?></button>
                        <div class="bullets" style="flex-grow: 1; display: flex; justify-content: center;"></div>
                        <button class="gallery-next btn-pagination small-pagination glass-backdrop" aria-label="Siguiente"><?= rory_get_icon('forward'); ?></button>
                    </div>
                </div>

            <?php else : 
                // NO ES GALERÍA: Mostrar Thumbnail normal
                if (has_post_thumbnail()) {
                    the_post_thumbnail('medium_large', [
                        'class' => 'post-thumbnail', 
                        'alt' => get_the_title(),
                        'loading' => 'lazy'
                    ]);
                }
            endif; 
            ?>
            <div class="post-overlay">
                <h3 class="post-title"><?php the_title(); ?></h3>
            </div>
    </article>
    
</div>