<?php

namespace Vabien\Theme;

class WooCommerce_Product {

    public function __construct() {
      
        add_action( 'woocommerce_init', array($this, 'vabien_modify_actions'), 10, 1 );
        // add_filter( 'woocommerce_single_product_carousel_options', array($this, 'cuswoo_update_woo_flexslider_options') );

        add_action( 'woocommerce_before_single_variation', array( $this, 'bra_size_calculator_popup' ) );
        add_action( 'woocommerce_after_single_product_summary', array( $this, 'bra_sizing_form_popup_html') );

        add_action( 'woocommerce_after_single_variation', array($this, 'vabien_share_this'), 12 );

		add_filter( 'woocommerce_product_tabs', array( $this, 'woo_new_product_tab') );

		add_action( 'woocommerce_product_options_sku', array( $this, 'render_extra_product_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_extra_product_meta_boxes' ), 10, 2 );

		add_filter('wp_schema_pro_schema_product', array($this,'dst_wp_schema_pro_schema_product'), 10, 3);


        // add_action( 'woocommerce_product_thumbnails', array($this, 'wrap_images'), 1);
        // add_action( 'woocommerce_product_thumbnails', array($this, 'wrap_images_end'), 40);

        // add_filter( 'woocommerce_single_product_image_thumbnail_html', array($this, 'add_gallery_images'), 10, 2 );
		// change woocommerce thumbnail image size
		// add_filter( 'woocommerce_get_image_size_gallery_thumbnail', array($this, 'override_woocommerce_image_size_gallery_thumbnail') );

		add_action( 'before_woocommerce_init', array($this, 'vabien_add_single_product_ajax_add_to_cart') );

    }

	/**
	 * Create the sharing function
	 */
    public function vabien_share_this() {
        $id           = get_the_ID();
        $permalink    = get_permalink( $id );
        $title        = the_title_attribute(
            array(
                'echo' => 0,
                'post' => $id,
            )
        );
        $image_id     = get_post_thumbnail_id( $id );
        $image        = wp_get_attachment_image_src( $image_id, 'full' );
        ?>
            <aside class="share-article">
		<a class="thb_share">
			<?php get_template_part( 'assets/img/svg/share.svg' ); ?>
			<?php esc_html_e( 'Share This', 'north' ); ?>
		</a>
		<div class="icons">
			<div class="inner">
				<a href="<?php echo 'http://www.facebook.com/sharer.php?u=' . urlencode( esc_url( $permalink ) ) . ''; ?>" class="facebook social"><i class="fa fa-facebook"></i></a>
				<a href="<?php echo 'https://twitter.com/intent/tweet?text=' . htmlspecialchars( urlencode( html_entity_decode( $title, ENT_COMPAT, 'UTF-8' ) ), ENT_COMPAT, 'UTF-8' ) . '&url=' . urlencode( esc_url( $permalink ) ) . '&via=' . esc_attr( get_bloginfo( 'name' ) ) . ''; ?>" class="twitter social "><i class="fa fa-twitter"></i></a>

			</div>
		</div>
	</aside>
        <?php
    }
    public function vabien_modify_actions() {
        remove_action( 'woocommerce_before_main_content', 'thb_wc_breadcrumbs', 0 );
        // remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

        add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 1 );
        remove_theme_support( 'wc-product-gallery-zoom' );
        // remove_theme_support( 'wc-product-gallery-lightbox' );

        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
        add_action( 'woocommerce_before_single_product_summary', array($this, 'vabien_product_images'), 20 );
        // add_action( 'woocommerce_before_single_product_summary', 'after_product_images', 21 );

        // add_theme_support( 'wc-product-gallery-slider' );

    }
    public function vabien_product_images() {
        echo '<div id="product-thumbnails">';
		add_filter( 'woocommerce_gallery_image_size', array($this, 'change_woocommerce_image_size_gallery_thumbnail'), 10 );
        wc_get_template( 'single-product/product-image.php' );
		remove_filter( 'woocommerce_gallery_image_size', array($this, 'change_woocommerce_image_size_gallery_thumbnail'), 10 );

		// wc_get_template( 'single-product/product-gallery-thumbnails.php' );

        echo '</div>';
        echo '<div id="product-images">';
        wc_get_template( 'single-product/product-image.php' );
        echo '</div>';
    }
	/**
	 * Make the thumbnails square
	 */
	public function change_woocommerce_image_size_gallery_thumbnail( $size ) {
		return array(
			100, 100
		);
	}
    /** 
     * Filer WooCommerce Flexslider options - Add Navigation Arrows
     */
    // public function cuswoo_update_woo_flexslider_options( $options ) {
    //     $options['directionNav'] = true;
    //     $options['animationLoop'] = true;
    //     $options['touch'] = true;

    //     return $options;
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
    public function render_extra_product_meta_boxes() {

		woocommerce_wp_text_input( array(
			'id'    => '_custom_pn',
			'class' => 'form-field ',
			'label' => __( 'Vabien Style Number:' ),
		) );
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

		/**
	 * Ajax Add to Cart Wrapper
	 */
	public function vabien_add_single_product_ajax_add_to_cart() {
		add_action( 'wp', array($this, 'thb_ajax_add_to_cart_redirect_template'), 1000 );
		
		add_action( 'woocommerce_after_add_to_cart_button', array($this, 'thb_woocommerce_after_add_to_cart_button') );

		add_action( 'woocommerce_before_main_content', array($this, 'thb_woocommerce_display_site_notice'), 10 );
	}
	/**
	 * Get the Ajax template
	 */
	public function thb_ajax_add_to_cart_redirect_template() {
		$thb_ajax = filter_input( INPUT_GET, 'thb-ajax-add-to-cart', FILTER_VALIDATE_BOOLEAN );

		if ( $thb_ajax ) {
			wc_get_template( 'ajax/add-to-cart-fragments.php' );
			exit;
		}
	}
	/**
	 * Render the button
	 */
	public function thb_woocommerce_after_add_to_cart_button() {
		global $product;
		?>
			<input type="hidden" name="action" value="wc_prod_ajax_to_cart" />
		<?php
		// Make sure we have the add-to-cart avaiable as button name doesn't submit via ajax.
		if ( $product->is_type( 'simple' ) ) {
			?>
			<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"/>
			<?php
		}
	}
	/**
	 * Special site notice div
	 */
	public function thb_woocommerce_display_site_notice() {
		?>
		<div class="thb_prod_ajax_to_cart_notices"></div>
		<?php
	}

}

$vabien_woocommerce_product = new WooCommerce_Product();