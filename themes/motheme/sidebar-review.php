<?php 
global $post; 
$detailed 		= get_post_meta( $post->ID, 'wp_review_item', true );
$comments 		= get_comments( array('post_id' => $post->ID) );
$userreview		= round( get_post_meta( $post->ID, 'wp_review_user_reviews', true ) );
$vote_counts 	= array('five' => 0, 'four' => 0, 'three' => 0, 'two' => 0, 'one' => 0, 'zero' => 0);
$vote_total		= count($comments); // get_post_meta( $post->ID, 'wp_review_review_count', true ); //count($comments);

foreach($comments as $comment) {
	$vote = ( get_comment_meta( $comment->comment_ID, 'wp_review_comment_rating', true ) ) ? get_comment_meta( $comment->comment_ID, 'wp_review_comment_rating', true ) : 0;
	switch ($vote) {
		case 5:
			$vote_counts['five'] += 1;
		break;		
		case 4:
			$vote_counts['four'] += 1;
		break;		
		case 3:
			$vote_counts['three'] += 1;
		break;		
		case 2:
			$vote_counts['two'] += 1;
		break;		
		case 1:
			$vote_counts['one'] += 1;
		break;		
		default:
			$vote_counts['zero'] += 1;
		break;
	}
}

$five 	= ($vote_counts['five'] != 0 && $vote_total != 0) ? round( ($vote_counts['five'] / $vote_total) * 100 ) : 0;
$four 	= ($vote_counts['four'] != 0 && $vote_total != 0) ? round( ($vote_counts['four'] / $vote_total) * 100 ) : 0;
$three 	= ($vote_counts['three'] != 0 && $vote_total != 0) ? round( ($vote_counts['three'] / $vote_total) * 100 ) : 0;
$two 	= ($vote_counts['two'] != 0 && $vote_total != 0) ? round( ($vote_counts['two'] / $vote_total) * 100 ) : 0;
$one 	= ($vote_counts['one'] != 0 && $vote_total != 0) ? round( ($vote_counts['one'] / $vote_total) * 100 ) : 0;
?>
<div class="widget_text widget-info">
	<div class="title-information">
		<a class="tab_toggle active" href="#rating_tab"><i class="fas fa-star"></i> Rating</a> <a class="tab_toggle" href="#detailed_tab"><i class="fas fa-signal"></i> Detailed Ratings</a>
	</div>
	<div class="info">
		<div class="tab_items active" id="rating_tab">
			<dl>
				<dd class="percentage">
					<span class="text1">5 <img src="<?php echo IMG.'/solostar.png'; ?>" /></span>
					<span class="line per-<?php echo $five; ?>"></span>
					<span class="text2"> <?php echo $five; ?>%</span>
				</dd>
				<dd class="percentage">
					<span class="text1">4 <img src="<?php echo IMG.'/solostar.png'; ?>" /></span>
					<span class="line per-<?php echo $four; ?>"></span>
					<span class="text2"> <?php echo $four; ?>%</span>
				</dd>
				<dd class="percentage">
					<span class="text1">3 <img src="<?php echo IMG.'/solostar.png'; ?>" /></span>
					<span class="line per-<?php echo $three; ?>"></span>
					<span class="text2"> <?php echo $three; ?>%</span>
				</dd>
				<dd class="percentage">
					<span class="text1">2 <img src="<?php echo IMG.'/solostar.png'; ?>" /></span>
					<span class="line per-<?php echo $two; ?>"></span>
					<span class="text2"> <?php echo $two; ?>%</span>
				</dd>
				<dd class="percentage">
					<span class="text1">1 <img src="<?php echo IMG.'/solostar.png'; ?>" /></span>
					<span class="line per-<?php echo $one; ?>"></span>
					<span class="text2"> <?php echo $one; ?>%</span>
				</dd>
			</dl>
			<div class="text"><?php echo $vote_total; ?> review(s)</div>
		</div>
		<div class="tab_items" id="detailed_tab">
			<div class="row">
				<div class="col-sm-7"><label>User Review(s)</label></div>
				<div class="col-sm-5">
					<img class="sidebar-detailed-star" src="<?php echo IMG.'/star'. round( $userreview ) .'.png'; ?>" /> 
					<span><?php echo $userreview; ?></span>
				</div>
			</div>
			<?php foreach($detailed as $detail) { ?>
			<div class="row">
				<div class="col-sm-7"><label><?php echo $detail['wp_review_item_title']; ?></label></div>
				<div class="col-sm-5">
					<img class="sidebar-detailed-star" src="<?php echo IMG.'/star'. round( $detail['wp_review_item_star'] ) .'.png'; ?>" />
					<span><?php echo $detail['wp_review_item_star']; ?></span>
				</div>
			</div>			
			<?php } ?>
		</div>	
	</div>	
</div>

<div class="widget_text widget comparison">
	<div class="comparison-title"><img src="<?php echo IMG; ?>/compare-icon.png"> <h3>Compare <?php echo $post->post_title; ?></h3></div>
	<div class="comparison-textwidget custom-html-widget">
		<?php 
        $args = array(
            'posts_per_page' => 3,
            'post_type' => 'compare',
            'meta_query' => array(
            					'relation' => 'OR',
								array(
									'key' => 'site_1',
									'value' => $post->ID,
									'compare' => 'IN'
								),
								array(
									'key' => 'site_2',
									'value' => $post->ID,
									'compare' => 'IN'
								)
							)
        );
        $the_query = new WP_Query( $args );
        if ( $the_query->have_posts() ) { 
            while ( $the_query->have_posts() ) { $the_query->the_post();
	            $featured_img_url = get_field( 'website_image', $post->ID );
	            ?>
	            <div class="feat-img">
					<a href="<?php echo the_permalink(); ?>"><img src="<?php echo $featured_img_url; ?>"></a>
				</div>            
	            <?php
            }
        }
        wp_reset_query(); 
        ?>
	</div>
</div>

<?php if ( ! dynamic_sidebar('review-sidebar') ) : ?>

<?php endif; ?>