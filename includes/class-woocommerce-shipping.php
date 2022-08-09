<?php

namespace Vabien\Theme;

class WooCommerce_Shipping {

    private $debug = false;

    public function __construct() {

        // add_filter( 'woocommerce_package_rates', array($this, 'custom_shipping_rates'), 100, 2 );
        // add_action( 'woocommerce_after_shipping_rate', array($this, 'checkout_shipping_additional_field'), 20, 2 );

        add_action( 'woocommerce_before_checkout_form', array($this, 'vabien_print_cart_weight') );
        add_action( 'woocommerce_before_cart', array($this, 'vabien_print_cart_weight') );
    

        add_action( 'woocommerce_after_shipping_rate', array($this, 'signature_selection_custom_field'), 100, 2 );
        add_action( 'woocommerce_checkout_process', array($this, 'signature_selection_checkout_validation') );
        add_action( 'woocommerce_checkout_create_order', array($this, 'signature_selection_update_order_meta') );
        // add_action( 'woocommerce_admin_order_data_after_shipping_address', array($this, 'display_signature_selection_on_order_edit_pages') );
        add_action( 'woocommerce_after_order_itemmeta', array($this, 'display_signature_selection_below_shipping_method'), 10, 3 );
        add_filter( 'woocommerce_get_order_item_totals', array($this, 'display_signature_selection_on_order_item_totals'), 1000, 3 );

        // Ajax to handle the signature selections
        add_action( 'wp_ajax_nopriv_vabien_shipping_signature_selection', array($this, 'vabien_shipping_signature_selection_ajax') );
        add_action( 'wp_ajax_vabien_shipping_signature_selection', array($this, 'vabien_shipping_signature_selection_ajax') );

    }

    /**
     * Filter the rates
     */
    // public function custom_shipping_rates( $rates, $package ) {
    //     console_log($rates);
    //     console_log($package);
    //     $shipping_class = 64; // HERE set the shipping class ID
    //     $found = false;
    
    //     // Loop through cart items Checking for the defined shipping method
    //     foreach( $package['contents'] as $cart_item ) {
    //         if ( $cart_item['data']->get_shipping_class_id() == $shipping_class ){
    //             $found = true;
    //             break;
    //         }
    //     }
    
    //     if ( ! $found ) return $rates; // If not found we exit
    
    //     // Loop through shipping methods
    //     foreach( $rates as $rate_key => $rate ) {
    //         // all other shipping methods other than "Local Pickup"
    //         if ( 'local_pickup' !== $rate->method_id && $found ){
    
    //             // Your code here
    //         }
    //     }
    
    //     return $rates;
    // }
    // function checkout_shipping_additional_field( $method, $index )
    // {   
    //     console_log($method->get_id());
    //     if( $method->get_id() == 'flexible_shipping_fedex:0:INTERNATIONAL_ECONOMY' ){
    //         echo '<br>
    //         <input type="checkbox" name="shipping_custom_1" id="shipping_custom_1" value="1" class="shipping_method shipping_custom_1">
    //         <label for="shipping_custom_1">Custom label</label>';
    //     }
    // }

    /**
     * Debugging function to show the cart weight at checkout
     */
    public function vabien_print_cart_weight() {
        if($this->debug) {
            $notice = 'Your cart weight is: ' . WC()->cart->get_cart_contents_weight() . get_option( 'woocommerce_weight_unit' );
            if ( is_cart() ) {
                wc_print_notice( $notice, 'notice' );
            } else {
                wc_add_notice( $notice, 'notice' );
            }
        }
    }


    /**
     * Add the signature selection fields
     */
    public function signature_selection_custom_field( $method, $index ) {
        /// only for the US zone
        $chosen_shipping_methods = \WC()->session->get('chosen_shipping_methods')[ $index ];
        $chosen_method_id = explode(':', $chosen_shipping_methods);

        // Only on checkout page and for INTERNATIONAL_ECONOMY shipping method
        if($method->get_meta_data()['service_type'] !== 'INTERNATIONAL_ECONOMY' )
            return;

        // only for the US zone
        $packages    = WC()->shipping->get_packages();
        foreach ( $packages as $i => $package ) {
            if ( isset( $package['rates'] ) && isset( $package['rates'][ $chosen_shipping_methods ] ) ) {
                $package = $package;
                break;
            }
        }
        $shipping_zone = \WC_Shipping_Zones::get_zone_matching_package( $package );
        $zone          = $shipping_zone->get_zone_name();
        
        if($zone != 'US Domestic') 
            return;
        // Only when the chosen shipping method is "INTERNATIONAL_ECONOMY"
        if(!in_array('INTERNATIONAL_ECONOMY', $chosen_method_id))
            return;

        // check if an option is already set in the session
        $vabien_shipping_signature_selection = \WC()->session->get('vabien_shipping_signature_selection');
        ?>
        <div class="wrapper-vabien_shipping_signature_selection">
            <input type="hidden" name="vabien_shipping_signature_selection_shown" id="vabien_shipping_signature_selection_shown" value="yes">
            <ul style="list-style:none; margin-top: 5px;">
                <li style="margin-bottom:0;">
                
                    <input type="radio" id="vabien_signature_selection_signature_required" name="vabien_signature_selection" value="signature_required"  <?php checked( $vabien_shipping_signature_selection, 'signature_required' ); ?> >
                    <label for="vabien_signature_selection_signature_required">Require Signature</label>
                </li>
                <li>
                    <input type="radio" id="vabien_signature_selection_signature_waived" name="vabien_signature_selection" value="signature_waived" <?php checked( $vabien_shipping_signature_selection, 'signature_waived' ); ?> >
                    <label for="vabien_signature_selection_signature_waived">Waive Signature</label>
                </li>
            </ul>
            <div class="error-vabien_shipping_signature_selection">Please select a signature option</div>
        </div>
        <?php
    }

    /**
     * Validate the signature selection
     */
    public function signature_selection_checkout_validation() {

        // see if the signature options are shown
        if(isset($_POST['vabien_shipping_signature_selection_shown']) && sanitize_text_field($_POST['vabien_shipping_signature_selection_shown']) == 'yes') {

            $chosen_shipping_methods = \WC()->session->get('chosen_shipping_methods')[0];
            $chosen_method_id = explode(':', $chosen_shipping_methods);
            if($chosen_method_id != null) {
                if(in_array('INTERNATIONAL_ECONOMY', $chosen_method_id) && empty( $_POST['vabien_signature_selection'] ) )
                wc_add_notice( __("Please choose whether a signature should be required under the shipping section."), "error" );
            }
        } 
       
    }

    /**
     * Save the signature selection as custom order meta data
     */
    public function signature_selection_update_order_meta( $order ) {
        if( isset( $_POST['vabien_signature_selection'] ) && ! empty( $_POST['vabien_signature_selection'] ) )
            $order->update_meta_data( 'vabien_signature_selection', esc_attr( $_POST['vabien_signature_selection'] ) );
    }

    /**
     * Display the shipping selection in the order below the shipping address
     */
    // public function display_signature_selection_on_order_edit_pages( $order ){
    //     $vabien_signature_selection = $order->get_meta( 'vabien_signature_selection' );
    //     if($vabien_signature_selection != null) {
    //         if($vabien_signature_selection == 'signature_waived') {
    //             $vabien_signature_selection = 'Waived';
    //         } else if($vabien_signature_selection == 'signature_required') {
    //             $vabien_signature_selection = 'Required';
    //         }
    //         echo '<p><strong>Signature:</strong> '.$vabien_signature_selection.'</p>';
    //     }
       
    // }
    /**
     * Show the signature selection below the shipping method
     */
    public function display_signature_selection_below_shipping_method( $item_id, $item, $none ) {
        if($item->get_type() == 'shipping' && $item->get_method_title() == 'FedEx International Economy') {

            $order = wc_get_order($item->get_data()['order_id']);
            $vabien_signature_selection = $order->get_meta( 'vabien_signature_selection' );
            if($vabien_signature_selection != null) {
                if($vabien_signature_selection == 'signature_waived') {
                    $vabien_signature_selection = 'Waived';
                } else if($vabien_signature_selection == 'signature_required') {
                    $vabien_signature_selection = 'Required';
                }
                echo '<p><strong>Signature:</strong> '.$vabien_signature_selection.'</p>';
            }
        } // end if shipping

    }

    // Display the chosen pickup store below the chosen shipping method everywhere
    /**
     * Display the signature selection everywhere that is appropriate
     */
    public function display_signature_selection_on_order_item_totals( $total_rows, $order, $tax_display ){
        if( $vabien_signature_selection = $order->get_meta( 'vabien_signature_selection' ) ) {
            if($vabien_signature_selection == 'signature_waived') {
                $vabien_signature_selection = 'Waived';
            } else if($vabien_signature_selection == 'signature_required') {
                $vabien_signature_selection = 'Required';
            }
            $new_total_rows = [];

            // Loop through order total rows
            foreach( $total_rows as $key => $values ) {
                $new_total_rows[$key] = $values;
                // Inserting the pickup store under shipping method
                if( $key === 'shipping' ) {
                    $new_total_rows['vabien_signature_selection'] = array(
                        'label' => __("Signature:"),
                        'value' => $vabien_signature_selection
                    );
                }
            }
            return $new_total_rows;
        }

        return $total_rows;
    }

    /**
     * Ajax for the signature selection when shown in the cart
     */
    public function vabien_shipping_signature_selection_ajax() {
        $nonce_check = check_ajax_referer( 'vabien_site_functions', 'nonce' );
        $vabien_signature_selection = sanitize_text_field(esc_attr( $_POST['vabien_signature_selection'] ));


		\WC()->session->set( 'vabien_shipping_signature_selection', $vabien_signature_selection );
        $return_arr = array(
            'status' => 'Added signature selection to session data.',
        );

        // wl(\WC()->session->get('vabien_shipping_signature_selection'));
        wp_send_json($return_arr);

    }
}


$vabien_woocommerce_shipping = new WooCommerce_Shipping();