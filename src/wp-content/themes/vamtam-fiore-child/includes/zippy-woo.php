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


add_shortcode('plant_combo', 'plant_combo_shortcode');

function plant_combo_shortcode()
{
    ob_start();

    // Get products
    $plants = new WP_Query([
        'post_type' => 'product',
        'posts_per_page' => -1,
        'product_cat' => 'plants'
    ]);

    $planters = new WP_Query([
        'post_type' => 'product',
        'posts_per_page' => -1,
        'product_cat' => 'large-planters'
    ]);
?>
    <div id="plant-combo-builder">
        <div class="column-left">
            <div>
                <!-- Plant Slider -->
                <div class="slick-slider plant-preview">
                    <?php $plants->rewind_posts();
                    while ($plants->have_posts()) : $plants->the_post(); ?>
                        <div>
                            <?php the_post_thumbnail('medium_large'); ?>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Planter Slider -->
                <div class="slick-slider planter-preview">
                    <?php while ($planters->have_posts()) : $planters->the_post(); ?>
                        <div>
                            <?php the_post_thumbnail('medium_large'); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <!-- Selections -->
        <div class="column-right">
            <h3>Choose Your Plant</h3>
            <div class="combo-options plants">
                <?php $index = 0;
                $plants->rewind_posts();
                while ($plants->have_posts()) : $plants->the_post();
                    global $product;
                    $hidden_class = $index >= 4 ? 'hidden' : ''; ?>
                    <div class="combo-item <?= $hidden_class ?>" data-type="plant"
                        data-index="<?= $index++; ?>"
                        data-name="<?php the_title(); ?>"
                        data-price="<?= $product->get_price(); ?>"
                        data-height="<?= get_post_meta(get_the_ID(), 'plant_height', true); ?>"
                        data-product_id="<?= get_the_ID(); ?>">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </div>
                <?php endwhile; ?>
            </div>
            <button class="show-more" data-target="plants">Show More Plants</button>

            <h3>Choose Your Planter</h3>
            <div class="combo-options planters">
                <?php $index = 0;
                $planters->rewind_posts();
                while ($planters->have_posts()) : $planters->the_post();
                    global $product;
                    $hidden_class = $index >= 4 ? 'hidden' : ''; ?>
                    <div class="combo-item <?= $hidden_class ?>" data-type="planter"
                        data-index="<?= $index++; ?>"
                        data-name="<?php the_title(); ?>"
                        data-price="<?= $product->get_price(); ?>"
                        data-potheight="<?= get_post_meta(get_the_ID(), 'pot_height', true); ?>"
                        data-product_id="<?= get_the_ID(); ?>">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </div>
                <?php endwhile; ?>
            </div>
            <button class="show-more" data-target="planters">Show More Planters</button>

            <div class="group-total-add-to-cart">
                <strong>$<span id="combo-total">0.00</span></strong>
                <a href="#" class="button" id="add-to-cart">Add To Cart</a>
            </div>
        </div>
    </div>

    <script>
       
    </script>

<?php
    return ob_get_clean();
}

add_action('wp_ajax_add_to_cart_combo', 'handle_add_to_cart_combo');
add_action('wp_ajax_nopriv_add_to_cart_combo', 'handle_add_to_cart_combo');

function handle_add_to_cart_combo() {
    $plant_id = intval($_POST['plant_id']);
    $planter_id = intval($_POST['planter_id']);

    if (!$plant_id || !$planter_id) {
        wp_send_json_error('Missing product ID');
    }

    WC()->cart->add_to_cart($plant_id, 1);
    WC()->cart->add_to_cart($planter_id, 1);

    wp_send_json_success();
}
