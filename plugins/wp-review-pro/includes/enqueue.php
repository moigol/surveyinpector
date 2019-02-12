<?php
/**
 * Custom style for the plugin.
 *
 * @since     1.0
 * @copyright Copyright (c) 2013, MyThemesShop
 * @author    MyThemesShop
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package   WP_Review
 */

/* Enqueue style for this plugin. */
add_action( 'wp_enqueue_scripts', 'wp_review_enqueue', 12 );

/**
 * Enqueue style
 *
 * @since 1.0
 * @since 3.0.0 Add font awesome.
 */
function wp_review_enqueue() {

	// Register.
	wp_register_style( 'fontawesome', WP_REVIEW_ASSETS . 'css/font-awesome.min.css', array(), '4.7.0' );

	wp_register_style( 'wp-review-popup', WP_REVIEW_ASSETS . 'css/popup.css', array(), '3.0.0' );

	wp_register_style( 'magnificPopup', WP_REVIEW_ASSETS . 'css/magnific-popup.css', array(), '1.1.0' );
	wp_register_script( 'magnificPopup', WP_REVIEW_ASSETS . 'js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );

	wp_register_style( 'wp_review-style', WP_REVIEW_ASSETS . 'css/wp-review.css', array(), WP_REVIEW_PLUGIN_VERSION, 'all' );

	wp_register_script( 'wp_review-jquery-appear', WP_REVIEW_ASSETS . 'js/jquery.appear.js', array( 'jquery' ), '1.1', true );

	wp_register_script( 'wp-review-exit-intent', WP_REVIEW_ASSETS . 'js/jquery.exitIntent.js', array( 'jquery' ), '3.0.0', true );

	wp_register_script( 'js-cookie', WP_REVIEW_ASSETS . 'js/js.cookie.min.js', array(), '2.1.4', true );

	wp_register_script( 'jquery-knob', WP_REVIEW_ASSETS . 'js/jquery.knob.min.js', array( 'jquery' ), '1.1', true );

	wp_register_script( 'stacktable', WP_REVIEW_ASSETS . 'js/stacktable.js', array( 'jquery' ), false, true );

	wp_register_script( 'wp-review-circle-output', WP_REVIEW_URI . 'rating-types/circle-output.js', array( 'jquery-knob' ), '3.0.8', true );
	wp_register_script( 'wp-review-circle-input', WP_REVIEW_URI . 'rating-types/circle-input.js', array( 'jquery-knob' ), '3.0.8', true );

	wp_register_script( 'wp_review-js', WP_REVIEW_ASSETS . 'js/main.js', array( 'stacktable', 'wp_review-jquery-appear', 'magnificPopup', 'js-cookie', 'wp-review-exit-intent', 'wp-util' ), WP_REVIEW_PLUGIN_VERSION, true );

	wp_localize_script(
		'wp_review-js',
		'wpreview',
		array(
			'ajaxurl'          => admin_url( 'admin-ajax.php' ),
			'popup'            => wp_review_get_popup_config(),
			'rateAllFeatures'  => __( 'Please rate all features', 'wp-review' ),
			'verifiedPurchase' => __( '(Verified purchase)', 'wp-review' ),
		)
	);

	// Enqueue.
	wp_enqueue_style( 'fontawesome' );
	wp_enqueue_script( 'js-cookie' );
	wp_enqueue_script( 'wp_review-js' );
	wp_enqueue_script( 'stacktable' );
	wp_enqueue_script( 'wp_review-jquery-appear' );
	wp_enqueue_script( 'jquery-knob' );
	wp_enqueue_style( 'magnificPopup' );
	wp_enqueue_script( 'magnificPopup' );
	wp_enqueue_script( 'wp-review-exit-intent' );
	wp_enqueue_style( 'wp_review-style' );

	$popup_config = wp_review_get_popup_config();
	if ( $popup_config['enable'] ) {
		wp_enqueue_style( 'wp-review-popup' );
	}
}

/**
 * IE7 style for the font icon.
 *
 * @since 1.0
 * @deprecated 3.0.0 Default icon font is no longer used.
 */
function wp_review_ie7() {
	_deprecated_function( __FUNCTION__, '3.0.0' );
	?>
	<!--[if IE 7]>
	<link rel="stylesheet" href="<?php echo trailingslashit( WP_REVIEW_ASSETS ) . 'css/wp-review-ie7.css'; ?>">
	<![endif]-->
	<?php
}
