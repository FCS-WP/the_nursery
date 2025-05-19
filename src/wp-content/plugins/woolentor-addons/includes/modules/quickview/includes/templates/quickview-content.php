<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="woolentor-quickview-content-area woocommerce single-product">
	<div id="product-<?php the_ID(); ?>" <?php post_class('product'); ?> >
		<?php do_action( 'woolentor_quickview_image' ); ?>
		<div class="summary entry-summary woolentor-quickview-custom-scroll">
			<?php do_action( 'woolentor_quickview_before_summary' ); ?>
			<div class="summary-content">
				<?php do_action( 'woolentor_quickview_content' ); ?>
			</div>
			<?php do_action( 'woolentor_quickview_after_summary' ); ?>
		</div>
	</div>
</div>