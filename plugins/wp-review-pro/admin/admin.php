<?php
/**
 * Admin functions for this plugin.
 *
 * @since     1.0
 * @copyright Copyright (c) 2013, MyThemesShop
 * @author    MyThemesShop
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package   WP_Review
 */

/**
 * Register custom style for the meta box.
 *
 * @since 1.0
 * @since 3.0.0 Add select2
 *
 * @param string $hook_suffix Admin page hook suffix.
 */
function wp_review_admin_style( $hook_suffix ) {
	if ( ! in_array( $hook_suffix, array( 'post-new.php', 'edit-comments.php', 'post.php', 'edit.php', 'widgets.php', 'settings_page_wp-review-pro' ) ) ) {
		return;
	}

	if ( 'customize' === get_current_screen()->id ) {
		return;
	}

	if ( in_array( $hook_suffix, array( 'edit.php', 'edit-comments.php', 'settings_page_wp-review-pro' ) ) ) {
		wp_enqueue_style( 'fontawesome', WP_REVIEW_ASSETS . 'css/font-awesome.min.css', array(), '4.7.0' );
	}

	wp_register_script( 'jquery-knob', trailingslashit( WP_REVIEW_ASSETS ) . 'js/jquery.knob.min.js', array( 'jquery' ), '1.1', true );
	wp_register_script( 'wp-review-circle-output', WP_REVIEW_URI . 'rating-types/circle-output.js', array( 'jquery-knob' ), '3.0.8', true );

	if ( in_array( $hook_suffix, array( 'settings_page_wp-review-pro', 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_script( 'select2', WP_REVIEW_URI . 'admin/assets/js/select2.min.js', array( 'jquery' ), '4.0.6-rc.0', true );
		wp_enqueue_style( 'select2', WP_REVIEW_URI . 'admin/assets/css/select2.min.css', array(), '4.0.6-rc.0' );

		wp_enqueue_style(
			'jquery-ui',
			WP_REVIEW_URI . 'admin/assets/css/jquery-ui.min.css',
			array(),
			'1.12.1'
		);

		wp_register_script( 'js-cookie', WP_REVIEW_ASSETS . 'js/js.cookie.min.js', array(), '2.1.4', true );
	}

	wp_enqueue_style( 'wp-review-admin-style', WP_REVIEW_URI . 'admin/assets/css/admin.css', array( 'wp-color-picker' ) );

	wp_enqueue_style( 'magnificPopup', WP_REVIEW_ASSETS . 'css/magnific-popup.css', array(), '1.1.0' );
	wp_enqueue_script( 'magnificPopup', WP_REVIEW_ASSETS . 'js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );

	if ( in_array( $hook_suffix, array( 'settings_page_wp-review-pro' ) ) ) {
		wp_enqueue_media();

		wp_enqueue_script(
			'wp-review-admin-import',
			WP_REVIEW_URI . 'admin/assets/js/admin.import.js',
			array( 'jquery', 'wp-util' ),
			'3.0.0',
			true
		);

		wp_localize_script(
			'wp-review-admin-import',
			'wprImportVars',
			array(
				// translators: import plugin.
				'confirm'              => __( 'Are you sure you want to import from %s?', 'wp-review' ),
				'server_error'         => __( 'The server responded with an error. Try again.', 'wp-review' ),
				'confirmOptionsImport' => __( 'Are you sure you want to import options? All current options will be lost.', 'wp-review' ),
				'importOptionsNonce'   => wp_create_nonce( 'wp-review-import-options' ),
			)
		);
	}

	$api_key = wp_review_option( 'google_api_key' );
	if ( ! empty( $api_key ) && ( ! wp_review_option( 'dequeue_map_backend' ) || 'widgets.php' === $hook_suffix ) ) {
		wp_register_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=' . $api_key, array(), false, true );
		wp_enqueue_script( 'google-maps' );
	}

	if ( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_script( 'jquery-knob', WP_REVIEW_ASSETS . 'js/jquery.knob.min.js', array( 'jquery' ), '1.2.12', true );

		wp_enqueue_script(
			'wp-review-rating-inputs',
			WP_REVIEW_URI . 'admin/assets/js/rating-inputs.js',
			array( 'jquery-knob', 'jquery-ui-slider' ),
			'3.0.0',
			true
		);

		wp_enqueue_script(
			'wp-review-review-items',
			WP_REVIEW_URI . 'admin/assets/js/review-items.js',
			array( 'backbone', 'wp-review-admin-script', 'jquery-ui-sortable' ),
			'3.0.6',
			true
		);
	}

	wp_enqueue_script(
		'wp-review-admin-script',
		WP_REVIEW_URI . 'admin/assets/js/admin.js',
		array(
			'wp-color-picker',
			'jquery',
			'jquery-ui-core',
			'jquery-ui-slider',
			'jquery-ui-sortable',
			'jquery-ui-datepicker',
			'wp-util',
			'imagesloaded',
		),
		'3.0.0',
		true
	);

	wp_localize_script(
		'wp-review-admin-script',
		'wprVars',
		array(
			'ratingPermissionsCommentOnly' => WP_REVIEW_REVIEW_COMMENT_ONLY,
			'ratingPermissionsBoth'        => WP_REVIEW_REVIEW_ALLOW_BOTH,
			'imgframe_title'               => __( 'Select Image', 'wp-review' ),
			'imgbutton_title'              => __( 'Insert Image', 'wp-review' ),
			'imgremove_title'              => __( 'Remove Image', 'wp-review' ),
			'yelpSearch'                   => __( 'Yelp business search', 'wp-review' ),
			'yelpBusiness'                 => __( 'Yelp business total rating', 'wp-review' ),
			'yelpBusinessReviews'          => __( 'Yelp business user reviews', 'wp-review' ),
			'title'                        => __( 'Title', 'wp-review' ),
			'searchTerm'                   => __( 'Search term', 'wp-review' ),
			'searchLocation'               => __( 'Search location', 'wp-review' ),
			'limit'                        => __( 'Limit', 'wp-review' ),
			'searchRadius'                 => __( 'Radius', 'wp-review' ),
			'searchCategories'             => __( 'Categories', 'wp-review' ),
			'searchLocale'                 => __( 'Locale', 'wp-review' ),
			'searchOffset'                 => __( 'Offset', 'wp-review' ),
			'sort_by'                      => __( 'SortBy', 'wp-review' ),
			'searchPrice'                  => __( 'Price range', 'wp-review' ),
			'open_now'                     => __( 'Open now', 'wp-review' ),
			'attributes'                   => __( 'Attributes', 'wp-review' ),
			'businessId'                   => __( 'Business ID', 'wp-review' ),
			'locationLookup'               => __( 'Location lookup', 'wp-review' ),
			'placeId'                      => __( 'Place ID', 'wp-review' ),
			'googlePlaceReviews'           => __( 'Google place reviews', 'wp-review' ),
			'googlePlaceAverageRating'     => __( 'Google place average rating', 'wp-review' ),
			'placeType'                    => __( 'Place type', 'wp-review' ),
			'yelpReviewNum'                => __( 'Number of reviews (Max allowed 3)', 'wp-review' ),
			'googleReviewNum'              => __( 'Number of reviews (Max allowed 5)', 'wp-review' ),
			'all'                          => __( 'All', 'wp-review' ),
			'establishments'               => __( 'Establishments', 'wp-review' ),
			'addresses'                    => __( 'Addresses', 'wp-review' ),
			'geocodes'                     => __( 'Geocodes', 'wp-review' ),
			'facebookReviews'              => __( 'Facebook reviews', 'wp-review' ),
			'pageId'                       => __( 'Page ID', 'wp-review' ),
			'emptyFBPageId'                => __( 'Page ID must not be empty', 'wp-review' ),
			'fbIsNotLoaded'                => __( 'Facebook API is not loaded', 'wp-review' ),
			'generateToken'                => __( 'Generate token', 'wp-review' ),
			'comparisonTable'              => __( 'Comparison table', 'wp-review' ),
			'reviewIds'                    => __( 'Review IDs (separate by commas)', 'wp-review' ),
			'reviewPosts'                  => __( 'Review posts', 'wp-review' ),
			'queryType'                    => __( 'Query type', 'wp-review' ),
			'recentReviews'                => __( 'Recent reviews', 'wp-review' ),
			'topRated'                     => __( 'Top rated', 'wp-review' ),
			'mostVoted'                    => __( 'Most voted', 'wp-review' ),
			'categoryReviews'              => __( 'Category reviews', 'wp-review' ),
			'reviewTypesText'              => __( 'Review types', 'wp-review' ),
			'separateByCommas'             => __( 'separate by commas', 'wp-review' ),
			'categoryIds'                  => __( 'Category IDs', 'wp-review' ),
			'allowPagination'              => __( 'Allow pagination', 'wp-review' ),
			'numberOfReviews'              => __( 'Number of reviews', 'wp-review' ),
			'titleLength'                  => __( 'Title length (words)', 'wp-review' ),
			'thumbSize'                    => __( 'Thumb size', 'wp-review' ),
			'small'                        => __( 'Small', 'wp-review' ),
			'large'                        => __( 'Large', 'wp-review' ),
			'showDate'                     => __( 'Show date', 'wp-review' ),
			'reviewBox'                    => __( 'Review box', 'wp-review' ),
			'reviewTotal'                  => __( 'Review total', 'wp-review' ),
			'visitorRating'                => __( 'Visitor rating', 'wp-review' ),
			'commentsRating'               => __( 'Comments rating', 'wp-review' ),
			'reviewId'                     => __( 'Review ID', 'wp-review' ),
			'leaveReviewIdEmpty'           => __( 'Leave empty to use current review ID', 'wp-review' ),
			'insert'                       => __( 'Insert', 'wp-review' ),
			'cancel'                       => __( 'Cancel', 'wp-review' ),
			'reviewTypes'                  => wp_review_get_rating_types(),
			'globalReviewType'             => wp_review_option( 'review_type', 'none' ),
			'generateFBTokenNonce'         => wp_create_nonce( 'wp_review_generate_fb_page_token' ),
			'assetsUrl'                    => WP_REVIEW_ASSETS,
			'boxTemplates'                 => wp_review_get_box_templates(),
			'purgeRatingsNonce'            => wp_create_nonce( 'wpr_purge_ratings' ),
			'confirmPurgeRatings'          => esc_html__( 'Are you sure you want to do this?', 'wp-review' ),
			'importDemoConfirm'            => __( 'Are you sure you want to import demo?', 'wp-review' ),
			'importDemoDone'               => __( 'Importing proccess finished!', 'wp-review' ),
		)
	);

	if ( in_array( $hook_suffix, array( 'edit.php', 'edit-comments.php' ) ) ) {
		wp_enqueue_style( 'wp_review-style', trailingslashit( WP_REVIEW_ASSETS ) . 'css/wp-review.css', array(), WP_REVIEW_PLUGIN_VERSION, 'all' );
	}
}

add_action( 'admin_enqueue_scripts', 'wp_review_admin_style' );


/**
 * Gets list of icons.
 *
 * @since 3.0.0
 *
 * @return array
 */
function wp_review_get_icons() {
	$icons = include WP_REVIEW_ADMIN . 'font-awesome-icons.php';
	/**
	 * Filters list of font icons.
	 *
	 * @since 3.0.0
	 *
	 * @param array $icons List of icons. View file `wp-review-pro/admin/font-awesome-icons.php
	 */
	return apply_filters( 'wp_review_icons', $icons );
}


/**
 * Gets list of animations in.
 *
 * @since 3.0.0
 *
 * @return array
 */
function wp_review_get_animations_in() {
	return array(
		__( 'Attention Seekers', 'wp-review' )  => array(
			'bounce'     => __( 'bounce', 'wp-review' ),
			'flash'      => __( 'flash', 'wp-review' ),
			'pulse'      => __( 'pulse', 'wp-review' ),
			'rubberBand' => __( 'rubberBand', 'wp-review' ),
			'shake'      => __( 'shake', 'wp-review' ),
			'swing'      => __( 'swing', 'wp-review' ),
			'tada'       => __( 'tada', 'wp-review' ),
			'wobble'     => __( 'wobble', 'wp-review' ),
			'jello'      => __( 'jello', 'wp-review' ),
		),
		__( 'Bouncing Entrances', 'wp-review' ) => array(
			'bounceIn'      => __( 'bounceIn', 'wp-review' ),
			'bounceInDown'  => __( 'bounceInDown', 'wp-review' ),
			'bounceInLeft'  => __( 'bounceInLeft', 'wp-review' ),
			'bounceInRight' => __( 'bounceInRight', 'wp-review' ),
			'bounceInUp'    => __( 'bounceInUp', 'wp-review' ),
		),
		__( 'Fading Entrances', 'wp-review' )   => array(
			'fadeIn'         => __( 'fadeIn', 'wp-review' ),
			'fadeInDown'     => __( 'fadeInDown', 'wp-review' ),
			'fadeInDownBig'  => __( 'fadeInDownBig', 'wp-review' ),
			'fadeInLeft'     => __( 'fadeInLeft', 'wp-review' ),
			'fadeInLeftBig'  => __( 'fadeInLeftBig', 'wp-review' ),
			'fadeInRight'    => __( 'fadeInRight', 'wp-review' ),
			'fadeInRightBig' => __( 'fadeInRightBig', 'wp-review' ),
			'fadeInUp'       => __( 'fadeInUp', 'wp-review' ),
			'fadeInUpBig'    => __( 'fadeInUpBig', 'wp-review' ),
		),
		__( 'Flippers', 'wp-review' )           => array(
			'flip'     => __( 'flip', 'wp-review' ),
			'flipInX'  => __( 'flipInX', 'wp-review' ),
			'flipInY'  => __( 'flipInY', 'wp-review' ),
			'flipOutX' => __( 'flipOutX', 'wp-review' ),
			'flipOutY' => __( 'flipOutY', 'wp-review' ),
		),
		__( 'Lightspeed', 'wp-review' )         => array(
			'lightSpeedIn'  => __( 'lightSpeedIn', 'wp-review' ),
			'lightSpeedOut' => __( 'lightSpeedOut', 'wp-review' ),
		),
		__( 'Rotating Entrances', 'wp-review' ) => array(
			'rotateIn'          => __( 'rotateIn', 'wp-review' ),
			'rotateInDownLeft'  => __( 'rotateInDownLeft', 'wp-review' ),
			'rotateInDownRight' => __( 'rotateInDownRight', 'wp-review' ),
			'rotateInUpLeft'    => __( 'rotateInUpLeft', 'wp-review' ),
			'rotateInUpRight'   => __( 'rotateInUpRight', 'wp-review' ),
		),
		__( 'Sliding Entrances', 'wp-review' )  => array(
			'slideInUp'    => __( 'slideInUp', 'wp-review' ),
			'slideInDown'  => __( 'slideInDown', 'wp-review' ),
			'slideInLeft'  => __( 'slideInLeft', 'wp-review' ),
			'slideInRight' => __( 'slideInRight', 'wp-review' ),
		),
		__( 'Zoom Entrances', 'wp-review' )     => array(
			'zoomIn'      => __( 'zoomIn', 'wp-review' ),
			'zoomInDown'  => __( 'zoomInDown', 'wp-review' ),
			'zoomInLeft'  => __( 'zoomInLeft', 'wp-review' ),
			'zoomInRight' => __( 'zoomInRight', 'wp-review' ),
			'zoomInUp'    => __( 'zoomInUp', 'wp-review' ),
		),
		__( 'Specials', 'wp-review' )           => array(
			'hinge'        => __( 'hinge', 'wp-review' ),
			'jackInTheBox' => __( 'jackInTheBox', 'wp-review' ),
			'rollIn'       => __( 'rollIn', 'wp-review' ),
			'rollOut'      => __( 'rollOut', 'wp-review' ),
		),
	);
}


/**
 * Gets list of animations out.
 *
 * @since 3.0.0
 *
 * @return array
 */
function wp_review_get_animations_out() {
	return array(
		__( 'Attention Seekers', 'wp-review' ) => array(
			'bounce'     => __( 'bounce', 'wp-review' ),
			'flash'      => __( 'flash', 'wp-review' ),
			'pulse'      => __( 'pulse', 'wp-review' ),
			'rubberBand' => __( 'rubberBand', 'wp-review' ),
			'shake'      => __( 'shake', 'wp-review' ),
			'swing'      => __( 'swing', 'wp-review' ),
			'tada'       => __( 'tada', 'wp-review' ),
			'wobble'     => __( 'wobble', 'wp-review' ),
			'jello'      => __( 'jello', 'wp-review' ),
		),
		__( 'Bouncing Exits', 'wp-review' )    => array(
			'bounceOut'      => __( 'bounceOut', 'wp-review' ),
			'bounceOutDown'  => __( 'bounceOutDown', 'wp-review' ),
			'bounceOutLeft'  => __( 'bounceOutLeft', 'wp-review' ),
			'bounceOutRight' => __( 'bounceOutRight', 'wp-review' ),
			'bounceOutUp'    => __( 'bounceOutUp', 'wp-review' ),
		),
		__( 'Fading Exits', 'wp-review' )      => array(
			'fadeOut'         => __( 'fadeOut', 'wp-review' ),
			'fadeOutDown'     => __( 'fadeOutDown', 'wp-review' ),
			'fadeOutDownBig'  => __( 'fadeOutDownBig', 'wp-review' ),
			'fadeOutLeft'     => __( 'fadeOutLeft', 'wp-review' ),
			'fadeOutLeftBig'  => __( 'fadeOutLeftBig', 'wp-review' ),
			'fadeOutRight'    => __( 'fadeOutRight', 'wp-review' ),
			'fadeOutRightBig' => __( 'fadeOutRightBig', 'wp-review' ),
			'fadeOutUp'       => __( 'fadeOutUp', 'wp-review' ),
			'fadeOutUpBig'    => __( 'fadeOutUpBig', 'wp-review' ),
		),
		__( 'Flippers', 'wp-review' )          => array(
			'flip'     => __( 'flip', 'wp-review' ),
			'flipInX'  => __( 'flipInX', 'wp-review' ),
			'flipInY'  => __( 'flipInY', 'wp-review' ),
			'flipOutX' => __( 'flipOutX', 'wp-review' ),
			'flipOutY' => __( 'flipOutY', 'wp-review' ),
		),
		__( 'Lightspeed', 'wp-review' )        => array(
			'lightSpeedIn'  => __( 'lightSpeedIn', 'wp-review' ),
			'lightSpeedOut' => __( 'lightSpeedOut', 'wp-review' ),
		),
		__( 'Rotating Exits', 'wp-review' )    => array(
			'rotateOut'          => __( 'rotateOut', 'wp-review' ),
			'rotateOutDownLeft'  => __( 'rotateOutDownLeft', 'wp-review' ),
			'rotateOutDownRight' => __( 'rotateOutDownRight', 'wp-review' ),
			'rotateOutUpLeft'    => __( 'rotateOutUpLeft', 'wp-review' ),
			'rotateOutUpRight'   => __( 'rotateOutUpRight', 'wp-review' ),
		),
		__( 'Sliding Exits', 'wp-review' )     => array(
			'slideOutUp'    => __( 'slideOutUp', 'wp-review' ),
			'slideOutDown'  => __( 'slideOutDown', 'wp-review' ),
			'slideOutLeft'  => __( 'slideOutLeft', 'wp-review' ),
			'slideOutRight' => __( 'slideOutRight', 'wp-review' ),
		),
		__( 'Zoom Exits', 'wp-review' )        => array(
			'zoomOut'      => __( 'zoomOut', 'wp-review' ),
			'zoomOutDown'  => __( 'zoomOutDown', 'wp-review' ),
			'zoomOutLeft'  => __( 'zoomOutLeft', 'wp-review' ),
			'zoomOutRight' => __( 'zoomOutRight', 'wp-review' ),
			'zoomOutUp'    => __( 'zoomOutUp', 'wp-review' ),
		),
		__( 'Specials', 'wp-review' )          => array(
			'hinge'        => __( 'hinge', 'wp-review' ),
			'jackInTheBox' => __( 'jackInTheBox', 'wp-review' ),
			'rollIn'       => __( 'rollIn', 'wp-review' ),
			'rollOut'      => __( 'rollOut', 'wp-review' ),
		),
	);
}


/**
 * Animation dropdown.
 *
 * @since 3.0.0
 *
 * @param string $id    Element ID.
 * @param string $name  Element name.
 * @param string $value Selected value.
 * @param bool   $exit  Show only exit animations.
 */
function wp_review_animations_dropdown( $id = '', $name = '', $value = '', $exit = false ) {
	$animations = array( '' => esc_html__( 'No Animation', 'wp-review' ) );

	if ( ! $exit ) {
		$animations += wp_review_get_animations_in();
	} else {
		$animations += wp_review_get_animations_out();
	}

	printf( '<select id="%1$s" name="%2$s" class="js-select2">', esc_attr( $id ), esc_attr( $name ) );
		wp_review_print_select_options( $animations, $value );
	echo '</select>';
}


/**
 * Prints select options.
 *
 * @since 3.0.0
 *
 * @param array  $options Options.
 * @param string $value   Select value.
 */
function wp_review_print_select_options( $options, $value ) {
	foreach ( $options as $key => $text ) {
		if ( is_array( $text ) ) {
			printf( '<optgroup label="%s">', esc_attr( $key ) );
			wp_review_print_select_options( $text, $value );
			echo '</optgroup>';
		} else {
			printf(
				'<option value="%1$s" %3$s>%2$s</option>',
				esc_attr( $key ),
				esc_html( $text ),
				selected( $value, $key, false )
			);
		}
	}
}


/**
 * Add settings link on plugin page.
 *
 * @param  array $links Plugins setting links.
 * @return array
 */
function wpreview_plugin_settings_link( $links ) {
	$hide = wp_review_network_option( 'hide_global_options_' );
	if ( ! $hide ) {
		$dir           = explode( '/', WP_REVIEW_PLUGIN_BASE );
		$dir           = $dir[0];
		$settings_link = '<a href="options-general.php?page=wp-review-pro">' . __( 'Settings', 'wp-review' ) . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_filter( 'plugin_action_links_' . WP_REVIEW_PLUGIN_BASE, 'wpreview_plugin_settings_link' );


/**
 * Adds editor buttons.
 *
 * @param  array $buttons Editor buttons.
 * @return array
 */
function wp_review_editor_buttons( $buttons ) {
	array_push( $buttons, 'wpreviewpro' );
	return $buttons;
}
add_filter( 'mce_buttons', 'wp_review_editor_buttons' );


/**
 * Adds editor plugin.
 *
 * @param  array $plugin_array Editor plugins.
 * @return array
 */
function wp_review_editor_js( $plugin_array ) {
	if ( is_admin() ) {
		$plugin_array['wp_review_pro'] = WP_REVIEW_URI . 'admin/assets/js/editor-plugin.js';
	}
	return $plugin_array;
}
add_filter( 'mce_external_plugins', 'wp_review_editor_js' );


/**
 * Embeds Facebook JS SDK.
 *
 * @since 3.0.0
 */
function wp_review_fb_js_sdk() {
	$app_id = wp_review_option( 'facebook_app_id' );
	if ( ! $app_id ) {
		return;
	}
	?>
	<script>
		window.fbAsyncInit = function() {
			FB.init({
				appId            : '<?php echo esc_js( $app_id ); ?>',
				autoLogAppEvents : true,
				xfbml            : true,
				version          : 'v<?php echo WP_REVIEW_GRAPH_API_VERSION; ?>'
			});
		};

		(function(d, s, id){
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "https://connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>
	<?php
}
add_action( 'admin_footer', 'wp_review_fb_js_sdk' );


/**
 * Processes option value before saving.
 *
 * @since 3.0.0
 *
 * @param  array $new_options New option value.
 * @return array
 */
function wp_review_process_option_value( $new_options ) {
	// Adds empty value to checkbox options which default is checked.
	$new_options['embed_show_title']      = isset( $new_options['embed_show_title'] ) ? $new_options['embed_show_title'] : '';
	$new_options['embed_show_thumbnail']  = isset( $new_options['embed_show_thumbnail'] ) ? $new_options['embed_show_thumbnail'] : '';
	$new_options['embed_show_excerpt']    = isset( $new_options['embed_show_excerpt'] ) ? $new_options['embed_show_excerpt'] : '';
	$new_options['embed_show_rating_box'] = isset( $new_options['embed_show_rating_box'] ) ? $new_options['embed_show_rating_box'] : '';
	$new_options['embed_show_credit']     = isset( $new_options['embed_show_credit'] ) ? $new_options['embed_show_credit'] : '';

	// Normalize option value.
	foreach ( $new_options as $key => $value ) {
		$new_options[ $key ] = wp_review_normalize_option_value( $value );
	}

	$new_options['exit_intent'] = isset( $new_options['exit_intent'] );

	return $new_options;
}
add_filter( 'pre_update_option_wp_review_options', 'wp_review_process_option_value' );
add_filter( 'pre_update_option_wp_review_popup', 'wp_review_process_option_value' );
add_filter( 'pre_update_option_wp_review_hello_bar', 'wp_review_process_option_value' );


/**
 * Normalizes option value
 * Convert string as 'true' and 'false' to boolean value.
 *
 * @since 3.0.0
 *
 * @param  mixed $value Option value.
 * @return mixed
 */
function wp_review_normalize_option_value( $value ) {
	if ( 'true' === $value ) {
		return true;
	}
	if ( 'false' === $value ) {
		return false;
	}
	return $value;
}


/**
 * Gets plugin options export code.
 *
 * @since 3.0.0
 *
 * @return string
 */
function wp_review_get_options_export_code() {
	$options = get_option( 'wp_review_options', array() );
	return wp_json_encode( $options );
}


/**
 * Imports plugin options.
 *
 * @since 3.0.0
 *
 * @param string $code Export code.
 * @return bool
 */
function wp_review_import_options( $code ) {
	$options = json_decode( $code, true );
	if ( ! $options ) {
		return false;
	}
	return update_option( 'wp_review_options', $options );
}
