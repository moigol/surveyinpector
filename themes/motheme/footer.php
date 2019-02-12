		<footer>
        	<div id="footer">
            	<div class="container">
                    <div class="row">
                    	<div class="col-md-3 col-xs-12">
                            <div class="f-logo">
                                <?php 
                                    $custom_logo_id = get_theme_mod( 'custom_logo' );
                                    $custom_logo_url = wp_get_attachment_image_url( $custom_logo_id , 'full' );
									echo '<a href="'. site_url() .'"><img src="' . ( ($custom_logo_url) ? esc_url( $custom_logo_url ) : IMG.'/logo.png' ) . '" alt=""></a>';
                                ?>
                            </div>
                        </div>
                   	</div>
                  	<div class="row">
						<?php if ( ! dynamic_sidebar('footer-widget') ) : ?>
						
                        <?php endif; ?>
                    </div>
				</div>
            </div>
        </footer>
        <p class="totop"> 
            <a href="#top"><img src="<?php echo IMG; ?>/scrolltotop.png"></a> 
        </p>
    	<?php wp_footer(); ?>
    </body>
</html>