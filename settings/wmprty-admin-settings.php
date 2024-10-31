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
$get_action  = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
$get_data    = '';
$field_type  = '';
$get_post_id = '';
if ( 'edit' === $get_action ) {
	$get_post_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_STRING );
	$get_data    = wmprty_get_data_from_db( $get_post_id );
}

$check_general_data            = wmprty_check_array_key_exists( 'general', $get_data );
$check_wmprty_addon_status     = wmprty_check_array_key_exists( 'wmprty_addon_status', $get_data );
$check_wmprty_addon_name       = wmprty_check_array_key_exists( 'wmprty_addon_name', $get_data );
$check_wmprty_addon_position   = wmprty_check_array_key_exists( 'wmprty_addon_position', $get_data );
$check_wmprty_addon_custom_url = wmprty_check_array_key_exists( 'wmprty_addon_custom_url', $get_data );

?>
	<h1 class="wp-heading-inline"><?php echo esc_html( 'Add Redirect', 'redirect-thank-you-page' ); ?></h1>
	<hr class="wp-header-end">
	<div class="wmprty_main_form_section">
		<div class="wmprty_main_left_section">
			<table class="form-table wmprty_section wmprty_field_info">
				<tbody>
				<tr>
					<td>
						<div class="cop_ft_field_section form-table wmprty_section wmprty_field_info">
							<div class="cop_ft_general_field_div">
								<div class="cop_ft_class cop_ft_main_field">
									<div scope="row1" class="col-251">
										<div scope="row" class="col-25">
											<label><?php esc_html_e( 'Status', 'redirect-thank-you-page' ); ?></label>
										</div>
									</div>
									<div class="forminp">
										<div class="forminp1">
											<input type="checkbox" name="wmprty_data[wmprty_addon_status]" id="wmprty_addon_status_id" value="on" <?php checked( 'on', $check_wmprty_addon_status ); ?>>
											<label class="wmprty_addon_status_id_label" for="wmprty_addon_status_id"><?php esc_html_e( 'Enable product redirect cutom thank you page', 'redirect-thank-you-page' ); ?></label>
										</div>
									</div>
								</div>
								<div class="cop_ft_class cop_ft_main_field">
									<div scope="row1" class="col-251">
										<div scope="row" class="col-25 col-25-main-field">
											<label><?php esc_html_e( 'Name', 'redirect-thank-you-page' ); ?></label>
										</div>
									</div>
									<div class="forminp">
										<div class="forminp1">
											<input type="text" name="wmprty_data[wmprty_addon_name]" id="wmprty_addon_id" placeholder="<?php esc_html_e( 'Enter name of product redirect thank you page', 'redirect-thank-you-page' ); ?>" value="<?php echo esc_attr( $check_wmprty_addon_name ); ?>">
										</div>
									</div>
								</div>
								<?php
								do_action( 'wmprty_products_fields', $get_data );
								do_action( 'wmprty_pages_fields', $get_data );
								?>
							</div>
						</div>
						<strong><?php esc_html_e( 'Note:', 'redirect-thank-you-page' ); ?></strong> <?php esc_html_e( 'Shortcode to display order details on custom redirection page', 'redirect-thank-you-page' ); ?> <code> [wmprty_order_detail]</code>
					</td>
				</tr>
				</tbody>
			</table>

			<p class="submit">
				<input type="submit" class="button button-primary" name="wmprty_save" value="<?php esc_html_e( 'Save Changes', 'redirect-thank-you-page' ); ?>">
			</p>
		</div>
	</div>
<?php
wp_nonce_field( 'woocommerce_save_method', 'woocommerce_save_method_nonce' );
