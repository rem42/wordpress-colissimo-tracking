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

add_action( 'updated_post_meta', 'updateMeta', 10, 4);

function updateMeta($meta_id, $post_id, $meta_key, $_meta_value) {
    if($meta_key !== 'lpc_outward_parcel_number') {
        return;
    }

    if(!is_plugin_active( 'colissimo-shipping-methods-for-woocommerce/index.php' )) {
        return;
    }

    if(!file_exists(LPC_INCLUDES.'label/lpc_outward_label_db.php')) {
        return;
    }

    require_once LPC_INCLUDES.'label/lpc_outward_label_db.php';

    $lpc = new LpcOutwardLabelDb();
    $lpc->insert($post_id, null, $_meta_value);
}
