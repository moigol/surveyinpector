<?php 
/*
Template Name: Homepage
*/
get_header(); 
?>
<section class="post">
    <div class="container">
        <div class="row">
            <div class="no-padding col-md-12 col-xs-12">
                <div class="research-site">
                    <h1>Research and Browse Top Survey Sites</h1>
                    <div class="home-search-form">
                        <div class="search-button">                                
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </div>
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="no-padding col-md-12 col-xs-12">
                <div class="updated-sites">
                    <div class="site-list">
                        <h2>Top Rated</h2>
                        <div class="site-items row">
                            
                            <?php 
                            $args = array(
                                'posts_per_page' => 3,
                                'orderby' => 'meta_value_num',
                                'meta_key' => 'wp_review_review_count',
                                'order' => 'DESC'
                            );
                            $the_query = new WP_Query( $args );
                            if ( $the_query->have_posts() ) { 
                                while ( $the_query->have_posts() ) { $the_query->the_post();

                                $userreview  = round( get_post_meta( get_the_ID(), 'wp_review_user_reviews', true ) );
                                $vote_total  = get_post_meta( get_the_ID(), 'wp_review_review_count', true ); //count($comments);

                                $schema     = get_post_meta( $post->ID, 'wp_review_schema_options', true );
                                $website    = $schema['WebSite'];
                                ?>
                                <div class="col-md-4 col-xs-12">
                                    <div class="site-item">
                                        <?php
                                        $the_title = apply_filters( 'the_title', $post->post_title);
                                        $the_content = apply_filters( 'the_content', $post->post_content);
                                        $featured_img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                                        ?>
                                        <a class="featuted-image" href="<?php echo the_permalink(); ?>">
                                            <img src="<?php echo ($website['image']['url']) ? $website['image']['url'] : IMG.'/b1.jpg' ; ?>" />
                                        </a>
                                        <div class="site-info">
                                            <div class="site-title"><?php echo $the_title; ?></div>
                                            <div class="review-info">
                                                <img class="home-top-rated-star" src="<?php echo IMG.'/star'.$userreview.'.png'; ?>" /> 
                                                <a href="<?php echo the_permalink(); ?>"><?php echo $vote_total; ?> <span>Reviews</span></a>
                                            </div>
                                            <div class="categories">
                                            <?php 
                                            $terms = get_the_terms( get_the_ID(), 'category' );
                                            $cntr = 0;
                                            foreach($terms as $term) {
                                                $cntr++;
                                                echo '<a href="/'.$term->slug.'">'.$term->name.'</a>';
                                                echo $cntr < count($terms) ? ', ' : '';
                                            } ?>                                                
                                            </div>
                                            <!--a class="head-title" href="<?php echo the_permalink(); ?>"><h3><?php echo mo_shorten_text( $the_title, 90 ); ?></h3></a-->
                                        <?php //echo mo_shorten_text( $the_content, 145, '<div class="read-more"><a class="read-more-btn" href="'. get_the_permalink() .'">Read More</a></div>' ); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                            }
                            wp_reset_query(); 
                            ?>
                            <div class="col-md-12 col-xs-12">
                                <div class="browse-more"><a href="/reviews">Browse More <i class="fas fa-angle-right"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="no-padding col-md-12 col-xs-12">
                <div class="updated-sites">
                    <div class="site-list">
                        <h2>Latest/Newest Sites</h2>
                        <div class="site-items row">

                            <?php 
                            $args = array(
                                'posts_per_page' => 3
                            );
                            $the_query = new WP_Query( $args );
                            if ( $the_query->have_posts() ) { 
                                while ( $the_query->have_posts() ) { $the_query->the_post();

                                $userreview  = round( get_post_meta( get_the_ID(), 'wp_review_user_reviews', true ) );
                                $vote_total  = get_post_meta( get_the_ID(), 'wp_review_review_count', true ); //count($comments);

                                $schema     = get_post_meta( $post->ID, 'wp_review_schema_options', true );
                                $website    = $schema['WebSite'];
                                ?>
                                <div class="col-md-4 col-xs-12">
                                    <div class="site-item">
                                        <?php
                                        $the_title = apply_filters( 'the_title', $post->post_title);
                                        $the_content = apply_filters( 'the_content', $post->post_content);
                                        $featured_img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                                        ?>
                                        <a class="featuted-image" href="<?php echo the_permalink(); ?>">
                                            <img src="<?php echo ($website['image']['url']) ? $website['image']['url'] : IMG.'/b1.jpg' ; ?>" />
                                        </a>
                                        <div class="site-info">
                                            <div class="site-title"><?php echo $the_title; ?></div>
                                            <div class="review-info">
                                                <img class="home-top-rated-star" src="<?php echo IMG.'/star'.$userreview.'.png'; ?>" /> 
                                                <a href="<?php echo the_permalink(); ?>"><?php echo $vote_total; ?> <span>Review(s)</span></a>
                                            </div>
                                            <div class="categories">
                                            <?php 
                                            $terms = get_the_terms( get_the_ID(), 'category' );
                                            $cntr = 0;
                                            foreach($terms as $term) {
                                                $cntr++;
                                                echo '<a href="/'.$term->slug.'">'.$term->name.'</a>';
                                                echo $cntr < count($terms) ? ', ' : '';
                                            } ?>                                             
                                            </div>
                                            <!--a class="head-title" href="<?php echo the_permalink(); ?>"><h3><?php echo mo_shorten_text( $the_title, 90 ); ?></h3></a-->
                                        <?php //echo mo_shorten_text( $the_content, 145, '<div class="read-more"><a class="read-more-btn" href="'. get_the_permalink() .'">Read More</a></div>' ); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                }
                            }
                            wp_reset_query(); 
                            ?>
                            <div class="col-md-12 col-xs-12">
                                <div class="browse-more"><a href="/reviews">Browse More <i class="fas fa-angle-right"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="latest-review">
    <div class="container-fluid gray-background">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <h2>Top Rated and New</h2>
                </div>
            </div>
        </div>
        
        <div class="latest-reviews row">
            <div class="col-md-2 col-sm-4 col-xs-12">
            <?php 
                // args
                $args = array(
                    'orderby' => 'comment_ID',
                    'order' => 'DESC'
                );

                // get comments
                $comments = get_comments($args);

                if($comments) { $cntr = 0;
                    foreach($comments as $comment) { $cntr++;
                        $img = esc_url( get_avatar_url( $comment->user_id ) );
                        $vote = ( get_comment_meta( $comment->comment_ID, 'wp_review_comment_rating', true ) ) ? get_comment_meta( $comment->comment_ID, 'wp_review_comment_rating', true ) : 1;
                        $tema = ( get_comment_meta( $comment->comment_ID, 'wp_review_comment_title', true ) ) ? get_comment_meta( $comment->comment_ID, 'wp_review_comment_title', true ) : '';
                        $help = get_comment_meta( $comment->comment_ID, 'wp_review_comment_helpful', true );
                       
                        ?>
                        <div class="review">
                            <img class="image" src="<?php echo $img; ?>"> <img class="star-rating" src="<?php echo IMG; ?>/rev-<?php echo round( $vote ); ?>stars.png"><br>
                            <div class="name"><?php echo $comment->comment_author; ?></div><span class="rev">reviewed</span>
                            <div class="website"><?php echo $tema; ?></div>
                            <div class="comments">“<?php echo mo_shorten_text( $comment->comment_content, 60 ); ?></div>
                        </div>
                        <?php
                        echo ($cntr % 2) ? '' : '</div><div class="col-md-2 col-sm-4 col-xs-12">';
                    }
                }
            ?>
            </div>
        </div>
    </div>
</section>

<section class="post">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="heading">
                    <h2>How To Guides</h2>
                    <span>Contrary to popular belief Lorem Ipsum is not simply random.</span>
                </div>
            </div>
            <?php 
            $args = array(
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'blog-category',
                        'terms' => 'how-to-guides',
                        'field' => 'slug',
                        'include_children' => true,
                        'operator' => 'IN'
                    )
                ),
                'post_type' => 'blog'
            );
            $posts_array = get_posts( $args ); 
            foreach ( $posts_array as $post ) { setup_postdata( $post );
                ?>
                <div class="col-md-4 col-xs-12 hblogitems">
                    <div class="content-post">
                        <?php
                        $the_title = apply_filters( 'the_title', $post->post_title);
                        $the_content = apply_filters( 'the_content', $post->post_content);
                        $featured_img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                        ?>
                        <div class="image">
                            <img src="<?php echo ($featured_img_url) ? esc_url( $featured_img_url ) : IMG.'/b2.jpg' ; ?>" />
                        </div>
                        <div class="post-info">
                        <a class="head-title" href="<?php echo the_permalink(); ?>"><h3><?php echo mo_shorten_text( $the_title, 90 ); ?></h3></a>
                        <?php echo mo_shorten_text( $the_content, 145, '<div class="read-more"><a class="read-more-btn" href="'. get_the_permalink() .'">Read More</a></div>' ); ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            wp_reset_query();
            ?>
        </div>

        <div class="row">
            <div class="view-articles">
                <!--a id="hloadMore" class="load-btn" href="/blog/">See more Smart Buyer Tips <i class="fas fa-angle-down"></i></a-->
                <a class="load-btn" href="/blog-category/how-to-guides/">See more Smart Buyer Tips <i class="fas fa-angle-down"></i></a>
            </div>
        </div>
        <hr class="line" />

    </div>  
</section>
<div class="clear"></div>

<?php get_footer(); ?>