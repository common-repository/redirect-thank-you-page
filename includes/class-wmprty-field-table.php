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

if ( ! class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
/**
 * WMPRTY_Field_Table class.
 *
 * @extends WP_List_Table
 */
if ( ! class_exists( 'WMPRTY_Field_Table' ) ) {
	/**
	 * WMPRTY_Field_Table class.
	 */
	class WMPRTY_Field_Table extends WP_List_Table {
		/**
		 * Count total items
		 *
		 * @var string $wmprty_found_items store count of post.
		 *
		 * @since 1.0
		 */
		private static $wmprty_found_items = 0;
		/**
		 * Get post type
		 *
		 * @var $post_type store post type.
		 *
		 * @since 1.0.0
		 */
		private static $post_type = null;
		/**
		 * Admin object call.
		 *
		 * @var string $admin_object The class of external plugin.
		 *
		 * @since 1.0.0
		 */
		private static $admin_object = null;
		/**
		 * Get current page.
		 *
		 * @var string $current_page Getting current page.
		 *
		 * @since 1.0.0
		 */
		private static $current_page = null;
		/**
		 * Get current tab.
		 *
		 * @var string $current_tab Getting current tab.
		 *
		 * @since 1.0.0
		 */
		private static $current_tab = null;

		/**
		 * Get_columns function.
		 *
		 * @return array
		 *
		 * @since 3.5
		 */
		public function get_columns() {
			return array(
				'cb'       => '<input type="checkbox" />',
				'title'    => esc_html__( 'Title', 'redirect-thank-you-page' ),
				'product'  => esc_html__( 'Product', 'redirect-thank-you-page' ),
				'redirect' => esc_html__( 'Redirect Page', 'redirect-thank-you-page' ),
				'status'   => esc_html__( 'Status', 'redirect-thank-you-page' ),
				'date'     => esc_html__( 'Date', 'redirect-thank-you-page' ),
			);
		}

		/**
		 * Get_sortable_columns function.
		 *
		 * @return array
		 *
		 * @since 1.0.0
		 */
		protected function get_sortable_columns() {
			$columns = array(
				'title' => array( 'title', true ),
				'date'  => array( 'date', false ),
			);

			return $columns;
		}

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			parent::__construct(
				array(
					'singular' => 'post',
					'plural'   => 'posts',
					'ajax'     => false,
				)
			);
			self::$admin_object = new WMPRTY_Admin();
			self::$current_page = self::$admin_object->wmprty_current_page();
			self::$current_tab  = self::$admin_object->wmprty_current_tab();
			self::$post_type    = WMPRTY_DFT_POST_TYPE;
			if ( 'payment-gateway-section' === self::$current_tab ) {
				self::$post_type = WMPRTY_PAYMENT_GATEWAY_POST_TYPE;
			}

		}

		/**
		 * Get Methods to display.
		 *
		 * @since 1.0.0
		 */
		public function prepare_items() {
			$this->prepare_column_headers();
			$per_page    = $this->get_items_per_page( 'wmprty_per_page' );
			$get_search  = filter_input( INPUT_POST, 's', FILTER_SANITIZE_STRING );
			$get_orderby = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_STRING );
			$get_order   = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_STRING );
			$args        = array(
				'posts_per_page' => $per_page,
				'orderby'        => 'ID',
				'order'          => 'DESC',
				'offset'         => ( $this->get_pagenum() - 1 ) * $per_page,
			);
			if ( isset( $get_search ) && ! empty( $get_search ) ) {
				$args['s'] = trim( wp_unslash( $get_search ) );
			}
			if ( isset( $get_orderby ) && ! empty( $get_orderby ) ) {
				if ( 'title' === $get_orderby ) {
					$args['orderby'] = 'title';
				} elseif ( 'date' === $get_orderby ) {
					$args['orderby'] = 'date';
				}
			}
			if ( isset( $get_order ) && ! empty( $get_order ) ) {
				if ( 'asc' === strtolower( $get_order ) ) {
					$args['order'] = 'ASC';
				} elseif ( 'desc' === strtolower( $get_order ) ) {
					$args['order'] = 'DESC';
				}
			}
			$this->items = $this->wmprty_find( $args, $get_orderby );
			$total_items = $this->wmprty_count();
			$total_pages = ceil( $total_items / $per_page );
			$this->set_pagination_args(
				array(
					'total_items' => $total_items,
					'total_pages' => $total_pages,
					'per_page'    => $per_page,
				)
			);
		}

		/**
		 * If no listing found then display message.
		 *
		 * @since 1.0.0
		 */
		public function no_items() {
			if ( isset( $this->error ) ) {
				echo esc_html( $this->error->get_error_message() );
			} else {
				esc_html_e( 'No redirection found.', 'redirect-thank-you-page' );
			}
		}

		/**
		 * Checkbox column.
		 *
		 * @param string $item Get Field Option id.
		 *
		 * @return mixed
		 *
		 * @since 1.0.0
		 */
		public function column_cb( $item ) {
			if ( ! $item->ID ) {
				return;
			}

			return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', 'method_id_cb', esc_attr( $item->ID ) );
		}

		/**
		 * Output the shipping name column.
		 *
		 * @param object $item Get Field Option id.
		 *
		 * @since 1.0.0
		 */
		public function column_title( $item ) {
			$editurl     = self::$admin_object->dynamic_url( self::$current_page, self::$current_tab, 'edit', $item->ID );
			$method_name = '<strong>
                            <a href="' . wp_nonce_url( $editurl, 'edit_' . $item->ID, 'wmprty_nonce' ) . '" class="row-title">' . esc_html( $item->post_title ) . '</a>
                        </strong>';
			echo wp_kses(
				$method_name,
				array(
					'strong' => array(),
					'a'      => array(
						'href'  => array(),
						'class' => array(),
					),
				)
			);
		}

		/**
		 * Generates and displays row action links.
		 *
		 * @param object $item        Link being acted upon.
		 * @param string $column_name Current column name.
		 * @param string $primary     Primary column name.
		 *
		 * @return string Row action output for links.
		 *
		 * @since 1.0.0
		 */
		protected function handle_row_actions( $item, $column_name, $primary ) {
			if ( $primary !== $column_name ) {
				return '';
			}
			$editurl              = self::$admin_object->dynamic_url( self::$current_page, self::$current_tab, 'edit', $item->ID );
			$delurl               = self::$admin_object->dynamic_url( self::$current_page, self::$current_tab, 'delete', $item->ID );
			$duplicateurl         = self::$admin_object->dynamic_url( self::$current_page, self::$current_tab, 'duplicate', $item->ID );
			$actions              = array();
			$actions['edit']      = '<a href="' . wp_nonce_url( $editurl, 'edit_' . esc_attr( $item->ID ), 'wmprty_nonce' ) . '">' . esc_html__( 'Edit', 'redirect-thank-you-page' ) . '</a>';
			$actions['delete']    = '<a href="' . wp_nonce_url( $delurl, 'del_' . esc_attr( $item->ID ), 'wmprty_nonce' ) . '">' . esc_html__( 'Delete', 'redirect-thank-you-page' ) . '</a>';
			$actions['duplicate'] = '<a href="' . wp_nonce_url( $duplicateurl, 'duplicate_' . esc_attr( $item->ID ), 'wmprty_nonce' ) . '">' . esc_html__( 'Duplicate', 'redirect-thank-you-page' ) . '</a>';

			return $this->row_actions( $actions );
		}

		/**
		 * Output the method enabled column.
		 *
		 * @param object $item Get Field Option id.
		 *
		 * @return string
		 *
		 * @since 1.0.0
		 */
		public function column_status( $item ) {
			if ( 0 === $item->ID ) {
				return esc_html__( 'Everywhere', 'redirect-thank-you-page' );
			}
			if ( 'publish' === $item->post_status ) {
				$status = esc_html__( 'Enable', 'redirect-thank-you-page' );
			} else {
				$status = esc_html__( 'Disable', 'redirect-thank-you-page' );
			}

			return $status;
		}

		/**
		 * Output the method amount column.
		 *
		 * @param object $item Get Field Option id.
		 *
		 * @return mixed $item->post_date.
		 *
		 * @since 1.0.0
		 */
		public function column_date( $item ) {
			if ( 0 === $item->ID ) {
				return esc_html__( 'Everywhere', 'redirect-thank-you-page' );
			}

			return $item->post_date;
		}

		/**
		 * Output the method amount column.
		 *
		 * @param object $item Get Field Option id.
		 *
		 * @return mixed $item->post_date.
		 *
		 * @since 1.0.0
		 */
		public function column_product( $item ) {

			$product_id = get_post_meta( $item->ID, 'wmprty_products', true );

			if ( $product_id ) {
				return sprintf( "<a href='%s'>%s</a>", get_edit_post_link( $product_id ), get_the_title( $product_id ) );
			}

			return esc_html__( '-', 'redirect-thank-you-page' );
		}

		/**
		 * Output the method amount column.
		 *
		 * @param object $item Get Field Option id.
		 *
		 * @return mixed $item->post_date.
		 *
		 * @since 1.0.0
		 */
		public function column_redirect( $item ) {

			$page_id = get_post_meta( $item->ID, 'wmprty_page', true );

			if ( $page_id ) {
				return sprintf( "<a href='%s'>%s</a>", get_edit_post_link( $page_id ), get_the_title( $page_id ) );
			}

			return esc_html__( '-', 'redirect-thank-you-page' );

		}

		/**
		 * Display bulk action in filter.
		 *
		 * @return array $actions
		 *
		 * @since 1.0.0
		 */
		public function get_bulk_actions() {
			$actions = array(
				'disable' => esc_html__( 'Disable', 'redirect-thank-you-page' ),
				'enable'  => esc_html__( 'Enable', 'redirect-thank-you-page' ),
				'delete'  => esc_html__( 'Delete', 'redirect-thank-you-page' ),
			);

			return $actions;
		}

		/**
		 * Process bulk actions.
		 *
		 * @since 1.0.0
		 */
		public function process_bulk_action() {
			$delete_nonce     = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
			$get_method_id_cb = filter_input( INPUT_POST, 'method_id_cb', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY );
			$method_id_cb     = ! empty( $get_method_id_cb ) ? array_map( 'sanitize_text_field', wp_unslash( $get_method_id_cb ) ) : array();
			$get_tab          = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$action           = $this->current_action();
			if ( ! isset( $method_id_cb ) ) {
				return;
			}
			$deletenonce = wp_verify_nonce( $delete_nonce, 'bulk-shippingmethods' );
			if ( ! isset( $deletenonce ) && 1 !== $deletenonce ) {
				return;
			}
			$items = array_filter( array_map( 'absint', $method_id_cb ) );
			if ( ! $items ) {
				return;
			}
			if ( 'delete' === $action ) {
				foreach ( $items as $id ) {
					wp_delete_post( $id );
				}
				self::$admin_object->wmprty_updated_message( 'deleted', $get_tab, '' );
			} elseif ( 'enable' === $action ) {
				foreach ( $items as $id ) {
					$enable_post = array(
						'post_type'   => self::$post_type,
						'ID'          => $id,
						'post_status' => 'publish',
					);
					wp_update_post( $enable_post );
				}
				self::$admin_object->wmprty_updated_message( 'enabled', $get_tab, '' );
			} elseif ( 'disable' === $action ) {
				foreach ( $items as $id ) {
					$disable_post = array(
						'post_type'   => self::$post_type,
						'ID'          => $id,
						'post_status' => 'draft',
					);
					wp_update_post( $disable_post );
				}
				self::$admin_object->wmprty_updated_message( 'disabled', $get_tab, '' );
			}
		}

		/**
		 * Find post data.
		 *
		 * @param mixed  $args        pass query args.
		 *
		 * @param string $get_orderby pass order by for listing.
		 *
		 * @return array $posts
		 *
		 * @since 1.0.0
		 */
		public static function wmprty_find( $args = '', $get_orderby ) {
			$defaults          = array(
				'post_status'    => 'any',
				'posts_per_page' => - 1,
				'offset'         => 0,
				'orderby'        => 'ID',
				'order'          => 'ASC',
			);
			$args              = wp_parse_args( $args, $defaults );
			$args['post_type'] = self::$post_type;
			$wmprty_query      = new WP_Query( $args );
			$posts             = $wmprty_query->query( $args );
			if ( ! isset( $get_orderby ) && empty( $get_orderby ) ) {
				$sort_order     = array();
				$get_sort_order = get_option( 'sm_sortable_order' );
				if ( isset( $get_sort_order ) && ! empty( $get_sort_order ) ) {
					foreach ( $get_sort_order as $sort ) {
						$sort_order[ $sort ] = array();
					}
				}
				foreach ( $posts as $carrier_id => $carrier ) {
					$carrier_name = $carrier->ID;
					if ( array_key_exists( $carrier_name, $sort_order ) ) {
						$sort_order[ $carrier_name ][ $carrier_id ] = $posts[ $carrier_id ];
						unset( $posts[ $carrier_id ] );
					}
				}
				foreach ( $sort_order as $carriers ) {
					$posts = array_merge( $posts, $carriers );
				}
			}
			self::$wmprty_found_items = $wmprty_query->found_posts;

			return $posts;
		}

		/**
		 * Count post data.
		 *
		 * @return string
		 *
		 * @since 1.0.0
		 */
		public static function wmprty_count() {
			return self::$wmprty_found_items;
		}

		/**
		 * Set column_headers property for table list.
		 *
		 * @since 1.0.0
		 */
		protected function prepare_column_headers() {
			$this->_column_headers = array(
				$this->get_columns(),
				array(),
				$this->get_sortable_columns(),
			);
		}
	}
}
