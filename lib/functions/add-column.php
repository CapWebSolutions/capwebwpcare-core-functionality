<?php
/**
 * Create Custom Column in the Manage Sites table for Last Secirty Scan date 
 * ref: https://meta.mainwp.com/t/solved-add-second-field-to-dashboard/3015
 * ref: https://meta.mainwp.com/t/display-group-s-column-in-the-manage-sites-table/2932
 * ref: https://meta.mainwp.com/t/create-custom-column-in-the-manage-sites-table-with-data-from-client-data/2877
 */ 


add_filter( 'mainwp_sitestable_getcolumns', 'mycustom_mainwp_sitestable_getcolumns', 10, 1 );
function mycustom_mainwp_sitestable_getcolumns( $columns ) {
	$columns['security_scan'] = "Security Scan";
	return $columns;
}

add_filter( 'mainwp_sitestable_item', 'mycustom_mainwp_sitestable_item', 10, 1 );
function mycustom_mainwp_sitestable_item( $item ) {
	global $mainWPSucuriExtensionActivator;

	$website = \MainWP\Dashboard\MainWP_DB::instance()->get_website_by_id( $item['id'], true );

	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );

	$website = (object) $website ;
	$sucuri = MainWP_Sucuri_DB::get_instance()->get_sucuri_by( 'site_id', $website->id );
	if ( $sucuri->lastscan != 0  ) {
		$item['security_scan'] = date( $date_format, $sucuri->lastscan ) . ' ' . date( $time_format, $sucuri->lastscan );
	} else {
		$item['security_scan'] = 'N/A';
	}
	return $item;

}

