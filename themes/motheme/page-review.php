<?php 
/*
Template Name: Review Page
*/
get_header(); 
?>
<section class="post">
	<div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="content-post-single">
                    <?php if(have_posts()): while(have_posts()): the_post(); ?>                    
                        <?php echo the_content(); ?>
                    
                    <div class="clear"></div>
                    <div class="review-content-tab">
                        <h3>2843 Survey  Inspector Review and Complaints</h3>
                        <div class="review-category-tab">
                            <div class="review-category-box">
                                <span>Sort: <strong>Recent</strong></span>
                            </div>
                            <div class="review-category-box">
                                <span>Filtered by: <strong>Any</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="review-content-list container">
                        <div class="individual-review row">
                            <div class="review-image col-md-2 col-sm-3">
                                <img class="inspector-img" src="<?php echo IMG; ?>/r1.jpg">
                                <span class="name">LoriKezos</span>
                            </div>
                            <div class="comments col-md-10 col-sm-9">
                                <div class="inspector-comments">
                                    <div class="star-rating">
                                        <img class="rating" src="<?php echo IMG; ?>/star4.png"> 4/5
                                    </div>
                                    <div class="title">Amazing Review</div>
                                    <div class="comment">
                                        <p>"At the last second I clicked the wrong promo and was charged an extra hundred dollars because of my own mistake. I called the next morning and it was taken care of and refunded no questions asked! Great service. Thank you!!!"</p>
                                    </div>
                                </div>
                            </div>
                            <div class="review-image col-md-2 col-sm-12">
                            </div>
                            <div class="comments col-md-10 col-sm-12">
                                <div class="inspector-comments">
                                    <div class="detailed-ratings">
                                        <span>Detailed Ratings</span><a href="#">Show more +</a>
                                    </div>
                                    <div class="rating-info row">
                                        <div class="col-md-9 col-sm-12">
                                            <div class="rating-item">
                                                Pricing of products and services
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <img class="rating" src="<?php echo IMG; ?>/star5.png">
                                        </div>
                                    </div>
                                    <div class="rating-info row blur-text">
                                        <div class="col-md-9 col-sm-12">
                                            <div class="rating-item">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <img class="rating" src="<?php echo IMG; ?>/star5.png">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="individual-review row">
                            <div class="review-image col-md-2 col-sm-3">
                                <img class="inspector-img" src="<?php echo IMG; ?>/r2.jpg">
                                <span class="name">LoriKezos</span>
                            </div>
                            <div class="comments col-md-10 col-sm-9">
                                <div class="inspector-comments">
                                    <div class="star-rating">
                                        <img class="rating" src="<?php echo IMG; ?>/star4.png"> 4/5
                                    </div>
                                    <div class="title">Amazing Review</div>
                                    <div class="comment">
                                        <p>"At the last second I clicked the wrong promo and was charged an extra hundred dollars because of my own mistake. I called the next morning and it was taken care of and refunded no questions asked! Great service. Thank you!!!"</p>
                                    </div>
                                </div>
                            </div>
                            <div class="review-image col-md-2 col-sm-12">
                            </div>
                            <div class="comments col-md-10 col-sm-12">
                                <div class="inspector-comments">
                                    <div class="detailed-ratings">
                                        <span>Detailed Ratings</span><a href="#">Show more +</a>
                                    </div>
                                    <div class="rating-info row">
                                        <div class="col-md-9 col-sm-12">
                                            <div class="rating-item">
                                                Pricing of products and services
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <img class="rating" src="<?php echo IMG; ?>/star5.png">
                                        </div>
                                    </div>
                                    <div class="rating-info row blur-text">
                                        <div class="col-md-9 col-sm-12">
                                            <div class="rating-item">
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <img class="rating" src="<?php echo IMG; ?>/star5.png">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php endwhile; endif; wp_reset_query(); ?>
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