<?php
/**
 * Common functions for plugins.
 *
 * @since      1.0.0
 * @package    Redirect_Thank_You_Page
 * @subpackage Redirect_Thank_You_Page/settings
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Start - Html Action
 */
add_action( 'wmprty_field_start_tr', 'wmprty_field_start_tr_fn', 10, 2 );
add_action( 'wmprty_field_end_tr', 'wmprty_field_end_tr_fn' );
add_action( 'wmprty_field_start_th', 'wmprty_field_start_th_fn' );
add_action( 'wmprty_field_end_th', 'wmprty_field_end_th_fn' );
add_action( 'wmprty_field_start_label', 'wmprty_field_start_label_fn', 10, 2 );
add_action( 'wmprty_field_end_label', 'wmprty_field_end_label_fn' );
add_action( 'wmprty_field_start_td', 'wmprty_field_start_td_fn', 10, 2 );
add_action( 'wmprty_field_end_td', 'wmprty_field_end_td_fn' );

/**
 * Html for main start tr.
 *
 * @param string $id    ID of the tr.
 * @param string $class Class of the tr.
 *
 * @since 1.0.0
 */
function wmprty_field_start_tr_fn( $id, $class ) {
	?>
	<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
	<?php
}

/**
 * Html for main end tr.
 *
 * @since 1.0.0
 */
function wmprty_field_end_tr_fn() {
	?>
	</div>
	<?php
}

/**
 * Html for main start th.
 *
 * @since 1.0.0
 */
function wmprty_field_start_th_fn() {
	?>
	<div scope="row1" class="col-251">
	<div scope="row" class="col-25">
	<?php
}

/**
 * Html for main end th.
 *
 * @since 1.0.0
 */
function wmprty_field_end_th_fn() {
	?>
	</div>
	</div>
	<?php
}

/**
 * Html for main start label.
 *
 * @param string $for_attr Attr for label.
 *
 * @since 1.0.0
 */
function wmprty_field_start_label_fn( $for_attr ) {
	?>
	<label for="<?php echo esc_attr( $for_attr ); ?>">
	<?php
}

/**
 * Html for main end label.
 *
 * @since 1.0.0
 */
function wmprty_field_end_label_fn() {
	?>
	</label>
	<?php
}

/**
 * Html for main start td.
 *
 * @param string $id    ID of the tr.
 * @param string $class Class of the tr.
 *
 * @since 1.0.0
 */
function wmprty_field_start_td_fn( $id, $class ) {
	?>
	<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
	<div class="forminp1">
	<?php
}

/**
 * Html for main end td.
 *
 * @since 1.0.0
 */
function wmprty_field_end_td_fn() {
	?>
	</div>
	</div>
	<?php
}

/**
 * Field data.
 *
 * @param array $field_property_array Return array of options.
 *
 * @return mixed|void
 *
 * @since 1.0.0
 */
function wmprty_field_property_settings( $field_property_array ) {
	$filter_arr = array();
	if ( $field_property_array ) {
		foreach ( $field_property_array as $arr_key => $arr_value ) {
			if ( ! empty( $arr_value ) ) {
				$filter_arr[ $arr_key ] = $arr_value;
			}
		}
	}

	return apply_filters( 'wmprty_field_text_arr', $filter_arr );
}

add_action( 'wmprty_products_fields', 'wmprty_products_fields_fn', 10, 1 );

/**
 * Additional rule html.
 *
 * @param array $get_data Get all data on edit time.
 *
 * @since 1.0.0
 */
function wmprty_products_fields_fn( $get_data ) {
	$field_slug  = 'wmprty_products';
	$field_title = esc_html( WMPRTY_PRODUCT_FIELDS );
	$field_desc  = esc_html__( 'Select product which you want set custom redirect thank you page.', 'redirect-thank-you-page' );
	do_action( 'wmprty_field_start_tr', 'cop_ft_id', 'cop_ft_class' );
	do_action( 'wmprty_field_start_th' );
	do_action( 'wmprty_field_start_label', 'wmprty_' . $field_slug );
	echo esc_html( $field_title );
	do_action( 'wmprty_field_end_label' );
	echo wp_kses_post( wc_help_tip( $field_desc ) );
	do_action( 'wmprty_field_end_th' );
	do_action( 'wmprty_field_start_td', '', 'forminp' );
	$aadditional_rule_data = wmprty_check_array_key_exists( 'wmprty_products', $get_data );
	?>
	<div class="aditional_rules_section" id="aditional_rules_section">
		<div class="wmprty_rule_repeater">
			<?php
			if ( ! empty( $aadditional_rule_data ) ) {
				?>
				<div class="wmprty_rule_repeater">
					<ul class="additional_rule_ul" id="additional_rule_ul">
						<li>
							<select placeholder="<?php esc_html_e( 'Please select product', 'redirect-thank-you-page' ); ?>" id="wmprty_condition_data_products_id" class="wmprty_condition_data_class_product wmprty_condition_data_class" name="wmprty_data[wmprty_products][]">
							<?php
							foreach ( $aadditional_rule_data as $condition_value_id ) {
								?>
								<option value="<?php echo esc_attr( $condition_value_id ); ?>" <?php selected( $condition_value_id, $condition_value_id ); ?>><?php echo wp_kses_post( get_the_title( $condition_value_id ) ); ?></option>
								<?php
							}
							?>
							</select>
						</li>
					</ul>
				</div>
				<?php
			} else {
				?>
				<ul id="additional_rule_ul" class="additional_rule_ul">
					<li>
						<select placeholder="<?php esc_html_e( 'Please select product', 'redirect-thank-you-page' ); ?>" id="wmprty_condition_data_products_id" class="wmprty_condition_data_class_product wmprty_and_condition_data_class_1" name="wmprty_data[wmprty_products][]"></select>
					</li>
				</ul>
				<?php
			}
			?>
		</div>
	</div>
	<?php
	do_action( 'wmprty_field_end_td' );
	do_action( 'wmprty_field_end_tr' );
}

/**
 * Additional rule html.
 *
 * @param array $get_data Get all data on edit time.
 *
 * @since 1.0.0
 */
function wmprty_payments_fields_fn( $get_data ) {
	$field_slug  = 'wmprty_payments';
	$field_title = esc_html( WMPRTY_PAYMENT_FIELDS );
	$field_desc  = esc_html__( 'Select payment gateway which you want set custom redirect thank you page.', 'redirect-thank-you-page' );
	do_action( 'wmprty_field_start_tr', 'cop_ft_id', 'cop_ft_class' );
	do_action( 'wmprty_field_start_th' );
	do_action( 'wmprty_field_start_label', 'wmprty_' . $field_slug );
	echo esc_html( $field_title );
	do_action( 'wmprty_field_end_label' );
	echo wp_kses_post( wc_help_tip( $field_desc ) );
	do_action( 'wmprty_field_end_th' );
	do_action( 'wmprty_field_start_td', '', 'forminp' );
	$aadditional_rule_data = wmprty_check_array_key_exists( 'wmprty_products', $get_data );
	$gateways              = WC()->payment_gateways->get_available_payment_gateways();

	?>
	<div class="aditional_rules_section" id="aditional_rules_section">
		<div class="wmprty_rule_repeater">
			<?php
			if ( ! empty( $aadditional_rule_data ) ) {
				?>
				<div class="wmprty_rule_repeater">
					<ul class="additional_rule_ul" id="additional_rule_ul">
						<li>
							<select id="wmprty_condition_data_products_id" class="wmprty_condition_data_class_payment wmprty_condition_data_class" name="wmprty_data[wmprty_products][]">
								<?php
								foreach ( $aadditional_rule_data as $condition_value_id ) {
									$gateway = $gateways[ $condition_value_id ];
									?>
										<option value="<?php echo esc_attr( $condition_value_id ); ?>" <?php selected( $condition_value_id, $condition_value_id ); ?>><?php echo wp_kses_post( $gateway->title ); ?></option>
										<?php
								}
								?>
							</select>
						</li>
					</ul>
				</div>
				<?php
			} else {
				?>
				<ul id="additional_rule_ul" class="additional_rule_ul">
					<li>
						<select id="wmprty_condition_data_products_id" class="wmprty_condition_data_class_payment wmprty_and_condition_data_class_1" name="wmprty_data[wmprty_products][]"></select>
					</li>
				</ul>
				<?php
			}
			?>
		</div>
	</div>
	<?php
	do_action( 'wmprty_field_end_td' );
	do_action( 'wmprty_field_end_tr' );
}

add_action( 'wmprty_payments_fields', 'wmprty_payments_fields_fn', 10, 1 );

add_action( 'wmprty_pages_fields', 'wmprty_pages_fields_fn', 10, 1 );
add_action( 'wmprty_payment_pages_fields', 'wmprty_payment_pages_fields_fn', 10, 1 );

/**
 * Additional rule html.
 *
 * @param array $get_data Get all data on edit time.
 *
 * @since 1.0.0
 */
function wmprty_payment_pages_fields_fn( $get_data ) {
	$field_slug  = 'wmprty_products';
	$field_title = esc_html( WMPRTY_PAYMENT_PAGES_FIELDS );
	$field_desc  = esc_html__( 'Select the page where you need redirect.', 'redirect-thank-you-page' );
	do_action( 'wmprty_field_start_tr', 'cop_ft_id', 'cop_ft_class' );
	do_action( 'wmprty_field_start_th' );
	do_action( 'wmprty_field_start_label', 'wmprty_' . $field_slug );
	echo esc_html( $field_title );
	do_action( 'wmprty_field_end_label' );
	echo wp_kses_post( wc_help_tip( $field_desc ) );
	do_action( 'wmprty_field_end_th' );
	do_action( 'wmprty_field_start_td', '', 'forminp' );
	$aadditional_rule_data = wmprty_check_array_key_exists( 'wmprty_pages', $get_data );
	?>
	<div class="aditional_rules_section" id="aditional_rules_section">
		<div class="wmprty_rule_repeater">
			<?php
			if ( ! empty( $aadditional_rule_data ) ) {
				?>
				<div class="wmprty_rule_repeater">
					<ul class="additional_rule_ul" id="additional_rule_ul">
						<li>
							<select placeholder="<?php esc_html_e( 'Select a page from your website', 'redirect-thank-you-page' ); ?>" id="wmprty_condition_data_pages_id" class="wmprty_condition_data_class_pages wmprty_condition_data_class multiselect2" name="wmprty_data[wmprty_pages][]">
								<?php
								foreach ( $aadditional_rule_data as $condition_value_id ) {
									?>
									<option value="<?php echo esc_attr( $condition_value_id ); ?>" <?php selected( $condition_value_id, $condition_value_id ); ?>><?php echo wp_kses_post( get_the_title( $condition_value_id ) ); ?></option>
									<?php
								}
								?>
							</select>
						</li>
					</ul>
				</div>
				<?php
			} else {
				?>
				<ul id="additional_rule_ul" class="additional_rule_ul">
					<li>
						<select placeholder="<?php esc_html_e( 'Select a page from your website', 'redirect-thank-you-page' ); ?>" id="wmprty_condition_data_pages_id" class="wmprty_condition_data_class_pages wmprty_and_condition_data_class_1 multiselect2" name="wmprty_data[wmprty_pages][]"></select>
					</li>
				</ul>
				<?php
			}
			?>
		</div>
	</div>
	<?php
	do_action( 'wmprty_field_end_td' );
	do_action( 'wmprty_field_end_tr' );
}

/**
 * Additional rule html.
 *
 * @param array $get_data Get all data on edit time.
 *
 * @since 1.0.0
 */
function wmprty_pages_fields_fn( $get_data ) {
	$field_slug  = 'wmprty_products';
	$field_title = esc_html( WMPRTY_PAGES_FIELDS );
	$field_desc  = esc_html__( 'Select the page where you need redirect.', 'redirect-thank-you-page' );
	do_action( 'wmprty_field_start_tr', 'cop_ft_id', 'cop_ft_class' );
	do_action( 'wmprty_field_start_th' );
	do_action( 'wmprty_field_start_label', 'wmprty_' . $field_slug );
	echo esc_html( $field_title );
	do_action( 'wmprty_field_end_label' );
	echo wp_kses_post( wc_help_tip( $field_desc ) );
	do_action( 'wmprty_field_end_th' );
	do_action( 'wmprty_field_start_td', '', 'forminp' );
	$aadditional_rule_data = wmprty_check_array_key_exists( 'wmprty_pages', $get_data );
	?>
	<div class="aditional_rules_section" id="aditional_rules_section">
		<div class="wmprty_rule_repeater">
			<?php
			if ( ! empty( $aadditional_rule_data ) ) {
				?>
				<div class="wmprty_rule_repeater">
					<ul class="additional_rule_ul" id="additional_rule_ul">
						<li>
							<select placeholder="<?php esc_html_e( 'Select a page from your website', 'redirect-thank-you-page' ); ?>" id="wmprty_condition_data_pages_id" class="wmprty_condition_data_class_pages wmprty_condition_data_class multiselect2" name="wmprty_data[wmprty_pages][]">
								<?php
								foreach ( $aadditional_rule_data as $condition_value_id ) {
									?>
									<option value="<?php echo esc_attr( $condition_value_id ); ?>" <?php selected( $condition_value_id, $condition_value_id ); ?>><?php echo wp_kses_post( get_the_title( $condition_value_id ) ); ?></option>
									<?php
								}
								?>
							</select>
						</li>
					</ul>
				</div>
				<?php
			} else {
				?>
				<ul id="additional_rule_ul" class="additional_rule_ul">
					<li>
						<select placeholder="<?php esc_html_e( 'Select a page from your website', 'redirect-thank-you-page' ); ?>" id="wmprty_condition_data_pages_id" class="wmprty_condition_data_class_pages wmprty_and_condition_data_class_1 multiselect2" name="wmprty_data[wmprty_pages][]"></select>
					</li>
				</ul>
				<?php
			}
			?>
		</div>
	</div>
	<?php
	do_action( 'wmprty_field_end_td' );
	do_action( 'wmprty_field_end_tr' );
}

/**
 * Get data for fields.
 *
 * @param int $get_post_id Post id.
 *
 * @return array $get_data Return data for fields.
 *
 * @since 1.0.0
 */
function wmprty_get_data_from_db( $get_post_id ) {
	$get_data_json = get_post_meta( $get_post_id, 'wmprty_prd_opt_data', true );
	$get_data      = json_decode( $get_data_json, true );

	return $get_data;
}

/**
 * Check array key exists or not.
 *
 * @param string $key   Key of the field.
 * @param array  $array Array of the data.
 *
 * @return string $var_name Return var name.
 *
 * @since 1.0.0
 */
function wmprty_check_array_key_exists( $key, $array ) {
	$var_name = '';
	if ( ! empty( $array ) ) {
		if ( array_key_exists( $key, $array ) ) {
			$var_name = $array[ $key ];
		}
	}

	return $var_name;
}

/**
 * Buy btn.
 *
 * @param string $text Text to be write in button.
 *
 * @since 1.0.0
 */
function wmprty_buy_btn( $text ) {
	?>

	<?php

}
