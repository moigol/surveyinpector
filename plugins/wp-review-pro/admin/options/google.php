<?php
/**
 * Google options
 *
 * @package WP_Review
 */

$api_key = wp_review_option( 'google_api_key' );
?>
<div class="wp-review-field">
	<div class="wp-review-field-label">
		<label for="wp_review_google_api_key"><?php esc_html_e( 'API Key', 'wp-review' ); ?></label>
	</div>

	<div class="wp-review-field-option">
		<input name="wp_review_options[google_api_key]" id="wp_review_google_api_key" class="large-text" type="password" value="<?php echo esc_attr( $api_key ); ?>" class="all-options">
	</div>

	<span class="description">
		<?php
		printf(
			/* translators: Google API link. */
			esc_html__( '%s to get Google API key.', 'wp-review' ),
			'<a href="https://mythemeshop.com/kb/wp-review-pro/google-reviews/" target="_blank">' . esc_html__( 'Click here', 'wp-review' ) . '</a>'
		);
		?>
	</span>
</div>
