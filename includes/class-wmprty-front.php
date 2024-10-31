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
if ( ! class_exists( 'WMPRTY_Front' ) ) {
	/**
	 * WMPRTY_Admin class.
	 */
	class WMPRTY_Front {

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
		 * @var string $plugin_name The ID of this plugin.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			$this->wmprty_front_init();
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
		 * Add filter in which add some functionality.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_front_init() {

			add_action( 'woocommerce_thankyou', array( $this, 'wmprty_get_match_data_from_db' ), PHP_INT_MAX, 1 );
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 40 );
			add_action( 'woocommerce_settings_tabs_wmprty_thank_you', array( $this, 'settings_tab' ) );
			add_action( 'woocommerce_update_options_wmprty_thank_you', array( $this, 'update_settings' ) );
			add_action( 'woocommerce_admin_field_notice_show', array( $this, 'notice_show' ), PHP_INT_MAX, 1 );
			add_shortcode( 'wmprty_order_detail', array( $this, 'wmprty_order_details' ) );

			if ( 'wmprty_thank_you' === filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) && 'wc-settings' === filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) ) {
				remove_action( 'in_admin_header', 'Automattic\WooCommerce\Admin\Loader::embed_page_header' );
				add_action( 'in_admin_header', array( $this, 'embed_page_header' ) );
			} elseif ( 'product' === filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING ) && 'about_info' === filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) && 'wmprty-main' === filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) ) {
				add_action( 'in_admin_header', array( $this, 'embed_page_header_info' ) );
			} elseif ( 'product' === filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING ) && 'payment-gateway-section' === filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING ) && 'wmprty-main' === filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) ) {
				add_action( 'in_admin_header', array( $this, 'embed_page_header_payment' ) );
			} elseif ( 'product' === filter_input( INPUT_GET, 'post_type', FILTER_SANITIZE_STRING ) && 'wmprty-main' === filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) ) {
				add_action( 'in_admin_header', array( $this, 'embed_page_header_product' ) );
			}

		}

		/**
		 * Admin header function.
		 */
		public function embed_page_header_info() {
			?>
			<div id="wmprty-admin-top-bar-root" class="wmprty-admin-top-bar--active ff">
				<div class="wmprty-admin-top-bar">
					<div class="wmprty-admin-top-bar__main-area">
						<div class="wmprty-admin-top-bar__heading">
							<div class="wmprty-admin-top-bar__heading-logo">
								<img src="<?php echo esc_url( WMPRTY_PLUGIN_URL . 'assets/images/icon.png' ); ?>" />
							</div>
							<h1 class="wmprty-admin-top-bar__heading-title"><?php esc_html_e( 'Advanced Redirect Thank You for Woocommerce', 'redirect-thank-you-page' ); ?></h1>
						</div>
						<div class="wmprty-admin-top-bar__main-area-buttons"></div>
					</div>
					<div class="wmprty-admin-top-bar__secondary-area">
						<span class=""><?php esc_html_e( 'v ', 'redirect-thank-you-page' ); ?></span>
						<h1 class="wmprty-admin-top-bar__bar-button-title"><?php echo esc_html( WMPRTY_PLUGIN_VERSION ); ?></h1>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Admin header function.
		 */
		public function embed_page_header_payment() {

			?>
			<div id="wmprty-admin-top-bar-root" class="wmprty-admin-top-bar--active ff">
				<div class="wmprty-admin-top-bar">
					<div class="wmprty-admin-top-bar__main-area">
						<div class="wmprty-admin-top-bar__heading">
							<div class="wmprty-admin-top-bar__heading-logo">
								<img src="<?php echo esc_url( WMPRTY_PLUGIN_URL . 'assets/images/icon.png' ); ?>" />
							</div>
							<h1 class="wmprty-admin-top-bar__heading-title"><?php esc_html_e( 'Advanced Redirect Thank You for Woocommerce', 'redirect-thank-you-page' ); ?></h1>
						</div>
						<div class="wmprty-admin-top-bar__main-area-buttons"></div>
					</div>
					<div class="wmprty-admin-top-bar__secondary-area">
						<span class=""><?php esc_html_e( 'v ', 'redirect-thank-you-page' ); ?></span>
						<h1 class="wmprty-admin-top-bar__bar-button-title"><?php echo esc_html( WMPRTY_PLUGIN_VERSION ); ?></h1>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Admin header function.
		 */
		public function embed_page_header_product() {

			?>
			<div id="wmprty-admin-top-bar-root" class="wmprty-admin-top-bar--active ff">
				<div class="wmprty-admin-top-bar">
					<div class="wmprty-admin-top-bar__main-area">
						<div class="wmprty-admin-top-bar__heading">
							<div class="wmprty-admin-top-bar__heading-logo">
								<img src="<?php echo esc_url( WMPRTY_PLUGIN_URL . 'assets/images/icon.png' ); ?>" />
							</div>
							<h1 class="wmprty-admin-top-bar__heading-title"><?php esc_html_e( 'Advanced Redirect Thank You for Woocommerce', 'redirect-thank-you-page' ); ?></h1>
						</div>
						<div class="wmprty-admin-top-bar__main-area-buttons"></div>
					</div>
					<div class="wmprty-admin-top-bar__secondary-area">
						<span class=""><?php esc_html_e( 'v ', 'redirect-thank-you-page' ); ?></span>
						<h1 class="wmprty-admin-top-bar__bar-button-title"><?php echo esc_html( WMPRTY_PLUGIN_VERSION ); ?></h1>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Admin header function.
		 */
		public function embed_page_header() {

			?>
			<style type="text/css">
				.woocommerce-page #contextual-help-link-wrap, .woocommerce-page #screen-options-link-wrap {
					display: none;
				}

				#wmprty-admin-top-bar-root.wmprty-admin-top-bar--active ~ #wpbody #wpbody-content {
					margin-top: 0px;
				}
			</style>
			<div id="wmprty-admin-top-bar-root" class="wmprty-admin-top-bar--active ff">
				<div class="wmprty-admin-top-bar">
					<div class="wmprty-admin-top-bar__main-area">
						<div class="wmprty-admin-top-bar__heading">
							<div class="wmprty-admin-top-bar__heading-logo">
								<img src="<?php echo esc_url( WMPRTY_PLUGIN_URL . 'assets/images/icon.png' ); ?>" />
							</div>
							<h1 class="wmprty-admin-top-bar__heading-title"><?php esc_html_e( 'Advanced Redirect Thank You for Woocommerce', 'redirect-thank-you-page' ); ?></h1>
						</div>
						<div class="wmprty-admin-top-bar__main-area-buttons"></div>
					</div>
					<div class="wmprty-admin-top-bar__secondary-area">
						<span class=""><?php esc_html_e( 'v ', 'redirect-thank-you-page' ); ?></span>
						<h1 class="wmprty-admin-top-bar__bar-button-title"><?php echo esc_html( WMPRTY_PLUGIN_VERSION ); ?></h1>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Failed Page.
		 *
		 * @param array $value Value data.
		 */
		public static function buy_btn_failed_page( $value ) {
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label><?php echo esc_html( $value['title'] ); ?></label>
				</th>
				<td class="forminp">
					<?php
					$text = __( 'Upgrade Now!', 'redirect-thank-you-page' );
					wmprty_buy_btn( $text );
					?>
				</td>
			</tr>
			<?php
		}

		/**
		 * Failed Url.
		 *
		 * @param array $value Data value.
		 */
		public static function notice_show( $value ) {
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">

				</th>
				<td class="forminp">
					<strong><?php esc_html_e( 'Note:', 'redirect-thank-you-page' ); ?></strong> <?php esc_html_e( 'Shortcode to display order details on custom redirection page', 'redirect-thank-you-page' ); ?> <code> [wmprty_order_detail]</code>
				</td>
			</tr>
			<?php
		}

		/**
		 * Settings tab.
		 */
		public static function settings_tab() {
			woocommerce_admin_fields( self::get_settings() );
		}

		/**
		 * Save the options.
		 */
		public static function update_settings() {
			woocommerce_update_options( self::get_settings() );
		}

		/**
		 * Register settings.
		 *
		 * @return mixed|void
		 */
		public static function get_settings() {

			$settings = array(
				'section_title'  => array(
					'name' => '',
					'type' => 'title',
					'desc' => '',
					'id'   => 'wc_settings_redirect_thank_you_title',
				),
				'enable'         => array(
					'name' => __( 'Status', 'redirect-thank-you-page' ),
					'type' => 'checkbox',
					'desc' => __( 'Enable global thank you page', 'redirect-thank-you-page' ),
					'id'   => 'wc_settings_enable_redirect',
				),
				'thank_you_page' => array(
					'title'    => __( 'Global Thank you Page', 'redirect-thank-you-page' ),
					'id'       => 'wc_settings_thank_you_page',
					'type'     => 'single_select_page_with_search',
					'default'  => '',
					'class'    => 'wc-page-search',
					'css'      => 'min-width:300px;',
					'args'     => array(
						'exclude' => array(),
					),
					'autoload' => false,
				),
				'custom_note'    => array(
					'name'        => __( 'Failed Custom URL', 'redirect-thank-you-page' ),
					'type'        => 'notice_show',
					'placeholder' => 'Failed order page custom URL',
					'id'          => 'wc_settings_failed_custom_url',
				),
				'section_end'    => array(
					'type' => 'sectionend',
					'id'   => 'wc_settings_redirect_thank_you_section_end',
				),
			);

			return apply_filters( 'wc_multi_product_redirect_thank_you_settings', $settings );
		}

		/**
		 * Register the settings tab.
		 *
		 * @param array $settings_tabs Setting tab.
		 *
		 * @return mixed
		 */
		public static function add_settings_tab( $settings_tabs ) {
			$settings_tabs['wmprty_thank_you'] = __( 'Global Redirect Thank You Page', 'redirect-thank-you-page' );
			return $settings_tabs;
		}

		/**
		 * Get match data from DB.
		 *
		 * @param int $order_id order id.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_get_match_data_from_db( $order_id ) {

			$order          = new WC_Order( $order_id );
			$order_status   = $order->get_status();
			$payment_method = $order->get_payment_method();
			$global_enabled = get_option( 'wc_settings_enable_redirect', 'no' );

			if ( 'failed' !== $order_status ) {

				$items    = $order->get_items();
				$products = array();
				foreach ( $items as $key => $this_product ) {
					$products[] = (int) $this_product['product_id'];
				}

				if ( ! empty( $products ) ) {
					$products = implode( ',', $products );

					$args  = array(
						'post_type'      => WMPRTY_DFT_POST_TYPE,
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								'key'     => 'wmprty_products',
								'value'   => $products,
								'compare' => '=',
							),
							array(
								'key'     => 'wmprty_status',
								'value'   => 'on',
								'compare' => '=',
							),
						),
					);
					$query = new WP_Query( $args );

					if ( $query->have_posts() ) {
						foreach ( $query->posts as $redirect_post ) {
							$get_page_id = get_post_meta( $redirect_post->ID, 'wmprty_page', true );
							if ( $get_page_id ) {
								wp_safe_redirect( add_query_arg( 'order', $order_id, get_the_permalink( $get_page_id ) ) );
								exit();
							}
						}
					}

					// Payment Gateway based redirect.
					$payment_args  = array(
						'post_type'      => WMPRTY_PAYMENT_GATEWAY_POST_TYPE,
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
						'meta_query'     => array(
							'relation' => 'AND',
							array(
								'key'     => 'wmprty_products',
								'value'   => $payment_method,
								'compare' => '=',
							),
							array(
								'key'     => 'wmprty_status',
								'value'   => 'on',
								'compare' => '=',
							),
						),
					);
					$payment_query = new WP_Query( $payment_args );
					if ( $payment_query->have_posts() ) {
						foreach ( $payment_query->posts as $redirect_post ) {
							$get_page_id = get_post_meta( $redirect_post->ID, 'wmprty_page', true );
							if ( $get_page_id ) {
								wp_safe_redirect( add_query_arg( 'order', $order_id, get_the_permalink( $get_page_id ) ) );
								exit();
							}
						}
					}
					if ( 'yes' === $global_enabled ) {
						$thank_you  = get_option( 'wc_settings_thank_you_page', '' );
						$thank_type = get_option( 'wc_settings_thank_you_type', 'page' );
						if ( $thank_you ) {
							if ( 'page' === $thank_type && '' !== $thank_you ) {
								wp_safe_redirect( add_query_arg( 'order', $order_id, get_the_permalink( $thank_you ) ) );
								exit();
							}
						}
					}
				}
			}
		}

		/**
		 * Shortcode for order details.
		 *
		 * @param array $atts Shortcode data attribute.
		 *
		 * @return false|string|void
		 */
		public function wmprty_order_details( $atts ) {

			$order_id = filter_input( INPUT_GET, 'order', FILTER_VALIDATE_INT );
			$order    = wc_get_order( $order_id );

			if ( ! $order ) {
				return;
			}

			$order_items           = $order->get_items( apply_filters( 'wmprty_woocommerce_purchase_order_item_types', 'line_item' ) );
			$show_purchase_note    = $order->has_status( apply_filters( 'wmprty_woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
			$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
			$downloads             = $order->get_downloadable_items();
			$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();

			ob_start();
			if ( $show_downloads ) {
				wc_get_template(
					'order/order-downloads.php',
					array(
						'downloads'  => $downloads,
						'show_title' => true,
					)
				);
			}
			?>
			<div class="woocommerce">
				<div class="woocommerce-order">
					<section class="woocommerce-order-details">
						<?php do_action( 'wmprty_woocommerce_order_details_before_order_table', $order ); ?>
						<header class="entry-header">
							<h1 class="entry-title"
								itemprop="headline"><?php esc_html_e( 'Order received', 'redirect-thank-you-page' ); ?></h1>
						</header>
						<p>&nbsp;</p>
						<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

							<li class="woocommerce-order-overview__order order">
								<?php esc_html_e( 'Order number', 'redirect-thank-you-page' ); ?>:
								<strong><?php echo esc_html( $order_id ); ?></strong>
							</li>

							<li class="woocommerce-order-overview__order order">
								<?php
								$wctr_date_format = get_option( 'date_format' );
								esc_html_e( 'Date', 'redirect-thank-you-page' ); // phpcs:ignore
								?>
								:
								<strong><?php echo wp_date( $wctr_date_format, strtotime( $order->get_date_created() ) ); // phpcs:ignore ?></strong>
							</li>

							<li class="woocommerce-order-overview__order order">
								<?php esc_html_e( 'Name', 'redirect-thank-you-page' ); ?>:
								<strong><?php echo esc_html( $order->get_formatted_billing_full_name() ); ?></strong>
							</li>

							<li class="woocommerce-order-overview__order order">
								<?php esc_html_e( 'Payment Method', 'redirect-thank-you-page' ); ?>:
								<strong><?php echo esc_html( $order->get_payment_method_title() ); ?></strong>
							</li>

						</ul>
						<h2 class="woocommerce-order-details__title"><?php esc_html_e( 'Order details', 'redirect-thank-you-page' ); ?></h2>

						<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

							<thead>
							<tr>
								<th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'Product', 'redirect-thank-you-page' ); ?></th>
								<th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'Total', 'redirect-thank-you-page' ); ?></th>
							</tr>
							</thead>

							<tbody>
							<?php
							do_action( 'wmprty_woocommerce_order_details_before_order_table_items', $order );

							foreach ( $order_items as $item_id => $item ) {
								$product = $item->get_product();

								wc_get_template(
									'order/order-details-item.php',
									array(
										'order'         => $order,
										'item_id'       => $item_id,
										'item'          => $item,
										'show_purchase_note' => $show_purchase_note,
										'purchase_note' => $product ? $product->get_purchase_note() : '',
										'product'       => $product,
									)
								);
							}

							do_action( 'wmprty_woocommerce_order_details_after_order_table_items', $order );
							?>
							</tbody>

							<tfoot>
							<?php
							foreach ( $order->get_order_item_totals() as $key => $total ) {
								?>
								<tr>
									<th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
									<td><?php echo ( 'payment_method' === $key ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ) ); // phpcs:ignore ?></td>
								</tr>
								<?php
							}
							?>
							<?php if ( $order->get_customer_note() ) : ?>
								<tr>
									<th><?php esc_html_e( 'Note', 'redirect-thank-you-page' ); ?>:</th>
									<td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
								</tr>
							<?php endif; ?>
							</tfoot>
						</table>

						<?php do_action( 'wmprty_woocommerce_order_details_after_order_table', $order ); ?>
					</section>
				</div>
			</div>

			<?php
			/**
			 * Action hook fired after the order details.
			 *
			 * @since 1.0.0
			 *
			 * @param WC_Order $order Order data.
			 */
			do_action( 'wmprty_woocommerce_after_order_details', $order );

			if ( $show_customer_details ) {
				wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
			}

			$shortcode_output = ob_get_clean();

			return $shortcode_output;

		}
	}
}
$wmprty_admin = new WMPRTY_Front();
