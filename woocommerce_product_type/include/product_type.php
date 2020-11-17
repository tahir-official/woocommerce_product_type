<?php
function register_video_product_type() {

	/**
	 * This should be in its own separate file.
	 */
	class WC_Product_Video extends WC_Product {

		public function __construct( $product ) {

			$this->product_type = 'video';

			parent::__construct( $product );

		}

	}

}
add_action( 'plugins_loaded', 'register_video_product_type' );


/**
 * Add to product type drop down.
 */
function add_video_product( $types ){

	// Key should be exactly the same as in the class
	$types[ 'video' ] = __( 'Video' );

	return $types;

}
add_filter( 'product_type_selector', 'add_video_product' );


/**
 * Show pricing fields for simple_rental product.
 */
function video_custom_js() {

	if ( 'product' != get_post_type() ) :
		return;
	endif;

	?><script type='text/javascript'>
		jQuery( document ).ready( function() {
			jQuery( '.options_group.pricing' ).addClass( 'show_if_video' ).show();
		});

	</script><?php

}
add_action( 'admin_footer', 'video_custom_js' );


/**
 * Add a custom product tab.
 */
function custom_product_tabs( $tabs) {

	$tabs['video'] = array(
		'label'		=> __( 'Video', 'woocommerce' ),
		'target'	=> 'video_options',
		'class'		=> array( 'show_if_video', 'show_if_variable_rental'  ),
	);

	return $tabs;

}
add_filter( 'woocommerce_product_data_tabs', 'custom_product_tabs' );


/**
 * Contents of the rental options product tab.
 */
function video_options_product_tab_content() {

	global $post;

	?><div id='video_options' class='panel woocommerce_options_panel'>
		<div class='options_group'>
			<?php


			$image = get_post_meta($post->ID, 'wpt_custom_video', true);
		    ?>
		    <table class="show_if_video">
		        <tr>
		            <td><a href="#" class="aw_upload_image_button button button-secondary"><?php _e('Upload Video'); ?></a></td>
		            <td><input type="text" name="wpt_custom_video" id="wpt_custom_video" value="<?php echo $image; ?>" style="width:500px;" /></td>
		        </tr>
		    </table>
		    </div>

	</div>
	<?php

    
}
add_action( 'woocommerce_product_data_panels', 'video_options_product_tab_content' );


/**
 * Save the custom fields.
 */
function save_video_option_field( $post_id ) {

	

	if ( isset( $_POST['wpt_custom_video'] ) ) :
		update_post_meta( $post_id, 'wpt_custom_video', sanitize_text_field( $_POST['wpt_custom_video'] ) );
	endif;

}
add_action( 'woocommerce_process_product_meta_video', 'save_video_option_field'  );
add_action( 'woocommerce_process_product_meta_variable_rental', 'save_video_option_field'  );


/**
 * Hide Attributes data panel.
 */
function hide_attributes_data_panel( $tabs) {

	$tabs['attribute']['class'][] = 'hide_if_video hide_if_variable_rental ';
	$tabs['shipping']['class'][] = 'hide_if_video hide_if_variable_rental ';

	return $tabs;

}
add_filter( 'woocommerce_product_data_tabs', 'hide_attributes_data_panel' );



