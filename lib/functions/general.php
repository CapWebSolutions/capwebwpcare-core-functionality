<?php
/**
 * General
 *
 * This file contains any general functions.
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/capwebsolutions/capwebwpcare-core-functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @copyright    Copyright (c) 2017, Matt Ryan
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */


/**
 * Customize Menu Order
 *
 * @since 1.0.0
 *
 * @param array $menu_ord. Current order.
 * @return array $menu_ord. New order.
 */
function capweb_custom_menu_order( $menu_ord ) {
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
add_filter( 'custom_menu_order', 'capweb_custom_menu_order' );
add_filter( 'menu_order', 'capweb_custom_menu_order' );

// Keep MainWP site user logged in all the time.
function extend_auth_cookie_expiration( $expiration, $user_id, $remember ) {
    // Set the desired cookie expiration time
    $cookie_expire = 2 * MONTH_IN_SECONDS;

    // Return the new expiration time
    return $cookie_expire;
}
add_filter( 'auth_cookie_expiration', 'extend_auth_cookie_expiration', 10, 3 );
