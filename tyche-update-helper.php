<?php
/**
Plugin Name: Tyche Update Helper
Plugin URI: #
Description: This plugin is used to activate the license.
Version: 1.0.0
Author: Tyche Softwares
Author URI: http://www.tychesoftwares.com/
Requires PHP: 5.6
Text Domain: woocommerce-tuh
WC requires at least: 3.0.0
WC tested up to: 4.4

@package Product-Delivery-Date-Pro-for-WooCommerce
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
