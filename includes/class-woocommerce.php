<?php

namespace Vabien\Theme;

// Make sure we're loaded after WC and fire it up!
// add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

class WooCommerce_Extensions {

	// protected static $_instance;

	public function __construct() {

		remove_action( 'wp_head', 'wc_generator_tag' );
		remove_filter( 'the_title', 'wc_page_endpoint_title' );
		remove_action( 'admin_notices', 'woothemes_updater_notice' );

		/*
		* Force customer creation process in Stripe.
		*/
		add_filter( 'wc_stripe_force_save_source', '__return_true' );


		// add_action( 'woocommerce_before_single_variation', array( $this, 'bra_size_calculator_popup' ) );

		// add_action( 'woocommerce_after_single_product_summary', array( $this, 'bra_sizing_form_popup_html') );

		add_filter( 'woocommerce_default_address_fields', array( $this, 'override_address_fields' ), 10, 1 );
		// add_action( 'save_post', array( $this, 'save_extra_product_meta_boxes' ), 10, 2 );
		// add_action( 'woocommerce_product_options_sku', array( $this, 'render_extra_product_meta_boxes' ) );
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_item_on_checkout' ), 10, 4 );
		// add_filter( 'woocommerce_product_tabs', array( $this, 'woo_new_product_tab') );
		add_filter( 'woocommerce_endpoint_order-received_title', array( $this, 'change_order_details_title' ), 10, 1);

		add_filter( 'woocommerce_register_shop_order_post_statuses', array( $this, 'add_custom_order_status_post_type') );
		add_filter( 'wc_order_statuses', array( $this, 'add_custom_order_status') );

		// add_filter('wp_schema_pro_schema_product', array($this,'dst_wp_schema_pro_schema_product'), 10, 3);

		// add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );
		// add_filter( 'woocommerce_helper_suppress_connect_notice', '__return_true' );
		
		// show the tax even if $0
		add_filter( 'woocommerce_cart_hide_zero_taxes', '__return_false' );
		add_filter( 'woocommerce_order_hide_zero_taxes', '__return_false' );

		add_action( 'woocommerce_before_checkout_form', array($this, 'add_checkout_title'), 1 );

		add_action( 'init' , array($this, 'setup_cart_sidebar'), 15 );


	}

	// public static function init() {
	// 	if ( is_null( self::$_instance ) ) {
	// 		self::$_instance = new self();
	// 	}

	// 	return self::$_instance;
	// }

	public function bra_size_calculator_popup() {
		?>
		<div class="sizing-help">
			<a href="#bc-popup" class="popup-bc-calculator" style="cursor: pointer;">Sizing Help</a>
		</div>
		<div id="bc-popup" class="theme-popup bra-size-popup mfp-hide">
			<div class="bc-content">
				<?php echo do_shortcode( '[bra_size_calculator]' ); ?>
			</div>
			<button title="Close (Esc)" class="mfp-close">
				<svg xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="12" height="12" viewBox="1.1 1.1 12 12"
				     enable-background="new 1.1 1.1 12 12"
				     xml:space="preserve"><path
						d="M8.3 7.1l4.6-4.6c0.3-0.3 0.3-0.8 0-1.2 -0.3-0.3-0.8-0.3-1.2 0L7.1 5.9 2.5 1.3c-0.3-0.3-0.8-0.3-1.2 0 -0.3 0.3-0.3 0.8 0 1.2L5.9 7.1l-4.6 4.6c-0.3 0.3-0.3 0.8 0 1.2s0.8 0.3 1.2 0L7.1 8.3l4.6 4.6c0.3 0.3 0.8 0.3 1.2 0 0.3-0.3 0.3-0.8 0-1.2L8.3 7.1z"></path></svg>
			</button>
		</div>


		<?php
	}
	public function bra_sizing_form_popup_html() {
		// Must enqueue the proper scripts for form ID 1 or AJAX won't work right.
		gravity_form_enqueue_scripts(1,true);
		// Prevent the background from scrolling down.
		add_filter( 'gform_confirmation_anchor_2', '__return_false' );
		?>
		<div id="bc-sizing-help" class="theme-popup bra-size-popup mfp-hide">
			<div class="bc-content">
				<div class="sizing-container">
					<?php echo do_shortcode( '[gravityform id="2" title="false" description="false" ajax="true"]' ); ?>
				</div>
			</div>
			<button title="Close (Esc)" class="mfp-close">
				<svg xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" width="12" height="12" viewBox="1.1 1.1 12 12"
					enable-background="new 1.1 1.1 12 12"
					xml:space="preserve"><path
						d="M8.3 7.1l4.6-4.6c0.3-0.3 0.3-0.8 0-1.2 -0.3-0.3-0.8-0.3-1.2 0L7.1 5.9 2.5 1.3c-0.3-0.3-0.8-0.3-1.2 0 -0.3 0.3-0.3 0.8 0 1.2L5.9 7.1l-4.6 4.6c-0.3 0.3-0.3 0.8 0 1.2s0.8 0.3 1.2 0L7.1 8.3l4.6 4.6c0.3 0.3 0.8 0.3 1.2 0 0.3-0.3 0.3-0.8 0-1.2L8.3 7.1z"></path></svg>
			</button>
		</div>
	<?php 
	}

	public function woo_new_product_tab( $tabs ) {

		// Adds the new tab

		if ( isset( $tabs['additional_information'] ) ) {
			unset( $tabs['additional_information']);
		}

		$tab = get_field( 'vabien_product_common_questions' );

		if ( $tab && $tab != '' ) {

			$tabs['common_questions'] = array(
					'title'    => __( 'Common Questions', 'woocommerce' ),
					'priority' => 50,
					'callback' => array( $this, 'woo_new_product_tab_content' ),
			);

		}

		return $tabs;

	}
	public function woo_new_product_tab_content() {

		$tab = get_field( 'vabien_product_common_questions' );

		if ( $tab && $tab != '' ) {
			// The new tab content
			echo get_field('vabien_product_common_questions');
		}

	}

	public function override_address_fields( $address_fields ) {

		$address_fields['address_1']['placeholder'] = 'House number and street - No PO boxes please';

		return $address_fields;

	}

	public function add_custom_order_status_post_type( $order_statuses ) {

		$order_statuses['wc-partial-refund'] = array(
				'label'                     => _x( 'Partially Refunded', 'Order status'),
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Partially Refunded <span class="count">(%s)</span>', 'Partially Refunded <span class="count">(%s)</span>', 'woocommerce'),
		);

		return $order_statuses;
	}

	public function add_custom_order_status( $current_statuses ) {

		$new_statuses = array();

		foreach ( $current_statuses as $key => $status ) {
			if ( $key == 'wc-refunded' ) {
				$new_statuses['wc-partial-refund'] = _x( 'Partially Refunded', 'Order status', 'woocommerce' );
			}

			$new_statuses[ $key ] = $status;
		}

		return $new_statuses;
	}
 
	public function change_order_details_title( $old_title ){
		error_log('title man');
		error_log($old_title);
		return 'Order Confirmation';
	
	}
	/**
	 * Save  meta box data.
	 */
	public function save_extra_product_meta_boxes( $post_id, $post ) {

		if ( $post_id === null ) {
			return false;
		}

		if ( isset( $_POST['_custom_pn'] ) ) {
			// Update post meta
			update_post_meta( $post_id, '_custom_pn', wc_clean( $_POST['_custom_pn'] ) );
		}
	}

	// public function render_extra_product_meta_boxes() {

	// 	woocommerce_wp_text_input( array(
	// 		'id'    => '_custom_pn',
	// 		'class' => 'form-field ',
	// 		'label' => __( 'Vabien Style Number:' ),
	// 	) );
	// }

	public function save_item_on_checkout( \WC_Order_Item_Product $item, $cart_item_key, $values, $order ) {

		// save unique Vabien code to the meta
		// set the product ID to retrieve the session discount data
		$_product_id = $item->get_product_id();
		$_product    = wc_get_product( $_product_id );

		// save the original price
		$item->add_meta_data( __( 'Style Number' ), $_product->get_meta('_custom_pn') );

	}

	public function dst_wp_schema_pro_schema_product($schema, $data, $post) {
		$product_permalink = get_the_permalink($post->ID);

		$schema['brand'] = 'Va Bien';
		return $schema;
	}

	public function add_checkout_title() {
		echo '<h1 class="thb-shop-title">Checkout</h1>';
	}

	/**
	 * Remove the theme cart sidebar and add our customized one
	 */
	public function setup_cart_sidebar() {
		remove_action( 'thb_side_cart', 'thb_side_cart', 3 );
		add_action( 'thb_side_cart', array($this, 'vabien_thb_side_cart'), 3 );
	}
	/**
	 * Customized version of the theme cart sidebar
	 */
	function vabien_thb_side_cart() {
		if ( ! is_cart() && ! is_checkout() ) {
			?>
			<nav id="side-cart" class="side-panel">
				<header>
					<h6><?php esc_html_e( 'Shopping Bag', 'north' ); ?></h6>
					<a href="#" class="thb-close" title="<?php esc_attr_e( 'Close', 'north' ); ?>"><?php get_template_part( 'assets/img/svg/close.svg' ); ?></a>
				</header>
				<div class="side-panel-content">
					<?php
					if ( class_exists( 'WC_Widget_Cart' ) ) {
						the_widget(
							'WC_Widget_Cart',
							array(
								'title' => false,
							)
						);
					}
					?>
				</div>
			</nav>
			<?php
		}
	}
	
}

$vabien_woocommerce_extensions = new WooCommerce_Extensions();

// WooExtensions::init();
