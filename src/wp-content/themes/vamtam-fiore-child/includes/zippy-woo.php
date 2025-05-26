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

    if (!$plant_id || !$planter_id) {
        wp_send_json_error('Invalid product selection.');
    }

    // Add both to cart
    WC()->cart->add_to_cart($plant_id);
    WC()->cart->add_to_cart($planter_id);

    wp_send_json_success('Combo added to cart.');
}


add_shortcode('plant_combo', 'plant_combo_shortcode');

function plant_combo_shortcode()
{
    if (!is_product()) {
        return '<p>This combo builder only works on single product pages.</p>';
    }

    global $post, $product;

    // Check if current product is in 'plants' category
    if (!has_term('plants', 'product_cat', $post)) {
        return '<p>This combo builder is only available for plant products.</p>';
    }

    ob_start();

    $plant_id = $product->get_id();
    $plant_price = $product->get_price();
    $plant_title = get_the_title();

    // Get all planters
    $planters = new WP_Query([
        'post_type' => 'product',
        'posts_per_page' => -1,
        'product_cat' => 'large-planters'
    ]);
?>
    <div id="plant-combo-builder" data-plant-id="<?= $plant_id ?>" data-plant-price="<?= $plant_price ?>">
        <div class="column-left">
            <div>
                <!-- Current Plant Thumbnail -->
                <div class="current-plant-image">
                    <?php echo get_the_post_thumbnail($post->ID, 'medium_large'); ?>
                </div>

                <!-- Planter Slider (optional to remove if not needed anymore) -->
                <div class="slick-slider planter-preview">
                    <?php
                    $index = 0;
                    $planters->rewind_posts();
                    while ($planters->have_posts()) : $planters->the_post();
                        global $product;
                        $planter_id = get_the_ID();
                        $planter_price = $product->get_price();
                        $planter_name = get_the_title();
                    ?>
                        <div class="combo-item"
                            data-type="planter"
                            data-name="<?= esc_attr($planter_name); ?>"
                            data-price="<?= esc_attr($planter_price); ?>"
                            data-potheight="0"
                            data-product_id="<?= esc_attr($planter_id); ?>"
                            data-index="<?= $index; ?>">
                            <?php the_post_thumbnail('medium_large'); ?>
                        </div>
                        <?php $index++; ?>
                    <?php endwhile; ?>
                </div>

            </div>
        </div>

        <!-- Selections -->
        <div class="column-right">
            <div>
                <h3 class="product-title"><?= $plant_title ?></h3>
                <p class="product-description"><?= $product->get_short_description() ?></p>
                <p class="product-price">$<span id="plant-price"><?= number_format($plant_price, 2) ?></span></p>
            </div>

            <div class="choose-planter">
                <h5>Choose Your Planter</h5>
                <select id="planter-select">
                    <option value="" data-price="0">-- Select a Planter --</option>
                    <?php
                    $planters->rewind_posts();
                    while ($planters->have_posts()) : $planters->the_post();
                        global $product;
                        $planter_price = $product->get_price();
                        $planter_id = get_the_ID();
                    ?>
                        <option
                            value="<?= $planter_id; ?>"
                            data-price="<?= $planter_price ?>"
                            data-product_id="<?= $planter_id; ?>">
                            <?= get_the_title(); ?> - $<?= number_format($planter_price, 2) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

            </div>

            <div class="message-added-to-cart" style="display: none;">
                <p>Combo added to cart successfully!</p>
            </div>
            <div class="group-total-add-to-cart">
                <strong>Total: $<span id="combo-total"><?= number_format($plant_price, 2) ?></span></strong>
                <a href="#" class="button" id="add-to-cart-combo">Add To Cart</a>
            </div>
        </div>
    </div>

<?php
    wp_reset_postdata();
    return ob_get_clean();
}
