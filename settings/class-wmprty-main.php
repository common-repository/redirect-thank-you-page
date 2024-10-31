<?php
/**
 * Plugins main file.
 *
 * @package    Redirect_Thank_You_Page
 * @subpackage Redirect_Thank_You_Page/settings
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WMPRTY_MAIN class.
 */
if ( ! class_exists( 'WMPRTY_MAIN' ) ) {
	/**
	 * WMPRTY_MAIN class.
	 */
	class WMPRTY_MAIN {

		/**
		 * Instance of class.
		 *
		 * @var $_instance Instance.
		 *
		 * @since 1.0.0
		 */
		protected static $_instance = null;

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
			$this->page        = WMPRTY_Admin::wmprty_current_page();
			$this->current_tab = WMPRTY_Admin::wmprty_current_tab();
		}

		/**
		 * Main plugins form.
		 *
		 * @since 1.0.0
		 */
		public function wmprty_main_form() {
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
							$url = WMPRTY_Admin::dynamic_url( $this->page, $name );
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
	}
}
