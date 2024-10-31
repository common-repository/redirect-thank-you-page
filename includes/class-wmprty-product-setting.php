<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Redirect_Thank_You_Page
 * @subpackage Redirect_Thank_You_Page/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WMPRTY_Product_Setting class.
 */
if ( ! class_exists( 'WMPRTY_Product_Setting' ) ) {
	/**
	 * WMPRTY_Product_Setting class.
	 */
	class WMPRTY_Product_Setting {

		/**
		 * Instance of class.
		 *
		 * @var $_instance Instance.
		 *
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Post type.
		 *
		 * @var $post_type Store post type.
		 *
		 * @since 1.0.0
		 */
		private static $post_type = null;

		/**
		 * Admin object call.
		 *
		 * @var string $wmprty_admin_obj The class of external plugin.
		 *
		 * @since 1.0.0
		 */
		private static $wmprty_admin_obj = null;

		/**
		 * Get current page.
		 *
		 * @var $current_page Store current page.
		 *
		 * @since 1.0.0
		 */
		private static $current_page = null;

		/**
		 * Get current tab.
		 *
		 * @var $current_tab Store current tab.
		 *
		 * @since 1.0.0
		 */
		private static $current_tab = null;

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
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			self::$wmprty_admin_obj = new WMPRTY_Admin();
			self::$current_page     = self::$wmprty_admin_obj->wmprty_current_page();
			self::$current_tab      = self::$wmprty_admin_obj->wmprty_current_tab();
			self::$post_type        = WMPRTY_PAYMENT_GATEWAY_POST_TYPE;
			add_action( 'add_new_btn_prd_list', array( $this, 'add_new_btn_prd_list_fn' ), 10, 2 );
		}

		/**
		 * Display output.
		 *
		 * @since 1.0.0
		 *
		 * @uses  wmprty_save_method
		 * @uses  wmprty_add_product_option_form
		 * @uses  wmprty_edit_method_screen
		 * @uses  wmprty_delete_method
		 * @uses  wmprty_duplicate_method
		 * @uses  wmprty_list_methods_screen
		 * @uses  WMPRTY_Admin::wmprty_updated_message()
		 */
		public static function wmprty_output() {

			$action          = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
			$post_id_request = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
			$wmprty_nonce    = filter_input( INPUT_GET, 'wmprty_nonce', FILTER_SANITIZE_STRING );
			$get_wmprty_add  = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
			$get_tab         = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$message         = filter_input( INPUT_GET, 'message', FILTER_SANITIZE_STRING );
			if ( isset( $action ) && ! empty( $action ) ) {
				if ( 'add' === $action ) {
					self::wmprty_save_method();
					self::wmprty_add_product_option_form();
				} elseif ( 'edit' === $action ) {
					if ( isset( $wmprty_nonce ) && ! empty( $wmprty_nonce ) ) {
						$getnonce = wp_verify_nonce( $wmprty_nonce, 'edit_' . $post_id_request );
						if ( isset( $getnonce ) && 1 === $getnonce ) {
							self::wmprty_edit_method_screen( $post_id_request );
						} else {
							wp_safe_redirect(
								add_query_arg(
									array(
										'page' => 'wmprty-start-page',
										'tab'  => 'extra_product_option',
									),
									admin_url( 'admin.php' )
								)
							);
							exit;
						}
					} elseif ( isset( $get_wmprty_add ) && ! empty( $get_wmprty_add ) ) {
						if ( ! wp_verify_nonce( $get_wmprty_add, 'wmprty_add' ) ) {
							$message = 'nonce_check';
						} else {
							self::wmprty_edit_method_screen( $post_id_request );
						}
					}
				} elseif ( 'delete' === $action ) {
					self::wmprty_delete_method( $post_id_request );
				} elseif ( 'duplicate' === $action ) {
					self::wmprty_duplicate_method( $post_id_request );
				} else {
					self::wmprty_list_methods_screen();
				}
			} else {
				self::wmprty_list_methods_screen();
			}
			if ( isset( $message ) && ! empty( $message ) ) {
				self::$wmprty_admin_obj->wmprty_updated_message( $message, $get_tab, '' );
			}
		}

		/**
		 * Delete Field Option.
		 *
		 * @param int $id Get Field Option id.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_delete_method( $id ) {
			$wmprty_nonce = filter_input( INPUT_GET, 'wmprty_nonce', FILTER_SANITIZE_STRING );
			$get_tab      = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$getnonce     = wp_verify_nonce( $wmprty_nonce, 'del_' . $id );
			if ( isset( $getnonce ) && 1 === $getnonce ) {
				wp_delete_post( $id );
				$delet_action_redirect_url = self::$wmprty_admin_obj->dynamic_url( self::$current_page, self::$current_tab, '', '', '', 'deleted' );
				wp_safe_redirect( $delet_action_redirect_url );
				exit;
			} else {
				self::$wmprty_admin_obj->wmprty_updated_message( 'nonce_check', $get_tab, '' );
			}
		}

		/**
		 * Duplicate Field Option.
		 *
		 * @param int $id Get Field Option id.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_duplicate_method( $id ) {
			$wmprty_nonce = filter_input( INPUT_GET, 'wmprty_nonce', FILTER_SANITIZE_STRING );
			$get_tab      = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$getnonce     = wp_verify_nonce( $wmprty_nonce, 'duplicate_' . $id );
			$wmprty_add   = wp_create_nonce( 'wmprty_add' );
			$post_id      = isset( $id ) ? absint( $id ) : '';
			$new_post_id  = '';
			if ( isset( $getnonce ) && 1 === $getnonce ) {
				if ( ! empty( $post_id ) || '' !== $post_id ) {
					$post            = get_post( $post_id );
					$current_user    = wp_get_current_user();
					$new_post_author = $current_user->ID;
					if ( isset( $post ) && null !== $post ) {
						$args           = array(
							'comment_status' => $post->comment_status,
							'ping_status'    => $post->ping_status,
							'post_author'    => $new_post_author,
							'post_content'   => $post->post_content,
							'post_excerpt'   => $post->post_excerpt,
							'post_name'      => $post->post_name,
							'post_parent'    => $post->post_parent,
							'post_password'  => $post->post_password,
							'post_status'    => 'draft',
							'post_title'     => $post->post_title . '-duplicate',
							'post_type'      => self::$post_type,
							'to_ping'        => $post->to_ping,
							'menu_order'     => $post->menu_order,
						);
						$new_post_id    = wp_insert_post( $args );
						$post_meta_data = get_post_meta( $post_id );
						if ( 0 !== count( $post_meta_data ) ) {
							foreach ( $post_meta_data as $meta_key => $meta_data ) {
								if ( '_wp_old_slug' === $meta_key ) {
									continue;
								}
								if ( is_array( $meta_data[0] ) ) {
									$meta_value = maybe_unserialize( $meta_data[0] );
								} else {
									$meta_value = $meta_data[0];
								}
								update_post_meta( $new_post_id, $meta_key, $meta_value );
							}
						}
					}
					$duplicat_action_redirect_url = self::$wmprty_admin_obj->dynamic_url( self::$current_page, self::$current_tab, 'edit', $new_post_id, esc_attr( $wmprty_add ), 'duplicated' );
					wp_safe_redirect( $duplicat_action_redirect_url );
					exit();
				} else {
					$action_redirect_url = self::$wmprty_admin_obj->dynamic_url( self::$current_page, self::$current_tab, '', '', '', 'failed' );
					wp_safe_redirect( $action_redirect_url );
					exit();
				}
			} else {
				self::$wmprty_admin_obj->wmprty_updated_message( 'nonce_check', $get_tab, '' );
			}
		}

		/**
		 * Count total Field Option.
		 *
		 * @return int $count_method Count total Field Option ID.
		 *
		 * @since 1.0.0
		 */
		public static function cposmp_sm_count_method() {
			$product_option_args = array(
				'post_type'      => self::$post_type,
				'post_status'    => array( 'publish', 'draft' ),
				'posts_per_page' => - 1,
				'orderby'        => 'ID',
				'order'          => 'DESC',
			);
			$sm_post_query       = new WP_Query( $product_option_args );
			$product_option_list = $sm_post_query->posts;

			return count( $product_option_list );
		}

		/**
		 * Save Field Option when add or edit.
		 *
		 * @param int $method_id Product Redirection id.
		 *
		 * @return bool false when nonce is not verified.
		 * @uses   cposmp_sm_count_method()
		 *
		 * @since  1.0.0
		 */
		private static function wmprty_save_method( $method_id = 0 ) {
			$action                        = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
			$wmprty_save                   = filter_input( INPUT_POST, 'wmprty_save', FILTER_SANITIZE_STRING );
			$woocommerce_save_method_nonce = filter_input( INPUT_POST, 'woocommerce_save_method_nonce', FILTER_SANITIZE_STRING );
			if ( ( isset( $action ) && ! empty( $action ) ) ) {
				if ( isset( $wmprty_save ) ) {
					if ( empty( $woocommerce_save_method_nonce ) || ! wp_verify_nonce( sanitize_text_field( $woocommerce_save_method_nonce ), 'woocommerce_save_method' ) ) {
						esc_html_e( 'Error with security check.', 'redirect-thank-you-page' );

						return false;
					}
					$wmprty_data            = filter_input( INPUT_POST, 'wmprty_data', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
					$wmprty_shipping_status = '';
					$post_title             = '';
					$new_wmprty_data        = array();
					if ( ! empty( $wmprty_data ) ) {
						foreach ( $wmprty_data as $wmprty_key => $wmprty_sub_data ) {
							if ( 'additional_rule_data' === $wmprty_key ) {
								$new_wmprty_data['additional_rule_data'] = $wmprty_data['additional_rule_data'];
							} else {
								if ( 'wmprty_addon_name' === $wmprty_key ) {
									$post_title = $wmprty_sub_data;
								} elseif ( 'wmprty_addon_status' === $wmprty_key ) {
									$wmprty_shipping_status = $wmprty_sub_data;
								}
								$new_wmprty_data[ $wmprty_key ] = $wmprty_sub_data;
							}
						}
					}
					$product_option_count = self::cposmp_sm_count_method();
					$method_id            = (int) $method_id;
					if ( isset( $wmprty_shipping_status ) && 'on' === $wmprty_shipping_status ) {
						$post_status = 'publish';
					} else {
						$post_status = 'draft';
					}
					if ( '' !== $method_id && 0 !== $method_id ) {
						$fee_post  = array(
							'ID'          => $method_id,
							'post_title'  => $post_title,
							'post_status' => $post_status,
							'menu_order'  => $product_option_count + 1,
							'post_type'   => self::$post_type,
						);
						$method_id = wp_update_post( $fee_post );
					} else {
						$fee_post  = array(
							'post_title'   => $post_title,
							'post_content' => $post_title,
							'post_status'  => $post_status,
							'menu_order'   => $product_option_count + 1,
							'post_type'    => self::$post_type,
						);
						$method_id = wp_insert_post( $fee_post, true );
					}

					$products = '';
					if ( $new_wmprty_data && isset( $new_wmprty_data['wmprty_products'] ) && ! empty( $new_wmprty_data['wmprty_products'] ) && is_array( $new_wmprty_data['wmprty_products'] ) ) {
						$products = implode( ',', wp_parse_list( $new_wmprty_data['wmprty_products'] ) );
					}

					$status = '';
					if ( $new_wmprty_data && isset( $new_wmprty_data['wmprty_addon_status'] ) && ! empty( $new_wmprty_data['wmprty_addon_status'] ) && '' !== $new_wmprty_data['wmprty_addon_status'] ) {
						$status = $new_wmprty_data['wmprty_addon_status'];
					}

					$page_id = '';
					if ( $new_wmprty_data && isset( $new_wmprty_data['wmprty_pages'] ) && ! empty( $new_wmprty_data['wmprty_pages'] ) && is_array( $new_wmprty_data['wmprty_pages'] ) ) {
						$page_id = implode( ',', wp_parse_list( $new_wmprty_data['wmprty_pages'] ) );
					}

					$custom_url = '';
					if ( $new_wmprty_data && isset( $new_wmprty_data['wmprty_addon_custom_url'] ) && ! empty( $new_wmprty_data['wmprty_addon_custom_url'] ) ) {
						$custom_url = implode( ',', wp_parse_list( $new_wmprty_data['wmprty_addon_custom_url'] ) );
					}

					if ( '' !== $method_id && 0 !== $method_id ) {
						if ( $method_id > 0 ) {
							update_post_meta( $method_id, 'wmprty_prd_opt_data', wp_json_encode( $new_wmprty_data ) );
							update_post_meta( $method_id, 'wmprty_products', $products );
							update_post_meta( $method_id, 'wmprty_page', $page_id );
							update_post_meta( $method_id, 'wmprty_status', $status );
							update_post_meta( $method_id, 'wmprty_custom_url', $custom_url );
						}
					} else {
						echo '<div class="updated error"><p>' . esc_html__( 'Error saving Product Option.', 'redirect-thank-you-page' ) . '</p></div>';

						return false;
					}
					$wmprty_add = wp_create_nonce( 'wmprty_add' );
					if ( 'add' === $action ) {
						$add_action_redirect_url = self::$wmprty_admin_obj->dynamic_url( self::$current_page, self::$current_tab, 'edit', $method_id, esc_attr( $wmprty_add ), 'created' );
						wp_safe_redirect( $add_action_redirect_url );
						exit();
					}
					if ( 'edit' === $action ) {
						$edit_action_redirect_url = self::$wmprty_admin_obj->dynamic_url( self::$current_page, self::$current_tab, 'edit', $method_id, esc_attr( $wmprty_add ), 'saved' );
						wp_safe_redirect( $edit_action_redirect_url );
						exit();
					}
				}
			}
		}

		/**
		 * Edit Product Option screen.
		 *
		 * @param string $id Get Product Option id.
		 *
		 * @uses  wmprty_save_method()
		 * @uses  wmprty_edit_method()
		 *
		 * @since 1.0.0
		 */
		public static function wmprty_edit_method_screen( $id ) {
			self::wmprty_save_method( $id );
			self::wmprty_edit_method();
		}

		/**
		 * Edit Product Option.
		 *
		 * @since 1.0.0
		 */
		public static function wmprty_edit_method() {
			include WMPRTY_PLUGIN_DIR . '/settings/wmprty-payment-admin-settings.php';
		}

		/**
		 * Add new button in Product Option list section.
		 *
		 * @param string $link_method_url Link method url.
		 *
		 * @param string $text            button text.
		 */
		public function add_new_btn_prd_list_fn( $link_method_url, $text ) {
			?>
			<a href="<?php echo esc_url( $link_method_url ); ?>"
			   class="page-title-action"><?php echo esc_html( $text ); ?>
			</a>
			<?php
		}

		/**
		 * List_product_options function.
		 *
		 * @since 1.0.0
		 *
		 * @uses  WMPRTY_Field_Payment_Table class
		 * @uses  WMPRTY_Field_Payment_Table::process_bulk_action()
		 * @uses  WMPRTY_Field_Payment_Table::prepare_items()
		 * @uses  WMPRTY_Field_Payment_Table::search_box()
		 * @uses  WMPRTY_Field_Payment_Table::display()
		 */
		public static function wmprty_list_methods_screen() {
			if ( ! class_exists( 'WMPRTY_Field_Table' ) ) {
				include_once WMPRTY_PLUGIN_DIR . '/includes/class-wmprty-field-payment-table.php';
			}
			$link_method_url = self::$wmprty_admin_obj->dynamic_url( self::$current_page, self::$current_tab, 'add', '', '', '' );
			?>
			<h1 class="wp-heading-inline">
				<?php
				echo esc_html__( 'Redirect', 'redirect-thank-you-page' );
				?>
			</h1>
			<?php
			do_action( 'before_add_new_btn_prd_list' );
			do_action( 'add_new_btn_prd_list', $link_method_url, esc_html__( 'Add New', 'redirect-thank-you-page' ) );
			do_action( 'after_add_new_btn_prd_list' );
			$request_s = filter_input( INPUT_POST, 's', FILTER_SANITIZE_STRING );
			if ( isset( $request_s ) && ! empty( $request_s ) ) {
				?>
				<span class="subtitle">
				<?php
				esc_html_e( 'Search results for ', 'redirect-thank-you-page' ) . '&#8220;' . esc_html( $request_s ) . '&#8221;';
				?>
				</span>
				<?php
			}
			?>
			<hr class="wp-header-end">
			<?php
			$wc_product_options_table = new WMPRTY_Field_Payment_Table();
			$wc_product_options_table->process_bulk_action();
			$wc_product_options_table->prepare_items();
			$wc_product_options_table->search_box(
				esc_html__(
					'Search Redirection',
					'redirect-thank-you-page'
				),
				'wmprty-prd-opt'
			);
			$wc_product_options_table->display();
		}

		/**
		 * Add_product_option_form function.
		 *
		 * @since 1.0.0
		 */
		public static function wmprty_add_product_option_form() {
			include WMPRTY_PLUGIN_DIR . '/settings/wmprty-payment-admin-settings.php';
		}
	}
}
