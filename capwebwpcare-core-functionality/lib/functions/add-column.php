<?php
/**
 * Create Custom Column in the Manage Sites table with Data from Client data 
 * Creating a custom column with Client data should be fairly simple, here is a code example for creating a Client Email 
 * column that will show your client’s email address saved in the Side Edit page for the [client.email] token.
 */ 


add_filter( 'mainwp_sitestable_getcolumns', 'mycustom_mainwp_sitestable_getcolumns', 10, 1 );
function mycustom_mainwp_sitestable_getcolumns( $columns ) {
	$columns['security_scan'] = "Security Scan";
	return $columns;
}

add_filter( 'mainwp_sitestable_item', 'mycustom_mainwp_sitestable_item', 10, 1 );
function mycustom_mainwp_sitestable_item( $item ) {
	global $mainWPSucuriExtensionActivator;


	$websites = apply_filters( 'mainwp_getsites', $mainWPSucuriExtensionActivator->get_child_file(), $mainWPSucuriExtensionActivator->get_child_key() );
	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );
	if ( $websites ) :
		foreach ( $websites as $website ) {
			$website = (object) $website ;
			$sucuri = MainWP_Sucuri_DB::get_instance()->get_sucuri_by( 'site_id', $website->id );
			if ( $sucuri->lastscan != 0  ) {
				$item['security_scan'] = date( $date_format, $sucuri->lastscan ) . ' ' . date( $time_format, $sucuri->lastscan );
			} else {
				$item['security_scan'] = 'N/A';
			}
		}
	endif;
	return $item;

}
