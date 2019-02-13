<?php $theme_options = get_option('motheme_theme_options'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>    
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <?php 
        if(is_home()) { 
            //wp_title(''); 
        } else {
            echo '<title>';
            wp_title('');
            echo '</title>';
        }
        ?>
		<?php wp_head(); ?>        
    </head>

    <body <?php body_class(); ?> >
    	<header>
            <section class="header-top">
            	<div class="container">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <div class="logo">
                            <?php 
                                $custom_logo_id = get_theme_mod( 'custom_logo' );
                                $custom_logo_url = wp_get_attachment_image_url( $custom_logo_id , 'full' );
                                echo '<a href="'. site_url() .'" class="desktop"><img src="' . ( ($custom_logo_url) ? esc_url( $custom_logo_url ) : IMG.'/logo.png' ) . '" alt=""></a>';
                            ?>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
							
							<?php if(!is_front_page()) { ?>
                            <div class="search-form">                                
                                <i class="fa fa-search" aria-hidden="true"></i>
								<?php get_search_form(); ?>
                            </div>
							<?php }

                            $category_id = get_query_var( 'cat' ); ?>
							
                            <div class="category-search">
                                <div class="custom-select">
                                    <select name="search-by-category">
                                        <option value="<?php echo SITEURL; ?>">Categories</option>
                                        <?php                                        
                                        $cats = get_categories(); 
                                        foreach( $cats as $cat ) {
                                            $selected = ($category_id == $cat->term_id) ? 'selected="selected"' : '';
                                            echo '<option value="'. get_category_link($cat->term_id) .'" '.$selected.'>'. $cat->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <div class="login">
                                <?php wp_loginout(); ?>
                                 <?php 
                                 if(!is_user_logged_in()) {
                                     echo '<a class="signup" href="'.wp_registration_url().'">Signup</a>'; 
                                 }
                                 ?> 
                            </div>
                            <?php if(is_singular('post')) { ?>
                            <div class="write-a-review">
                                <a href="#commentform">Write Review <i class="fas fa-file-alt"></i></a>
                            </div>
                            <?php } else { 
                                if(is_user_logged_in()) { ?>
                                    <div class="write-a-review">
                                        <a href="/reviews">Write Review <i class="fas fa-file-alt"></i></a>
                                    </div>
                                <?php } else { ?>
                                    <div class="write-a-review">
                                        <a href="<?php echo wp_login_url( SITEURL.'/reviews' ); ?>">Write Review <i class="fas fa-file-alt"></i></a>
                                    </div>
                                <?php }
                            } ?>
                            
                        </div>
                    </div>
                </div>
            </section>
            <section class="header-top-mobile">
            	<div class="container">
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <div class="logo">
                            <?php 
                                echo '<a href="'. site_url() .'" class="mobile"><img src="' . ( ($theme_options['mobile_logo']) ? esc_url( $theme_options['mobile_logo'] ) : IMG.'/logo-mobile.png' ) . '" alt=""></a>';
                            ?>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <div class="mobile-menudiv">   
                                <div class="navbar-header siheader">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar1">
                                      <span class="sr-only">Toggle navigation</span>
                                      <span class="icon-bar"></span>
                                      <span class="icon-bar"></span>
                                      <span class="icon-bar"></span>
                                    </button>
                                </div>
                                <div id="navbar1" class="navbar-collapse collapse">
                                    <ul class="nav navbar-nav">
                                    <?php 
                                        $MainMenu = array(
                                            'container' => false, 
                                            'theme_location'  => 'main_menu',
                                            'menu_class'      => 'main-menu-item',
                                            'items_wrap' => __('%3$s')
                                        );
                                    wp_nav_menu( $MainMenu ); 
                                    ?>
                                </ul>
                              </div>                                  
                              <!--/.nav-collapse -->
                            </div>
                            <a href="javascript:void()" class="collapsed mobile-searchicon" data-toggle="collapse" data-target="#searchbar"><img src="<?php echo IMG.'/search-icon.png'; ?>"></a> 
                        </div>
                    </div>
                    
                </div>
                <div id="searchbar" class="search-form navbar-collapse collapse">                                
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <?php get_search_form(); ?>
                </div>
            </section>
            <section class="header-bottom">
            	<div class="container">
                    <div class="row">
                        <?php if(!is_front_page()) { ?>
                        <div class="col-md-9 col-sm-6 col-xs-12">
                        	<div class="bread-crumbs">
								<?php 
                                if( is_front_page() ) { 
                                    echo "Home";
                                } elseif( is_home() ) { 
                                    echo '<a href="'. SITEURL .'">Home</a> <span><i class="fa fa-chevron-right"></i></span>';
                                    echo 'Blog';
                                } elseif( is_page() ) {
                                	echo '<a href="'. SITEURL .'">Home</a> <span><i class="fa fa-chevron-right"></i></span>';
                                    the_title();
                                } elseif( is_singular( 'blog' ) ) {
                                    $terms = get_the_terms( get_the_ID(), 'blog-category' );
                                    echo '<a href="'. SITEURL .'">Home</a> <span><i class="fa fa-chevron-right"></i></span>';
                                    echo '<a href="'. SITEURL .'/blog-category/'.$terms[0]->slug.'">'.$terms[0]->name.'</a> <span><i class="fa fa-chevron-right"></i></span>';
                                    the_title(); 
                                } elseif( is_single() ) {
                                    $terms = get_the_terms( get_the_ID(), 'category' );
                                    echo '<a href="'. SITEURL .'">Home</a> <span><i class="fa fa-chevron-right"></i></span>';
                                    echo '<a href="'. SITEURL .'/'.$terms[0]->slug.'">'.$terms[0]->name.'</a> <span><i class="fa fa-chevron-right"></i></span>';
                                    the_title();                                
                                } elseif( is_tax('blog-category') ) { 
                                    $c = get_query_var( 'blog-category' );
                                    $term = get_term_by('slug', $c, 'blog-category');
                                    ?>
                                    <a href="<?php echo SITEURL; ?>">Home</a> <span><i class="fa fa-chevron-right"></i></span>
                                    <a href="<?php echo SITEURL; ?>/blogs/">Blog Category</a> <span><i class="fa fa-chevron-right"></i></span>
                                    <?php echo $term->name; ?>
                                <?php  
                                } elseif( is_archive() ) { 
                                    $c = get_query_var( 'cat' );
                                    $term = get_category( $c );
                                    ?>
                                    <a href="<?php echo SITEURL; ?>">Home</a> <span><i class="fa fa-chevron-right"></i></span>
                                    <a href="<?php echo SITEURL; ?>/reviews/">Reviews</a> <span><i class="fa fa-chevron-right"></i></span>
                                    <?php echo $term->name; ?>
                                <?php }
                                ?>
                           	</div>
                        </div>
                        <?php if(is_singular('post') || is_page_template( 'page-overview.php' )) { ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="date"><img class="clock" src="<?php echo IMG; ?>/clock.png"> Updated on <?php the_modified_date('m/d/Y'); ?></div>
                        </div>
                        <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </section>
        </header>