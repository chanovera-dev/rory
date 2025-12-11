<?php
/**
 * Template part for displaying post tags
 *
 * @package rory
 * @since Rory 1.0.0
 */
?>
<div class="post--tags">
    <?php
    $tags = get_the_tags();
    if ($tags) {
        foreach ($tags as $tag) {
            echo '<a class="post-tag" href="' . esc_url(get_tag_link($tag->term_id)) . '">' . rory_get_icon('tag') . esc_html($tag->name) . '</a>';
        }
    }
    ?>
</div>