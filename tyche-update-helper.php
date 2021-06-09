<?php
/**
Plugin Name: Tyche Update Helper
Plugin URI: https://www.tychesoftwares.com/premium-woocommerce-plugins/
Description: This plugin is used to activate the license.
Version: 1.1.0
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

if ( ! class_exists( 'Puc_v4_Factory' ) ) {
	// load our custom updater if it doesn't already exist.
	include dirname( __FILE__ ) . '/plugin-update-checker/plugin-update-checker.php';
}

/**
 * Setup the updater
 *
 * @since 1.1.0
 */
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://staging.tychesoftwares.com/nitin/details.json',
	__FILE__,
	'tyche-update-helper'
);

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
 * @version 1.1.0
 */
function tyh_remove_transient_code() {
	$orddd_is_transient_removed = get_option('orddd_is_transient_removed');
	$acpro_is_transient_removed = get_option('acpro_is_transient_removed');
	$is_orddd_plugin_active     = is_plugin_active( 'order-delivery-date/order_delivery_date.php' );
	$is_acpro_plugin_active     = is_plugin_active( 'woocommerce-abandon-cart-pro/woocommerce-ac.php' );

	// This will remove the unwanted transient from Order Delivery Date Pro for WooCommerce plugin.
	if ( ! $orddd_is_transient_removed && $is_orddd_plugin_active ) {
		$fname   = ABSPATH . '/wp-content/plugins/order-delivery-date/plugin-updates/EDD_SL_Plugin_Updater.php';
		$fhandle = fopen( $fname, "r" );
		$content = fread( $fhandle, filesize( $fname ) );
		$content = str_replace( '&& ! $plugin_transient', '', $content );
		$fhandle = fopen( $fname, "w" );
		fwrite( $fhandle, $content );
		fclose( $fhandle );
		update_option( 'orddd_is_transient_removed', 'yes' );
	}

	// This will remove the unwanted transient from Abandoned Cart Pro for WooCommerce plugin.
	if ( ! $acpro_is_transient_removed && $is_acpro_plugin_active ) {
		$fname   = ABSPATH . '/wp-content/plugins/woocommerce-abandon-cart-pro/plugin-updates/EDD_AC_WOO_Plugin_Updater.php';
		$fhandle = fopen( $fname, "r" );
		$content = fread( $fhandle, filesize( $fname ) );
		$content = str_replace( '&& ! $plugin_transient', '', $content );
		$fhandle = fopen( $fname, "w" );
		fwrite( $fhandle, $content );
		fclose( $fhandle );
		update_option( 'acpro_is_transient_removed', 'yes' );
	}
}

add_action( 'admin_init', 'tyh_remove_transient_code' );
