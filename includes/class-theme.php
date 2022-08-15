<?php
namespace Vabien\Theme;

class Theme_Setup {

	// protected static $_instance;

	public function __construct() {

		/*-----------------------------------------------------------------------------------*/
		/*  Script Control  */
		/*-----------------------------------------------------------------------------------*/

		add_action( 'wp_enqueue_scripts', array( $this, '_load_scripts' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, '_load_styles' ), 12 );
		add_action( 'wp_enqueue_scripts', array( $this, '_unload_styles' ), 9999 );

		/*-----------------------------------------------------------------------------------*/
		/* Clean Up  */
		/*-----------------------------------------------------------------------------------*/

		// add_filter( 'show_admin_bar', '__return_false' );
		remove_action( 'wp_head', 'rsd_link' );                                      // EditURI link
		remove_action( 'wp_head', 'wlwmanifest_link' );                              // windows live writer
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );        // links for adjacent posts
		remove_action( 'wp_head', 'wp_generator' );                                  // WP version
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		add_filter( 'the_generator', '__return_false' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'use_default_gallery_style', '__return_false' );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		add_action( 'wp_head', 'ob_start', 1, 0 );
		add_action( 'wp_head', function () {
			$pattern = '/.*' . preg_quote( esc_url( get_feed_link( 'comments_' . get_default_feed() ) ), '/' ) . '.*[\r\n]+/';
			echo preg_replace( $pattern, '', ob_get_clean() );
		}, 3, 0 );

		if ( ! class_exists( 'WPSEO_Frontend' ) ) {
			remove_action( 'wp_head', 'rel_canonical' );
			add_action( 'wp_head', array( $this, 'rel_canonical' ) );
		}
		add_filter( 'xmlrpc_methods', array( $this, 'remove_xmlrpc_pingback_ping' ) );
		add_filter('comment_flood_filter', '__return_false');

		/* Remove theme script setup and create a customized one */
		add_action( 'init', array($this, 'remove_thb_scripts_styles'), 10, 1 );
		// remove_action( 'wp_enqueue_scripts', 'thb_woocommerce_scripts_styles', 10001 );
		add_action( 'wp_enqueue_scripts', array($this, 'vabien_thb_woocommerce_scripts_styles'), 1001 );
		/*-----------------------------------------------------------------------------------*/
		/* Theme Settings  */
		/*-----------------------------------------------------------------------------------*/

		add_action( 'login_enqueue_scripts', array( $this, '_my_login_logo' ) );
		add_shortcode( 'bra_size_calculator', array( $this, 'bra_size_calculator_shortcode' ) );
		add_action( 'template_redirect', array( $this, '_block_user_enumeration_attempts' ) );


		/*-----------------------------------------------------------------------------------*/
		/* ACF Json syncing  */
		/*-----------------------------------------------------------------------------------*/

		if ( class_exists( 'acf' ) ) {

			add_filter( 'acf/settings/save_json', array( $this, '_acf_json_save_point' ) );
			add_filter( 'acf/settings/load_json', array( $this, '_acf_json_load_point' ) );

		}

		add_filter( 'gform_ajax_spinner_url', array($this, 'gravity_forms_spinner_url'), 10, 2 );

		/* LOGO */

		add_action( 'thb_logo', array($this, 'vabien_white_logo'), 2, 1 );

		/* Image Title and Alt Tags */
		add_filter( 'the_content', array( $this, 'img_move_title_to_alt_tag' ), 100 );
		add_filter( 'post_thumbnail_html', array( $this, 'img_move_title_to_alt_tag' ), 100 );
		add_filter( 'woocommerce_single_product_image_thumbnail_html', array($this, 'img_move_title_to_alt_tag'), 10 );

	}

	// public static function init() {
	// 	static::$_instance = new static();
	// }

	// public static function get_instance() {
	// 	if ( is_null( static::$_instance ) ) {
	// 		throw new \RuntimeException( 'You must initialize this instance before gaining access to it' );
	// 	}

	// 	return static::$_instance;
	// }

	public function _acf_json_save_point( $path ) {

		$path = get_stylesheet_directory() . '/acf-json';

		return $path;

	}

	public function _acf_json_load_point( $paths ) {

		unset( $paths[0] );

		$paths[] = get_stylesheet_directory() . '/acf-json';

		return $paths;

	}

	/**
	 * Block User Enumeration
	 */
	public function _block_user_enumeration_attempts() {
		if ( is_admin() ) {
			return;
		}

		$author_by_id = ( isset( $_REQUEST['author'] ) && is_numeric( $_REQUEST['author'] ) );

		if ( $author_by_id ) {
			wp_die( 'Author archives have been disabled.' );
		}
	}

	public function bra_size_calculator_shortcode( $atts ) {

		ob_start();

		get_template_part( 'views/content', 'bra-calculator-form' );

		return ob_get_clean();

	}

	public function remove_xmlrpc_pingback_ping( $methods ) {
		unset( $methods['pingback.ping'] );

		return $methods;
	}

	public function rel_canonical() {
		global $wp_the_query;

		if ( ! is_singular() ) {
			return;
		}

		if ( ! $id = $wp_the_query->get_queried_object_id() ) {
			return;
		}

		$link = get_permalink( $id );
		echo "\t<link rel=\"canonical\" href=\"$link\">\n";
	}


	public function _my_login_logo() { ?>
		<style type="text/css">
			.login h1 a {
				background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo.png) !important;
				background-size: 100% 100% !important;
				height: 68px !important;
				width: 300px !important;
			}
		</style>
	<?php }


	public function _load_scripts() {


		if ( ! is_admin() ) {

			wp_enqueue_script( 'magnific-popup', get_stylesheet_directory_uri() . '/lib/vendor/magnific-popup/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
			wp_enqueue_script( 'slick-slider', get_stylesheet_directory_uri() . '/lib/vendor/slick/slick.min.js', array( 'jquery' ), '1.9.0', true );
			wp_enqueue_script( 'perfect-scrollbar', get_stylesheet_directory_uri() . '/lib/vendor/perfect-scrollbar/perfect-scrollbar.min.js', array( 'jquery' ), '1.5.5', true );
			wp_enqueue_script( 'gsap', get_stylesheet_directory_uri() . '/lib/vendor/gsap/gsap.min.js', array( 'jquery' ), '3.5.1', true );
			wp_enqueue_script( 'bezier-easing', get_stylesheet_directory_uri() . '/lib/vendor/bezier-easing/bezier-easing.js', array( 'jquery' ), '3.5.1', true );
			wp_enqueue_script( 'vabien-site', get_stylesheet_directory_uri() . '/assets/js/site.js', array( 'jquery' ), wp_get_theme()->get('Version'), true );

			wp_localize_script( 'vabien-site', 'vabien_site_functions', array(
				'ajaxurl'   => admin_url( 'admin-ajax.php' ),
				'ajaxnonce' => wp_create_nonce( 'vabien_site_functions' )
			  ) );
			// wp_localize_script(
			// 	'vabien-site',
			// 	array(
			// 		'url'      => admin_url( 'admin-ajax.php' ),
			// 		'l10n'     => array(
			// 			'loadmore'        => esc_html__( 'Load More', 'north' ),
			// 			'loading'         => esc_html__( 'Loading ...', 'north' ),
			// 			'nomore'          => esc_html__( 'All Posts Loaded', 'north' ),
			// 			'nomore_products' => esc_html__( 'All Products Loaded', 'north' ),
			// 			'results_found'   => esc_html__( 'results found.', 'north' ),
			// 			'results_all'     => esc_html__( 'View All Results', 'north' ),
			// 			'adding_to_cart'  => esc_html__( 'Adding to Cart', 'north' ),
			// 			'added_to_cart'   => esc_html__( 'Added to Cart', 'north' ),
			// 		),
			// 		'nonce'    => array(
			// 			'product_quickview' => wp_create_nonce( 'thb_quickview_ajax' ),
			// 			'autocomplete_ajax' => wp_create_nonce( 'thb_autocomplete_ajax' ),
			// 		),

			// 	)
			// );
		}
	}


	public function _load_styles() {
		
		wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', '', wp_get_theme()->get('Version'), 'all' );

		wp_enqueue_style( 'vabien-child-style', get_stylesheet_directory_uri() . '/style.css', '', wp_get_theme()->get('Version'), 'all' );

		wp_enqueue_style( 'magnific-popup', get_stylesheet_directory_uri() . '/lib/vendor/magnific-popup/magnific-popup.css', '', '1.1.0', 'all' );

		wp_enqueue_style( 'slick-slider', get_stylesheet_directory_uri() . '/lib/vendor/slick/slick.css', '', '1.9.0', 'all' );


	}

	public function _unload_styles() {
		wp_dequeue_style('thb-style');
	}

	public function _unload_scripts() {

		if ( ! is_admin() ) {
			wp_deregister_script( 'wp-embed' );
		}

	}
	public function remove_thb_scripts_styles() {
		remove_action( 'wp_enqueue_scripts', 'thb_woocommerce_scripts_styles', 10001 );
	}		
	/**
	 * Customized version of theme script setup
	 */
	public function vabien_thb_woocommerce_scripts_styles() {
		if ( ! is_admin() ) {
			if ( thb_wc_supported() ) {
				wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
				wp_deregister_style( 'woocommerce_prettyPhoto_css' );
	
				wp_dequeue_style( 'jquery-selectBox' );
				wp_dequeue_script( 'jquery-selectBox' );
	
				// if ( ! class_exists( 'WC_Checkout_Add_Ons_Loader' ) ) {
				// 	wp_dequeue_style( 'selectWoo' );
				// 	wp_deregister_style( 'selectWoo' );
				// 	wp_dequeue_script( 'selectWoo' );
				// 	wp_deregister_script( 'selectWoo' );
				// }
	
				// if ( ! is_checkout() ) {
				// 	wp_dequeue_script( 'jquery-selectBox' );
				// 	wp_dequeue_style( 'selectWoo' );
				// 	wp_dequeue_script( 'selectWoo' );
				// }
			}
		}
	}
		
	// Changes Gravity Forms Ajax Spinner (next, back, submit) to a transparent image
	// this allows you to target the css and create a pure css spinner like the one used below in the style.css file of this gist.
	public function gravity_forms_spinner_url( $image_src, $form ) {
		return  'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; // relative to you theme images folder
	}
	/**
	 * Add a white logo for transparent pages
	 */
	public function vabien_white_logo( $section = false ) {
	?>
	<div class="logo-holder transparent">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logolink" title="<?php echo esc_attr( bloginfo( 'name' ) ); ?>">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-white.png" alt="<?php echo esc_attr( bloginfo( 'name' ) ); ?>" class="logoimg transparent" />
		</a>
	</div>
	
	<?php
	}

		/**
	 * Move the title tag to the alt tag if the alt tag is empty
	 */
	public function img_move_title_to_alt_tag($content) {
		$dom = new \DOMDocument( '1.0', 'UTF-8' );
        

		@$dom->loadHTML( mb_convert_encoding( "<div class='vabien-container'>{$content}</div>", 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

        $html = new \DOMXPath( $dom );
        foreach ( $html->query( "//img" ) as $node ) {
            // Return image URL
            $img_url = $node->getAttribute( "src" );

            // Set alt for Post & Pages & Products
            if ( is_singular( array( 'post', 'page', 'product' ) ) ) {
                if ( empty($node->getAttribute( 'alt' )) ) {
					if( !empty($node->getAttribute('title'))) {
						$node->setAttribute( "alt", $node->getAttribute('title'));
					}
				}
				$node->setAttribute( "title", '');
            }
        }
        // Set alt for Post/Pages/Products
        if ( is_singular( array( 'post', 'page', 'product' ) ) ) {
			$content = $dom->saveHtml();
		}
        return $content;
	}
}

$vabien_theme_setup = new Theme_Setup();
