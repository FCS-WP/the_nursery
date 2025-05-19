<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( wp_is_block_theme() ) {
    wp_site_icon();
    // wp_head();
    block_template_part('header');
    wp_head();
} else {
    get_header();
}

if( wp_is_block_theme() ){ 
    ?><body <?php body_class(); ?>><?php
}


do_action( 'woolentor_builder_before_content' );


$width = apply_filters( 'woolentor_builder_template_width', 1200 );

if ( $width ) {
    echo '<div class="woolentor-template-default" style="max-width: '.esc_attr($width).'px; margin: 0 auto;">';
}

    while ( have_posts() ) { 
        the_post();
        do_action('woolentor/builder/content');
    }

if ( $width ) {
    echo '</div>';
}

do_action( 'woolentor_builder_after_content' );

if ( wp_is_block_theme() ) {
    // wp_footer();
    block_template_part('footer');
    wp_footer();
} else {
    get_footer();
}
if( wp_is_block_theme() ){ 
	echo '</body>';
}