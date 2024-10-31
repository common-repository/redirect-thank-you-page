<?php
/**
 * Admin section.
 *
 * @package    Redirect_Thank_You_Page
 * @subpackage Redirect_Thank_You_Page/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WMPRTY_Admin class.
 */
if ( ! class_exists( 'WMPRTY_Admin' ) ) {
	/**
	 * WMPRTY_Admin class.
	 */
	class WMPRTY_Admin {

		/**
		 * Instance of class.
		 *
		 * @var $_instance Instance.
		 *
		 * @since 1.0.0
		 */
		protected static $_instance = null;
		/**
		 * The name of this plugin.
		 *
		 * @since 1.0.0
		 * @var   string $plugin_name The ID of this plugin.
		 */
		private $plugin_name;
		/**
		 * The version of this plugin.
		 *
		 * @since 1.0.0
		 * @var   string $version The current version of this plugin.
		 */
		private $version;
		/**
		 * Get current page.
		 *
		 * @var $page Store current page.
		 *
		 * @since 1.0.0
		 */
		private $page;
		/**
		 * Get current tab.
		 *
		 * @var $current_tab Store current tab.
		 *
		 * @since 1.0.0
		 */
		private $current_tab;

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->plugin_name = 'Advanced Redirect Thank You for Woocommerce';
			$this->version     = WMPRTY_PLUGIN_VERSION;
			include_once WMPRTY_PLUGIN_DIR . '/includes/class-wmprty-field-setting.php';
			include_once WMPRTY_PLUGIN_DIR . '/includes/class-wmprty-product-setting.php';
			$this->wmprty_init();

		}

		/**
		 * Define the plugins name and versions and also call admin section.
		 *
		 * @since 1.0.0
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Register actions and filters.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_init() {
			$prefix = is_network_admin() ? 'network_admin_' : '';
			add_action( 'admin_menu', array( $this, 'wmprty_menu' ) );
			add_action( 'init', array( $this, 'wmprty_post_type' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'wmprty_enqueue_scripts_fn' ) );
			add_filter( 'wmprty_getting_page', array( $this, 'wmprty_getting_page_fn' ), 10 );
			add_filter( 'wmprty_ie_admin_tab_ft', array( $this, 'wmprty_admin_tab' ), 10 );
			add_filter( "{$prefix}plugin_action_links_" . plugin_basename( __FILE__ ), array( $this, 'wmprty_plugin_action_links' ), 10 );

			add_action( 'wp_ajax_wmprty_get_data_based_on_cd', array( $this, 'wmprty_get_data_based_on_cd' ) );
			add_action( 'wp_ajax_nopriv_wmprty_get_data_based_on_cd', array( $this, 'wmprty_get_data_based_on_cd' ) );

			add_action( 'wp_ajax_wmprty_get_data_based_on_payments_cd', array( $this, 'wmprty_get_data_based_on_payments_cd' ) );
			add_action( 'wp_ajax_nopriv_wmprty_get_data_based_on_payments_cd', array( $this, 'wmprty_get_data_based_on_payments_cd' ) );

			add_action( 'wp_ajax_wmprty_get_pages_data', array( $this, 'wmprty_get_pages_data' ) );
			add_action( 'wp_ajax_nopriv_wmprty_get_pages_data', array( $this, 'wmprty_get_pages_data' ) );

		}

		/**
		 * Post type register
		 *
		 * @since 1.0.0
		 */
		public function wmprty_post_type() {

			register_post_type(
				WMPRTY_DFT_POST_TYPE,
				array(
					'labels' => array(
						'name'          => esc_html__( 'Advanced Redirect Thank You for Woocommerce', 'redirect-thank-you-page' ),
						'singular_name' => esc_html__( 'Advanced Redirect Thank You for Woocommerce', 'redirect-thank-you-page' ),
					),
				)
			);
			register_post_type(
				WMPRTY_PAYMENT_GATEWAY_POST_TYPE,
				array(
					'labels' => array(
						'name'          => esc_html__( 'Woo Payment Redirect Thank You', 'redirect-thank-you-page' ),
						'singular_name' => esc_html__( 'Woo Payment Redirect Thank You', 'redirect-thank-you-page' ),
					),
				)
			);

		}

		/**
		 * Where condition for WP_Query.
		 *
		 * @param mixed $where    Where condition for WP_Query.
		 *
		 * @param mixed $wp_query WP_Query - For getting posts data.
		 *
		 * @return mixed $where Where condition for WP_Query.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_posts_where( $where, $wp_query ) {
			global $wpdb;
			$search_term = $wp_query->get( 'search_pro_title' );
			if ( ! empty( $search_term ) ) {
				$search_term_like = $wpdb->esc_like( $search_term );
				$where           .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
			}

			return $where;
		}

		/**
		 * Get data based on conditional rule.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_get_data_based_on_cd() {
			$request_value = filter_input( INPUT_POST, 'value', FILTER_SANITIZE_STRING );
			$post_value    = isset( $request_value ) ? sanitize_text_field( $request_value ) : '';
			$data_array    = array();
			$args          = array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => '-1',
				'fields'         => 'ids',
			);
			if ( isset( $post_value ) ) {
				$args['search_pro_title'] = $post_value;
				add_filter( 'posts_where', array( $this, 'wmprty_posts_where' ), 10, 2 );
				$prd_query = new WP_Query( $args );
				remove_filter( 'posts_where', array( $this, 'wmprty_posts_where' ), 10, 2 );
			} else {
				$prd_query = new WP_Query( $args );
			}
			if ( ! empty( $prd_query ) ) {
				foreach ( $prd_query->posts as $prd_id ) {
					$data_array[] = array( $prd_id, get_the_title( $prd_id ) );
				}
			}

			echo wp_json_encode( $data_array );
			wp_die();
		}

		/**
		 * Get data based on conditional rule.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_get_data_based_on_payments_cd() {

			$gateways         = WC()->payment_gateways->get_available_payment_gateways();
			$enabled_gateways = array();

			if ( $gateways ) {
				foreach ( $gateways as $gateway ) {

					if ( 'yes' === $gateway->enabled ) {

						$enabled_gateways[] = array( $gateway->id, $gateway->title );

					}
				}
			}

			echo wp_json_encode( $enabled_gateways );
			wp_die();
		}

		/**
		 * Get data based on conditional rule.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_get_pages_data() {
			$request_value = filter_input( INPUT_POST, 'value', FILTER_SANITIZE_STRING );
			$post_value    = isset( $request_value ) ? sanitize_text_field( $request_value ) : '';
			$data_array    = array();
			$args          = array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => '-1',
				'fields'         => 'ids',
			);
			if ( isset( $post_value ) ) {
				$args['search_pro_title'] = $post_value;
				add_filter( 'posts_where', array( $this, 'wmprty_posts_where' ), 10, 2 );
				$prd_query = new WP_Query( $args );
				remove_filter( 'posts_where', array( $this, 'wmprty_posts_where' ), 10, 2 );
			} else {
				$prd_query = new WP_Query( $args );
			}
			if ( ! empty( $prd_query ) ) {
				foreach ( $prd_query->posts as $prd_id ) {
					$data_array[] = array( $prd_id, get_the_title( $prd_id ) );
				}
			}

			echo wp_json_encode( $data_array );
			wp_die();
		}

		/**
		 * Using tab array.
		 *
		 * @return array $tab_array
		 *
		 * @since 1.0.0
		 */
		public static function wmprty_admin_action_tab_fn() {

			$current_tab_array = array(
				'field-section'           => esc_html__( 'Product Redirect', 'redirect-thank-you-page' ),
				'payment-gateway-section' => esc_html__( 'Payment Gateway Redirect', 'redirect-thank-you-page' ),
				'about_info'              => esc_html__( 'Getting Started', 'redirect-thank-you-page' ),
			);

			return $current_tab_array;
		}

		/**
		 * Getting Tab array.
		 *
		 * @param array $aon_tab_array Checking array tab.
		 *
		 * @return array $tab_array Checking array tab.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_admin_tab( $aon_tab_array ) {
			$current_tab_array = $this->wmprty_admin_action_tab_fn();
			if ( ! empty( $aon_tab_array ) ) {
				$tab_array = array_merge( $current_tab_array, $aon_tab_array );
			} else {
				$tab_array = $current_tab_array;
			}

			return $tab_array;
		}

		/**
		 * Add menu in woocommerce main menu.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_menu() {
			$this->screen_id = add_submenu_page(
				'edit.php?post_type=product',
				'Redirect Thank You',
				'Redirect Thank You',
				'edit_products',
				'wmprty-main',
				array(
					$this,
					'wmprty_main_fn',
				)
			);
		}

		/**
		 * Enqueue plugins css and js for admin purpose.
		 *
		 * @param string $hook Hooks.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_enqueue_scripts_fn( $hook ) {

			$rtl_css = is_rtl() ? '-rtl' : '';
			$min     = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
			$text    = array(
				'ajaxurl'                       => admin_url( 'admin-ajax.php' ),
				'placeholder_product_select'    => esc_html__( 'Please select product', 'redirect-thank-you-page' ),
				'placeholder_product_thank_you' => esc_html__( 'Select a page from your website', 'redirect-thank-you-page' ),
				'placeholder_payment_select'    => esc_html__( 'Please select payment gateway', 'redirect-thank-you-page' ),
				'placeholder_payment_thank_you' => esc_html__( 'Select a page from your website', 'redirect-thank-you-page' ),

			);

			if ( false !== strpos( $hook, 'wmprty-main' ) ) {
				wp_enqueue_style( 'wmprty-admin-css', WMPRTY_PLUGIN_URL . 'assets/css/wmprty-admin' . $rtl_css . $min . '.css', array(), $this->version );
				wp_enqueue_style( 'select2-min-css', WMPRTY_PLUGIN_URL . 'assets/css/lib/select2.min.css', array(), $this->version );
				if ( class_exists( 'Woocommerce' ) ) {
					wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin' . $rtl_css . $min . '.css', array(), $this->version );
				}
				wp_enqueue_script( 'select2-min-js', WMPRTY_PLUGIN_URL . 'assets/js/lib/select2.full.min.js', array( 'jquery' ), $this->version, true );
				wp_enqueue_style( 'jquery-ui-css', WMPRTY_PLUGIN_URL . 'assets/css/lib/jquery-ui.min.css', array(), $this->version );
				wp_enqueue_script( 'wmprty-admin-js', WMPRTY_PLUGIN_URL . 'assets/js/wmprty-admin' . $min . '.js', array( 'jquery', 'jquery-tiptip' ), $this->version, true );
				wp_localize_script( 'wmprty-admin-js', 'wmprty_var', $text );
			} elseif ( 'woocommerce_page_wc-settings' === $hook && 'wmprty_thank_you' === filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) && 'wc-settings' === filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) ) {
				wp_enqueue_style( 'wmprty-admin-css', WMPRTY_PLUGIN_URL . 'assets/css/wmprty-admin' . $rtl_css . $min . '.css', array(), $this->version );
				wp_enqueue_script( 'wmprty-admin-js', WMPRTY_PLUGIN_URL . 'assets/js/wmprty-admin' . $min . '.js', array( 'jquery', 'jquery-tiptip' ), $this->version, true );
				wp_localize_script( 'wmprty-admin-js', 'wmprty_var', $text );
			}
		}

		/**
		 * Manage Field List Page.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_main_fn() {
			$this->page        = self::wmprty_current_page();
			$this->current_tab = self::wmprty_current_tab();
			$current_tab_array = do_action( 'wmprty_admin_action_current_tab' );
			if ( has_filter( 'wmprty_ie_admin_tab_ft' ) ) {
				$tabing_array = apply_filters( 'wmprty_ie_admin_tab_ft', $current_tab_array );
			} else {
				$tabing_array = apply_filters( 'wmprty_admin_tab_ft', '' );
			}
			?>
			<div class="wrap woocommerce">
				<form method="post" enctype="multipart/form-data">
					<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
						<?php
						foreach ( $tabing_array as $name => $label ) {
							$url = self::dynamic_url( $this->page, $name );
							echo '<a href="' . esc_url( $url ) . '" class="nav-tab ';
							if ( $this->current_tab === $name ) {
								echo 'nav-tab-active';
							}
							echo '">' . esc_html( $label ) . '</a>';
						}
						?>
					</nav>
					<?php
					if ( has_filter( 'wmprty_ie_admin_page_ft' ) ) {
						apply_filters( 'wmprty_ie_admin_page_ft', $this->current_tab );
						apply_filters( 'wmprty_getting_page', $this->current_tab );
					} else {
						apply_filters( 'wmprty_getting_page', $this->current_tab );
					}
					?>
				</form>
			</div>
			<?php
		}

		/**
		 * Getting dynamic url.
		 *
		 * @param string $page_name Getting page name.
		 * @param string $tab_name  Getting tab name.
		 * @param string $action    Getting action.
		 * @param string $post_id   Getting current post id.
		 * @param string $nonce     Checking nonce if available in url.
		 * @param string $message   Checking if any dynamic messages pass in url.
		 *
		 * @return mixed $url return url.
		 *
		 * @since 1.0.0
		 */
		public function dynamic_url( $page_name, $tab_name, $action = '', $post_id = '', $nonce = '', $message = '' ) {
			$url_param = array();
			if ( ! empty( $page_name ) ) {
				$url_param['page'] = $page_name;
			}
			if ( ! empty( $tab_name ) ) {
				$url_param['tab'] = $tab_name;
			}
			if ( ! empty( $action ) ) {
				$url_param['action'] = $action;
			}
			if ( ! empty( $post_id ) ) {
				$url_param['post'] = $post_id;
			}
			if ( ! empty( $nonce ) ) {
				$url_param['_wpnonce'] = $nonce;
			}
			if ( ! empty( $message ) ) {
				$url_param['message'] = $message;
			}
			$url = add_query_arg(
				$url_param,
				admin_url( 'edit.php?post_type=product' )
			);

			return $url;
		}

		/**
		 * Getting Page.
		 *
		 * @param string $current_tab Getting current tab name.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_getting_page_fn( $current_tab ) {
			if ( 'field-section' === $current_tab ) {
				$wmprty_sms = new WMPRTY_Field_Setting();
				$wmprty_sms->wmprty_output();
			} elseif ( 'payment-gateway-section' === $current_tab ) {
				$wmprty_product = new WMPRTY_Product_Setting();
				$wmprty_product->wmprty_output();
			} elseif ( 'about_info' === $current_tab ) {
				include_once WMPRTY_PLUGIN_DIR . '/settings/wmprty-about-info.php';
			}
		}

		/**
		 * Get current page.
		 *
		 * @return string $current_page Getting current page name.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_current_page() {
			$current_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );

			return $current_page;
		}

		/**
		 * Get current tab.
		 *
		 * @return string $current_tab Getting current tab name.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_current_tab() {
			$current_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			if ( ! isset( $current_tab ) ) {
				$current_tab = 'field-section';
			}

			return $current_tab;
		}

		/**
		 * Validate message for plugins form.
		 *
		 * @param string $message        Custom Validate message for plugins form.
		 *
		 * @param string $tab            Get current tab for current page.
		 *
		 * @param string $validation_msg Display validation error.
		 *
		 * @return bool
		 *
		 * @since 1.0.0
		 */
		public function wmprty_updated_message( $message, $tab, $validation_msg ) {
			if ( empty( $message ) ) {
				return false;
			}
			if ( 'field-section' === $tab ) {
				if ( 'created' === $message ) {
					$updated_message = esc_html__( 'Redirection successfully created.', 'redirect-thank-you-page' );
				} elseif ( 'saved' === $message ) {
					$updated_message = esc_html__( 'Redirection successfully updated.', 'redirect-thank-you-page' );
				} elseif ( 'deleted' === $message ) {
					$updated_message = esc_html__( 'Redirection deleted.', 'redirect-thank-you-page' );
				} elseif ( 'duplicated' === $message ) {
					$updated_message = esc_html__( 'Redirection duplicated.', 'redirect-thank-you-page' );
				} elseif ( 'disabled' === $message ) {
					$updated_message = esc_html__( 'Redirection disabled.', 'redirect-thank-you-page' );
				} elseif ( 'enabled' === $message ) {
					$updated_message = esc_html__( 'Redirection enabled.', 'redirect-thank-you-page' );
				}
				if ( 'failed' === $message ) {
					$failed_messsage = esc_html__( 'There was an error with saving data.', 'redirect-thank-you-page' );
				} elseif ( 'nonce_check' === $message ) {
					$failed_messsage = esc_html__( 'There was an error with security check.', 'redirect-thank-you-page' );
				}
				if ( 'validated' === $message ) {
					$validated_messsage = esc_html( $validation_msg );
				}
			} else {
				if ( 'saved' === $message ) {
					$updated_message = esc_html__( 'Settings save successfully', 'redirect-thank-you-page' );
				}
				if ( 'nonce_check' === $message ) {
					$failed_messsage = esc_html__( 'There was an error with security check.', 'redirect-thank-you-page' );
				}
				if ( 'validated' === $message ) {
					$validated_messsage = esc_html( $validation_msg );
				}
			}
			if ( ! empty( $updated_message ) ) {
				echo sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) );

				return false;
			}
			if ( ! empty( $failed_messsage ) ) {
				echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $failed_messsage ) );

				return false;
			}
			if ( ! empty( $validated_messsage ) ) {
				echo sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $validated_messsage ) );

				return false;
			}
		}
	}
}
$wmprty_admin = new WMPRTY_Admin();
