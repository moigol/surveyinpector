<?php get_header(); ?>
<section class="post">
	<div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="content-post-single">
                    <?php if(have_posts()): while(have_posts()): the_post(); ?>
                    <a class="head-title-single" href="<?php echo the_permalink(); ?>">
                        <h2><?php the_title(); ?></h2>
                        <div class="date-and-author">
							<?php the_author(); ?> | <?php the_date(); ?>
                        </div>
                    </a>                    
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <?php echo the_content(); ?>
                        </div>
                        </div>
                    </div>
                    <?php endwhile; endif; wp_reset_query(); ?>

                    <div class="row">
                        <div class="col-md-12 col-xs-12 other-articles">
                            <h4>Older Articles</h4>
                        </div>
                    
                        <?php 
                        $args = array(
                            'post_type' => 'blog',
                            'posts_per_page' => 6
                        );
                        $posts_array = get_posts( $args ); 
                        foreach ( $posts_array as $post ) { setup_postdata( $post );
                            ?>
                            <div class="col-md-4 col-xs-12">
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
				</div>
            </div>
            <div class="col-md-3">
                <?php get_sidebar(); ?>
            </div>
        </div>
	</div>
</section>
<div class="clear"></div>

<?php get_footer(); ?>