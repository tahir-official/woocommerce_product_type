<?php
/**
 * Plugin Name: Woocommerce Product Type
 * Plugin URI: https://www.infocratsweb.com/
 * Description: This plugin added new product type for woocommerce product.
 * Version: 1.0
 * Author: tahir mansuri
 * Author URI: https://www.infocratsweb.com/
 */

/*check woocommerce plugin active or not*/
include( plugin_dir_path( __FILE__ ) . 'include/product_type.php');
register_activation_hook( __FILE__, 'wpt_plugin_activate' );
function wpt_plugin_activate(){

    // Require parent plugin
    if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) and current_user_can( 'activate_plugins' ) ) {
        // Stop activation redirect and show error
        wp_die('Sorry, but this plugin requires the woocommerce Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }


}
/*check woocommerce plugin active or not*/

function wpt_include_script() {
 
    if ( ! did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }
  
    wp_enqueue_script( 'wpt',  plugin_dir_url( __FILE__ ).'js/wptscript.js', array('jquery'), null, false );
}
add_action( 'admin_enqueue_scripts', 'wpt_include_script' );

add_action( "woocommerce_video_add_to_cart", function() {
    do_action( 'woocommerce_simple_add_to_cart' );
});

add_action( 'woocommerce_after_single_product_summary' , 'bbloomer_add_below_prod_gallery', 5 );
  
function bbloomer_add_below_prod_gallery() {
	global $product;
	
	if($product->get_type()=='video'){
       $current_user = wp_get_current_user();
		if ( wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->get_id() ) ){
			 $wpt_custom_video = get_post_meta($product->get_id(), 'wpt_custom_video', true );
			
			 echo '<div class="woocommerce-product-gallery" style="padding: 1em 2em">';
	 		 echo '<video width="400" height="250" controls  autoplay>
			    <source src="'.$wpt_custom_video.'" type="video/mp4">
			    </video> ';
	         echo '</div><style>.woocommerce-product-gallery__image,.woocommerce-pr {display: none;} .woocommerce-product-gallery__trigger {display: none !important;} </style>';
		} 

	   

	}
	
}