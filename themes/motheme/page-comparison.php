<?php 
/*
Template Name: Comparison Page
*/
get_header(); 
?>z

<section class="post">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="content-post-single">
                <?php if(have_posts()): while(have_posts()): the_post(); ?>
                    <?php
                    $featured_img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                    ?>
                    <div class="featured-image">
                        <img src="<?php echo ($featured_img_url) ? esc_url( $featured_img_url ) : IMG.'/b1.jpg' ; ?>" />
                    </div>
                    <a class="head-title-page" href="<?php echo the_permalink(); ?>">
                        <h2><?php the_title(); ?></h2>
                    </a>
                    
                    <?php //echo the_content(); ?>
                    <div class="rating-info">
                        <div class="title">Detailed Ratings</div>
                        <div class="content">
                            <div class="ratings">
                                <h4>Rewards</h4>
                                <div class="row">
                                    <div class="col-md-3 col-xs-6">
                                        <span>Grabpoints.com</span>
                                    </div>
                                    <div class="col-md-9 col-xs-6">
                                        <img class="star" src="<?php echo IMG; ?>/star5.png">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-6">
                                        <span>InstaGC.com</span>
                                    </div>
                                    <div class="col-md-9 col-xs-6">
                                        <img class="star" src="<?php echo IMG; ?>/star1.png">
                                    </div>
                                </div>
                            </div>
                            <div class="ratings">
                                <h4>Earning Potetials</h4>
                                <div class="row">
                                    <div class="col-md-3 col-xs-6">
                                        <span>Grabpoints.com</span>
                                    </div>
                                    <div class="col-md-9 col-xs-6">
                                        <img class="star" src="<?php echo IMG; ?>/star5.png">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-6">
                                        <span>InstaGC.com</span>
                                    </div>
                                    <div class="col-md-9 col-xs-6">
                                        <img class="star" src="<?php echo IMG; ?>/star1.png">
                                    </div>
                                </div>
                            </div>
                            <div class="ratings">
                                <h4>Payout Options</h4>
                                <div class="row">
                                    <div class="col-md-3 col-xs-6">
                                        <span>Grabpoints.com</span>
                                    </div>
                                    <div class="col-md-9 col-xs-6">
                                        <img class="star" src="<?php echo IMG; ?>/star5.png">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-6">
                                        <span>InstaGC.com</span>
                                    </div>
                                    <div class="col-md-9 col-xs-6">
                                        <img class="star" src="<?php echo IMG; ?>/star1.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rating-info">
                        <div class="title">Overall Ratings</div>
                        <div class="content">
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <div class="reviews">
                                        <div class="star"><img src="<?php echo IMG; ?>/star3.png"></div>
                                        <div class="visit"><a class="btn btn-success" href="#" role="button" ><i class="fas fa-link"></i> <span>Visit</span></a></div>
                                        <div class="read"><a href="">Read Reviews</a></div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="reviews">
                                        <div class="star"><img src="<?php echo IMG; ?>/star5.png"></div>
                                        <div class="visit"><a class="btn btn-success" href="#" role="button" ><i class="fas fa-link"></i> <span>Visit</span></a></div>
                                        <div class="read"><a href="">Read Reviews</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; endif; wp_reset_query(); ?>
                </div>
            </div>
            <div class="col-md-3">
                <?php get_sidebar('comparison'); ?>
            </div>
        </div>
    </div>  
</section>
<div class="clear"></div>

<?php get_footer(); ?>