<?php
/**
 * Constant of all plugins title
 *
 * @package    Redirect_Thank_You_Page
 * @subpackage Redirect_Thank_You_Page/settings
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! defined( 'WMPRTY_PLUGIN_VERSION' ) ) {
	define( 'WMPRTY_PLUGIN_VERSION', '1.0' );
}
if ( ! defined( 'WMPRTY_SLUG' ) ) {
	define( 'WMPRTY_SLUG', 'redirect-thank-you-page' );
}
if ( ! defined( 'WMPRTY_PLUGIN_NAME' ) ) {
	define( 'WMPRTY_PLUGIN_NAME', 'Advanced Redirect Thank You for Woocommerce' );
}
if ( ! defined( 'WMPRTY_TEXT_DOMAIN' ) ) {
	define( 'WMPRTY_TEXT_DOMAIN', 'redirect-thank-you-page' );
}
if ( ! defined( 'WMPRTY_DFT_POST_TYPE' ) ) {
	define( 'WMPRTY_DFT_POST_TYPE', 'wmprty_cpo' );
}
if ( ! defined( 'WMPRTY_PAYMENT_GATEWAY_POST_TYPE' ) ) {
	define( 'WMPRTY_PAYMENT_GATEWAY_POST_TYPE', 'wmprty_pay_gat' );
}
if ( ! defined( 'WMPRTY_PRODUCT_FIELDS' ) ) {
	define( 'WMPRTY_PRODUCT_FIELDS', esc_html__( 'Select Product', 'redirect-thank-you-page' ) );
}
if ( ! defined( 'WMPRTY_PAYMENT_FIELDS' ) ) {
	define( 'WMPRTY_PAYMENT_FIELDS', esc_html__( 'Select Payment Gateway', 'redirect-thank-you-page' ) );
}
if ( ! defined( 'WMPRTY_PAGES_FIELDS' ) ) {
	define( 'WMPRTY_PAGES_FIELDS', esc_html__( 'Product Thank you Page', 'redirect-thank-you-page' ) );
}
if ( ! defined( 'WMPRTY_PAYMENT_PAGES_FIELDS' ) ) {
	define( 'WMPRTY_PAYMENT_PAGES_FIELDS', esc_html__( 'Payment Gateway Thank you Page', 'redirect-thank-you-page' ) );
}
