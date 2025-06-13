<?php
add_action('woocommerce_before_shop_loop_item_title', 'custom_shop_loop_product_image', 10);

function custom_shop_loop_product_image()
{
    if (!is_shop()) return;

    global $product;

    $main_image_id = $product->get_image_id();
    $gallery_ids = $product->get_gallery_image_ids();
    $hover_image_id = !empty($gallery_ids) ? $gallery_ids[0] : $main_image_id;

    echo '<div class="custom-product-image-wrap">';
    echo wp_get_attachment_image($main_image_id, 'woocommerce_thumbnail', false, ['class' => 'custom-thumb main-image']);
    echo wp_get_attachment_image($hover_image_id, 'woocommerce_thumbnail', false, ['class' => 'custom-thumb hover-image']);
    echo '</div>';
}

add_action('pre_get_posts', 'exclude_category_from_berocket_filter');
function exclude_category_from_berocket_filter($query)
{
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if (isset($_GET['filters']) || isset($_GET['filter'])) {
        $tax_query = $query->get('tax_query');
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => array('workshop'),
            'operator' => 'NOT IN'
        );
        $query->set('tax_query', $tax_query);
    }
}


add_action('wp_ajax_add_to_cart_combo', 'handle_add_to_cart_combo');
add_action('wp_ajax_nopriv_add_to_cart_combo', 'handle_add_to_cart_combo');

function handle_add_to_cart_combo()
{
    $plant_id = intval($_POST['plant_id']);
    $planter_id = intval($_POST['planter_id']);
    $gift_id = isset($_POST['gift_id']) ? intval($_POST['gift_id']) : 0;

    if (!$plant_id || !$planter_id) {
        wp_send_json_error('Invalid product selection.');
    }

    WC()->cart->add_to_cart($plant_id);
    WC()->cart->add_to_cart($planter_id);

    if ($gift_id) {
        WC()->cart->add_to_cart($gift_id);
    }

    wp_send_json_success('Combo added to cart.');
}



add_shortcode('plant_combo', 'plant_combo_shortcode');

function plant_combo_shortcode($atts)
{
    $atts = shortcode_atts([
        'plant_category' => 'plants',
        'gift_category'  => 'giftarium',
    ], $atts, 'plant_combo');

    if (!is_product()) {
        return '<p>This combo builder only works on single product pages.</p>';
    }

    global $post, $product;
    if (!has_term($atts['plant_category'], 'product_cat', $post)) {
        return '<p>This combo builder is only available for products in the "' . esc_html($atts['plant_category']) . '" category.</p>';
    }

    ob_start();

    $plant_id     = $product->get_id();
    $plant_price  = $product->get_price();
    $plant_title  = get_the_title();
    $combos       = get_field('products_combo', $post->ID);

    if (empty($combos)) {
        return '<p>No combo planters available for this plant.</p>';
    }
?>
    <div id="plant-combo-builder" data-plant-id="<?= esc_attr($plant_id) ?>" data-plant-price="<?= esc_attr($plant_price) ?>">

        <!-- === LEFT COLUMN: PLANTERS === -->
        <div class="column-left">

            <!-- Planter Slider -->
            <div class="slick-slider planter-preview">

                <?php foreach ($combos as $index => $combo) :
                    $planter_product = $combo['product_option'];
                    $image = $combo['image_product'];
                    if (!$planter_product instanceof WP_Post) continue;

                    $planter_id = $planter_product->ID;
                    $planter = wc_get_product($planter_id);
                    if (!$planter) continue;

                    $planter_price = $planter->get_price();
                    $planter_name = $planter->get_name();
                    $planter_description = $planter->get_short_description();
                ?>
                    <div class="combo-item"
                        data-type="planter"
                        data-name="<?= esc_attr($planter_name); ?>"
                        data-price="<?= esc_attr($planter_price); ?>"
                        data-potheight="0"
                        data-product_id="<?= esc_attr($planter_id); ?>"
                        data-index="<?= esc_attr($index); ?>">
                        <?php if ($image): ?>
                            <img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($planter_name); ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- === RIGHT COLUMN === -->
        <div class="column-right">
            <h3 class="product-title"><?= esc_html($plant_title); ?></h3>

            <p class="product-price">
                $<span id="plant-price"><?= number_format($plant_price, 2); ?></span>
            </p>


            <!-- Planter Select -->
            <div class="choose-planter">
                <label>Choose Your Planter</label>
                <select id="planter-select">
                    <option value="">-- Select a Planter --</option>
                    <?php foreach ($combos as $combo):
                        $planter_product = $combo['product_option'];
                        if (!$planter_product instanceof WP_Post) continue;

                        $planter_id = $planter_product->ID;
                        $planter = wc_get_product($planter_id);
                        if (!$planter) continue;

                        $planter_name = $planter->get_name();
                        $planter_price = $planter->get_price();
                    ?>
                        <option value="<?= esc_attr($planter_id); ?>">
                            <?= esc_html($planter_name); ?> - $<?= number_format($planter_price, 2); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Gift Select -->
            <div class="choose-gift">
                <label>Choose a Gift</label>
                <select id="gift-select" name="gift_id">
                    <option value="">-- Select a Gift --</option>
                    <?php
                    $gift_args = [
                        'post_type'      => 'product',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'tax_query'      => [
                            [
                                'taxonomy' => 'product_cat',
                                'field'    => 'slug',
                                'terms'    => sanitize_title($atts['gift_category']),
                            ],
                        ],
                    ];

                    $gift_query = new WP_Query($gift_args);
                    if ($gift_query->have_posts()):
                        while ($gift_query->have_posts()):
                            $gift_query->the_post();
                            $gift = wc_get_product(get_the_ID());
                            if (!$gift) continue;
                    ?>
                            <option value="<?= esc_attr($gift->get_id()); ?>">
                                <?= esc_html($gift->get_name()); ?> - $<?= number_format($gift->get_price(), 2); ?>
                            </option>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </select>
            </div>

            <div class="message-added-to-cart" style="display: none;">
                <p>Combo added to cart successfully!</p>
            </div>
            <div class="group-total-add-to-cart">
                <p>Total: $<span id="combo-total"><?= number_format($plant_price, 2); ?></span></p>
                <a href="#" class="button" id="add-to-cart-combo">Add To Cart</a>
            </div>
            <div class="line"></div>
            <div class="accordion-wrapper product-information">
                <div class="accordion-header">
                    <span>Delivery Information</span> <span class="accordion-icon minus ">-</span>
                </div>
                <div class="accordion-content">
                    <?= apply_filters('woocommerce_short_description', $product->get_description()); ?>
                </div>
            </div>
            <div class="line"></div>
            <?php
            $width = $product->get_width();
            $height = $product->get_height(); ?>
            <div class="product-dimensions">
                <p><strong>Size:</strong></p>
                <p>Width: ~<?= $width ?>cm</p>
                <p>Height: ~<?= $height ?>cm</p>
            </div>
        </div>
    </div>
    <div class="product-gallery-images">
        <?php
        $attachment_ids = $product->get_gallery_image_ids();
        foreach ($attachment_ids as $attachment_id) {
            $gallery_img_url = wp_get_attachment_image_url($attachment_id, 'medium_large');
            $full_img_url = wp_get_attachment_url($attachment_id);
            echo '<div class="stale-images">';
            echo '<a data-fancybox="gallery-1" href="' . esc_url($full_img_url) . '">';
            echo '<img src="' . esc_url($gallery_img_url) . '" alt="Gallery image">';
            echo '</a>';
            echo '</div>';
        }
        ?>
    </div>
<?php
    return ob_get_clean();
}


function exclude_workshop_products_from_archive( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_category() || is_product_tag() || is_search() ) ) {
        $tax_query = (array) $query->get( 'tax_query' );

        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => array( 'workshop' ),
            'operator' => 'NOT IN',
        );

        $query->set( 'tax_query', $tax_query );
    }
}
add_action( 'pre_get_posts', 'exclude_workshop_products_from_archive' );