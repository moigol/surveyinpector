<?php
/**
 * WP Review: Fizzy
 * Description: Fizzy Review Box template for WP Review
 * Version: 1.0.0
 * Author: MyThemesShop
 * Author URI: http://mythemeshop.com/
 *
 * @package   WP_Review
 * @since     3.0.0
 * @copyright Copyright (c) 2017, MyThemesShop
 * @author    MyThemesShop
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/*
 *	Available items in $review array:
 *
 *		'post_id',
		'type',
		'heading',
		'author',
		'items',
		'hide_desc',
		'desc',
		'desc_title',
		'pros',
		'cons',
		'total',
		'colors',
		'width',
		'align',
		'schema',
		'schema_data',
		'show_schema_data',
		'rating_schema',
		'links',
		'user_review',
		'user_review_type',
		'user_review_total',
		'user_review_count',
		'user_has_reviewed',
		'comments_review'
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rating_types = wp_review_get_rating_types();

$classes = implode( ' ', $review['css_classes'] );

$is_embed = wp_review_is_embed();

if ( ! empty( $review['fontfamily'] ) ) : ?>
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,700" rel="stylesheet">
	<style type="text/css">
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper { font-family: 'Roboto Slab', serif; }
	</style>
<?php endif; ?>

<div id="review" class="<?php echo esc_attr( $classes ); ?>">
	<?php if ( empty( $review['heading'] ) ) : ?>
		<?php echo esc_html( apply_filters( 'wp_review_item_title_fallback', '' ) ); ?>
	<?php else : ?>
		<div class="review-heading">
			<h5 class="review-title">
				<?php echo esc_html( $review['heading'] ); ?>

				<?php if ( ! empty( $review['product_price'] ) ) : ?>
					<span class="review-price"><?php echo esc_html( $review['product_price'] ); ?></span>
				<?php endif; ?>
			</h5>
		</div>
	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-schema.php', compact( 'review' ) ); ?>

	<?php if ( $review['items'] && is_array( $review['items'] ) && empty( $review['disable_features'] ) ) : ?>
		<ul class="review-list">
			<?php foreach ( $review['items'] as $item ) :
				$item = wp_parse_args( $item, array(
					'wp_review_item_star'  => '',
					'wp_review_item_title' => '',
					'wp_review_item_color' => '',
					'wp_review_item_inactive_color' => '',
					'wp_review_item_positive'       => '',
					'wp_review_item_negative'       => '',
				) );
				$value_text = '';
				if ( 'star' != $review['type'] ) {
					$value_text = ' - <span>' . sprintf( $rating_types[ $review['type'] ]['value_text'], $item['wp_review_item_star'] ) . '</span>';
				}
				?>
				<li>
					<?php
					echo wp_review_rating(
						$item['wp_review_item_star'],
						$review['post_id'],
						array(
							'color' => $item['wp_review_item_color'],
							'inactive_color' => $item['wp_review_item_inactive_color'],
							'positive_count' => $item['wp_review_item_positive'],
							'negative_count' => $item['wp_review_item_negative'],
						)
					);
					?>
					<span><?php echo wp_kses_post( $item['wp_review_item_title'] ); ?><?php echo $value_text; ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ( ! $is_embed && $review['user_review'] && ! $review['hide_visitors_rating'] ) : ?>
		<?php if ( ! wp_review_user_can_rate_features( $review['post_id'] ) ) : ?>
			<div class="user-review-area visitors-review-area">
				<?php echo wp_review_user_rating( $review['post_id'] ); ?>
				<div class="user-total-wrapper">
					<h5 class="user-review-title"><?php esc_html_e( 'User Review', 'wp-review' ); ?></h5>
					<span class="review-total-box">
						<?php
						$usertotal_text = $review['user_review_total'];
						if ( 'star' != $review['user_review_type'] ) {
							$usertotal_text = sprintf( $rating_types[ $review['user_review_type'] ]['value_text'], $review['user_review_total'] );
						}
						?>
						<span class="wp-review-user-rating-total"><?php echo esc_html( $usertotal_text ); ?></span>
						<small>(<span class="wp-review-user-rating-counter"><?php echo esc_html( $review['user_review_count'] ); ?></span> <?php echo esc_html( _n( 'vote', 'votes', $review['user_review_count'], 'wp-review' ) ); ?>)</small>
					</span>
				</div>
			</div>
		<?php else : ?>
			<?php echo wp_review_visitor_feature_rating( $review['post_id'] ); ?>
		<?php endif; ?>
	<?php endif; // $review['user_review'] ?>

	<?php if ( ! $is_embed && $review['comments_review'] && ! $review['hide_comments_rating'] ) : ?>
		<div class="user-review-area comments-review-area">
			<?php echo wp_review_user_comments_rating( $review['post_id'] ); ?>
			<div class="user-total-wrapper">
				<span class="user-review-title"><?php esc_html_e( 'Comments Rating', 'wp-review' ); ?></span>
				<span class="review-total-box">
					<?php
					$comment_reviews       = mts_get_post_comments_reviews( $review['post_id'] );
					$comments_review_total = $comment_reviews['rating'];
					$comments_review_count = $comment_reviews['count'];
					$comments_total_text  = $comments_review_total;
					if ( 'star' != $review['user_review_type'] ) {
						$comments_total_text = sprintf( $rating_types[ $review['user_review_type'] ]['value_text'], $comments_review_total );
					}
					?>
					<span class="wp-review-user-rating-total"><?php echo esc_html( $comments_total_text ); ?></span>
					<small>(<span class="wp-review-user-rating-counter"><?php echo esc_html( $comments_review_count ); ?></span> <?php echo esc_html( _n( 'review', 'reviews', $comments_review_count, 'wp-review' ) ); ?>)</small>
					<br />
					<small class="awaiting-response-wrapper"></small>
				</span>
			</div>
		</div>
	<?php endif; // $review['comments_review'] ?>

	<?php if ( ! $review['hide_desc'] ) : ?>

		<?php if ( ! empty( $review['total'] ) ) :
			$total_text = $review['total'];
			if ( 'star' != $review['type'] ) {
				$total_text = sprintf( $rating_types[ $review['type'] ]['value_text'], $total_text );
			}
			?>
			<div class="review-total-wrapper">
				<span class="review-total-box">
    				<h5><?php esc_html_e( 'Overall', 'wp-review' ); ?></h5>
    				<div><?php echo $total_text; ?></div>
    			</span>
				<?php if ( 'point' != $review['type'] && 'percentage' != $review['type'] ) : ?>
					<?php
					echo wp_review_rating( $review['total'], $review['post_id'], array(
						'review_total' => true,
						'class'        => 'review-total',
					) );
					?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $review['desc'] ) : ?>
			<div class="review-desc">
				<p class="review-summary-title"><strong><?php echo $review['desc_title']; ?></strong></p>
				<?php // echo do_shortcode( shortcode_unautop( wp_kses_post( wpautop( $review['desc'] ) ) ) ); ?>
				<?php echo apply_filters( 'wp_review_desc', $review['desc'], $review['post_id'] ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $review['pros'] || $review['cons'] ) : ?>
			<div class="review-pros-cons">
				<div class="review-pros">
					<p class="mb-5"><strong><?php esc_html_e( 'Pros', 'wp-review' ); ?></strong></p>
					<div><?php echo apply_filters( 'wp_review_pros', $review['pros'], $review['post_id'] ); ?></div>
				</div>

				<div class="review-cons">
					<p class="mb-5"><strong><?php esc_html_e( 'Cons', 'wp-review' ); ?></strong></p>
					<div><?php echo apply_filters( 'wp_review_cons', $review['cons'], $review['post_id'] ); ?></div>
				</div>
			</div>
		<?php endif; ?>

	<?php endif; ?>

	<?php wp_review_load_template( 'global/partials/review-links.php', compact( 'review' ) ); ?>

	<?php if ( ! $is_embed && ! empty( $review['enable_embed'] ) ) : ?>
		<div class="review-embed-code">
			<label for="wp_review_embed_code"><?php esc_html_e( 'Embed code', 'wp-review' ); ?></label>
			<textarea id="wp_review_embed_code" rows="2" cols="40" readonly onclick="this.select()"><?php echo esc_textarea( wp_review_get_embed_code( $review['post_id'] ) ); ?></textarea>
		</div>
	<?php endif; ?>
</div>

<?php
if ( ! function_exists( 'color_luminance' ) ) {
	/**
	 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.
	 *
	 * @param string $hex     Colour as hexadecimal (with or without hash).
	 * @param float  $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() ).
	 * @return string         Lightened/Darkend colour as hexadecimal (with hash);
	 */
	function color_luminance( $hex, $percent ) {
		// Validate hex string.
		$hex = preg_replace( '/[^0-9a-f]/i', '', $hex );
		$new_hex = '#';

		if ( strlen( $hex ) < 6 ) {
			$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
		}

		// Convert to decimal and change luminosity.
		for ( $i = 0; $i < 3; $i++ ) {
			$dec = hexdec( substr( $hex, $i * 2, 2 ) );
			$dec = min( max( 0, $dec + $dec * $percent ), 255 );
			$new_hex .= str_pad( dechex( $dec ), 2, 0, STR_PAD_LEFT );
		}

		return $new_hex;
	}
}
$colors = $review['colors'];
$dark_color = color_luminance( $colors['color'], '-0.1' );

ob_start();
// phpcs:disable
?>
<style type="text/css">
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		width: <?php echo $review['width']; ?>%;
		float: <?php echo $review['align']; ?>;
		border: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc {
		padding: 25px 30px 25px 30px;
		min-height: 184px;
		overflow: hidden;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper,
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-desc p,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item p {
		color: <?php echo $colors['fontcolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links a {
		background: <?php echo $colors['color']; ?>;
		background: linear-gradient(to top, <?php echo $dark_color; ?> 0%, <?php echo $colors['color']; ?> 100%);
		color: #fff;
		padding: 10px 25px;
		border-radius: 25px;
		font-size: 15px;
		border: 1px solid <?php echo $dark_color; ?>;
		text-transform: uppercase;
		letter-spacing: 1px;
		cursor: pointer;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper {
		background: <?php echo $colors['bgcolor2']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-star-type .review-list li:before {
		content: '';
		height: 1px;
		width: 12%;
		background: <?php echo $colors['color']; ?>;
		position: absolute;
		top: 22px;
		right: 125px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-star-type .review-list li .review-star {
		border: 1px solid <?php echo $colors['color']; ?>;
		background: <?php echo $colors['bgcolor2']; ?>;
		margin-top: -6px;
		padding: 5px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-star-type .review-list li .review-star + span {
		background: <?php echo $colors['bgcolor2']; ?>;
		position: relative;
		z-index: 1;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-star-type .review-total .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-star-type .review-total .review-result {
	    margin: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-star-type .user-review-area .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-star-type .user-review-area .review-result-wrapper .review-result {
		margin: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-list li {
		position: relative;
		width: 50%;
		float: left;
		box-sizing: border-box;
	}
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li > span,
	.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li > span {
		position: absolute;
		top: 18px;
		left: 25px;
		color: #fff;
        font-size: 14px;
		line-height: 1;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:last-child {
		border: none;
	}
	.wp-review-<?php echo $review['post_id']; ?> .review-links {
		padding: 30px 30px 20px 30px;
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-result-wrapper .review-result i,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-result-wrapper i {
		color: #fff;
		opacity: 0.7;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper .review-result-wrapper .review-result i { opacity: 1; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons {
		clear: both;
		padding: 0;
		border-bottom: 1px solid
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons > div > div {
		padding: 10px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons {
		padding: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros .mb-5 {
		background: <?php echo $colors['fontcolor']; ?>;
		padding: 10px 20px 10px 30px;
		color: #fff;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons .mb-5 {
		background: <?php echo $colors['bordercolor']; ?>;
		padding: 10px 20px 10px 30px;
		color: rgba(0, 0, 0, 0.45);
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros ul {
		padding: 10px 0 30px;
		margin: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-pros .mb-5 + p {
		padding: 10px 30px 30px 30px;
		margin: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons ul {
		padding: 10px 0 30px;
		margin: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons .review-cons .mb-5 + p {
		padding: 10px 30px 30px 30px;
		margin: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .mb-5 {
		text-transform: uppercase;
		letter-spacing: 1px;
	}
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area {
		padding: 12px 30px;
		border-bottom: 1px solid;
	}
	.wp-review-<?php echo $review['post_id']; ?> .wp-review-user-rating .review-result-wrapper .review-result {
        letter-spacing: -1.85px;
    }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title {
		border: none;
		font-weight: 700;
		padding: 12px 30px;
		background: <?php echo $colors['bgcolor1']; ?>;
		letter-spacing: 1px;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper {
		margin: 30px 30px 30px;
		color: #fff;
		text-align: center;
		background: <?php echo $colors['color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper span.review-total-box h5 {
		margin-bottom: 12px;
		color: inherit;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box {
		display: block;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper > .review-total-box > div { display: none; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper {
		background: transparent;
		color: inherit;
		padding: 30px;
		margin: 0;
	    min-height: 186px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .review-total-wrapper .review-circle.review-total {
		margin: 0 auto;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-total-wrapper .user-review-title {
		border: 0;
		margin-top: 0;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-review-area {
		padding: 12px 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-percentage .review-result,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result-wrapper,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-point .review-result {
		box-shadow: inset 0px 0px 3px rgba(0,0,0,0.1);
		height: 32px;
		margin-bottom: 0;
		background: <?php echo $colors['inactive_color']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper span.review-total-box {
		padding: 0 30px;
		line-height: 1.5;
		text-align: center;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper span.review-total-box small {
		margin-top: 10px;
		text-align: center;
		font-weight: normal;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-point-type .review-total-wrapper span.review-total-box,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-percentage-type .review-total-wrapper span.review-total-box {
		width: 100%;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-star.review-total {
		color: #fff;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-title {
		padding: 10px 30px;
		color: inherit;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-total-wrapper .user-review-title {
		display: inline-block;
		color: <?php echo $colors['fontcolor']; ?>;
		padding: 0;
		margin-top: 5px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item {
		padding: 30px;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list {
		overflow: hidden;
		padding: 20px 15px 20px 15px;
		border-bottom: 1px solid <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-thumbs-type .review-list li { width: 100% }
	.wp-review-<?php echo $review['post_id']; ?> .review-embed-code { padding: 10px 30px 30px; }
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper,
	.wp-review-<?php echo $review['post_id']; ?> .review-title,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li,
	.wp-review-<?php echo $review['post_id']; ?> .review-list li:last-child,
	.wp-review-<?php echo $review['post_id']; ?> .user-review-area,
	.wp-review-<?php echo $review['post_id']; ?> .reviewed-item,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc,
	.wp-review-<?php echo $review['post_id']; ?> .review-links,
	.wp-review-<?php echo $review['post_id']; ?> .wpr-user-features-rating,
	.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons {
		border-color: <?php echo $colors['bordercolor']; ?>;
	}
	.wp-review-<?php echo $review['post_id']; ?> .wpr-rating-accept-btn {
		background: <?php echo $colors['color']; ?>;
		background: linear-gradient(to top, <?php echo $dark_color; ?> 0%, <?php echo $colors['color']; ?> 100%);
		border-radius: 50px;
		border: 1px solid <?php echo $dark_color; ?>;
		margin: 10px 30px;
		width: -moz-calc(100% - 60px);
		width: -webkit-calc(100% - 60px);
		width: -o-calc(100% - 60px);
		width: calc(100% - 60px);
	}
	@media screen and (max-width:480px) {
		.wp-review-<?php echo $review['post_id']; ?>.wp-review-star-type .review-list li:before { top: 30px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-title,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .reviewed-item,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list li,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-desc,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-area,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-embed-code,
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .user-review-title { padding: 15px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-pros-cons > div > div { padding: 15px; padding-top: 0; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list { padding: 0; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-list li { width: 100%; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .ui-tabs-nav { padding: 0 15px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-links { padding: 15px 15px 5px; }
		.wp-review-<?php echo $review['post_id']; ?>.wp-review-point-type .review-list li > span,
		.wp-review-<?php echo $review['post_id']; ?>.wp-review-percentage-type .review-list li > span { top: 15px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper.wp-review-circle-type .user-review-area { padding: 10px 15px; }
		.wp-review-<?php echo $review['post_id']; ?>.review-wrapper .review-total-wrapper { width: 100%; margin: 0 0 15px; }
	}
</style>
<?php
$color_output = ob_get_clean();

// Apply legacy filter.
$color_output = apply_filters( 'wp_review_color_output', $color_output, $review['post_id'], $colors );

/**
 * Filters style output of fizzy template.
 *
 * @since 3.0.0
 *
 * @param string $style   Style output (include <style> tag).
 * @param int    $post_id Current post ID.
 * @param array  $colors  Color data.
 */
$color_output = apply_filters( 'wp_review_box_template_fizzy_style', $color_output, $review['post_id'], $colors );

echo $color_output;

// Schema json-dl.
echo wp_review_get_schema( $review );
// phpcs:enable
