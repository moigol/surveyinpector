<?php if ( ! dynamic_sidebar('main-sidebar') ) : ?>
    <div class="widget_text widget">
    	<div class="widget-title"><h3>Top 5 Reviews</h3></div>
    	<div class="textwidget custom-html-widget">
    		<?php 
            $args = array(
                'posts_per_page' => 5,
                'orderby' => 'meta_value_num',
                'meta_key' => 'wp_review_review_count',
                'order' => 'DESC'
            );
            $the_query = new WP_Query( $args );
            if ( $the_query->have_posts() ) { 
                while ( $the_query->have_posts() ) { $the_query->the_post();

                $schema     = get_post_meta( $post->ID, 'wp_review_schema_options', true );
                $website    = $schema['WebSite'];
                ?>
	    		<a class="top-review" href="<?php echo the_permalink(); ?>">
					<img src="<?php echo $website['image']['url']; ?>">
					<span><?php the_title(); ?></span>
				</a>
				<?php
                }
            }
            wp_reset_query(); 
            ?>			
		</div>
	</div>
	<div class="widget">
		<div class="widget-title"><h3>Categories</h3></div>
		<ul>
			<?php
	        $cats = get_categories(); 
	        foreach( $cats as $cat ) {
	            echo '<li class="cat-item cat-item-'. $cat->term_id .'"><a href="'. get_category_link($cat->term_id) .'">'. $cat->name . '</a></li>';
	        }
	        ?>
        </ul>
	</div>
<?php endif; ?>