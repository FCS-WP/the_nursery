<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;

// Product data pass from Template args
if ( empty( $product ) ) { $product = $product_data; }

$attachment_ids = $product->get_gallery_image_ids();

$attachment_count = count( $attachment_ids );
?>
<div class="images">
	
	<div class="woocommerce-product-gallery__wrapper <?php echo ( 'slider' === $thumbnail_layout ? 'woolentor-quickview-main-image-slider' : '' ); ?>">
		<?php
			$attributes = [
				'title' => esc_attr( get_the_title( get_post_thumbnail_id() ) )
			];

			if ( has_post_thumbnail( $product->get_id() ) ) {

				$thumbnail_image_src = wp_get_attachment_image_src($product->get_image_id(), 'woocommerce_single' );

				echo '<figure class="woolentor-quickview-product-image-wrap woocommerce-product-gallery__image">' . get_the_post_thumbnail( $product->get_id(), apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ), $attributes ) . '</figure>';


				if ( $attachment_count > 0 && 'slider' === $thumbnail_layout ) {
					foreach ( $attachment_ids as $attachment_id ) {
						echo '<div class="woolentor-quickview-product-image-wrap"><figure class="woocommerce-product-gallery__image">' . wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ) ) . '</figure></div>';
					}
				}

			} else {
				echo '<figure class="woocommerce-product-gallery__image--placeholder">' . apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woolentor' ) ), $product->get_id() ) . '</figure>';
			}
		?>
	</div>

	<?php if( 'slider' === $thumbnail_layout ): ?>
		<div class="woolentor-quickview-thumbnail-slider">
	        <?php
	            if ( has_post_thumbnail( $product->get_id() ) ) {

					$thumbnail_image_src = wp_get_attachment_image_src($product->get_image_id(), 'woocommerce_single' );
	                echo '<div class="woolentor-quickview-thumb-single"><img src=" '.$thumbnail_image_src[0].' " alt="'.esc_attr( get_the_title() ).'"></div>';

	                if ( $attachment_count > 0 ) {
						foreach ( $attachment_ids as $attachment_id ) {
	                        $thumbnail_src = wp_get_attachment_image_src( $attachment_id, 'woocommerce_single' );
	                        echo '<div class="woolentor-quickview-thumb-single"><img src=" '.$thumbnail_src[0].' " alt="'.esc_attr( get_the_title() ).'"></div>';
						}
					}

				}else{
					echo '<div class="woocommerce-product-gallery__image--placeholder">' . apply_filters( 'woocommerce_gallery_thumbnail', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woolentor' ) ), $product->get_id() ) . '</div>';
				}

	        ?>
	    </div>
	<?php endif; ?>

</div>
