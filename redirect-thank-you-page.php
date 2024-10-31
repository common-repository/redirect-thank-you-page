<?php //phpcs:ignore
/**
 * Plugin Name:         Advanced Redirect Thank You for Woocommerce
 * Description:         Easily redirect your customers after checkout! Generate more opportunities to create a sale!
 * Version:             1.0
 * Author:              actpro
 * Author URI:          https://profiles.wordpress.org/actpro/
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         redirect-thank-you-page
 * Domain Path:         /languages
 * WC requires at least: 3.0
 * WC tested up to: 5.5.2
 *
 * @package Redirect_Thank_You_Page
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WMPRTY_PLUGIN_URL' ) ) {
	define( 'WMPRTY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'WMPRTY_PLUGIN_DIR' ) ) {
	define( 'WMPRTY_PLUGIN_DIR', dirname( __FILE__ ) );
}
if ( ! defined( 'WMPRTY_PLUGIN_DIR_PATH' ) ) {
	define( 'WMPRTY_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'WMPRTY_PLUGIN_BASENAME' ) ) {
	define( 'WMPRTY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
require_once plugin_dir_path( __FILE__ ) . 'settings/wmprty-constant.php';
/**
 * The code that runs during plugin activation.
 */
function wmprty_activate_fn() {
	if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
		wp_die( '<strong>' . esc_html( WMPRTY_PLUGIN_NAME ) . "</strong> plugin requires <strong>WooCommerce</strong>. Return to <a href='" . esc_url( get_admin_url( null, 'plugins.php' ) ) . "'>Plugins page</a>." );
	} else {
		update_option( 'wmprty_enable_logging', 'on' );
	}
}

register_activation_hook( __FILE__, 'wmprty_activate_fn' );
$prefix = is_network_admin() ? 'network_admin_' : '';
add_filter(
	"{$prefix}plugin_action_links_" . WMPRTY_PLUGIN_BASENAME,
	'wmprty_plugin_action_links',
	10
);
/**
 * Add helpful link in plugins section.
 *
 * @param array $actions associative array of action names to anchor tags.
 *
 * @return array associative array of plugin action links
 *
 * @since 1.0.0
 */
function wmprty_plugin_action_links( $actions ) {
	$custom_actions = array(
		'configure' => sprintf(
			'<a href="%s">%s</a>',
			esc_url(
				add_query_arg(
					array(
						'page' => 'wmprty-main',
					),
					admin_url( 'edit.php?post_type=product' )
				)
			),
			esc_html__( 'Settings', 'redirect-thank-you-page' )
		),
	);

	return array_merge( $custom_actions, $actions );
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
	if ( ! class_exists( 'WMPRTY_Init' ) ) {

		/**
		 * Class WMPRTY_Init
		 */
		class WMPRTY_Init {

			/**
			 * WMPRTY_Init constructor.
			 */
			public function __construct() {
				add_action( 'init', array( $this, 'init' ) );
				add_action( 'plugins_loaded', array( $this, 'wmprty_load_plugin_text_domain' ) );
			}

			/**
			 * Initialize the plugin files.
			 */
			public function init() {
				include_once plugin_dir_path( __FILE__ ) . 'settings/wmprty-common-function.php';
				include_once plugin_dir_path( __FILE__ ) . 'includes/class-wmprty-admin.php';
				include_once plugin_dir_path( __FILE__ ) . 'includes/class-wmprty-front.php';
			}

			/**
			 * Load text domain.
			 */
			public function wmprty_load_plugin_text_domain() {
				load_plugin_textdomain( 'redirect-thank-you-page', false, basename( dirname( __FILE__ ) ) . '/languages/' );
			}
		}
	}
	new WMPRTY_Init();
}
