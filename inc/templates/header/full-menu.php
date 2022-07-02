<?php
/**
 * Full Menu
 *
 * @package WordPress
 * @subpackage pure-fashion
 * @since 1.0
 * @version 1.0
 */

?>
<nav class="vabien-menu-wrapper">
	<?php
	if ( has_nav_menu( 'nav-menu' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'nav-menu',
				'depth'          => 4,
				'container'      => false,
				'menu_class'     => 'vabien-menu',
				'walker'         => new thb_MegaMenu(),

			)
		);
	}
	?>
</nav>
