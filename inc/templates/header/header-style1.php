<?php
/**
 * Header Template
 *
 * @package WordPress
 * @subpackage pure-fashion
 * @since 1.0
 * @version 1.0
 */

$header_class[] = 'header';
$header_class[] = 'thb-main-header';
$header_class[] = thb_customizer( 'fixed_header_shadow', 'thb-fixed-shadow-style1' );

// CUSTOM -- MOVED SUBHEADER CODE HERE
$global_notification = thb_customizer( 'subheader', 1 );
// END CUSTOM
?>

<div class="header-wrapper">

	<header class="<?php echo esc_attr( implode( ' ', $header_class ) ); ?>">
		<aside class="subheader dark">
			<div class="row">
				<div class="small-12 columns">
					<?php echo do_shortcode( thb_customizer( 'subheader_content' ) ); ?>
				</div>
			</div>
		</aside>
		<div class="header-logo-row">
			<div class="row align-middle full-width-row">
				<div class="small-3 large-4 columns">
					<?php do_action( 'thb_mobile_toggle' ); ?>
					<div class="thb-navbar">
						<?php get_template_part( 'inc/templates/header/full-menu' ); ?>
					</div>
				</div>
				<div class="small-6 large-4 columns">
					<?php do_action( 'thb_logo' ); ?>
				</div>
				<div class="small-3 large-4 columns">
					<?php do_action( 'thb_secondary_area' ); ?>
				</div>
			</div>
		</div>
	</header>
</div>
