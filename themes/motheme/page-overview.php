<?php 
/*
Template Name: Category Overview Page
*/
get_header(); 
$sortby = isset($_POST['sortby']) ? $_POST['sortby'] : false; 
?>
<section class="post">
	<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content-post-single">
                    <div class="other-content">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="title">
                                    <h2><?php echo get_field('header_title', $post->ID); ?></h2>
                                </div>
                                <div class="info"><?php echo get_field('header_content', $post->ID); ?></div>
                            </div>
                        </div>
                    </div>  

                    <div class="category-overview">
                        <div class="row">
                            <div class="col-md-9 col-xs-12">
                                <div class="title">Compare Reviews for Top Appliance Stores</div>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <div class="most-reviewed">
                                	<div class="custom-select">
                                        <form method="post">
                                            <select name="sortby" onchange="this.form.submit();">
                                                <option <?php echo ($sortby == 'TOP') ? 'selected' : ''; ?> value="TOP">Most Reviewed</option>
                                                <option <?php echo ($sortby == 'DESC') ? 'selected' : ''; ?> value="DESC">Most Recent</option>
                                                <option <?php echo ($sortby == 'ASC') ? 'selected' : ''; ?> value="ASC">Oldest</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <?php 
                            //$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                            $args = array(
                                'posts_per_page' => -1,
                                'orderby' => 'meta_value_num',
                                'meta_key' => 'wp_review_review_count',
                                'order' => 'ASC'
                            );

                            if($sortby) {
                                if($sortby == 'TOP') {
                                    $args['orderby'] = 'meta_value_num';
                                    $args['meta_key'] = 'wp_review_review_count';
                                    $args['order'] = 'ASC';
                                } else {
                                    $args['orderby'] = 'ID';
                                    $args['order'] = $sortby;
                                }
                            }

                            $the_query = new WP_Query( $args );
                            $limit = 5;
                            $counter = 0;
                            $pagecnt = 1;
                            if ( $the_query->have_posts() ) { 
                                while ( $the_query->have_posts() ) { $the_query->the_post(); $counter++;
                                    $userreview  = round( get_post_meta( get_the_ID(), 'wp_review_user_reviews', true ) );
                                    $vote_total  = get_post_meta( get_the_ID(), 'wp_review_review_count', true ); //count($comments);

                                    $schema     = get_post_meta( $post->ID, 'wp_review_schema_options', true );
                                    $website    = $schema['WebSite'];

                                    $the_title = apply_filters( 'the_title', $post->post_title);                                
                                    ?>
                                    <div class="overview page<?php echo $pagecnt; ?>">
                                        <div class="row">
                                            <div class="col-md-9 col-xs-12">
                                                <div class="review">
                                                    <h4><?php echo $the_title; ?></h4>
                                                    <img class="rating" src="<?php echo IMG.'/star'.$userreview.'.png'; ?>"> | <a class="total-reviews" href="#"><?php echo $vote_total; ?> REVIEWS</a>
                                                    <p>"<?php echo $website['description']; ?> “</p>
                                                    <div class="read mobile-hide"><a href="<?php echo the_permalink(); ?>">Read more</a></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-xs-12">
                                                <img class="overview-img" src="<?php echo ($website['image']['url']) ? $website['image']['url'] : IMG.'/co1.jpg' ; ?>" />
                                                <div class="visit"><a class="btn btn-success" href="<?php echo $website['url']; ?>" role="button" ><i class="fas fa-link"></i> <span>Visit</span></a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if($counter % $limit == 0) {
                                        $pagecnt++;
                                    }
                                }
                            }
                            wp_reset_query(); 
                            ?>                            
                        </div>

                        <div class="review-pagination center">
                            <div class="pagination">
                                <a href="page1" class="paginationitemfirst"><i class="fas fa-angle-double-left"></i></a>
                                <a href="#" class="paginationitemprev"><i class="fas fa-angle-left"></i></a>
                                <?php for($i = 1; $i <= $pagecnt; $i++) { ?>
                                <a href="page<?php echo $i; ?>" class="mobile-hide paginationitem"><?php echo $i; ?></a>
                                <?php } ?>
                                <a href="#" class="paginationitemnext"><i class="fas fa-angle-right"></i></a>
                                <a href="page<?php echo $pagecnt; ?>" class="paginationitemlast"><i class="fas fa-angle-double-right"></i></a>
                            </div>
                        </div>


                    </div>
                    <div class="clear"></div>

                    <div class="other-content">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <div class="title">
                                    <h2><?php echo get_field('bottom_title', $post->ID); ?></h2>
                                </div>
                                <div class="info"><?php echo get_field('bottom_content', $post->ID); ?></div>
                            </div>
                        </div>
                    </div>
				</div>
            </div>
		</div>
    </div>	
</section>
<div class="clear"></div>

<?php get_footer(); ?>