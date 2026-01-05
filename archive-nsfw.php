<?php
/**
 * Archive 'NSFW' Template
 * 
 * This template displays the archive for the 'nsfw' custom post type.
 * It includes a header with the archive title, a loop that lists posts,
 * and standard pagination.
 *
 * @package rory
 * @since 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">

    <?php wp_breadcrumbs(); ?>

    <!-- Archive Posts Section -->
    <section class="block posts--body">
        <div class="content">
            <div class="loop">
                <?php
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        $post_format = get_post_format();
                        $part = 'archive';

                        if ($post_format) {
                            if (locate_template("template-parts/loop/content-{$post_format}.php")) {
                                $part = $post_format;
                            }
                        }

                        get_template_part('template-parts/loop/content', $part);
                    }

                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => rory_get_icon('backward') . ' Anterior',
                        'next_text' => 'Siguiente' . rory_get_icon('forward')
                    ));
                } else {
                    echo '<p>' . esc_html__('No se encontraron artículos', 'stories') . '</p>';
                }
                ?>
            </div>
        </div>
    </section>

</main><!-- .site-main -->

<?php get_footer(); ?>