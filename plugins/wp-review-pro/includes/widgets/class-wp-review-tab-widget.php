<?php
/**
 * Tab widget
 *
 * @package WP_Review
 */

/**
 * Class WP_Review_Tab_Widget
 */
class WP_Review_Tab_Widget extends WP_Widget {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		// ajax functions.
		add_action( 'wp_ajax_wp_review_tab_widget_content', array( $this, 'ajax_wp_review_tab_widget_content' ) );
		add_action( 'wp_ajax_nopriv_wp_review_tab_widget_content', array( $this, 'ajax_wp_review_tab_widget_content' ) );

		// css.
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_review_tab_register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wp_review_tab_admin_scripts' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'wp_review_tab_admin_scripts' ) );

		$widget_ops  = array(
			'classname'   => 'widget_wp_review_tab',
			'description' => __( 'Display Reviews in tabbed format.', 'wp-review' ),
		);
		$control_ops = array(
			'width'  => 200,
			'height' => 350,
		);
		parent::__construct( 'wp_review_tab_widget', __( 'WP Review: Tab Widget', 'wp-review' ), $widget_ops, $control_ops );
	}

	/**
	 * Enqueues widget admin scripts.
	 *
	 * @param string $hook Page hook suffix.
	 */
	public function wp_review_tab_admin_scripts( $hook ) {
		if ( 'widgets.php' !== $hook ) {
			return;
		}
		wp_register_script( 'wp_review_tab_widget_admin', WP_REVIEW_URI . 'admin/assets/js/wp-review-tab-widget-admin.js', array( 'jquery' ), '3.0.0', true );
		wp_enqueue_script( 'wp_review_tab_widget_admin' );
	}

	/**
	 * Enqueues widget scripts.
	 */
	public function wp_review_tab_register_scripts() {
		// JS.
		wp_register_script( 'wp_review_tab_widget', WP_REVIEW_ASSETS . 'js/wp-review-tab-widget.js', array( 'jquery' ), '3.0.0', true );
		wp_localize_script(
			'wp_review_tab_widget',
			'wp_review_tab',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	/**
	 * Prints the widget form.
	 *
	 * @param array $instance Widget instance.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'tabs'                    => array(
					'toprated'       => 1,
					'recent'         => 1,
					'mostvoted'      => 0,
					'recent_ratings' => 0,
					'custom'         => 0,
				),
				'tab_order'               => array(
					'toprated'       => 1,
					'recent'         => 2,
					'mostvoted'      => 3,
					'recent_ratings' => 4,
					'custom'         => 5,
				),
				'tab_titles'              => array(
					'toprated'       => __( 'Top Rated', 'wp-review' ),
					'recent'         => __( 'Recent', 'wp-review' ),
					'mostvoted'      => __( 'Most Voted', 'wp-review' ),
					'recent_ratings' => __( 'Comments', 'wp-review' ),
					'custom'         => __( 'Editor\'s choice', 'wp-review' ),
				),
				'allow_pagination'        => 1,
				'review_type'             => array(),
				'post_num'                => '5',
				'comment_num'             => '5',
				'thumb_size'              => 'small',
				'show_date'               => 1,
				'top_rated_posts'         => 'visitors',
				'restrict_recent_reviews' => 0,
				'custom_reviews'          => '',
				'title_length'            => apply_filters( 'wpt_title_length_default', '15' ),
			)
		);

		// Fix notice when switch to new version.
		if ( ! isset( $instance['tabs']['recent_ratings'] ) ) {
			$instance['tabs']['recent_ratings'] = 0;
		}
		if ( ! isset( $instance['tab_order']['recent_ratings'] ) ) {
			$instance['tab_order']['recent_ratings'] = 4;
		}
		if ( ! isset( $instance['tab_titles']['recent_ratings'] ) ) {
			$instance['tab_titles']['recent_ratings'] = __( 'Comments', 'wp-review' );
		}
		extract( $instance ); // phpcs:ignore
		$review_type     = (array) $instance['review_type'];
		$tabs            = $instance['tabs'];
		$tab_titles      = $instance['tab_titles'];
		$tab_order       = $instance['tab_order'];
		$post_num        = $instance['post_num'];
		$top_rated_posts = $instance['top_rated_posts'];
		$number_of_days  = isset( $instance['number_of_days'] ) ? $instance['number_of_days'] : '';
		?>
		<div class="wp_review_tab_options_form">
			<h4><?php esc_html_e( 'Select Tabs', 'wp-review' ); ?></h4>

			<div class="wp_review_tab_select_tabs">
				<label class="alignleft" style="display: block; width: 50%; margin-bottom: 7px;" for="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>_toprated">
					<input
						type="checkbox"
						class="checkbox wp_review_tab_enable_toprated"
						id="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>_toprated"
						name="<?php echo esc_attr( $this->get_field_name( 'tabs' ) ); ?>[toprated]"
						value="1"
						<?php checked( 1, $tabs['toprated'] ); ?>
					/>
					<?php esc_html_e( 'Top Rated', 'wp-review' ); ?>
				</label>

				<label class="alignleft" style="display: block; width: 50%; margin-bottom: 7px;" for="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>_recent">
					<input
						type="checkbox"
						class="checkbox wp_review_tab_enable_recent"
						id="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>_recent"
						name="<?php echo esc_attr( $this->get_field_name( 'tabs' ) ); ?>[recent]"
						value="1"
						<?php checked( 1, $tabs['recent'] ); ?>
					/>
					<?php esc_html_e( 'Recent Reviews', 'wp-review' ); ?>
				</label>

				<label class="alignleft" style="display: block; width: 50%; margin-bottom: 7px;" for="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>_mostvoted">
					<input
						type="checkbox"
						class="checkbox wp_review_tab_enable_mostvoted"
						id="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>_mostvoted"
						name="<?php echo esc_attr( $this->get_field_name( 'tabs' ) ); ?>[mostvoted]"
						value="1"
						<?php checked( 1, $tabs['mostvoted'] ); ?>
					/>
					<?php esc_html_e( 'Most Voted', 'wp-review' ); ?>
				</label>

				<label class="alignleft" style="display: block; width: 50%; margin-bottom: 7px;" for="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>_recent_ratings">
					<input
						type="checkbox"
						class="checkbox wp_review_tab_enable_recent_ratings"
						id="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>_recent_ratings"
						name="<?php echo esc_attr( $this->get_field_name( 'tabs' ) ); ?>[recent_ratings]"
						value="1"
						<?php checked( 1, $tabs['recent_ratings'] ); ?>
					/>
					<?php esc_html_e( 'Recent Comments', 'wp-review' ); ?>
				</label>

				<label class="alignleft" style="display: block; width: 50%; margin-bottom: 7px;" for="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>_custom">
					<input
						type="checkbox"
						class="checkbox wp_review_tab_enable_custom"
						id="<?php echo esc_attr( $this->get_field_id( 'tabs' ) ); ?>_custom"
						name="<?php echo esc_attr( $this->get_field_name( 'tabs' ) ); ?>[custom]"
						value="1"
						<?php checked( 1, $tabs['custom'] ); ?>
					/>
					<?php esc_html_e( 'Custom', 'wp-review' ); ?>
				</label>
			</div>

			<div class="clear"></div>

			<div class="wp_review_tab_advanced_options">
				<?php $hide = ! isset( $tabs['recent_ratings'] ) ? 'wpr-hide' : ''; ?>
				<p class="wp_review_restrict_recent_review" <?php echo esc_attr( $hide ); ?>>
					<label for="<?php echo esc_attr( $this->get_field_id( 'restrict_recent_reviews' ) ); ?>">
						<input
							type="checkbox"
							class="checkbox"
							id="<?php echo esc_attr( $this->get_field_id( 'restrict_recent_reviews' ) ); ?>"
							name="<?php echo esc_attr( $this->get_field_name( 'restrict_recent_reviews' ) ); ?>"
							value="1"
							<?php checked( 1, $instance['restrict_recent_reviews'] ); ?>
						/>
						<?php esc_html_e( 'Restrict recent reviews to current post', 'wp-review' ); ?>
					</label>
				</p>

				<?php $hide = intval( $tabs['toprated'] ) ? '' : 'wpr-hide'; ?>
				<p class="wp_review_tab_top_rated_filter <?php echo esc_attr( $hide ); ?>">
					<label for="<?php echo esc_attr( $this->get_field_id( 'top_rated_posts' ) ); ?>"><?php esc_html_e( 'Top Rated Posts By:', 'wp-review' ); ?></label>
					<select id="<?php echo esc_attr( $this->get_field_id( 'top_rated_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'top_rated_posts' ) ); ?>" style="margin-left: 12px;">
						<option value="visitors" <?php selected( $top_rated_posts, 'visitors', true ); ?>><?php esc_html_e( 'Visitors', 'wp-review' ); ?></option>
						<option value="comments" <?php selected( $top_rated_posts, 'comments', true ); ?>><?php esc_html_e( 'Comments', 'wp-review' ); ?></option>
					</select>
				</p>

				<?php $hide = intval( $tabs['toprated'] ) || intval( $tabs['mostvoted'] ) ? '' : 'wpr-hide'; ?>
				<p class="wp_review_tab_number_of_days <?php echo esc_attr( $hide ); ?>">
					<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_days' ) ); ?>"><?php esc_html_e( 'Number of days:', 'wp-review' ); ?></label>
					<input type="number" class="widefat" min="0" step="1" id="<?php echo esc_attr( $this->get_field_id( 'number_of_days' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_of_days' ) ); ?>" value="<?php echo absint( $number_of_days ); ?>">
				</p>

				<p class="wp_review_tab_review_type">
					<label for="<?php echo esc_attr( $this->get_field_id( 'review_type' ) ); ?>"><?php esc_html_e( 'Review type:', 'wp-review' ); ?></label>
					<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'review_type' ) ); ?>[]" id="<?php echo esc_attr( $this->get_field_id( 'review_type' ) ); ?>" multiple>
						<?php
						$review_types = wp_review_get_rating_types();
						foreach ( $review_types as $name => $type ) {
							printf(
								'<option value="%1$s" %2$s>%3$s</option>',
								esc_attr( $name ),
								in_array( $name, $review_type, true ) ? 'selected' : '',
								esc_html( $type['label'] )
							);
						}
						?>
					</select>
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'allow_pagination' ) ); ?>">
						<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'allow_pagination' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'allow_pagination' ) ); ?>" value="1" <?php checked( 1, $instance['allow_pagination'] ); ?> />
						<?php esc_html_e( 'Allow pagination', 'wp-review' ); ?>
					</label>
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'post_num' ) ); ?>"><?php esc_html_e( 'Number of reviews to show:', 'wp-review' ); ?>
						<br />
						<input id="<?php echo esc_attr( $this->get_field_id( 'post_num' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_num' ) ); ?>" type="number" min="1" step="1" value="<?php echo intval( $post_num ); ?>" />
					</label>
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'title_length' ) ); ?>"><?php esc_html_e( 'Title length (words):', 'wp-review' ); ?>
						<br />
						<!-- dummy input so that WP doesn't pick up title_length as title -->
						<input
							id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
							name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
							type="text"
							style="display: none;"
						/>
						<input
							id="<?php echo esc_attr( $this->get_field_id( 'title_length' ) ); ?>"
							name="<?php echo esc_attr( $this->get_field_name( 'title_length' ) ); ?>"
							type="number"
							min="1"
							step="1"
							value="<?php echo intval( $instance['title_length'] ); ?>"
						/>
					</label>
				</p>

				<p class="wp_review_tab_thumbnail_size">
					<label for="<?php echo esc_attr( $this->get_field_id( 'thumb_size' ) ); ?>"><?php esc_html_e( 'Thumbnail size:', 'wp-review' ); ?></label>
					<select id="<?php echo esc_attr( $this->get_field_id( 'thumb_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_size' ) ); ?>" style="margin-left: 12px;">
						<option value="small" <?php selected( $instance['thumb_size'], 'small' ); ?>><?php esc_html_e( 'Small', 'wp-review' ); ?></option>
						<option value="large" <?php selected( $instance['thumb_size'], 'large' ); ?>><?php esc_html_e( 'Large', 'wp-review' ); ?></option>
					</select>
				</p>

				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Extra information', 'wp-review' ); ?></label>
					<select name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" class="widefat">
						<option value=""><?php esc_html_e( 'None', 'wp-review' ); ?></option>
						<option value="1" <?php selected( $instance['show_date'], 1 ); ?>><?php esc_html_e( 'Post date', 'wp-review' ); ?></option>
						<option value="2" <?php selected( $instance['show_date'], 2 ); ?>><?php esc_html_e( 'Number of reviews', 'wp-review' ); ?></option>
					</select>
				</p>

				<p class="wp_review_tab_custom_reviews"<?php echo empty( $tabs['custom'] ) ? ' style="display: none;"' : ''; ?>>
					<label for="<?php echo esc_attr( $this->get_field_id( 'custom_reviews' ) ); ?>"><?php esc_html_e( 'Reviews on Custom tab:', 'wp-review' ); ?></label>
					<input
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'custom_reviews' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'custom_reviews' ) ); ?>"
						type="text"
						value="<?php echo esc_attr( $instance['custom_reviews'] ); ?>"
					/>
					<span style="color: #999;">Add IDs, separated by commas, eg. <em>145, 168, 229</em></span>
				</p>

				<div class="clear"></div>

				<h4>
					<a href="#" class="wp_review_tab_titles_header"><?php esc_html_e( 'Tab Titles', 'wp-review' ); ?></a> |
					<a href="#" class="wp_review_tab_order_header"><?php esc_html_e( 'Tab Order', 'wp-review' ); ?></a>
				</h4>

				<div class="wp_review_tab_order" style="display: none;">

					<label
						class="alignleft wp_review_tab_toprated_order"
						for="<?php echo esc_attr( $this->get_field_id( 'tab_order' ) ); ?>_toprated"
						style="width: 50%; <?php echo empty( $tabs['toprated'] ) ? ' display: none;' : ''; ?>"
					>
						<input
							id="<?php echo esc_attr( $this->get_field_id( 'tab_order' ) ); ?>_toprated"
							name="<?php echo esc_attr( $this->get_field_name( 'tab_order' ) ); ?>[toprated]"
							type="number"
							min="1"
							step="1"
							value="<?php echo intval( $tab_order['toprated'] ); ?>"
							style="width: 48px;"
						/>
						<?php esc_html_e( 'Top Rated', 'wp-review' ); ?>
					</label>

					<label
						class="alignleft wp_review_tab_recent_order"
						for="<?php echo esc_attr( $this->get_field_id( 'tab_order' ) ); ?>_recent"
						style="width: 50%; <?php echo empty( $tabs['recent'] ) ? ' display: none;' : ''; ?>"
					>
						<input
							id="<?php echo esc_attr( $this->get_field_id( 'tab_order' ) ); ?>_recent"
							name="<?php echo esc_attr( $this->get_field_name( 'tab_order' ) ); ?>[recent]"
							type="number"
							min="1"
							step="1"
							value="<?php echo intval( $tab_order['recent'] ); ?>"
							style="width: 48px;"
						/>
						<?php esc_html_e( 'Recent', 'wp-review' ); ?>
					</label>

					<label
						class="alignleft wp_review_tab_mostvoted_order"
						for="<?php echo esc_attr( $this->get_field_id( 'tab_order' ) ); ?>_mostvoted"
						style="width: 50%; <?php echo empty( $tabs['mostvoted'] ) ? ' display: none;' : ''; ?>"
					>
						<input
							id="<?php echo esc_attr( $this->get_field_id( 'tab_order' ) ); ?>_mostvoted"
							name="<?php echo esc_attr( $this->get_field_name( 'tab_order' ) ); ?>[mostvoted]"
							type="number"
							min="1"
							step="1"
							value="<?php echo intval( $tab_order['mostvoted'] ); ?>"
							style="width: 48px;"
						/>
						<?php esc_html_e( 'Most Voted', 'wp-review' ); ?>
					</label>

					<label
						class="alignleft wp_review_tab_recent_ratings_order"
						for="<?php echo esc_attr( $this->get_field_id( 'tab_order' ) ); ?>_recent_ratings"
						style="width: 50%; <?php echo empty( $tabs['recent_ratings'] ) ? ' display: none;' : ''; ?>"
					>
						<input
							id="<?php echo esc_attr( $this->get_field_id( 'tab_order' ) ); ?>_mostvoted"
							name="<?php echo esc_attr( $this->get_field_name( 'tab_order' ) ); ?>[recent_ratings]"
							type="number"
							min="1"
							step="1"
							value="<?php echo intval( $tab_order['recent_ratings'] ); ?>"
							style="width: 48px;"
						/>
						<?php esc_html_e( 'Recent Ratings', 'wp-review' ); ?>
					</label>

					<label
						class="alignleft wp_review_tab_custom_order"
						for="<?php echo esc_attr( $this->get_field_id( 'tab_order' ) ); ?>_custom"
						style="width: 50%; <?php echo empty( $tabs['custom'] ) ? ' display: none;' : ''; ?>"
					>
						<input
							id="<?php echo esc_attr( $this->get_field_id( 'tab_order' ) ); ?>_custom"
							name="<?php echo esc_attr( $this->get_field_name( 'tab_order' ) ); ?>[custom]"
							type="number"
							min="1"
							step="1"
							value="<?php echo intval( $tab_order['custom'] ); ?>"
							style="width: 48px;"
						/>
						<?php esc_html_e( 'Custom', 'wp-review' ); ?>
					</label>
				</div>

				<div class="clear" style="margin-bottom: 15px;"></div>

				<div class="wp_review_tab_titles" style="display: none;">

					<label class="alignleft wp_review_tab_toprated_title" for="<?php echo $this->get_field_id( 'tab_titles' ); ?>_toprated" style="width: 50%;<?php echo empty( $tabs['toprated'] ) ? ' display: none;' : ''; ?>">
						<?php esc_html_e( 'Top Rated', 'wp-review' ); ?>
						<input id="<?php echo $this->get_field_id( 'tab_titles' ); ?>_toprated" name="<?php echo $this->get_field_name( 'tab_titles' ); ?>[toprated]" type="text" value="<?php echo esc_attr( $tab_titles['toprated'] ); ?>" style="width: 98%;" />
					</label>
					<label class="alignleft wp_review_tab_recent_title" for="<?php echo $this->get_field_id( 'tab_titles' ); ?>_recent" style="width: 50%;<?php echo empty( $tabs['recent'] ) ? ' display: none;' : ''; ?>">
						<?php _e( 'Recent', 'wp-review' ); ?>
						<input id="<?php echo $this->get_field_id( 'tab_titles' ); ?>_recent" name="<?php echo $this->get_field_name( 'tab_titles' ); ?>[recent]" type="text" value="<?php echo esc_attr( $tab_titles['recent'] ); ?>" style="width: 98%;" />
					</label>
					<label class="alignleft wp_review_tab_mostvoted_title" for="<?php echo $this->get_field_id( 'tab_titles' ); ?>_mostvoted" style="width: 50%;<?php echo empty( $tabs['mostvoted'] ) ? ' display: none;' : ''; ?>">
						<?php _e( 'Most Voted', 'wp-review' ); ?>
						<input id="<?php echo $this->get_field_id( 'tab_titles' ); ?>_mostvoted" name="<?php echo $this->get_field_name( 'tab_titles' ); ?>[mostvoted]" type="text" value="<?php echo esc_attr( $tab_titles['mostvoted'] ); ?>" style="width: 98%;" />
					</label>
					<label class="alignleft wp_review_tab_recent_ratings_title" for="<?php echo $this->get_field_id( 'tab_titles' ); ?>_recent_ratings" style="width: 50%;<?php echo empty( $tabs['recent_ratings'] ) ? ' display: none;' : ''; ?>">
						<?php _e( 'Recent Ratings', 'wp-review' ); ?>
						<input id="<?php echo $this->get_field_id( 'tab_titles' ); ?>_recent_ratings" name="<?php echo $this->get_field_name( 'tab_titles' ); ?>[recent_ratings]" type="text" value="<?php echo esc_attr( $tab_titles['recent_ratings'] ); ?>" style="width: 98%;" />
					</label>
					<label class="alignleft wp_review_tab_custom_title" for="<?php echo $this->get_field_id( 'tab_titles' ); ?>_custom" style="width: 50%;<?php echo empty( $tabs['custom'] ) ? ' display: none;' : ''; ?>">
						<?php _e( 'Custom', 'wp-review' ); ?>
						<input id="<?php echo $this->get_field_id( 'tab_titles' ); ?>_custom" name="<?php echo $this->get_field_name( 'tab_titles' ); ?>[custom]" type="text" value="<?php echo esc_attr( $tab_titles['custom'] ); ?>" style="width: 98%;" />
					</label>
				</div>
				<div class="clear" style="margin-bottom: 15px;"></div>

			</div><!-- .wp_review_tab_advanced_options -->
		</div><!-- .wp_review_tab_options_form -->
		<?php
	}

	/**
	 * Updates widget instance.
	 *
	 * @param array $new_instance New instance.
	 * @param array $old_instance Old instance.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['tabs']                    = $new_instance['tabs'];
		$instance['tab_order']               = $new_instance['tab_order'];
		$instance['tab_titles']              = wp_kses_post( $new_instance['tab_titles'] );
		$instance['review_type']             = $new_instance['review_type'];
		$instance['allow_pagination']        = $new_instance['allow_pagination'];
		$instance['post_num']                = $new_instance['post_num'];
		$instance['title_length']            = $new_instance['title_length'];
		$instance['thumb_size']              = $new_instance['thumb_size'];
		$instance['show_date']               = isset( $new_instance['show_date'] ) ? intval( $new_instance['show_date'] ) : 1;
		$instance['custom_reviews']          = $new_instance['custom_reviews'];
		$instance['restrict_recent_reviews'] = $new_instance['restrict_recent_reviews'];
		$instance['top_rated_posts']         = $new_instance['top_rated_posts'];
		$instance['number_of_days']          = ! empty( $new_instance['number_of_days'] ) ? intval( $new_instance['number_of_days'] ) : '';

		return $instance;
	}

	/**
	 * Shows the widget content.
	 *
	 * @param array $args     Widget args.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		global $post;
		extract( $args, EXTR_SKIP ); // phpcs:ignore
		extract( $instance, EXTR_SKIP ); // phpcs:ignore
		wp_enqueue_script( 'wp_review_tab_widget' );
		wp_enqueue_script( 'jquery-knob' );
		wp_enqueue_script( 'wp_review-js' );
		wp_enqueue_style( 'wp_review-style' );
		wp_enqueue_script( 'wp-review-circle-output', trailingslashit( WP_REVIEW_URI ) . 'rating-types/circle-output.js', array( 'jquery' ) );
		wp_enqueue_style( 'wp_review_tab_widget' );

		if ( empty( $tabs ) ) {
			$tabs = array(
				'recent'   => 1,
				'toprated' => 1,
			);
		}
		$tabs_count = count( $tabs );
		if ( $tabs_count <= 1 ) {
			$tabs_count = 1;
		} elseif ( $tabs_count > 5 ) {
			$tabs_count = 5;
		}

		$tab_titles = wp_parse_args(
			$instance['tab_titles'],
			array(
				'toprated'       => __( 'Top Rated', 'wp-review' ),
				'recent'         => __( 'Recent', 'wp-review' ),
				'mostvoted'      => __( 'Most Voted', 'wp-review' ),
				'recent_ratings' => __( 'Comments', 'wp-review' ),
				'custom'         => __( 'Editor\'s choice', 'wp-review' ),
			)
		);

		$available_tabs = array(
			'toprated'       => $tab_titles['toprated'],
			'recent'         => $tab_titles['recent'],
			'mostvoted'      => $tab_titles['mostvoted'],
			'recent_ratings' => $tab_titles['recent_ratings'],
			'custom'         => $tab_titles['custom'],
		);

		array_multisort( $tab_order, $available_tabs );
		?>
		<?php echo wp_kses_post( $args['before_widget'] ); ?>
		<div class="wp_review_tab_widget_content" id="<?php echo esc_attr( $args['widget_id'] ); ?>_content">
			<ul class="wp-review-tabs has-<?php echo intval( $tabs_count ); ?>-tabs">
				<?php foreach ( $available_tabs as $tab => $label ) : ?>
					<?php if ( ! empty( $tabs[ $tab ] ) ) : ?>
						<li class="tab_title"><a href="#" id="<?php echo esc_attr( $tab ); ?>-tab"><?php echo esc_html( $label ); ?></a></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul> <!--end .tabs-->
			<div class="clear"></div>
			<div class="inside">
				<?php if ( ! empty( $tabs['toprated'] ) ) : ?>
					<div id="toprated-tab-content" class="tab-content">
					</div> <!--end #toprated-tab-content-->
				<?php endif; ?>
				<?php if ( ! empty( $tabs['recent'] ) ) : ?>
					<div id="recent-tab-content" class="tab-content">
					</div> <!--end #recent-tab-content-->
				<?php endif; ?>
				<?php if ( ! empty( $tabs['mostvoted'] ) ) : ?>
					<div id="mostvoted-tab-content" class="tab-content">
						<ul></ul>
					</div> <!--end #mostvoted-tab-content-->
				<?php endif; ?>
				<?php if ( ! empty( $tabs['recent_ratings'] ) ) : ?>
					<div id="recent_ratings-tab-content" class="tab-content">
						<ul></ul>
					</div> <!--end #recent_ratings-tab-content-->
				<?php endif; ?>
				<?php if ( ! empty( $tabs['custom'] ) ) : ?>
					<div id="custom-tab-content" class="tab-content">
						<ul></ul>
					</div> <!--end #custom-tab-content-->
				<?php endif; ?>
				<div class="clear"></div>
			</div> <!--end .inside -->
			<div class="clear"></div>
		</div><!--end #tabber -->
		<?php
		// inline script
		// to support multiple instances per page with different settings.
		unset( $instance['tabs'], $instance['tab_order'], $instance['tab_titles'] ); // unset unneeded.
		$instance['current_post_id'] = ! empty( $post->ID ) ? $post->ID : 0;

		if ( isset( $_GET['clear'] ) ) {
			$instance['clear_cache'] = true;
		}
		?>
		<script type="text/javascript">
			jQuery(function( $) {
				$( '#<?php echo esc_attr( $args['widget_id'] ); ?>_content' ).data( 'args', <?php echo wp_json_encode( $instance ); ?>);
			});
		</script>
		<?php
		echo wp_kses_post( $args['after_widget'] );
	}


	/**
	 * AJAX handler for tab widget content.
	 */
	public function ajax_wp_review_tab_widget_content() {
		$tab  = $_POST['tab'];
		$args = $_POST['args'];
		$page = intval( $_POST['page'] );
		if ( $page < 1 ) {
			$page = 1;
		}

		$GLOBALS['in_widget'] = 1;

		$thumb_size = $args['thumb_size'];
		if ( 'small' !== $thumb_size && 'large' !== $thumb_size ) {
			$thumb_size = 'small'; // default.
		}

		$title_length = ! empty( $args['title_length'] ) ? $args['title_length'] : apply_filters( 'wpt_title_length_default', '15' );

		$show_date        = intval( $args['show_date'] );
		$allow_pagination = ! empty( $args['allow_pagination'] );

		$post_num    = ( empty( $args['post_num'] ) || $args['post_num'] > 20 || $args['post_num'] < 1 ) ? 5 : intval( $args['post_num'] );
		$review_type = ! empty( $args['review_type'] ) ? (array) $args['review_type'] : array();

		// Recent ratings tab renders different than other tabs.
		if ( 'recent_ratings' === $tab ) {
			$comment_post_id  = 0;
			$offset           = intval( $page - 1 ) * $post_num;
			$restrict_reviews = $args['restrict_recent_reviews'];
			if ( $restrict_reviews ) {
				$comment_post_id = $args['current_post_id'];
			}

			$comments = get_comments(
				array(
					'type__in' => array( WP_REVIEW_COMMENT_TYPE_VISITOR, WP_REVIEW_COMMENT_TYPE_COMMENT ),
					'number'   => $post_num,
					'status'   => 'approve',
					'offset'   => $offset,
					'post_id'  => $comment_post_id,
				)
			);

			$total_comments = get_comments(
				array(
					'type__in' => array( WP_REVIEW_COMMENT_TYPE_VISITOR, WP_REVIEW_COMMENT_TYPE_COMMENT ),
					'status'   => 'approve',
					'post_id'  => $comment_post_id,
					'count'    => true,
				)
			);

			$last_page = ceil( $total_comments / $post_num );
			?>
			<ul>
				<?php
				if ( $comments ) {
					foreach ( $comments as $comment ) {
						$comment_post_id = intval( $comment->comment_post_ID );
						$comment_user_id = intval( $comment->user_id );
						if ( $comment_user_id ) {
							$user_data  = get_user_by( 'ID', $comment_user_id );
							$user_name  = $user_data->display_name;
							$user_email = $user_data->user_email;
						} else {
							$user_name  = $comment->comment_author;
							$user_email = $comment->comment_author_email;
						}
						$rating = get_comment_meta( $comment->comment_ID, 'wp_review_comment_rating', true );
						if ( ! $rating ) {
							$rating = get_comment_meta( $comment->comment_ID, 'wp_review_visitor_rating', true );
						}
						?>
						<li>
							<div class="wp_review_tab_thumbnail wp_review_tab_thumb_<?php echo esc_attr( $thumb_size ); ?>">
								<img src="<?php echo get_avatar_url( $user_email ); ?>" alt="<?php echo get_the_title( $comment_post_id ); ?>" class="wp-post-image" />
							</div>
							<div class="title-right">
								<div class="entry-title">
									<a title="<?php echo get_the_title( $comment_post_id ); ?>" href="<?php echo get_the_permalink( $comment_post_id ); ?>">
										<?php echo get_the_title( $comment_post_id ); ?>
									</a>
									<span>
										<?php esc_html_e( 'reviewed by', 'wp-review' ); ?>
										<?php echo esc_html( $user_name ); ?>
									</span>
								</div>
								<p><?php echo esc_html( wp_trim_words( $comment->comment_content, $title_length ) ); ?></p>
								<?php
								$rating_args = array(
									'in_widget'      => true,
									'circle_size'    => 20,
									'color'          => '#fff',
									'inactive_color' => '#dedcdc',
								);
								echo wp_review_show_total( true, 'review-total-only ' . $thumb_size . '-thumb', $comment_post_id, $rating_args );

								if ( 1 === $show_date ) :
									$comment_time = strtotime( $comment->comment_date );
									$comment_time = date( 'M j, Y', $comment_time );
									?>
									<div class="wp-review-tab-postmeta">
										<?php echo $comment_time; ?>
									</div> <!--end .entry-meta-->
								<?php endif; ?>
							</div>
							<div class="clear"></div>
						</li>
					<?php } ?>
				<?php } ?>
			</ul>
			<?php
		} else {
			// Normal tabs.
			$query_args = array(
				'post_type'   => 'any',
				'post_num'    => $post_num,
				'page'        => $page,
				'review_type' => $review_type,
				'clear_cache' => ! empty( $args['clear_cache'] ),
			);

			if ( 'custom' === $tab ) {
				$custom_reviews = array();
				if ( ! empty( $args['custom_reviews'] ) ) {
					$custom_reviews = explode( ',', $args['custom_reviews'] );
					$custom_reviews = array_map( 'trim', $custom_reviews );
					$custom_reviews = array_map( 'intval', $custom_reviews );
				}
				$query_args['ids'] = $custom_reviews;
			} elseif ( 'toprated' === $tab ) {
				$toprated_key = 'wp_review_total';

				if ( ! empty( $args['top_rated_posts'] ) && 'comments' === $args['top_rated_posts'] ) {
					$toprated_key = 'wp_review_comments_rating_value';
				}

				$query_args['toprated_key'] = $toprated_key;
			}

			if ( in_array( $tab, array( 'toprated', 'mostvoted' ), true ) ) {
				$query_args['number_of_days'] = isset( $args['number_of_days'] ) ? intval( $args['number_of_days'] ) : 0;
			}

			$query = wp_review_get_reviews_query( $tab, $query_args );
			?>
			<ul>
				<?php
				$last_page = $query->max_num_pages;
				while ( $query->have_posts() ) :
					$query->the_post();
					?>
					<li>
						<a title="<?php the_title(); ?>" rel="nofollow" href="<?php the_permalink(); ?>">
							<div class="wp_review_tab_thumbnail wp_review_tab_thumb_<?php echo esc_attr( $thumb_size ); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'wp_review_' . $thumb_size, array( 'title' => '' ) ); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( WP_REVIEW_ASSETS . 'images/' . $thumb_size . 'thumb.png' ); ?>" alt="<?php the_title(); ?>" class="wp-post-image" />
								<?php endif; ?>
							</div>
						</a>
						<div class="title-right">
							<div class="entry-title">
								<a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>"><?php echo $this->post_title( $title_length ); ?></a>
							</div>
							<?php
							$rating_args = array(
								'in_widget'      => true,
								'circle_size'    => 20,
								'color'          => '#fff',
								'inactive_color' => '#dedcdc',
							);
							wp_review_show_total( true, 'review-total-only ' . $thumb_size . '-thumb', null, $rating_args );

							wp_review_extra_info(
								get_the_ID(),
								$show_date,
								array(
									'date_format' => 'M j, Y',
									'class'       => 'wp-review-tab-postmeta',
								)
							); // Using `show_date` to keep compatibility.
							?>
						</div>
						<div class="clear"></div>
					</li>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
			<?php
		}
		?>
		<div class="clear"></div>
		<?php if ( $allow_pagination ) : ?>
			<?php $this->tab_pagination( $page, $last_page ); ?>
		<?php endif; ?>
		<?php
		unset( $GLOBALS['in_widget'] );
		die(); // required to return a proper result.
	}

	/**
	 * Shows tab pagination.
	 *
	 * @param int $page      Current page.
	 * @param int $last_page The last page.
	 */
	public function tab_pagination( $page, $last_page ) {
		if ( $last_page <= 1 ) {
			return;
		}
		?>
		<div class="wp-review-tab-pagination">
			<?php if ( $page > 1 ) : ?>
				<a href="#" class="previous"><span><?php esc_html_e( '&laquo; Previous', 'wp-review' ); ?></span></a>
			<?php endif; ?>
			<?php if ( $page != $last_page ) : ?>
				<a href="#" class="next"><span><?php esc_html_e( 'Next &raquo;', 'wp-review' ); ?></span></a>
			<?php endif; ?>
		</div>
		<div class="clear"></div>
		<input type="hidden" class="page_num" name="page_num" value="<?php echo intval( $page ); ?>" />
		<?php
	}

	/**
	 * Gets post title.
	 *
	 * @param int $limit Words limit.
	 * @return string
	 */
	public function post_title( $limit = 15 ) {
		$title = get_the_title();
		if ( ! $title ) {
			return '';
		}
		$limit++;
		$title = explode( ' ', $title, $limit );
		if ( count( $title ) >= $limit ) {
			array_pop( $title );
			$title = implode( ' ', $title ) . '&hellip;';
		} else {
			$title = implode( ' ', $title );
		}
		return $title;
	}

	/**
	 * Truncates string.
	 *
	 * @param string $str    String needs to truncate.
	 * @param int    $length String length.
	 * @return string
	 */
	public function truncate( $str, $length = 24 ) {
		if ( mb_strlen( $str ) > $length ) {
			return mb_substr( $str, 0, $length ) . '&hellip;';
		}
		return $str;
	}
}
