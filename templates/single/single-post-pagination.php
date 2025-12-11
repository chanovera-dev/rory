<?php
/**
 * Template part for displaying single post pagination
 *
 * @package rory
 * @since Rory 1.0.0
 */
?>
<nav class="post-navigation" aria-label="<?= esc_attr__('Navegación de publicaciones', 'rory'); ?>">
    <div class="left">
        <?php
        $next_post = get_next_post();
        if ($next_post):
            ?>
            <a href="<?= esc_url(get_permalink($next_post->ID)); ?>" class="next-post-link">
                <p class="pagination-indicator">
                    <?= rory_get_icon('backward') . esc_html__('Siguiente', 'rory'); ?>
                </p>
                <p class="title-post"><?= esc_html($next_post->post_title); ?></p>
            </a>
        <?php endif; ?>
    </div>
    <div class="right">
        <?php
        $prev_post = get_previous_post();
        if ($prev_post):
            ?>

            <a href="<?= esc_url(get_permalink($prev_post->ID)); ?>" class="previous-post-link">
                <p class="pagination-indicator">
                    <?= esc_html__('Anterior', 'rory') . rory_get_icon('forward'); ?>
                </p>
                <p class="title-post"><?= esc_html($prev_post->post_title); ?></p>
            </a>
        <?php endif; ?>
    </div>
</nav>