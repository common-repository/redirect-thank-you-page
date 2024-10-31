/* jshint browser: true */
/* global wmprty_var */
/* @version 1.0.0 */
( function ( $ ) {
	'use strict';

	function forAllFilter() {
		selectFilter( '.wmprty_condition_data_class_product', 'wmprty_get_data_based_on_cd', 'products', wmprty_var.placeholder_product_select );
		selectFilter( '.wmprty_condition_data_class_payment', 'wmprty_get_data_based_on_payments_cd', 'payments', wmprty_var.placeholder_payment_select );
		selectFilter( '.wmprty_condition_data_class_pages', 'wmprty_get_pages_data', 'pages', wmprty_var.placeholder_product_thank_you );
	}

	function cpoAllowSpecialCharacter( str ) {
		return str.replace( '&#8211;', '–' ).replace( '&gt;', '>' ).replace( '&lt;', '<' ).replace( '&#197;', 'Å' );
	}

	function selectFilter( filterBasedOnClass, ajaxAction, current_val, placeholder_text ) {
		jQuery( filterBasedOnClass ).each(
			function () {
				jQuery( filterBasedOnClass ).select2(
					{
						ajax: {
							url: wmprty_var.ajaxurl,
							dataType: 'json',
							delay: 250,
							type: 'post',
							data: function ( params ) {
								return {
									value: params.term,
									current_condition: current_val,
									action: ajaxAction
								};
							},
							processResults: function ( data ) {
								var options = [];
								if ( data ) {
									$.each(
										data,
										function ( index, text ) {
											options.push( {
												id: text[ 0 ],
												text: cpoAllowSpecialCharacter( text[ 1 ] )
											} );
										}
									);
								}
								return {
									results: options
								};
							},
							cache: true
						},
						minimumInputLength: 2,
						placeholder: placeholder_text,
					}
				);
			}
		);
	}

	var AWS_ADMIN = {
		init: function () {
			forAllFilter();
			$( '.tips, .help_tip, .woocommerce-help-tip' ).tipTip(
				{
					'attribute': 'data-tip',
					'fadeIn': 50,
					'fadeOut': 50,
					'delay': 200
				}
			);

			$( '#mainform table.form-table > tbody  > tr' ).each( function () {
				var trInputClass = $( this ).find( 'input' ).attr( 'class' );
				var trSelectClass = $( this ).find( 'select' ).attr( 'class' );

				if ( undefined !== trInputClass ) {
					$( this ).addClass( trInputClass );
				}

				if ( undefined !== trSelectClass ) {
					if ( trSelectClass.indexOf( 'select-thank-you-page' ) != -1 ) {
						$( this ).addClass( 'select-thank-you-page' );
					}

					if ( trSelectClass.indexOf( 'select-failed-page' ) != -1 ) {
						$( this ).addClass( 'select-failed-page' );
					}

				}

			} );

			var thankYouType = $('input[name=wc_settings_thank_you_type]:checked', '#mainform').val();
			var failedType = $('input[name=wc_settings_failed_type]:checked', '#mainform').val();

			if ( 'page' === failedType ) {
				$( '#mainform .form-table tbody tr.failed-url' ).hide();
				$( '#mainform .form-table tbody tr.select-failed-page' ).show();
			} else {
				$( '#mainform .form-table tbody tr.failed-url' ).show();
				$( '#mainform .form-table tbody tr.select-failed-page' ).hide();
			}

			if ( 'page' === thankYouType ) {
				$( '#mainform .form-table tbody tr.thank-you-url' ).hide();
				$( '#mainform .form-table tbody tr.select-thank-you-page' ).show();
			} else {
				$( '#mainform .form-table tbody tr.thank-you-url' ).show();
				$( '#mainform .form-table tbody tr.select-thank-you-page' ).hide();
			}

			$('#mainform input[name=wc_settings_thank_you_type]').on('change', function() {
				thankYouType = $('input[name=wc_settings_thank_you_type]:checked', '#mainform').val();
				if ( 'page' === thankYouType ) {
					$( '#mainform .form-table tbody tr.thank-you-url' ).hide();
					$( '#mainform .form-table tbody tr.select-thank-you-page' ).show();
				} else {
					$( '#mainform .form-table tbody tr.thank-you-url' ).show();
					$( '#mainform .form-table tbody tr.select-thank-you-page' ).hide();
				}

			});

			$('#mainform input[name=wc_settings_failed_type]').on('change', function() {
				failedType = $('input[name=wc_settings_failed_type]:checked', '#mainform').val();
				if ( 'page' === failedType ) {
					$( '#mainform .form-table tbody tr.failed-url' ).hide();
					$( '#mainform .form-table tbody tr.select-failed-page' ).show();
				} else {
					$( '#mainform .form-table tbody tr.failed-url' ).show();
					$( '#mainform .form-table tbody tr.select-failed-page' ).hide();
				}

			});




		},
	};
	$(
		function () {
			AWS_ADMIN.init();
		}
	);
} )( jQuery );
