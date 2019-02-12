<?php get_header(); ?>
<section class="post">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="content-post-single">
                <?php if(have_posts()): while(have_posts()): the_post(); ?>
                    <?php 
                    $featured_img_url = get_field( 'website_image', $post->ID ); 

                    $site_1 = get_field( 'site_1', $post->ID ); 
                    $site_2 = get_field( 'site_2', $post->ID );

                    $detailed1      = get_post_meta( $site_1, 'wp_review_item', true );
                    $schema1        = get_post_meta( $site_1, 'wp_review_schema_options', true );
                    $website1       = $schema1['WebSite'];
                    $userreview1    = round( get_post_meta( $site_1, 'wp_review_user_reviews', true ) );
                    $comments1      = get_comments( array('post_id' => $site_1) );
                    $vote_total1    = count($comments1); 
                    $postinfo1      = get_post( $site_1 );

                    $detailed2      = get_post_meta( $site_2, 'wp_review_item', true );
                    $schema2        = get_post_meta( $site_2, 'wp_review_schema_options', true );
                    $website2       = $schema2['WebSite'];
                    $userreview2    = round( get_post_meta( $site_2, 'wp_review_user_reviews', true ) );
                    $comments2      = get_comments( array('post_id' => $site_2) );
                    $vote_total2    = count($comments2); 
                    $postinfo2      = get_post( $site_2 );

                    $alldetails = array();

                    foreach($detailed1 as $d1) {
                        $alldetails[$d1['wp_review_item_title']]['site1'] = round( $d1['wp_review_item_star'] );
                    }

                    foreach($detailed2 as $d2) {
                        $alldetails[$d2['wp_review_item_title']]['site2'] = round( $d2['wp_review_item_star'] );
                    }

                    // echo "<pre>";
                    // print_r($alldetails);
                    // echo "</pre>";
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
                            <?php foreach($alldetails as $key => $val ) { ?>
                            <div class="ratings">
                                <h4><?php echo $key; ?></h4>
                                <div class="row">
                                    <div class="col-md-3 col-xs-6">
                                        <span><?php echo $postinfo1->post_title; ?></span>
                                    </div>
                                    <div class="col-md-9 col-xs-6">
                                        <img class="star" src="<?php echo IMG; ?>/star<?php echo ($val['site1']) ? $val['site1'] : 0;?>.png">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-xs-6">
                                        <span><?php echo $postinfo2->post_title; ?></span>
                                    </div>
                                    <div class="col-md-9 col-xs-6">
                                        <img class="star" src="<?php echo IMG; ?>/star<?php echo ($val['site2']) ? $val['site2'] : 0;?>.png">
                                    </div>
                                </div>
                            </div>
                            <?php } ?>                            
                        </div>
                    </div>
                    <div class="rating-info">
                        <div class="title">Overall Ratings</div>
                        <div class="content">
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <div class="reviews">
                                        <div class="star"><img src="<?php echo IMG; ?>/star<?php echo $userreview1; ?>.png"></div>
                                        <div class="visit"><a class="btn btn-success" href="<?php echo $website1['url']; ?>" role="button" ><i class="fas fa-link"></i> <span>Visit</span></a></div>
                                        <div class="read"><a href="<?php echo get_the_permalink($site_1); ?>">Read Reviews</a></div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <div class="reviews">
                                        <div class="star"><img src="<?php echo IMG; ?>/star<?php echo $userreview2; ?>.png"></div>
                                        <div class="visit"><a class="btn btn-success" href="<?php echo $website2['url']; ?>" role="button" ><i class="fas fa-link"></i> <span>Visit</span></a></div>
                                        <div class="read"><a href="<?php echo get_the_permalink($site_2); ?>">Read Reviews</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; endif; wp_reset_query(); ?>
                </div>
            </div>
            <div class="col-md-3">
                <?php get_sidebar('compare'); ?>
            </div>
        </div>
    </div>  
</section>
<div class="clear"></div>

<?php get_footer(); ?>