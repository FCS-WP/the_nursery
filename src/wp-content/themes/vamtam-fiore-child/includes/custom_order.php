<?php

add_action('woocommerce_checkout_create_order', 'save_custom_delivery_fields_to_order', 20, 2);

function save_custom_delivery_fields_to_order($order, $data)
{
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'delivery_date_') !== false && !empty($value)) {
            $order->update_meta_data('_delivery_date', sanitize_text_field($value));
        }

        if (strpos($key, 'delivery_time_') !== false && !empty($value)) {
            $order->update_meta_data('_delivery_time', sanitize_text_field($value));
        }
    }
}

add_action('woocommerce_thankyou', 'show_custom_delivery_on_thankyou', 20);
add_action('woocommerce_email_after_order_table', 'show_custom_delivery_on_thankyou', 20);

function show_custom_delivery_on_thankyou($order_id)
{
    $order = wc_get_order($order_id);
    $delivery_date = $order->get_meta('_delivery_date');
    $delivery_time = $order->get_meta('_delivery_time');

    if ($delivery_date || $delivery_time) {
        echo '<h3>Delivery Details</h3><ul class="delivery-info">';
        if ($delivery_date) {
            echo '<li><strong>Delivery Date:</strong> ' . esc_html($delivery_date) . '</li>';
        }
        if ($delivery_time) {
            echo '<li><strong>Time Slot:</strong> ' . esc_html($delivery_time) . '</li>';
        }
        echo '</ul>';
    }
}
add_action('woocommerce_admin_order_data_after_shipping_address', 'admin_show_custom_delivery_fields', 10, 1);

function admin_show_custom_delivery_fields($order)
{
    $delivery_date = $order->get_meta('_delivery_date');
    $delivery_time = $order->get_meta('_delivery_time');

    if ($delivery_date || $delivery_time) {
        echo '<p><strong>Delivery Date:</strong> ' . esc_html($delivery_date) . '</p>';
        echo '<p><strong>Delivery Time Slot:</strong> ' . esc_html($delivery_time) . '</p>';
    }
}
