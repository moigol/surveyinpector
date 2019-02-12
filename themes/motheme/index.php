<?php 
get_header(); 

$s = get_query_var( 's', 1 ); 
$c = get_query_var( 'cat' );
?>
<section class="post">
    <div class="container">
        <div class="row">

            <div class="menu-wrapper category-tabs">
                <ul class="scrollmenu">
                    <li class="item <?php echo ($c) ? '' : 'active'; ?>"><a href="/blog">All</a></li>
                    <?php
                    $category_id = 0;
                    if(!is_home()) {
                        $categories = get_the_category();
                        $category_id = $categories[0]->cat_ID;
                    }

                    $cats = get_categories(); 
                    foreach( $cats as $cat ) {
                        $active = ($c == $cat->term_id) ? 'active' : '';
                        echo '<li class="item '. $active .'"><a href="'. get_category_link($cat->term_id) .'">'. $cat->name . '</a></li>';
                    }
                    ?>
                </ul>
                
                <!--
                <div class="paddles">
                    <button class="left-paddle paddle hidden"><i class="fa fa-chevron-left"></i></button>
                    <button class="right-paddle paddle "><i class="fa fa-chevron-right"></i></button>
                </div>
                -->
            </div>

        </div>
        <div class="row">
            
            <?php 
            $args = array(
                        'post_type' => 'blog',
                        'posts_per_page' => -1
                    );

            
            if($c) {
                $category = get_category( $c );
                $args['tax_query'] = array(
                                array(
                                    'taxonomy' => 'category',
                                    'terms' => $category->slug,
                                    'field' => 'slug',
                                    'include_children' => true,
                                    'operator' => 'IN'
                                )
                            ); 
            }

            if($s) {
                $args['s'] = $s;
            }
            
            $the_query = new WP_Query( $args );

            // The Loop
            if ( $the_query->have_posts() ) { while ( $the_query->have_posts() ) { $the_query->the_post();  ?>
            <div class="col-md-4 col-xs-12 blogitems">
                <div class="content-post">
                    <?php
                    $the_title = apply_filters( 'the_title', $post->post_title);
                    $the_content = apply_filters( 'the_content', $post->post_content);
                    $featured_img_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                    ?>
                    <div class="image">
                        <img src="<?php echo ($featured_img_url) ? esc_url( $featured_img_url ) : IMG.'/b1.jpg' ; ?>" />
                    </div>
                    <div class="post-info">
                    <a class="head-title" href="<?php echo the_permalink(); ?>"><h3><?php echo mo_shorten_text( $the_title, 90 ); ?></h3></a>
                    <?php echo mo_shorten_text( $the_content, 145, '<div class="read-more"><a class="read-more-btn" href="'. get_the_permalink() .'">Read More</a></div>' ); ?>
                    </div>
                </div>
            </div>
            <?php } } wp_reset_query(); ?>
        </div>
        <div class="row">
            <div class="view-articles">
                <a id="loadMore" class="load-btn" href="#">See Older Articles <i class="fas fa-angle-down"></i></a>
            </div>
        </div>
        <hr class="line" />
    </div>  
</section>
<div class="clear"></div>
<?php get_footer(); ?>