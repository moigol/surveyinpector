<?php
/**
 * Class WP_Review_Importer_WPRichSnippets
 *
 * @package WP_Review
 */

/**
 * Class WP_Review_Importer_WPRichSnippets
 */
class WP_Review_Importer_WPRichSnippets implements WP_Review_Importer_Interface {

	/**
	 * Runs import.
	 *
	 * @param int   $numposts Number of posts.
	 * @param int   $offset   Offset.
	 * @param array $options  Import options.
	 * @return WP_Review_Importer_Response.
	 */
	public function run( $numposts, $offset, $options ) {
		$posts = get_posts(
			array(
				'nopaging'               => true,
				'meta_key'               => '_wprs_post_snippets_types',
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);

		$posts_count = count( $posts );
		if ( ! $posts_count ) {
			return new WP_Review_Importer_Response( __( 'There is no review.', 'wp-review' ), true, 0, true );
		}

		$subset = array_slice( $posts, $offset, $numposts );
		foreach ( $subset as $post ) {
			$this->set_review_schema( $post );

			if ( $name = $post->_wprs_post_item_name ) { // phpcs:ignore
				update_post_meta( $post->ID, 'wp_review_heading', $name );
			}
			if ( $desc = $post->_wprs_post_item_description ) { // phpcs:ignore
				update_post_meta( $post->ID, 'wp_review_desc', $desc );
			}

			if ( $pros = $post->_wprs_post_pros ) { // phpcs:ignore
				update_post_meta( $post->ID, 'wp_review_pros', $pros );
			}

			if ( $cons = $post->_wprs_post_cons ) { // phpcs:ignore
				update_post_meta( $post->ID, '_wprs_post_cons', $cons );
			}

			switch ( strtolower( $post->_wprs_post_review_type ) ) {
				case 'votes':
					$this->import_votes( $post );
					break;
				case 'aggregate':
					$this->import_aggregate( $post );
					break;
				case 'percentage':
					$this->import_percentage( $post );
			}
		}

		$new_offset = $offset + count( $subset );
		if ( $new_offset < $posts_count ) {
			return new WP_Review_Importer_Response(
				// translators: %1$s: new offset, %2$s: posts count.
				sprintf( __( 'Imported %1$s of %2$s.', 'wp-review' ), $new_offset, $posts_count ),
				false,
				$new_offset
			);
		}

		return new WP_Review_Importer_Response(
			// translators: posts count.
			sprintf( __( 'Imported ratings from %s posts.', 'wp-review' ), $posts_count )
		);
	}

	/**
	 * Imports votes.
	 *
	 * @param WP_Post $post Post object.
	 */
	private function import_votes( WP_Post $post ) {
		update_post_meta( $post->ID, 'wp_review_type', 'star' );
		update_post_meta( $post->ID, 'wp_review_user_review_type', 'star' );
		update_post_meta( $post->ID, 'wp_review_userReview', WP_REVIEW_REVIEW_VISITOR_ONLY );

		if ( ( $criteria = $post->_wprs_post_repeatable ) && is_array( $criteria ) ) { // phpcs:ignore
			$review_items = array();
			foreach ( $criteria as $criterion ) {
				if ( empty( $criterion['rating'] ) ) {
					continue;
				}

				$review_item = array();

				$review_item['wp_review_item_star']  = $criterion['rating'];
				$review_item['wp_review_item_title'] = $criterion['desc'];
				$review_item['id']                   = sanitize_title( $criterion['title'] ) . '_' . wp_generate_password( 6 );

				$review_items[] = $review_item;
			}

			update_post_meta( $post->ID, 'wp_review_item', $review_items );
		}

		$user_rating_count = 0;
		$user_rating_total = 0;
		foreach ( $post->_wprs_post_user_rating_ips as $ip => $rating ) {
			$user_rating_total += $rating;
			$user_rating_count++;

			$comment = wp_insert_comment(
				array(
					'comment_type'      => WP_REVIEW_COMMENT_TYPE_VISITOR,
					'comment_post_ID'   => $post->ID,
					'comment_parent'    => 0,
					'comment_author_IP' => $ip,
					'comment_content'   => sprintf(
						// translators: rating text.
						__( 'Visitor Rating: %s', 'wp-review' ),
						// translators: rating value.
						sprintf( _x( '%s Stars', 'star rating value text', 'wp-review' ), $rating )
					),
					'comment_approved'  => 1,
				)
			);

			if ( $comment ) {
				update_comment_meta( $comment, WP_REVIEW_VISITOR_RATING_METAKEY, $rating );
			}
		}

		update_post_meta( $post->ID, 'wp_review_review_count', $user_rating_count );
		update_post_meta( $post->ID, 'wp_review_user_reviews', round( $user_rating_total / $user_rating_count, 2 ) );
	}

	/**
	 * Imports aggregate ratings.
	 *
	 * @param WP_Post $post Post object.
	 */
	private function import_aggregate( WP_Post $post ) {
		update_post_meta( $post->ID, 'wp_review_type', 'star' );
		update_post_meta( $post->ID, 'wp_review_user_review_type', 'star' );
		update_post_meta( $post->ID, 'wp_review_userReview', WP_REVIEW_REVIEW_COMMENT_ONLY );

		if ( ( $criteria = $post->_wprs_post_repeatable ) && is_array( $criteria ) ) { // phpcs:ignore
			$review_items  = array();
			$criterion_ids = array();
			foreach ( $criteria as $criterion ) {
				if ( empty( $criterion['rating'] ) ) {
					continue;
				}

				$review_item = array();

				$review_item['wp_review_item_star']  = $criterion['rating'];
				$review_item['wp_review_item_title'] = $criterion['desc'];
				$review_item['id']                   = sanitize_title( $criterion['title'] ) . '_' . wp_generate_password( 6 );

				$criterion_ids[ $review_item['id'] ] = $criterion['title'];

				$review_items[] = $review_item;
			}

			update_post_meta( $post->ID, 'wp_review_item', $review_items );

			$comments = get_comments(
				array(
					'meta_key' => 'rating',
					'post_id'  => $post->ID,
					'status'   => 'all',
				)
			);

			foreach ( $comments as $comment ) {
				$meta = get_comment_meta( $comment->comment_ID );

				$insert = wp_insert_comment(
					array(
						'comment_post_ID'   => $post->ID,
						'comment_author_IP' => $comment->comment_author_IP,
						'comment_date'      => $comment->comment_date,
						'comment_type'      => WP_REVIEW_COMMENT_TYPE_COMMENT,
						'comment_content'   => $comment->comment_content,
						'comment_approved'  => $comment->comment_approved,
						'comment_agent'     => $comment->comment_agent,
						'user_id'           => $comment->user_id,
						'comment_parent'    => $comment->comment_parent,
					)
				);

				if ( ! $insert ) {
					continue;
				}

				update_comment_meta( $comment->comment_ID, 'wp_review_comment_rating', $meta['rating'][0] );

				$feature_ratings = array();
				foreach ( $criterion_ids as $wprid => $wprsid ) {
					if ( ! empty( $meta[ 'item_range_' . $wprsid ] ) ) {
						$feature_ratings[ $wprid ] = $meta[ 'item_range_' . $wprsid ][0];
					}
				}

				update_comment_meta( $comment->comment_ID, 'wp_review_features_rating', $feature_ratings );
			}
		}
	}

	/**
	 * Imports percentage ratings.
	 *
	 * @param WP_Post $post Post object.
	 */
	private function import_percentage( WP_Post $post ) {
		update_post_meta( $post->ID, 'wp_review_type', 'percentage' );

		$name = $post->_wprs_post_item_name;
		if ( ! $name ) {
			$name = $post->post_title;
		}

		update_post_meta(
			$post->ID,
			'wp_review_item',
			array(
				array(
					'wp_review_item_star'  => $post->_wprs_post_slider_percentage,
					'wp_review_item_title' => $name,
					'id'                   => 'feature1_' . wp_generate_password( 6 ),
				),
			)
		);
	}

	/**
	 * Sets review schema for post.
	 *
	 * @param WP_post $post Post object.
	 */
	private function set_review_schema( WP_post $post ) {
		switch ( strtolower( $post->_wprs_post_snippets_types ) ) {
			case 'product':
				$schema_type = 'Product';
				break;
			case 'restaurant':
				$schema_type = 'Restaurant';
				break;
			case 'recipe':
				$schema_type = 'Recipe';
				break;
			case 'softwareapplication':
				$schema_type = 'SoftwareApplication';
				break;
			default:
				$schema_type = 'Thing';
				break;
		}

		update_post_meta( $post->ID, 'wp_review_schema', $schema_type );
	}
}
