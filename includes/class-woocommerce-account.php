<?php

namespace Vabien\Theme;

class WooCommerce_Account {

    public function __construct() {

        add_action( 'woocommerce_before_customer_login_form', array($this, 'vabien_before_customer_login_form'), 1, 1 );
        add_action( 'woocommerce_after_customer_login_form', array($this, 'vabien_after_customer_login_form'), 100, 1 );

        add_filter( 'woocommerce_account_menu_items', array($this, 'vabien_myaccount_items') );

    }
    
    /** 
     * Wrap the login form in a div
     */
    public function vabien_before_customer_login_form() {
        ?>
        <div class="vabien-login-form-wrapper">
            <div class="vabien-login-form">
        <?php
    }

    public function vabien_after_customer_login_form() {
        ?>
            </div>
        </div>
        <?php
    }

    /**
     * Change the links in the my account area
     */
    public function vabien_myaccount_items( $items ) {
        unset( $items['dashboard'] );
        unset( $items['payment-methods'] );
        return $items;
    }

}


$vabien_woocommerce_account = new WooCommerce_Account();