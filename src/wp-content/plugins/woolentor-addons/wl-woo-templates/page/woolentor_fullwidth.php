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

$width = apply_filters( 'woolentor_builder_template_width', 1200 );
?>
<?php 
	if( wp_is_block_theme() ){ 
		?><body <?php body_class(); ?>><?php
	}
?>
	<div class="woolentor-template-container" style="margin:0 auto; max-width:<?php echo $width ? esc_attr($width).'px; padding: 0 15px;' : '100%;'; ?>">
		<?php
			while ( have_posts() ) { 
				the_post();
				do_action('woolentor/builder/content');
			}
		?>
	</div>
<?php
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
