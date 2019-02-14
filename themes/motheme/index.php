<?php 
/*
Template Name: Review List
*/
get_header(); 

$s = get_query_var( 's', 1 ); 
$c = get_query_var( 'cat' );

$args = array(
            'post_type' => 'post',
            'posts_per_page' => -1
        );


$title = "All Reviews";

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

    $term = get_term_by('id', $c, 'category');
    $title = "All Reviews for category \"".$term->name."\"";
}

$first_tab = '<li class="item '. ( ($c) ? '' : 'active' ) .'"><a href="/reviews">All</a></li>';
if($s) {
    $args['s'] = $s;
    $title = "Search results for \"".$s."\"";
    $first_tab = '<li class="item active"><a href="#">Results</a></li><li class="item"><a href="/reviews">All</a></li>';
}

$the_query = new WP_Query( $args );
?>
<section class="post">
    <div class="container">
        <h3><?php echo $title;?></h3>
        <div class="row">

            <div class="menu-wrapper category-tabs">
                <ul class="scrollmenu">                    
                    <?php
                    echo $first_tab;

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
            </div>

        </div>
        <div class="row site-list">            
            <?php 
            // The Loop
            if ( $the_query->have_posts() ) { while ( $the_query->have_posts() ) { $the_query->the_post();  ?>
            <div class="col-md-4 col-xs-12 blogitems">

                <div class="site-item">
                    <?php
                    $userreview  = round( get_post_meta( get_the_ID(), 'wp_review_user_reviews', true ) );
                    $vote_total  = get_post_meta( get_the_ID(), 'wp_review_review_count', true ); //count($comments);

                    $the_title = apply_filters( 'the_title', $post->post_title);

                    $schema     = get_post_meta( $post->ID, 'wp_review_schema_options', true );
                    $website    = $schema['WebSite'];
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
                        if($terms) {
                            $cntr = 0;
                            foreach($terms as $term) {
                                $cntr++;
                                echo '<a href="/'.$term->slug.'">'.$term->name.'</a>';
                                echo $cntr < count($terms) ? ', ' : '';
                            } 
                        }
                        ?>                                            
                        </div>
                    </div>
                </div>
            </div>
            <?php } } wp_reset_query(); ?>
        </div>
        <div class="row">
            <div class="view-articles">
                <a id="loadMore" class="load-btn" href="#">See Older Reviews <i class="fas fa-angle-down"></i></a>
            </div>
        </div>
        <hr class="line" />
    </div>  
</section>
<div class="clear"></div>
<?php get_footer(); ?>