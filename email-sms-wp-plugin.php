<?php
/*
Plugin Name: Email and SMS Notification
Description: Adds functionality to notify seller via email or sms
Version: 1.0
Author: Roman Gemini
*/


add_action('woocommerce_product_options_general_product_data', function () {

    echo '<div class="options_group">';

    woocommerce_wp_text_input(array(
        'id' => '_notify_email',
        'label' => 'Seller\'s email address',
        'placeholder' => 'foo@bar.com'
    ));

    echo '</div>';

});

add_action('woocommerce_process_product_meta', function ($post_id) {
    $woo_notify_email = $_POST['_notify_email'];
    if (!empty($woo_notify_email)) {
        update_post_meta($post_id, '_notify_email', esc_attr($woo_notify_email));
    }
});

add_filter('woocommerce_email_recipient_new_order', function ($recipient, WC_Order $order) {
    $order_items = $order->get_items();
    foreach ($order_items as $order_item) {
        $product = $order->get_product_from_item($order_item);
        $notify_email = get_post_meta($product->id, "_notify_email", true);
        if (!empty($notify_email)) {
            $recipient .= ", " . $notify_email;
        }
    }
    return $recipient;
}, 10, 2);


