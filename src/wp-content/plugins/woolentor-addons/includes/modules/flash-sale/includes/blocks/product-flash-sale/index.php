<?php
use Woolentor\Modules\FlashSale\Woolentor_Flash_Sale as WooLentorFlashSale;
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings = $attributes;

$is_editor = ( isset( $_GET['is_editor_mode'] ) && $_GET['is_editor_mode'] == 'yes' ) ? true : false;

$uniqClass 	 = 'woolentorblock-'.$settings['blockUniqId'];
$areaClasses = [$uniqClass, 'woolentor_block_flash_sale_product'];
!empty( $settings['align'] ) ? $areaClasses[] = 'align'.$settings['align'] : '';
!empty( $settings['className'] ) ? $areaClasses[] = esc_attr( $settings['className'] ) : '';

!empty( $settings['columns']['desktop'] ) ? $areaClasses[] = 'woolentor-grid-columns-'.$settings['columns']['desktop'] : 'woolentor-grid-columns-4';
!empty( $settings['columns']['laptop'] ) ? $areaClasses[] = 'woolentor-grid-columns-laptop-'.$settings['columns']['laptop'] : 'woolentor-grid-columns-laptop-3';
!empty( $settings['columns']['tablet'] ) ? $areaClasses[] = 'woolentor-grid-columns-tablet-'.$settings['columns']['tablet'] : 'woolentor-grid-columns-tablet-2';
!empty( $settings['columns']['mobile'] ) ? $areaClasses[] = 'woolentor-grid-columns-mobile-'.$settings['columns']['mobile'] : 'woolentor-grid-columns-mobile-1';


// Countdown
$show_countdown     = (isset($settings['showSountdown']) && $settings['showSountdown']) ? "yes" : 'no' ;
$countdown_style    = !empty($settings['countdownStyle']) ? $settings['countdownStyle'] : '1';
$countdown_style    = $countdown_style == '2' ? 'flip' : 'default';
$countdown_position = !empty( $settings['countdownPosition'] ) ? $settings['countdownPosition'] : 'content_top';
$countdown_position_class = [
    'left'           => 'woolentor-flash-product-offer-pos-t-left',
    'right'          => 'woolentor-flash-product-offer-pos-t-right',
    'bottom'         => 'woolentor-flash-product-offer-pos-t-bottom',
    'content_top'    => '',
    'content_bottom' => 'woolentor-flash-product-offer-pos-c-bottom',
];
$countdown_title    = !empty( $settings['countdownTitle'] ) ? $settings['countdownTitle'] : false;
$show_custom_lavel  = !empty( $settings['customLabels'] ) ? $settings['customLabels'] : false;

$data_customlavel = [];
if( $show_countdown == 'yes' ){
    $data_customlavel['daytxt']     = $show_custom_lavel && !empty( $settings['customlabelDays'] ) ? esc_html($settings['customlabelDays']) : __('Days', 'woolentor');
    $data_customlavel['hourtxt']    = $show_custom_lavel && !empty( $settings['customlabelHours'] ) ? esc_html($settings['customlabelHours']) : __('Hours', 'woolentor');
    $data_customlavel['minutestxt'] = $show_custom_lavel && !empty( $settings['customlabelMinutes'] ) ? esc_html($settings['customlabelMinutes']) : __('Min', 'woolentor');
    $data_customlavel['secondstxt'] = $show_custom_lavel && !empty( $settings['customlabelSeconds'] ) ? esc_html($settings['customlabelSeconds']) : __('Sec', 'woolentor');
}

// Stock Progress bar
$show_stock_progress   = ( isset($settings['showStockProgress']) && $settings['showStockProgress'] ) ? "yes" : 'no' ;
$sold_custom_text      = !empty( $settings['soldCustomText'] ) ? $settings['soldCustomText'] : __('Sold:', 'woolentor');
$available_custom_text = !empty( $settings['availableCustomText'] ) ? $settings['availableCustomText'] : __('Available:', 'woolentor');

// Get deal
$selected_deal = !empty( $settings['selectedDeal'] ) ? ($settings['selectedDeal']-1) : -1;
$deals         = woolentorBlocks_get_option('deals', 'woolentor_flash_sale_settings');
$deal          = !empty($deals[$selected_deal]) ? $deals[$selected_deal] :[];

// Query Argument
$per_page           = $settings['perPage'];
$custom_order_ck    = $settings['customOrder'];
$orderby            = $settings['orderBy'];
$order              = $settings['order'];

$query_args = [
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'ignore_sticky_posts' => 1,
    'posts_per_page'      => $per_page,
];

// Custom Order
if( $custom_order_ck ){
    $query_args['orderby'] = $orderby;
    $query_args['order'] = $order;
}

$apply_on_all_products = !empty($deal['apply_on_all_products']) ? $deal['apply_on_all_products'] : 'off';
$include_categories    = !empty($deal['categories']) ? $deal['categories'] : [];
$include_products      = !empty($deal['products']) ? $deal['products'] : [];
$exclude_products      = !empty($deal['exclude_products']) ? $deal['exclude_products'] : [];
$product_ids           = [];

// Prepare product ids
if( $apply_on_all_products != 'on' ){

    if( $include_categories ){
        $query_1 = new \WP_Query( [
            'post_type'   => 'product',
            'post_status' => 'publish',
            'fields'      => 'ids',
            'tax_query'   => [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    =>  $include_categories,
                ],
                // grouped product is not supported right now
                'relation' => 'AND',
                [
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => ['simple', 'external','variable'],
                ],
            ],

        ]);

        $product_ids = $query_1->posts;
    }

    if( $include_products ){
        $product_ids = array_merge($product_ids, $include_products);
    }

    if( $exclude_products ){
        $product_ids = array_intersect($product_ids, $exclude_products);
    }

} elseif( $exclude_products ){
    $query_args['post__not_in'] = $exclude_products;
}

$found_products = false;
$deal_status = !empty($deal['status']) ? $deal['status'] : 'off';
if( $deal_status == 'on' && WooLentorFlashSale::user_validity($deal) && WooLentorFlashSale::datetime_validity($deal) ){
    if( $apply_on_all_products == 'on' ){
        $found_products = true;
    } elseif( $product_ids ){
        $found_products = true;
        $query_args['post__in'] = $product_ids;
    }
}


echo '<div class="'.esc_attr( implode(' ', $areaClasses ) ).'">';
    if( $found_products ){
        echo '<div class="ht-products woocommerce woolentor-grid '.( $settings['noGutter'] === true ? 'woolentor-no-gutters' : '' ).'">';


        $products = new \WP_Query( $query_args );
        if( $products->have_posts() ):

            // Countdown remaining time
            $remaining_time = WooLentorFlashSale::get_remaining_time($deal);

            while( $products->have_posts() ): $products->the_post();
                global $product;
                $product_id = $product->get_id();
                $ajax_add_to_cart_class  = $product->is_purchasable() && $product->is_in_stock() ? ' add_to_cart_button' : '';
                $ajax_add_to_cart_class .= $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? ' ajax_add_to_cart' : '';
        
                ?>
                    <div class="woolentor-grid-column">
                        <div class="woolentor-flash-product <?php echo esc_attr($countdown_position_class[$countdown_position]) ?>">

                            <div class="woolentor-flash-product-thumb">
                                <a href="<?php the_permalink(); ?>">
                                    <div class="woolentor-flash-product-image">
                                        <?php 
                                            woolentor_sale_flash();
                                            woocommerce_template_loop_product_thumbnail();
                                        ?>
                                    </div>
                                </a>

                                <?php if($show_countdown == 'yes'): ?>
                                    <div class="woolentor-countdown woolentor-countdown-<?php echo esc_attr($countdown_style); ?>" data-countdown="<?php echo esc_attr( $remaining_time ) ?>" data-customlavel='<?php echo wp_json_encode( $data_customlavel ) ?>'></div>
                                <?php endif; ?>


                                <ul class="woolentor-flash-product-action">
                                    <li><a href="<?php echo esc_url($product->add_to_cart_url()) ?>" data-quantity="1" class="woolentor-flash-product-action-btn <?php echo esc_attr($ajax_add_to_cart_class); ?>" data-product_id="<?php echo esc_attr($product_id); ?>"><i class="fa fa-shopping-cart"></i></a></li>

                                    <?php
                                        if( true === woolentor_has_wishlist_plugin() ){
                                            echo '<li>'.woolentor_add_to_wishlist_button('<i class="fa fa-heart"></i>','<i class="fa fa-heart"></i>').'</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        }
                                    ?>   

                                    <?php
                                        if( function_exists('woolentor_compare_button') && true === woolentor_exist_compare_plugin() ){
                                            echo '<li>';
                                                woolentor_compare_button(
                                                    [
                                                        'style'=>2,
                                                        'btn_text'=>'<i class="fa fa-exchange"></i>',
                                                        'btn_added_txt'=>'<i class="fa fa-exchange"></i>' 
                                                    ]
                                                );
                                        echo '</li>';
                                        }
                                    ?>
                                    <?php if( true === woolentor_has_quickview() ): ?>
                                        <li>
                                            <a href="#" class="woolentor-flash-product-action-btn woolentorquickview" data-product_id="<?php echo esc_attr($product_id);?>" <?php echo wc_implode_html_attributes( ['aria-label'=>$product->get_title()] ); ?>>
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>

                            <div class="woolentor-flash-product-content">

                                <?php if($show_countdown == 'yes'): ?>
                                    <div class="woolentor-flash-product-offer-timer">
                                        
                                        <?php if($countdown_title): ?>
                                        <p class="woolentor-flash-product-offer-timer-text"><?php echo wp_kses_post($countdown_title) ?></p>
                                        <?php endif; ?>

                                        
                                        <div class="woolentor-countdown woolentor-countdown-<?php echo esc_attr($countdown_style); ?>" data-countdown="<?php echo esc_attr( $remaining_time ) ?>" data-customlavel='<?php echo wp_json_encode( $data_customlavel ) ?>'></div>
                                    </div>
                                <?php endif; ?>

                                <?php
                                    $manage_stock  = get_post_meta( $product_id, '_manage_stock', true );
                                    $initial_stock = get_post_meta( $product_id, 'woolentor_total_stock_quantity', true );

                                    if($show_stock_progress == 'yes' && $manage_stock == 'yes' && $initial_stock):
                                        $current_stock = get_post_meta( $product_id, '_stock', true );
                                        $total_sold    = $initial_stock > $current_stock ? $initial_stock - $current_stock : 0;
                                        $percentage    = $total_sold > 0 ? round( $total_sold / $initial_stock * 100 ) : 0;

                                        if($current_stock >= 0):
                                    ?>
                                    <div class="woolentor-flash-product-progress">
                                        <div class="woolentor-flash-product-progress-total">
                                            <div class="woolentor-flash-product-progress-sold" style="width: <?php echo esc_attr($percentage) ?>%;"></div>
                                        </div>
                                        <div class="woolentor-flash-product-progress-label"><span><?php echo esc_html($sold_custom_text) ?> <?php echo esc_html($total_sold) ?></span><span><?php echo esc_html($available_custom_text) ?> <?php echo esc_html($current_stock); ?></span></div>
                                    </div>
                                    <?php endif; ?>

                                <?php elseif($show_stock_progress && $manage_stock == 'yes' && !$initial_stock): ?>
                                    <div class="woolentor-flash-product-progress woolentor-stock-message">
                                        <span><?php echo esc_html__( 'To show the stock progress bar. Set the initial stock amount from', 'woolentor' ) ?></span> <a href="<?php echo esc_url(get_edit_post_link( $product_id )) ?>" target="_blank"><b> <?php echo esc_html__( 'Here', 'woolentor' ); ?></b></a>
                                    </div>
                                <?php endif; ?>

                                <h3 class="woolentor-flash-product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                                <?php
                                    $has_rating_class = $product->get_average_rating() ? '' : 'woolentor-product-has-no-rating';
                                ?>
                                <div class="woolentor-flash-product-price-rating <?php echo esc_attr($has_rating_class) ?>">
                                    <div class="woolentor-flash-product-price">
                                        <?php
                                            if( $product->get_type() != 'variable' ){
                                                
                                                echo '<div class="price">' .wc_format_sale_price( wc_get_price_to_display( $product ), WooLentorFlashSale::get_calculated_price($product_id, $deal) ) . $product->get_price_suffix() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                                            } elseif($product->get_type() == 'variable') {
                                                $price_min_o        = wc_get_price_to_display( $product, [ 'price' => $product->get_variation_regular_price( 'min' ) ] );
                                                $price_min        = WooLentorFlashSale::get_calculated_price($product_id, $deal, $price_min_o);
                                                $price_max_o        = wc_get_price_to_display( $product, [ 'price' => $product->get_variation_regular_price( 'max' ) ] );
                                                $price_max        = WooLentorFlashSale::get_calculated_price($product_id, $deal, $price_max_o);
                                                $price_html       = wc_format_price_range( $price_min, $price_max );

                                                if($price_min == $price_max){
                                                    echo '<div class="price">' .wc_format_sale_price( $price_max_o, $price_max) . $product->get_price_suffix() . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                } else{
                                                    echo '<div class="price">' .wp_kses_post($price_html) . '</div>';
                                                }
                                            }
                                        ?>
                                    </div>

                                    <?php if($product->get_average_rating()): ?>
                                        <div class="woolentor-flash-product-rating"><i class="eicon-star"></i> <span><?php echo esc_html($product->get_average_rating()); ?></span></div>
                                    <?php endif; ?>
                                </div>

                            </div>

                        </div>
                    </div>
                <?php

            endwhile; wp_reset_query(); wp_reset_postdata(); endif;

        echo '</div>';
    }else{
        echo '<strong>' . esc_html__( 'Unfortunately, no products were found in the deal you selected.', 'woolentor' ) . '</strong>';
    }
echo '</div>';