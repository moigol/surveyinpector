<?php 
/*
Template Name: Review Page V2
*/
get_header(); 
?>
<section class="post">
	<div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="content-post-single">
                    <?php if(have_posts()): while(have_posts()): the_post(); ?>                    
                    <div class="review-overview">
                        <div class="row">
                            <div class="overview-item-image col-md-5 col-sm-12">
                                <img src="<?php echo IMG; ?>/b5.jpg">
                            </div>
                            <div class="overview-item-data col-md-7 col-sm-12">
                                <div class="overview-title">
                                    <h2>GrabPoints.com</h2>
                                    <div class="cat-page">Get Paid To</div>
                                    <div class="review-counts"><a href="#">0 <span>Reviews</span></a></div>
                                </div>
                                <div class="overview-info">
                                    <div class="overview-description">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elites sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad </p>
                                    </div>
                                    <div class="overview-description-rating">
                                        <div class="overview-ratings">
                                            <img src="<?php echo IMG; ?>/starb4.png">
                                            <span class="ratings-total">4.05<span class="overall">/5</span></span>
                                        </div>
                                        <div class="visit"><a class="btn btn-success" href="#" role="button" ><i class="fas fa-link"></i> <span>Visit</span></a></div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="quick-rate">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <h5>Quik Rate</h5><img src="<?php echo IMG; ?>/star0.png">
                            </div>
                        </div>
                    </div>

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