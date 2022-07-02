<?php

namespace Vabien\Theme;

class WooCommerce_Order {

    public function __construct() {

        add_action( 'init', array($this, 'change_thankyou_layout'), 10, 1 );
        add_action( 'init', array($this, 'add_custom_order_statuses') );

        add_filter( 'wc_order_statuses', array($this, 'rename_order_statuses'), 20, 1 );

        // Change certain text strings
        add_filter( 'gettext', array($this,'change_text_strings'), 20, 3 );

        add_action( 'current_screen', array($this,'vabien_woocommerce_order_admin'), 10, 1 );

        // add_action( 'woocommerce_before_thankyou', array($this, 'modify_woocommerce_receipt_header'), 10, 1 );
    }

    /**
     * Change the thank you page layout
     */
    public function change_thankyou_layout() {
        // remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
        // add_action( 'woocommerce_before_thankyou', 'woocommerce_order_details_table', 10 );
    }
    /*
    * Register a custom order status
    */
    public function add_custom_order_statuses() {

        register_post_status(
            'wc-vb-in-process',
            array(
                'label'		=> 'In Process',
                'public'	=> true,
                'show_in_admin_status_list' => true,
                'label_count'	=> _n_noop( 'In Process (%s)', 'In Process (%s)' )
            )
        );
    }
    /**
     * Rename and reorder WooCommerce order statuses
     */
    public function rename_order_statuses( $order_statuses ) {

        $new_order_statuses = array();
        foreach ( $order_statuses as $key => $label ) { 
            $new_order_statuses[$key] = $label;
            if($key == 'wc-processing' ) {
                $new_order_statuses[$key] = 'New';
                $new_order_statuses['wc-vb-in-process'] = 'In Process'; 
            }
        }
        return $new_order_statuses;
    }

 
    /**
     * Change various text strings
     */
    public function change_text_strings($translated_text, $text, $domain) {

        if($domain == 'woocommerce') {
            switch ( $translated_text ) {
               
                case 'Order #%1$s was placed on %2$s and is currently %3$s.':
                    $translated_text = __( 'Order #%1$s was placed on %2$s and currently is listed as %3$s.', $domain);
                    break;
                case 'Order details':
                    $translated_text = __( 'Order confirmation', $domain);
                    break;
                case 'Thank you. Your order has been received.':
                    $translated_text = __( 'Merci et Bisous!', $domain);
                    break;
            }
        } 
        return $translated_text;
    }
    /**
     * Detect the order list and add some CSS
     */
    public function vabien_woocommerce_order_admin($current_screen) {
        if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
            if($current_screen->id == 'edit-shop_order') {
                
                // add the CSS & JS
                add_action('admin_head', array($this, 'vabien_shop_order_quote_css_js'), 1);

            }
        } // end if 
    }

    /**
     * CSS & JS for the shop order edit screen
     */
    public function vabien_shop_order_quote_css_js() {
        ?>

        <style>
            .order-status.status-processing {
                background: #fac293;
                color: #b94622;
            }
            .order-status.status-vb-in-process {
                background: #c6e1c6;
                color: #5b841b;
            }
        </style>
        <?php
    }

    /**
     * Add items before the receipt text
     */
    public function modify_woocommerce_receipt_header($order_id) {
        echo '<div class="vabien-thankyou-header"><h1>Merci et Bisous!</h1></div>';
    }

    public function woocommerce_order_details_before_order_table($order) {

    }
}


$vabien_woocommerce_order = new WooCommerce_Order();