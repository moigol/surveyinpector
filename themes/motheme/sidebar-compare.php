<?php global $post; ?>


<div class="widget_text widget comparison">
	<div class="comparison-title"><img src="<?php echo IMG; ?>/compare-icon.png"> <h3>Recent Comparison</h3></div>
	<div class="comparison-textwidget custom-html-widget">
		<?php 
        $args = array(
            'posts_per_page' => 5,
            'post_type' => 'compare',
       //      'meta_query' => array(
       //      					'relation' => 'OR',
							// 	array(
							// 		'key' => 'site_1',
							// 		'value' => $post->ID,
							// 		'compare' => 'IN'
							// 	),
							// 	array(
							// 		'key' => 'site_2',
							// 		'value' => $post->ID,
							// 		'compare' => 'IN'
							// 	)
							// )
        );
        $the_query = new WP_Query( $args );
        if ( $the_query->have_posts() ) { 
            while ( $the_query->have_posts() ) { $the_query->the_post();
	            $featured_img_url = get_field( 'website_image', $post->ID );
	            ?>
	            <div class="feat-img">
					<a href="<?php echo the_permalink(); ?>"><img src="<?php echo $featured_img_url; ?>"></a>
				</div>            
	            <?php
            }
        }
        wp_reset_query(); 
        ?>
	</div>
</div>

<?php if ( ! dynamic_sidebar('comparison-sidebar') ) : ?>

<?php endif; ?>