<?php
/**
 * General
 *
 * This file contains any general functions
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/capwebsolutions/capwebwpcare-core-functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @copyright    Copyright (c) 2017, Matt Ryan
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Don't Update Plugin
 *
 * @since 1.0.0
 *
 * This prevents you being prompted to update if there's a public plugin
 * with the same name.
 *
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 * @param array  $r, request arguments
 * @param string $url, request url
 * @return array request arguments
 */
function be_core_functionality_hidden( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) ) {
		return $r; // Not a plugin update request. Bail immediately.
	}
	$plugins = unserialize( $r['body']['plugins'] );
	unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
	unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
	$r['body']['plugins'] = serialize( $plugins );
	return $r;
}
add_filter( 'http_request_args', 'be_core_functionality_hidden', 5, 2 );

// Enqueue / register needed scripts & styles
add_action( 'wp_enqueue_scripts', 'capweb_enqueue_needed_scripts' );
// add_action( 'admin_enqueue_scripts', 'CORE_FUNCTION_enqueue_needed_scripts' );
/**
 * Enque Needed Scripts
 * @since 1.0.0
 *
 * Enqueue scripts and styles needed by core functionality.
 *
 * @author Matt Ryan
 *
 * @param void
 * @return void
 */
function capweb_enqueue_needed_scripts() {
	wp_enqueue_style( 'font-awesome', 'https://use.fontawesome.com/73b396df58.css' );
	wp_enqueue_style( 'core-functionality', CORE_FUNCTION_URL . 'assets/css/core-functionality.css', array(), null, true );
}

/**
 * Customize Menu Order
 *
 * @since 1.0.0
 *
 * @param array $menu_ord. Current order.
 * @return array $menu_ord. New order.
 */
function be_custom_menu_order( $menu_ord ) {
	if ( ! $menu_ord ) { return true;
	}
	return array(
		'index.php', // this represents the dashboard link
		'edit.php?post_type=page', // the page tab
		'edit.php', // the posts tab
		'edit-comments.php', // the comments tab
		'upload.php', // the media manager
	);
}
add_filter( 'custom_menu_order', 'be_custom_menu_order' );
add_filter( 'menu_order', 'be_custom_menu_order' );

// Keep MainWP site user logged in all the time. 
add_filter('auth_cookie_expiration', function(){
    return YEAR_IN_SECONDS * 2;
});
