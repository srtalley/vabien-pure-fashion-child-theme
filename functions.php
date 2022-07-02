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
require_once( THEME_DIR . '/includes/class-woocommerce-shop.php' );
require_once( THEME_DIR . '/includes/class-sizing-help.php' );
require_once( THEME_DIR . '/includes/class-mega-menu.php' );

// add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
// function theme_enqueue_styles() {
//     wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
// }
// add_action( 'wp_enqueue_scripts', '_load_styles' , 10 );

//  function _load_styles() {
// 	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', '', wp_get_theme()->get('Version'), 'all' );

// 	wp_enqueue_style( 'vabien-child-style', get_stylesheet_directory_uri() . '/style.css', '', wp_get_theme()->get('Version'), 'all' );

// 	// wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/main.js', '', '1.2.2', true );

// }
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
    if ( is_array( $log ) || is_object( $log ) ) {
        error_log( print_r( $log, true ) );
    } else {
        error_log( $log );
    }
}

// NOTHING CHOULD BE ADDED TO THE FUNCTIONS FILE - PLEASE PLACE IT IN THE CORRECT FILE ABOVE AS A CLASS ENTRY