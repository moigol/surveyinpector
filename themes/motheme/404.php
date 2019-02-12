<?php get_header(); ?>
<section class="post">
	<div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="content-post-single">
               
                    <a class="head-title-single" href="<?php echo the_permalink(); ?>">
                        <h2>Page not found</h2>
                    </a>  
                    
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