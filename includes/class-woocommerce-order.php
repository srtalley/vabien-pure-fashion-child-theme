<?php

namespace Vabien\Theme;

class WooCommerce_Order {

    public function __construct() {

        add_action( 'init', array($this, 'change_thankyou_layout'), 10, 1 );
        add_action( 'init', array($this, 'add_custom_order_statuses') );
        add_filter( 'wc_order_statuses', array($this, 'rename_order_statuses'), 20, 1 );

        add_filter( 'bulk_actions-edit-shop_order', array($this, 'register_woocommerce_bulk_action'), 999 ); 
        add_action( 'admin_action_mark_vb-in-process', array($this, 'bulk_process_woocommerce_custom_status') );
        add_action( 'admin_notices', array($this, 'custom_woocommerce_order_status_notices') );

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


    /*
    * Add your custom bulk action in dropdown
    * @since 3.5.0
    */
    public function register_woocommerce_bulk_action( $bulk_actions ) {
    
        wl($bulk_actions);
        $new_bulk_actions = array();
        foreach ( $bulk_actions as $key => $label ) { 
            $new_bulk_actions[$key] = $label;
            if($key == 'mark_processing' ) {
                $new_bulk_actions[$key] = 'Change status to new';
                $new_bulk_actions['mark_vb-in-process'] = 'Change status to in process'; 
            }
        }
        wl($new_bulk_actions);

        return $new_bulk_actions;
    
    }
    /*
    * Bulk action handler
    * Make sure that "action name" in the hook is the same like the option value from the above function
    */
    public function bulk_process_woocommerce_custom_status() {
    
        // if an array with order IDs is not presented, exit the function
        if( !isset( $_REQUEST['post'] ) && !is_array( $_REQUEST['post'] ) )
            return;
    
        foreach( $_REQUEST['post'] as $order_id ) {
    
            $order = new \WC_Order( $order_id );
            $order_note = 'Updated by bulk edit:';
            $order->update_status( 'vb-in-process', $order_note, true ); // 
    
        }
    
        //using add_query_arg() is not required, you can build your URL inline
        $location = add_query_arg( array(
            'post_type' => 'shop_order',
            'marked_vb-in-process' => 1, // marked_vb-in-process=1 is  the $_GET variable for notices
            'changed' => count( $_REQUEST['post'] ), // number of changed orders
            'ids' => join( $_REQUEST['post'], ',' ),
            'post_status' => 'all'
        ), 'edit.php' );
    
        wp_redirect( admin_url( $location ) );
        exit;
    }
    
    /*
    * Notices about the updated order status
    */
    public function custom_woocommerce_order_status_notices() {
        global $pagenow, $typenow;
    
        if( $typenow == 'shop_order' 
        && $pagenow == 'edit.php'
        && isset( $_REQUEST['marked_vb-in-process'] )
        && $_REQUEST['marked_vb-in-process'] == 1
        && isset( $_REQUEST['changed'] ) ) {
    
            $message = sprintf( _n( 'Order status changed to In Process.', '%s order statuses changed.', $_REQUEST['changed'] ), number_format_i18n( $_REQUEST['changed'] ) );
            echo "<div class=\"updated\"><p>{$message}</p></div>";
    
        }
    }
}


$vabien_woocommerce_order = new WooCommerce_Order();