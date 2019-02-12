<?php 
get_header(); 

$search = isset($_POST['search']) ? $_POST['search'] : NULL; 
$sortby = isset($_POST['sortby']) ? $_POST['sortby'] : "DESC"; 
?>
<section class="post">
	<div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="content-post-single">
                    <?php 
                    if(have_posts()): 
                        while(have_posts()): the_post(); 
                            // get review info from post_meta
                            $revs       = get_post_meta( get_the_ID(), 'wp_review_hello_bar', true );
                            $userreview = get_post_meta( $post->ID, 'wp_review_user_reviews', true );
                            $reviewcont = round( $userreview );
                            $schema     = get_post_meta( $post->ID, 'wp_review_schema_options', true );
                            $website    = $schema['WebSite'];
                            $detailed   = get_post_meta( $post->ID, 'wp_review_item', true );

                            // args
                            $args = array(
                                'post_id' => get_the_ID(),
                                'orderby' => 'comment_ID',
                                'order' => $sortby
                            );
                            
                            // if search form is submitted
                            if(!is_null($search)) {
                                $args['search'] = $search;
                            }

                            // get comments
                            $comments = get_comments($args);
                            ?>                                  
                            <div class="review-overview">
                                <div class="row">
                                    <div class="overview-item-image col-md-5 col-sm-12">
                                        <img src="<?php echo $website['image']['url']; ?>">
                                    </div>
                                    <div class="overview-item-data col-md-7 col-sm-12">
                                        <div class="overview-title">
                                            <h2><?php echo the_title(); ?></h2>
                                            <div class="cat-page"><?php echo $website['name']; ?></div>
                                            <div class="review-counts"><a href="#"><?php echo count($comments); ?> <span>Reviews</span></a></div>
                                        </div>
                                        <div class="overview-info">
                                            <div class="overview-description">
                                                <p><?php echo $website['description']; ?></p>
                                            </div>
                                            <div class="overview-description-rating">
                                                <div class="overview-ratings">
                                                    <img src="<?php echo IMG; ?>/starb<?php echo $reviewcont; ?>.png">
                                                    <span class="ratings-total"><?php echo $userreview; ?><span class="overall">/5</span></span>
                                                </div>
                                                <div class="visit"><a class="btn btn-success" href="<?php echo $website['url']; ?>" role="button" ><i class="fas fa-link"></i> <span>Visit</span></a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="clear"></div>
                            <div class="quick-rate">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <h5>Quick Rate</h5><div class="rating"><?php echo do_shortcode('[wp-review-visitor-rating title="mo" id="'.get_the_ID().'"]'); ?></div>
                                    </div>
                                </div>
                            </div>                          
                            <div class="clear"></div>
                            <form method="post">
                            <div class="review-content-tab">                        
                                <h3><?php echo count($comments); ?> Survey  Inspector Review and Complaints</h3>
                                <div class="review-category-tab">                        
                                    <div class="review-category-box">
                                        <span>Sort: 
                                            <select name="sortby" onchange="this.form.submit();">
                                                <option <?php echo ($sortby == 'DESC') ? 'selected' : ''; ?> value="DESC">Most Recent</option>
                                                <option <?php echo ($sortby == 'ASC') ? 'selected' : ''; ?> value="ASC">Oldest</option>
                                            </select>
                                        </span>
                                    </div>
                                    <div class="review-category-box">
                                        <span>Filtered by: 
                                            <input type="text" name="search" placeholder="Any (Type Word)" value="<?php echo $search; ?>">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <?php
                            if($comments) {
                                
                                echo '<div class="review-content-list container">';

                                foreach($comments as $comment) {
                                    $img = esc_url( get_avatar_url( $comment->user_id ) );
                                    $vote = ( get_comment_meta( $comment->comment_ID, 'wp_review_comment_rating', true ) ) ? get_comment_meta( $comment->comment_ID, 'wp_review_comment_rating', true ) : 0;
                                    $tema = ( get_comment_meta( $comment->comment_ID, 'wp_review_comment_title', true ) ) ? get_comment_meta( $comment->comment_ID, 'wp_review_comment_title', true ) : '';
                                    $help = get_comment_meta( $comment->comment_ID, 'wp_review_comment_helpful', true );
                                   
                                    ?>
                                    <div class="individual-review row">
                                        <div class="review-image col-md-2 col-sm-3">
                                            <img class="inspector-img" src="<?php echo $img; ?>">
                                            <span class="name"><?php echo $comment->comment_author; ?></span>
                                        </div>
                                        <div class="comments col-md-10 col-sm-9">
                                            <div class="inspector-comments">
                                                <div class="star-rating">
                                                    <img class="rating" src="<?php echo IMG; ?>/star<?php echo $vote; ?>.png">
                                                </div>
                                                <div class="title"><?php echo $tema; ?></div>
                                                <div class="comment">
                                                    <p><?php echo $comment->comment_content; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="review-image col-md-2 col-sm-3"></div>
                                        <div class="comments col-md-10 col-sm-12">
                                            <div class="inspector-comments">
                                                <div class="detailed-ratings">
                                                    <span>Detailed Ratings</span><a class="showmore-review" href="#commentmore<?php echo $comment->comment_ID; ?>">Show more <small>+</small></a>
                                                </div>
                                                <div class="comment-detailed-container" id="commentmore<?php echo $comment->comment_ID; ?>">
                                                    <?php foreach($detailed as $detail) { ?>
                                                    <div class="rating-info row">
                                                        <div class="col-md-9 col-sm-12">
                                                            <div class="rating-item">
                                                                <?php echo $detail['wp_review_item_title']; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-sm-12">
                                                            <img class="rating" src="<?php echo IMG.'/star'. round( $detail['wp_review_item_star'] ) .'.png'; ?>">
                                                        </div>
                                                    </div>         
                                                    <?php } ?>                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }

                                echo '</div>';
                            }



                            // Comment form
                            if ( comments_open() || get_comments_number() ) :                       
                                comments_template();
                            endif;
                            
                        endwhile; 
                    endif; wp_reset_query(); ?> 
                    
                    <div class="clear"></div>
				</div>
            </div>
            <div class="col-md-4">
	            <?php get_sidebar('review'); ?>
            </div>
		</div>
    </div>	
</section>
<div class="clear"></div>

<?php get_footer(); ?>