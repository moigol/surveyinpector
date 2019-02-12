<?php
/**
 * Shortcode [wp-review-google-place-average-rating]
 *
 * @package WP_Review
 * @since 3.0.0
 */

/**
 * Class WP_Review_Google_Place_Average_Rating_Shortcode
 */
class WP_Review_Google_Place_Average_Rating_Shortcode extends WP_Review_Google_Place_Reviews_Shortcode {

	/**
	 * Shortcode name.
	 *
	 * @var string
	 */
	protected $name = 'wp-review-google-place-average-rating';

	/**
	 * Shortcode alias.
	 *
	 * @var string
	 */
	protected $alias = 'wp_review_google_place_average_rating';

	/**
	 * Class init.
	 */
	public function init() {
		add_shortcode( $this->name, array( $this, 'render' ) );
		add_shortcode( $this->alias, array( $this, 'render' ) );
	}

	/**
	 * Renders shortcode.
	 *
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'place_id' => '',
			),
			$atts,
			$this->name
		);

		if ( ! $atts['place_id'] ) {
			return;
		}

		$response = $this->get_place( $atts['place_id'] );
		if ( ! $response ) {
			return;
		}

		$response = json_decode( $response, true );
		if ( empty( $response['result'] ) ) {
			$error = $response['status'];
			if ( ! empty( $response['error_message'] ) ) {
				$error .= ': ' . $response['error_message'];
			}
			return '<div class="wpr-error">' . $error . '</div>';
		}

		ob_start();
		wp_review_load_template( 'shortcodes/google-place-average-rating.php', compact( 'response', 'atts' ) );
		return ob_get_clean();
	}
}

$shortcode = new WP_Review_Google_Place_Average_Rating_Shortcode();
$shortcode->init();
