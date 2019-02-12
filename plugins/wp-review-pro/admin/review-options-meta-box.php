<?php
/**
 * Review options meta box
 *
 * @package WP_Review
 */

/**
 * Render the meta box.
 *
 * @since 1.0
 *
 * @param WP_Post $post Post object.
 */
function wp_review_render_meta_box_review_options( $post ) {
	global $post, $wp_review_rating_types;

	/* Add an nonce field so we can check for it later. */
	wp_nonce_field( 'wp-review-meta-box-options', 'wp-review-review-options-nonce' );

	/* Retrieve an existing value from the database. */
	$type_post_value = get_post_meta( $post->ID, 'wp_review_type', true );
	if ( '' === $type_post_value ) {
		// Default value when create post.
		$type_post_value = wp_review_option( 'review_type', 'none' );
	}
	$options = get_option( 'wp_review_options', array() );

	$global_user_rating = isset( $options['global_user_rating'] ) ? $options['global_user_rating'] : false;
	if ( $global_user_rating && ( ! $type_post_value || 'none' === $type_post_value ) ) {
		$type = 'star';
	} else {
		$type = $type_post_value;
	}

	$schema           = wp_review_get_review_schema( $post->ID );
	$schema_data      = get_post_meta( $post->ID, 'wp_review_schema_options', true );
	$show_schema_data = get_post_meta( $post->ID, 'wp_review_show_schema_data', true );

	$heading         = get_post_meta( $post->ID, 'wp_review_heading', true );
	$rating_schema   = wp_review_get_rating_schema( $post->ID );
	$available_types = wp_review_get_rating_types();
	$schemas         = wp_review_schema_types();
	$enable_embed    = get_post_meta( $post->ID, 'wp_review_enable_embed', true );
	if ( '' === $enable_embed ) {
		$enable_embed = wp_review_option( 'enable_embed' );
	}
	$enable_embed = intval( $enable_embed );

	$custom_author = get_post_meta( $post->ID, 'wp_review_custom_author', true );
	$author        = get_post_meta( $post->ID, 'wp_review_author', true );

	/*
	 * Popup.
	 */
	$popup_config = wp_review_get_post_popup( $post->ID );

	/*
	 * Notification bar.
	 */
	$hello_bar_config = wp_review_get_post_hello_bar( $post->ID );
	$bg_image         = wp_parse_args(
		$hello_bar_config['bg_image'],
		array(
			'id'  => '',
			'url' => '',
		)
	);

	$form_field = new WP_Review_Form_Field();
	?>

	<div class="js-tabs wpr-tabs">
		<div class="nav-tab-wrapper tab-titles">
			<a href="#review-box" class="nav-tab tab-title nav-tab-active"><?php esc_html_e( 'Review Box', 'wp-review' ); ?></a>
			<?php if ( ! wp_review_network_option( 'hide_popup_box_' ) && current_user_can( 'wp_review_popup' ) ) { ?>
				<a href="#popup" class="nav-tab tab-title"><?php esc_html_e( 'Popup', 'wp-review' ); ?></a>
			<?php } ?>
			<?php if ( ! wp_review_network_option( 'hide_notification_bar_' ) && current_user_can( 'wp_review_notification_bar' ) ) { ?>
			<a href="#hello-bar" class="nav-tab tab-title"><?php esc_html_e( 'Notification Bar', 'wp-review' ); ?></a>
			<?php } ?>
		</div>

		<div id="review-box" class="tab-content">
			<div class="wp-review-field">
				<div class="wp-review-field-label">
					<label for="wp_review_type"><?php esc_html_e( 'Review Type', 'wp-review' ); ?></label>
				</div>

				<div class="wp-review-field-option">
					<select id="wp_review_type">
						<option value="none" <?php selected( $type, 'none' ); ?>><?php esc_html_e( 'No review', 'wp-review' ); ?></option>
						<?php foreach ( $available_types as $available_type_name => $available_type ) : ?>
							<option value="<?php echo esc_attr( $available_type_name ); ?>" data-max="<?php echo intval( $available_type['max'] ); ?>" data-decimals="<?php echo esc_attr( $available_type['decimals'] ); ?>" <?php selected( $type, $available_type_name ); ?>><?php echo esc_html( $available_type['label'] ); ?></option>
						<?php endforeach; ?>
					</select>
					<input type="hidden" name="wp_review_type" value="<?php echo esc_attr( $type ); ?>">

					<?php // translators: review ID. ?>
					<span id="wp_review_id_hint"><?php printf( esc_html__( 'Review ID: %s', 'wp-review' ), '<strong>' . intval( $post->ID ) . '</strong>' ); ?></span>
				</div>
			</div>

			<div class="wp-review-field" id="wp_review_heading_group">
				<div class="wp-review-field-label">
					<label for="wp_review_heading"><?php esc_html_e( 'Review Heading', 'wp-review' ); ?></label>
				</div>

				<div class="wp-review-field-option">
					<input type="text" name="wp_review_heading" id="wp_review_heading" class="large-text" value="<?php echo esc_attr( $heading ); ?>" />
				</div>
			</div>

			<div id="wp_review_schema_options_wrapper">

				<div class="wp-review-field" id="wp_review_schema_group">
					<div class="wp-review-field-label">
						<label for="wp_review_schema"><?php esc_html_e( 'Reviewed Item Schema', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<select name="wp_review_schema" id="wp_review_schema">
							<?php foreach ( $schemas as $key => $arr ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $schema ); ?>><?php echo esc_html( $arr['label'] ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div id="wp_review_schema_type_options_wrap"<?php if ( '' === $schema || 'none' === $schema ) echo ' style="display:none;"'; // phpcs:ignore ?>>

					<?php foreach ( $schemas as $type => $arr ) : ?>
						<div class="wp_review_schema_type_options" id="wp_review_schema_type_<?php echo esc_attr( $type ); ?>" <?php if ( $type !== $schema ) echo 'style="display:none;"'; // phpcs:ignore ?>>
							<?php if ( isset( $arr['fields'] ) ) : ?>
								<?php foreach ( $arr['fields'] as $data ) : ?>
									<div class="wp-review-field">
										<?php $values = isset( $schema_data[ $type ] ) ? $schema_data[ $type ] : array(); ?>
										<?php wp_review_schema_field( $data, $values, $type ); ?>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>

					<div class="wp-review-field" id="wp_review_schema_rating_group">
						<div class="wp-review-field-label">
							<label for="wp_review_rating_schema"><?php esc_html_e( 'Rating Schema', 'wp-review' ); ?></label>
						</div>

						<div class="wp-review-field-option">
							<select name="wp_review_rating_schema" id="wp_review_rating_schema">
								<option value="author" <?php selected( 'author', $rating_schema ); ?>><?php esc_html_e( 'Author Review Rating', 'wp-review' ); ?></option>
								<option value="visitors" <?php selected( 'visitors', $rating_schema ); ?>><?php esc_html_e( 'Visitors Aggregate Rating (if enabled)', 'wp-review' ); ?></option>
								<option value="comments" <?php selected( 'comments', $rating_schema ); ?>><?php esc_html_e( 'Comments Reviews Aggregate Rating (if enabled)', 'wp-review' ); ?></option>
							</select>
						</div>
					</div>

					<div id="wp_review_schema_author_wrapper"<?php if ( 'author' !== $rating_schema ) echo ' style="display: none;"'; // phpcs:ignore ?>>
						<div class="wp-review-field">
							<div class="wp-review-field-label">
								<label><?php esc_html_e( 'Custom Author', 'wp-review' ); ?></label>
							</div>

							<div class="wp-review-field-option">
								<?php
								$form_field->render_switch(
									array(
										'id'    => 'wp_review_custom_author',
										'name'  => 'wp_review_custom_author',
										'value' => $custom_author,
									)
								);
								?>
							</div>
						</div>

						<div class="wp-review-author-options"<?php if ( empty( $custom_author ) ) echo ' style="display: none;"'; // phpcs:ignore ?>>
							<div class="wp-review-field">
								<div class="wp-review-field-label">
									<label for="wp_review_author"><?php esc_html_e( 'Review Author', 'wp-review' ); ?></label>
								</div>

								<div class="wp-review-field-option">
									<input type="text" name="wp_review_author" id="wp_review_author" value="<?php echo esc_attr( $author ); ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- End #wp_review_schema_options_wrapper -->

			<div class="wp-review-field" id="wp_review_show_schema_data_wrapper">
				<div class="wp-review-field-label">
					<label><?php esc_html_e( 'Display Schema Data in the Box (if available)', 'wp-review' ); ?></label>
				</div>

				<div class="wp-review-field-option">
					<?php
					$form_field->render_switch(
						array(
							'id'    => 'wp_review_show_schema_data',
							'name'  => 'wp_review_show_schema_data',
							'value' => $show_schema_data,
						)
					);
					?>
				</div>
			</div>

			<div id="wp_review_embed_options_wrapper">
				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label><?php esc_html_e( 'Show Embed Code', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<?php
						$form_field->render_switch(
							array(
								'id'    => 'wp_review_enable_embed',
								'name'  => 'wp_review_enable_embed',
								'value' => $enable_embed,
							)
						);
						?>
					</div>
				</div>

				<?php $hidden = $enable_embed ? '' : 'hidden'; ?>
				<div id="wp_review_embed_code_wrapper" class="<?php echo esc_attr( $hidden ); ?>">
					<div class="wp-review-field">
						<div class="wp-review-field-label">
							<label for="wp_review_embed_code" style="vertical-align: top;"><?php esc_html_e( 'Embed code', 'wp-review' ); ?></label>
						</div>

						<div class="wp-review-field-option">
							<textarea id="wp_review_embed_code" rows="2" cols="40" readonly onclick="this.select()"><?php echo esc_textarea( wp_review_get_embed_code( $post->ID ) ); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div><!-- End #review-box -->

		<?php if ( ! wp_review_network_option( 'hide_popup_box_' ) && current_user_can( 'wp_review_popup' ) ) { ?>
		<div id="popup" class="tab-content wp-review-popup" style="display: none;">
			<div class="wp-review-field">
				<div class="wp-review-field-label">
					<label for="wp_review_popup_enable"><?php esc_html_e( 'Enable', 'wp-review' ); ?></label>
				</div>

				<div class="wp-review-field-option">
					<select name="wp_review_popup[enable]" id="wp_review_popup_enable">
						<option value="default" <?php selected( $popup_config['enable'], 'default' ); ?>><?php esc_html_e( 'Use global options', 'wp-review' ); ?></option>
						<option value="custom" <?php selected( $popup_config['enable'], 'custom' ); ?>><?php esc_html_e( 'Use custom options', 'wp-review' ); ?></option>
						<option value="none" <?php selected( $popup_config['enable'], 'none' ); ?>><?php esc_html_e( 'None', 'wp-review' ); ?></option>
					</select>
				</div>
			</div>

			<?php $hide = 'custom' == $popup_config['enable'] ? '' : 'hidden'; ?>
			<div id="wp-review-popup-options" class="<?php echo esc_attr( $hide ); ?>">
				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_popup_width"><?php esc_html_e( 'Popup width', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input name="wp_review_popup[width]" id="wp_review_popup_width" type="text" value="<?php echo esc_attr( $popup_config['width'] ); ?>">
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_popup_animation_in"><?php esc_html_e( 'Popup animation in', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<?php
						wp_review_animations_dropdown(
							'wp_review_popup_animation_in',
							'wp_review_popup[animation_in]',
							$popup_config['animation_in']
						);
						?>
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_popup_animation_out"><?php esc_html_e( 'Popup animation out', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<?php
						wp_review_animations_dropdown(
							'wp_review_popup_animation_out',
							'wp_review_popup[animation_out]',
							$popup_config['animation_out'],
							true
						);
						?>
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_popup_overlay_color"><?php esc_html_e( 'Popup overlay color', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input type="text" class="wp-review-color" name="wp_review_popup[overlay_color]" id="wp_review_popup_overlay_color" value="<?php echo esc_attr( $popup_config['overlay_color'] ); ?>" data-default-color="<?php echo esc_attr( $popup_config['overlay_color'] ); ?>">
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_popup_overlay_opacity"><?php esc_html_e( 'Popup overlay opacity (0.1 - 1)', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input type="text" class="wp-review-opacity small-text" name="wp_review_popup[overlay_opacity]" id="wp_review_popup_overlay_opacity" value="<?php echo esc_attr( $popup_config['overlay_opacity'] ); ?>">
					</div>
				</div>

				<?php $post_types = get_post_types( array( 'public' => true ) ); ?>
				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_popup_post_type"><?php esc_html_e( 'Post type', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<select name="wp_review_popup[post_type]" id="wp_review_popup_post_type">
							<option value=""><?php esc_html_e( 'Any', 'wp-review' ); ?></option>
							<?php foreach ( $post_types as $key => $value ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $popup_config['post_type'], $key ); ?>><?php echo esc_html( $value ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_popup_queryby"><?php esc_html_e( 'Popup content', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<select class="wp-review-opacity" name="wp_review_popup[queryby]" id="wp_review_popup_queryby" value="<?php echo esc_attr( $popup_config['queryby'] ); ?>">
							<option value="same_category" <?php selected( $popup_config['queryby'], 'same_category' ); ?>><?php esc_html_e( 'From same category', 'wp-review' ); ?></option>
							<option value="same_tag" <?php selected( $popup_config['queryby'], 'same_tag' ); ?>><?php esc_html_e( 'From same tag', 'wp-review' ); ?></option>
							<option value="same_review_type" <?php selected( $popup_config['queryby'], 'same_review_type' ); ?>><?php esc_html_e( 'From same review type', 'wp-review' ); ?></option>
							<option value="latest" <?php selected( $popup_config['queryby'], 'latest' ); ?>><?php esc_html_e( 'Latest reviews', 'wp-review' ); ?></option>
						</select>
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_popup_limit"><?php esc_html_e( 'Number of Reviews', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input type="number" min="-1" step="1" class="small-text" name="wp_review_popup[limit]" id="wp_review_popup_limit" value="<?php echo intval( $popup_config['limit'] ); ?>">
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_popup_orderby"><?php esc_html_e( 'Popup content order', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<select class="wp-review-opacity" name="wp_review_popup[orderby]" id="wp_review_popup_orderby" value="<?php echo esc_attr( $popup_config['orderby'] ); ?>">
							<option value="random" <?php selected( $popup_config['orderby'], 'random' ); ?>><?php esc_html_e( 'Random', 'wp-review' ); ?></option>
							<option value="popular" <?php selected( $popup_config['orderby'], 'popular' ); ?>><?php esc_html_e( 'Most popular', 'wp-review' ); ?></option>
							<option value="rated" <?php selected( $popup_config['orderby'], 'rated' ); ?>><?php esc_html_e( 'Most rated', 'wp-review' ); ?></option>
							<option value="latest" <?php selected( $popup_config['orderby'], 'latest' ); ?>><?php esc_html_e( 'Latest', 'wp-review' ); ?></option>
						</select>
					</div>
				</div>
			</div>
		</div><!-- End #popup -->
		<?php } ?>
		<?php if ( ! wp_review_network_option( 'hide_notification_bar_' ) && current_user_can( 'wp_review_notification_bar' ) ) { ?>
		<div id="hello-bar" class="tab-content wp-review-hello-bar" style="display: none;">
			<div class="wp-review-field">
				<div class="wp-review-field-label">
					<label for="wp_review_hello_bar_enable"><?php esc_html_e( 'Enable', 'wp-review' ); ?></label>
				</div>

				<div class="wp-review-field-option">
					<select name="wp_review_hello_bar[enable]" id="wp_review_hello_bar_enable">
						<option value="default" <?php selected( $hello_bar_config['enable'], 'default' ); ?>><?php esc_html_e( 'Use global options', 'wp-review' ); ?></option>
						<option value="custom" <?php selected( $hello_bar_config['enable'], 'custom' ); ?>><?php esc_html_e( 'Use custom options', 'wp-review' ); ?></option>
						<option value="none" <?php selected( $hello_bar_config['enable'], 'none' ); ?>><?php esc_html_e( 'None', 'wp-review' ); ?></option>
					</select>
				</div>
			</div>

			<?php $hide = 'custom' == $hello_bar_config['enable'] ? '' : 'hidden'; ?>
			<div id="wp-review-hello-bar-options" class="<?php echo esc_attr( $hide ); ?>">
				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_hello_bar_text"><?php esc_html_e( 'Text', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input name="wp_review_hello_bar[text]" id="wp_review_hello_bar_text" class="large-text" type="text" value="<?php echo esc_attr( $hello_bar_config['text'] ); ?>">
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_hello_bar_star_rating"><?php esc_html_e( 'Star Rating', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input name="wp_review_hello_bar[star_rating]" id="wp_review_hello_bar_star_rating" type="number" min="0.5" max="5" step="0.5" class="small-text" value="<?php echo floatval( $hello_bar_config['star_rating'] ); ?>">
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_hello_bar_price"><?php esc_html_e( 'Price', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input name="wp_review_hello_bar[price]" id="wp_review_hello_bar_price" type="text" value="<?php echo esc_attr( $hello_bar_config['price'] ); ?>">
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_hello_bar_button_label"><?php esc_html_e( 'Button label', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input name="wp_review_hello_bar[button_label]" id="wp_review_hello_bar_button_label" type="text" value="<?php echo esc_attr( $hello_bar_config['button_label'] ); ?>">
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_hello_bar_button_url"><?php esc_html_e( 'Button URL', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input name="wp_review_hello_bar[button_url]" id="wp_review_hello_bar_button_url" type="text" value="<?php echo esc_attr( $hello_bar_config['button_url'] ); ?>">
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label><?php esc_html_e( 'Open link in new tab', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<?php
						$form_field->render_switch(
							array(
								'id'    => 'wp_review_hello_bar_target_blank',
								'name'  => 'wp_review_hello_bar[target_blank]',
								'value' => $hello_bar_config['target_blank'],
							)
						);
						?>
					</div>
				</div>

				<!-- Styling -->
				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_hello_bar_location"><?php esc_html_e( 'Location', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<select name="wp_review_hello_bar[location]" id="wp_review_hello_bar_location">
							<option value="top" <?php selected( $hello_bar_config['location'], 'top' ); ?>><?php esc_html_e( 'Top', 'wp-review' ); ?></option>
							<option value="bottom" <?php selected( $hello_bar_config['location'], 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'wp-review' ); ?></option>
						</select>
					</div>
				</div>

				<?php $hide = 'top' == $hello_bar_config['location'] ? '' : 'hidden'; ?>
				<div class="wp-review-field <?php echo esc_attr( $hide ); ?>" id="wp-review-field-hello-bar-floating">
					<div class="wp-review-field-label">
						<label><?php esc_html_e( 'Floating', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<?php
						$form_field->render_switch(
							array(
								'id'    => 'wp_review_hello_bar_floating',
								'name'  => 'wp_review_hello_bar[floating]',
								'value' => $hello_bar_config['floating'],
							)
						);
						?>
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_hello_bar_bg_color"><?php esc_html_e( 'Background color', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input type="text" class="wp-review-color" name="wp_review_hello_bar[bg_color]" id="wp_review_hello_bar_bg_color" value="<?php echo esc_attr( $hello_bar_config['bg_color'] ); ?>" data-default-color="<?php echo esc_attr( $hello_bar_config['bg_color'] ); ?>">
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_hello_bar_bg_image"><?php esc_html_e( 'Background image', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<span class="wpr_image_upload_field">
							<span class="clearfix" id="wp_review_bg_image-preview">
								<?php
								if ( ! empty( $bg_image['url'] ) ) {
									echo '<img class="wpr_image_upload_img" src="' . esc_url( $bg_image['url'] ) . '">';
								}
								?>
							</span>
							<input type="hidden" id="wp_review_bg_image-id" name="wp_review_hello_bar[bg_image][id]" value="<?php echo intval( $bg_image['id'] ); ?>">
							<input type="hidden" id="wp_review_bg_image-url" name="wp_review_hello_bar[bg_image][url]" value="<?php echo esc_url( $bg_image['url'] ); ?>">
							<button type="button" class="button" name="wp_review_bg_image-upload" id="wp_review_bg_image-upload" data-id="wp_review_bg_image" onclick="wprImageField.uploader( 'wp_review_bg_image' ); return false;"><?php esc_html_e( 'Select Image', 'wp-review' ); ?></button>
							<?php
							if ( ! empty( $bg_image['url'] ) ) {
								echo '<a href="#" class="button button-link clear-image">' . esc_html__( 'Remove Image', 'wp-review' ) . '</a>';
							}
							?>
							<span class="clear"></span>
						</span>
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_hello_bar_text_color"><?php esc_html_e( 'Text color', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input type="text" class="wp-review-color" name="wp_review_hello_bar[text_color]" id="wp_review_hello_bar_text_color" value="<?php echo esc_attr( $hello_bar_config['text_color'] ); ?>" data-default-color="<?php echo esc_attr( $hello_bar_config['text_color'] ); ?>">
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_hello_bar_star_color"><?php esc_html_e( 'Star color', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input type="text" class="wp-review-color" name="wp_review_hello_bar[star_color]" id="wp_review_hello_bar_star_color" value="<?php echo esc_attr( $hello_bar_config['star_color'] ); ?>" data-default-color="<?php echo esc_attr( $hello_bar_config['star_color'] ); ?>">
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_hello_bar_button_bg_color"><?php esc_html_e( 'Button background color', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input type="text" class="wp-review-color" name="wp_review_hello_bar[button_bg_color]" id="wp_review_hello_bar_button_bg_color" value="<?php echo esc_attr( $hello_bar_config['button_bg_color'] ); ?>" data-default-color="<?php echo esc_attr( $hello_bar_config['button_bg_color'] ); ?>">
					</div>
				</div>

				<div class="wp-review-field">
					<div class="wp-review-field-label">
						<label for="wp_review_hello_bar_button_text_color"><?php esc_html_e( 'Button text color', 'wp-review' ); ?></label>
					</div>

					<div class="wp-review-field-option">
						<input type="text" class="wp-review-color" name="wp_review_hello_bar[button_text_color]" id="wp_review_hello_bar_button_text_color" value="<?php echo esc_attr( $hello_bar_config['button_text_color'] ); ?>" data-default-color="<?php echo esc_attr( $hello_bar_config['button_text_color'] ); ?>">
					</div>
				</div>
			</div>
		</div><!-- End #hello-bar -->
		<?php } ?>
	</div>
	<?php
}
