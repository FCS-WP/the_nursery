<?php
add_action('woocommerce_email_after_order_table', 'show_custom_delivery_in_email', 20, 4);

function show_custom_delivery_in_email($order, $sent_to_admin, $plain_text, $email)
{
    $delivery_date = $order->get_meta('_delivery_date');
    $delivery_time = $order->get_meta('_delivery_time');

    if (!$delivery_date && !$delivery_time) {
        return;
    }

    if ($plain_text) {
        echo "\nDelivery Details:\n";
        if ($delivery_date) echo 'Delivery Date: ' . $delivery_date . "\n";
        if ($delivery_time) echo 'Time Slot: ' . $delivery_time . "\n";
    } else {
        echo '<h3>Delivery Details</h3><ul style="margin:0; padding-left:15px;">';
        if ($delivery_date) {
            echo '<li><strong>Delivery Date:</strong> ' . esc_html($delivery_date) . '</li>';
        }
        if ($delivery_time) {
            echo '<li><strong>Time Slot:</strong> ' . esc_html($delivery_time) . '</li>';
        }
        echo '</ul>';
    }
}
