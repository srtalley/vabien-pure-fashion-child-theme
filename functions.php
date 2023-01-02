<?php
/**
 * luxi functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 *
 * @package luxi
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'THEME_DIR', __DIR__ );

require_once( THEME_DIR . '/includes/class-theme.php' );
require_once( THEME_DIR . '/includes/class-woocommerce.php' );
require_once( THEME_DIR . '/includes/class-woocommerce-account.php' );
require_once( THEME_DIR . '/includes/class-woocommerce-cart.php' );
require_once( THEME_DIR . '/includes/class-woocommerce-order.php' );
require_once( THEME_DIR . '/includes/class-woocommerce-product.php' );
require_once( THEME_DIR . '/includes/class-woocommerce-shipping.php' );
require_once( THEME_DIR . '/includes/class-woocommerce-shop.php' );
require_once( THEME_DIR . '/includes/class-sizing-help.php' );
require_once( THEME_DIR . '/includes/class-mega-menu.php' );

/**
 * Add the pinterest code
 */
function vabien_head() {
	echo '<meta name="p:domain_verify" content="b655a8740a47411bb94ed2bbe9f60803"/>';
}
add_action('wp_head', 'vabien_head');
// function register_custom_widget_area() {
// 	register_sidebar(
// 	array(
// 	'id' => 'new-widget-area',
// 	'name' => esc_html__( 'My new widget area', 'theme-domain' ),
// 	'description' => esc_html__( 'A new widget area made for testing purposes', 'theme-domain' ),
// 	'before_widget' => '<div id="%1$s" class="widget %2$s">',
// 	'after_widget' => '</div>',
// 	'before_title' => '<div class="widget-title-holder"><h3 class="widget-title">',
// 	'after_title' => '</h3></div>'
// 	)
// 	);
// 	}
// 	add_action( 'widgets_init', 'register_custom_widget_area' );
/*-----------------------------------------------------------------------------------*/
/* Quick Print Array functions for debugging
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'PrintArray' ) ) {
	function PrintArray( $array ) {

		if (WP_DEBUG) {
			echo "<pre>";
			print_r( $array );
			echo "</pre>";
		}

	}
}
/**
* Logging function to debug.log
*/
function wl ( $log )  {

    if(site_url() == "https://vabienusa.dev.dustysun.com") {
         // echo ini_get('error_log');
        // these lines fix an issue at runtime with soap and the Octolize plugin
        $log_path = '/var/www/html/wp-content/debug.log';
        ini_set( 'error_log', $log_path );
    }
   
    if ( is_array( $log ) || is_object( $log ) ) {
        error_log( print_r( $log, true ) );
    } else {
        error_log( $log );
    }
}

/**
 * Log to the console
 */
function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(Va Bien Message: ' . json_encode($output, JSON_HEX_TAG) . ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}
// NOTHING SHOULD BE ADDED TO THE FUNCTIONS FILE - PLEASE PLACE IT IN THE CORRECT FILE ABOVE AS A CLASS ENTRY




/**
 * Filter to hide the Gravity Forms "Add Form" button from posts.
 *
 * @return bool
 */
function hide_gf_add_form_button() {
	return false;
}
add_filter( 'gform_display_add_form_button', 'hide_gf_add_form_button' );