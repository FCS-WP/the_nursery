<?php $width = apply_filters( 'woolentor_builder_template_width', 1200 ); ?>
<?php if (wp_is_block_theme()) : wp_head(); ?>
    <div class="wp-site-blocks">
        <header class="wp-block-template-part">
            <?php block_header_area(); ?>
        </header>
        <main class="woolentor-template-default has-global-padding is-layout-constrained wp-block-group" style="margin:0 auto; max-width:<?php echo $width ? esc_attr($width).'px; padding: 0 15px;' : '100%;'; ?>">
            <?php
                while ( have_posts() ) { 
                    the_post();
                    do_action('woolentor/builder/content');
                }
            ?>
        </main>
        <footer class="wp-block-template-part">
            <?php block_footer_area(); ?>
            <?php wp_footer(); ?>
        </footer>
    </div>
<?php else : ?>
    <?php get_header(); ?>
    <div class="wp-site-blocks">
        <div class="woolentor-template-default" style="margin:0 auto; max-width:<?php echo $width ? esc_attr($width).'px; padding: 0 15px;' : '100%;'; ?>">
            <?php
                while ( have_posts() ) { 
                    the_post();
                    do_action('woolentor/builder/content');
                }
            ?>
        </div>
    </div>
    <?php get_footer(); ?>
<?php endif; ?>