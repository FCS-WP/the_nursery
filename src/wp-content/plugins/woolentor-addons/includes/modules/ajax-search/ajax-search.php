<?php
namespace Woolentor\Modules\AjaxSearch;
use WooLentor\Traits\Singleton;

class Ajax_Search{
	use Singleton;

	/**
	 * Default Constructor
	 */
	public function __construct() {
		// Ajax Callback
		add_action( 'wp_ajax_woolentor_ajax_search', [ $this, 'ajax_search_callback' ] );
        add_action( 'wp_ajax_nopriv_woolentor_ajax_search', [ $this, 'ajax_search_callback' ] );

		//Register Shortcode
		add_shortcode( 'woolentorsearch', [ $this, 'shortcode' ] );

		// WP Register widget
		add_action( 'widgets_init', [ $this, 'register_widget' ] );

	}

	/**
	 * Register WP Widget
	 */
	public function register_widget(){
		require ( __DIR__ . '/widget-product-search-ajax.php' );
		register_widget( '\Woolentor\Modules\AjaxSearch\Ajax_Search_Widget' );
		// Enqueue Style
		if( !is_admin() ){
			wp_enqueue_style( 'woolentor-ajax-search' );
        	wp_enqueue_script( 'woolentor-ajax-search' );
		}
	}

	/**
	 * Ajax Callback method
	 */
	public function ajax_search_callback(){
		check_ajax_referer('woolentor_psa_nonce', 'nonce');
		$s 		  = isset( $_REQUEST['s'] ) ? sanitize_text_field( $_REQUEST['s'] ) : '';
		$limit 	  = isset( $_REQUEST['limit'] ) ? intval( $_REQUEST['limit'] ) : 10;
		$category = isset( $_REQUEST['category'] ) ? $_REQUEST['category'] : '';

		$args = array(
		    'post_type'     => 'product',
			'post_status'   => 'publish',
		    'posts_per_page'=> $limit,
		    's' 			=> $s
		);

		if( !empty( $category )  ) {

			$categories  = explode(',', trim( $category, ',' ) );
			$clean_data  = array_map( function ( $item ){ return intval( $item ); }, $categories );
	
			$args['tax_query'] = array(
				array(
					'taxonomy'  => 'product_cat',
					'field'     => 'term_id',
					'terms'     => $clean_data,
					'operator'  => 'IN'
				)
			);
		}

		// Exclude Hidden Product
		$args['tax_query'][] = array(
			'taxonomy' 	=> 'product_visibility',
			'field' 	=> 'name',
			'terms' 	=> 'exclude-from-catalog',
			'operator' 	=> 'NOT IN',
		);

		$query = new \WP_Query( $args );

		ob_start();
		echo '<div class="woolentor_psa_inner_wrapper">';

			if( $query->have_posts() ):
				while( $query->have_posts() ): $query->the_post();
					echo $this->search_item(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			    endwhile; // main loop
			    wp_reset_query(); wp_reset_postdata();
			else:
				echo '<p class="text-center woolentor_psa_wrapper woolentor_no_result">'. esc_html__( 'No Results Found', 'woolentor' ) .'</p>';
			endif; // have posts

		echo '</div>';
		echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		wp_die();
	}

	/**
	 * Render Search Item.
	 */
	public function search_item(){
		$searchitem = '';
		ob_start();
		?>
			<div class="woolentor_single_psa">
				<a href="<?php the_permalink(); ?>">
					<?php if( has_post_thumbnail( get_the_id() ) ): ?>
						<div class="woolentor_psa_image">
							<?php the_post_thumbnail('thumbnail'); ?>
						</div>
					<?php endif; ?>
					<div class="woolentor_psa_content">
						<h3><?php echo wp_trim_words( get_the_title(), 5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
						<div class="woolentor_psa_price">
							<?php woocommerce_template_single_price() ?>
						</div>
					</div>
				</a>
			</div>
		<?php
		$searchitem .= ob_get_clean();
		return apply_filters( 'woolentor_ajaxsearch_item', $searchitem );

	}

	/**
	 * Returns the parsed shortcode.
	 */
	public function shortcode( $atts = array(), $content = '' ) {
		
		extract( shortcode_atts( array(
			'limit' 	  	=> 10,
			'placeholder' 	=> esc_html__( 'Search Products', 'woolentor' ),
			'show_category' => false,
			'all_category_text' => esc_html__('All Categories','woolentor')
		), $atts, 'woolentorsearch' ) );

		$data_settings = array(
			'limit'		  => esc_attr( $limit ),
			'wlwidget_id' => '#wluniq-'.uniqid(),
		);

		$category_list = [ '' => $all_category_text ] + woolentor_taxonomy_list( 'product_cat','term_id' );

		$show_category = $show_category == '1' ? true : $show_category;

		$selected_cat = sanitize_text_field( wp_unslash( isset($_GET['product_cat']) ? $_GET['product_cat'] : '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$output = '';
		ob_start();
		?>
        	<div class="woolentor_widget_psa" id="<?php echo esc_attr('wluniq-'.uniqid()); ?>">
	            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-settings='<?php echo wp_json_encode( $data_settings ); ?>'>
					<div class="woolentor_widget_psa_field_area">
						<?php if( $show_category === true ):?>
						<div class="woolentor_widget_psa_category">
							<select name="product_cat">
								<?php
									foreach( $category_list as $cat_key => $cat ){
										$term_object = get_term( $cat_key );
										$term_slug = is_wp_error($term_object) ? "" : ($term_object->slug ? $term_object->slug : "");

										echo '<option value="'.esc_attr( $term_slug ).'" data-value="'.esc_attr($cat_key).'" '.selected( ($selected_cat === $term_slug), true, false ).'>'.esc_html( $cat ).'</option>';
									}
								?>
							</select>
						</div>
						<?php endif; ?>
						<div class="woolentor_widget_psa_input_field">
							<input type="search" placeholder="<?php echo esc_attr__( $placeholder, 'woolentor' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
							<input type="hidden" name="post_type" value="product" />
							<span class="woolentor_widget_psa_clear_icon"><i class="sli sli-close"></i></span>
							<span class="woolentor_widget_psa_loading_icon"><i class="sli sli-refresh"></i></span>
						</div>
						<button type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'woolentor' ); ?>" aria-label="<?php echo esc_attr__( 'Search', 'woolentor' );?>">
							<i class="sli sli-magnifier"></i>
						</button>
					</div>
	                <div id="woolentor_psa_results_wrapper"></div>
	            </form>
	        </div>
		<?php
		$output .= ob_get_clean();
		return apply_filters( 'woolentor_ajaxsearch', $output );
	}

}

Ajax_Search::instance();