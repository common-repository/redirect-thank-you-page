<?php
/**
 * If this file is called directly, abort.
 *
 * @package    Redirect_Thank_You_Page
 * @subpackage Redirect_Thank_You_Page/settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<hr class="wp-header-end">
<div class="wmprty_main_form_section">
	<div class="wmprty_main_left_section">
		<table class="form-table wmprty_section wmprty_getting_started_info">
			<tbody>
			<tr>
				<td class="fr-2">
					<p class="wmprty_gs aboutinfo">
						<h1><?php esc_html_e( 'Easily redirect your customers after checkout! & Boost sales & Increase conversion rates ', 'redirect-thank-you-page' ); ?></h1>
					</p>

					<p class="wmprty_gs info_text"></p>

					<p class="wmprty_gs info_text">
						<?php
						esc_html_e( 'You can use any WordPress page of your choice and customize it as you prefer, so to give your own personal touch of style to the website and to fill a small gap in the purchasing process: saying thanks to them!', 'redirect-thank-you-page' );
						?>
					</p>

					<p class="wmprty_gs info_text"></p>

					<p class="wmprty_gs info_text">
						<?php
						esc_html_e( 'Create checkout experience customize to each customer with Show Product Recommendations, encourage social media sharing, Offer discount coupon codes for next purchase and cross-sell recommendations. Using a custom redirect after checkout generates more opportunities to increase sales with existing customers.', 'redirect-thank-you-page' );
						?>
					</p>

					<p class="wmprty_gs info_text"></p>

					<p class="wmprty_gs info_text">
						<?php
						esc_html_e( 'This plugin can also be used to build a custom WooCommerce thank you the page without overriding template files!', 'redirect-thank-you-page' );
						?>
					</p>

					<p class="wmprty_gs info_text">
						<strong>
							<?php esc_html_e( 'Types of redirect', 'redirect-thank-you-page' ); ?>
						</strong>
					</p>

					<p class="wmprty_gs info_text"></p>

					<p class="wmprty_gs info_text">
						<ol>
							<li><?php esc_html_e( 'Set Default Global Redirect Thank You Page or  by Custom URL', 'redirect-thank-you-page' ); ?></li>
							<li><?php esc_html_e( 'Set Single Product-based Custom Redirect Thank You Page or  by Custom URL', 'redirect-thank-you-page' ); ?></li>
							<li><?php esc_html_e( 'Set Multiple Product-based Custom Redirect Thank You Page or  by Custom URL', 'redirect-thank-you-page' ); ?></li>
							<li><?php esc_html_e( 'Set Payment Gateway Custom Redirect Thank You Page or  by Custom URL', 'redirect-thank-you-page' ); ?></li>
							<li><?php esc_html_e( 'Shortcode to display order details on redirection page', 'redirect-thank-you-page' ); ?><code>[wmprty_order_detail]</code></li>
							<li><?php esc_html_e( 'Beautiful Thank You page', 'redirect-thank-you-page' ); ?></li>
						</ol>
					</p>

					<p class="wmprty_gs info_text">
						<strong>
							<?php esc_html_e( 'Support Product Type ', 'redirect-thank-you-page' ); ?>
						</strong>
					</p>

					<p class="wmprty_gs info_text"></p>

					<ol>
						<li><?php esc_html_e( 'Simple Product', 'redirect-thank-you-page' ); ?></li>
						<li><?php esc_html_e( 'Variable Product', 'redirect-thank-you-page' ); ?></li>
						<li><?php esc_html_e( 'Bundle product', 'redirect-thank-you-page' ); ?></li>
						<li><?php esc_html_e( 'Group Product', 'redirect-thank-you-page' ); ?></li>
						<li><?php esc_html_e( 'Simple Subscription Product', 'redirect-thank-you-page' ); ?></li>
						<li><?php esc_html_e( 'Variable Subscription Product', 'redirect-thank-you-page' ); ?></li>
						<li><?php esc_html_e( 'Virtual Product', 'redirect-thank-you-page' ); ?></li>
						<li><?php esc_html_e( 'Download Product', 'redirect-thank-you-page' ); ?></li>
					</ol>

					<p class="wmprty_gs info_text"></p>

					<p class="wmprty_gs info_text">
						<?php esc_html_e( '1. Set Default Global Redirect Thank You Page or by Custom URL (pro)', 'redirect-thank-you-page' ); ?>
						<span class="aboutinfo">
							<img src="<?php echo esc_url( WMPRTY_PLUGIN_URL . 'assets/images/global-thank-you-free.png' ); ?>">
						</span>
					</p>
					<p class="wmprty_gs info_text">
						<?php esc_html_e( '2. Set Single Product-based Custom Redirect Thank You Page or by Custom URL (pro)', 'redirect-thank-you-page' ); ?>
						<span class="aboutinfo">
							<img src="<?php echo esc_url( WMPRTY_PLUGIN_URL . 'assets/images/free-payment-listing.png' ); ?>">
							<img src="<?php echo esc_url( WMPRTY_PLUGIN_URL . 'assets/images/product-single-settings.png' ); ?>">
						</span>
					</p>
					<p class="wmprty_gs info_text">
						</strong><?php esc_html_e( '3. Set Multiple Product-based Custom Redirect Thank You Page (pro) or by Custom URL (pro)', 'redirect-thank-you-page' ); ?>
						<span class="aboutinfo">
							<img src="<?php echo esc_url( WMPRTY_PLUGIN_URL . 'assets/images/multiple-products-based-redirections.png' ); ?>">
						</span>
					</p>
					<p class="wmprty_gs info_text">
						<?php esc_html_e( '4. Set Payment Gateway Custom Redirect Thank You Page or by Custom URL (pro)', 'redirect-thank-you-page' ); ?>
						<span class="aboutinfo">
							<img src="<?php echo esc_url( WMPRTY_PLUGIN_URL . 'assets/images/free-payment-listing.png' ); ?>">
							<img src="<?php echo esc_url( WMPRTY_PLUGIN_URL . 'assets/images/payment-single-settings.png' ); ?>">
						</span>
					</p>
					<p class="wmprty_gs info_text">
						<?php esc_html_e( '5. Shortcode to display order details on redirection page', 'redirect-thank-you-page' ); ?><code>[wmprty_order_detail]</code>
						<span class="aboutinfo">
							<img src="<?php echo esc_url( WMPRTY_PLUGIN_URL . 'assets/images/thank-you-page-shortcode.png' ); ?>">
						</span>
					</p>
					<p class="wmprty_gs info_text">
						<?php esc_html_e( '6. Beautiful Thank You page', 'redirect-thank-you-page' ); ?>
						<span class="aboutinfo">
							<img src="<?php echo esc_url( WMPRTY_PLUGIN_URL . 'assets/images/frontend-thank-you-page.png' ); ?>">
						</span>
					</p>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
