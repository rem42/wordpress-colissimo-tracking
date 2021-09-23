<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Wordpress Colissimo Tracking
 * Plugin URI:        https://github.com/rem42/wordpress-colissimo-tracking
 * Description:       When update parcel number, create label in colissimo DB
 * Version:           1.0.0
 * Author:            rem42
 * Author URI:        https://remy.ovh/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'woocommerce_rest_insert_shop_order_object', 'wct_updateOrder', 10, 2);

function wct_updateOrder($meta_id, $post_id) {
    if(!$post_id instanceof WP_REST_Request || !$meta_id instanceof Automattic\WooCommerce\Admin\Overrides\Order) {
        return;
    }
    if(!is_plugin_active( 'colissimo-shipping-methods-for-woocommerce/index.php' )) {
        return;
    }
    if(!file_exists(LPC_INCLUDES.'label/lpc_outward_label_db.php')) {
        return;
    }
    if(count($post_id->get_json_params()) < 1) {
        return;
    }
    $json = $post_id->get_json_params();
    if(
        isset($json['meta_data'][0]['value']) && isset($json['meta_data'][0]['key'])
        && $json['meta_data'][0]['key'] === 'lpc_outward_parcel_number'
    ) {
        $lpc = new LpcOutwardLabelDb();
        $lpc->insert($meta_id->get_id(), null, $json['meta_data'][0]['value']);
    }
}
