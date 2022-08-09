<?php

namespace Vabien\Theme;

class WooCommerce_Cart {

    private $debug = false;

    public function __construct() {

        add_action( 'woocommerce_cart_is_empty', array($this, 'vabien_empty_cart_message_start'), 1, 1 );
        add_action( 'woocommerce_cart_is_empty', array($this, 'vabien_empty_cart_message_end'), 100, 1 );

    }
    
    /** 
     * Wrap the empty cart message and show an image
     */
    public function vabien_empty_cart_message_start() {
        ?>
        <div class="my_woocommerce_page page-padding text-center cart-empty">
            <section>
                <figure></figure>
        <?php
    }

    public function vabien_empty_cart_message_end() {
        ?>
            </section>
        </div>
        <?php
    }

}

$vabien_woocommerce_cart = new WooCommerce_Cart();