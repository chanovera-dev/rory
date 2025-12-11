<?php
/**
 * Template part for displaying content posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package rory
 * @since Rory 1.0.0
 */

if (has_post_thumbnail()) {
    echo get_the_post_thumbnail(null, 'full', ['alt' => get_the_title(), 'loading' => 'lazy']);
}

the_content();

wp_link_pages(
    array(
        'before' => '<div class="page-links">' . __('Páginas:', 'rory'),
        'after' => '</div>',
    )
);