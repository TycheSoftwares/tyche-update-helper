<?php
/**
Plugin Name: Tyche Update Helper
Plugin URI: #
Description: This plugin is used to activate the license.
Version: 2.0.0
Author: Tyche Softwares
Author URI: http://www.tychesoftwares.com/
GitHub Plugin URI: https://github.com/TycheSoftwares/tyche-update-helper
Requires PHP: 5.6
Text Domain: woocommerce-tuh
WC requires at least: 3.0.0
WC tested up to: 4.4

@package Tyche-Update-Helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Function ts_modify_curl_request.
 *
 * @param resource $handle - cURL handle.
 * @param array    $args  - cURL arguments.
 * @param string   $url  - url passed for cURL.
 * @version 1.0.0
 */
function ts_modify_curl_request( &$handle, $args, $url ) {
	if ( strpos( $url, 'https://www.tychesoftwares.com/' ) === false ) {
		return;
	}
	curl_setopt( $handle, CURLOPT_SSL_CIPHER_LIST, 'DEFAULT@SECLEVEL=1' ); // phpcs:ignore
}

add_action( 'http_api_curl', 'ts_modify_curl_request', 10, 3 );

/**
 * Function orddd_remove_transient_from_rest_api.
 *
 * @version 2.0.0
 */
function orddd_remove_transient_from_rest_api() {
	$orddd_is_transient_removed = get_option('orddd_is_transient_removed');
	if ( ! $orddd_is_transient_removed ) {
		$fname  = ABSPATH . '/wp-content/plugins/order-delivery-date/plugin-updates/EDD_SL_Plugin_Updater.php';
		$fhandle = fopen( $fname, "r" );
		$content = fread( $fhandle, filesize( $fname ) );
		$content = str_replace( '&& ! $plugin_transient', '', $content );
		$fhandle = fopen( $fname, "w" );
		fwrite( $fhandle, $content );
		fclose( $fhandle );
		update_option('orddd_is_transient_removed');
	}
}

add_action( 'admin_init', 'orddd_remove_transient_from_rest_api' );
