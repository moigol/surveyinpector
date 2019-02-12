<?php get_header(); ?>
<section class="post">
	<div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="content-post-single">
                <?php if(have_posts()): while(have_posts()): the_post(); ?>
                    <a class="head-title-single" href="<?php echo the_permalink(); ?>">
                        <h2><?php the_title(); ?></h2>
                    </a>
                    
                    <?php echo the_content(); ?>
                <?php endwhile; endif; wp_reset_query(); ?>
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