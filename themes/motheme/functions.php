<?php
// Definitions
define('THEME', 'mo');
define('PREFIX', THEME.'_');
define('PREMETA', '_'.THEME.'_');
define('THEMENAME', 'motheme');
define('SITEURL', get_bloginfo('url'));
define('THEMEURL', get_bloginfo('template_url')); // Or TEMPLATEPATH (UNIX)
define('IMG', THEMEURL.'/img');
define('JS', THEMEURL.'/js'); 
define('CSS', THEMEURL.'/css'); 

// Add scripts
function mo_frontend_scripts() {	
	wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' );    
	wp_enqueue_style( 'font-awesome','https://use.fontawesome.com/releases/v5.6.3/css/all.css' );
	wp_enqueue_style( 'google-font','https://fonts.googleapis.com/css?family=Open+Sans:400,700' );
	wp_enqueue_style( 'slick', get_stylesheet_directory_uri() . '/slick/slick.css' );
	wp_enqueue_style( 'slicktheme', get_stylesheet_directory_uri() . '/slick/slick-theme.css' );
	wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/style.css?v=1.0.34' );
	wp_enqueue_style( 'review', get_stylesheet_directory_uri() . '/review-graph.css?v=1.0.5' );
	wp_enqueue_style( 'mo-responsive', get_stylesheet_directory_uri() . '/responsive.css?v=1.0.30' );	
	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js', array('jquery'), '3.3.4' );
	wp_enqueue_script( 'font-awesome', 'https://use.fontawesome.com/releases/v5.0.6/js/all.js', array('jquery'), '5.0.6' );
	wp_enqueue_script( 'customjs', JS.'/custom.js?v=1.0.30' );
	wp_enqueue_script( 'tabsscroll', JS.'/tabsscroll.js' );
	wp_enqueue_script( 'jQslick',THEMEURL.'/slick/slick.js');
	wp_enqueue_script( 'footercustom', JS.'/footercustom.js', array('jquery'), '1.0.30', true);
}
add_action('wp_enqueue_scripts', 'mo_frontend_scripts');

// Register Menus
if (function_exists('register_nav_menus')) {
	register_nav_menus(
		array(
		  'main_menu' => 'Main Menu'
		)
	);
}

// Register Sidebars
if(function_exists('register_sidebar')) {	
	register_sidebar(array(
		'id'=> 'main-sidebar',
		'name'=> 'Main Sidebar', 
		'description' => '', 
		'before_widget' => '<div class="widget">', 
		'after_widget' => '</div>',
		'before_title' => '<div class="widget-title"><h3>', 
		'after_title' => '</h3></div>'
	));

	register_sidebar(array(
		'id'=> 'comparison-sidebar',
		'name'=> 'Comparison Sidebar', 
		'description' => '', 
		'before_widget' => '<div class="widget">', 
		'after_widget' => '</div>',
		'before_title' => '<div class="comparison-title"><h3>', 
		'after_title' => '</h3></div>'
	));

	register_sidebar(array(
		'id'=> 'review-sidebar',
		'name'=> 'Review Sidebar', 
		'description' => '', 
		'before_widget' => '<div class="widget">', 
		'after_widget' => '</div>',
		'before_title' => '<div class="review-title"><h3>', 
		'after_title' => '</h3></div>'
	));

	register_sidebar(array(
		'id'=> 'footer-widget',
		'name'=> 'Footer Widget', 
		'description' => '', 
		'before_widget' => '<div class="col-md-3 col-xs-6 footer-column">', 
		'after_widget' => '</div>',
		'before_title' => '<h3>', 
		'after_title' => '</h3>'
	));
}

function mo_theme_setup() {
	// Add featured image support
	add_theme_support('post-thumbnails');
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 700,
		'flex-width' => true,
	) );
	
}
add_action('after_setup_theme', 'mo_theme_setup');

function mo_shorten_text( $text, $max_length = 140, $cut_off = '...', $keep_word = true )
{
    if(strlen($text) <= $max_length) {
        return $text;
    }

    if(strlen($text) > $max_length) {
        if($keep_word) {
            $text = substr($text, 0, $max_length + 1);

            if($last_space = strrpos($text, ' ')) {
                $text = substr($text, 0, $last_space);
                $text = rtrim($text);
                $text .=  $cut_off;
            }
        } else {
            $text = substr($text, 0, $max_length);
            $text = rtrim($text);
            $text .=  $cut_off;
        }
    }

    return $text;
}

function motheme_customize_register( $wp_customize )
{
	$wp_customize->add_section('motheme_mobile', array(
        'title'    => __('Mobile', 'motheme'),
        'description' => '',
        'priority' => 120,
    ));
 
    $wp_customize->add_setting('motheme_theme_options[mobile_logo]', array(
        'default'           => IMG.'/logo-mobile.png',
        'capability'        => 'edit_theme_options',
        'type'           => 'option',
 
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'mobile_logo', array(
        'label'    => __('Mobile Logo', 'motheme'),
        'section'  => 'motheme_mobile',
        'settings' => 'motheme_theme_options[mobile_logo]',
    )));
}
add_action( 'customize_register', 'motheme_customize_register' );

function mo_search_form( $html ) {
    $html = str_replace( 'name="s"', 'name="s" placeholder="Search for site review or category"', $html );
    return $html;
}
add_filter( 'get_search_form', 'mo_search_form' );

// Register Custom Post Type
function mo_blogs_post() {
	$labels = array(
		'name'                  => _x( 'Blogs', 'Post Type General Name', 'motheme' ),
		'singular_name'         => _x( 'Blog', 'Post Type Singular Name', 'motheme' ),
		'menu_name'             => __( 'Blogs', 'motheme' ),
		'name_admin_bar'        => __( 'Blogs', 'motheme' ),
		'archives'              => __( 'Item Archives', 'motheme' ),
		'attributes'            => __( 'Item Attributes', 'motheme' ),
		'parent_item_colon'     => __( 'Parent Item:', 'motheme' ),
		'all_items'             => __( 'All Items', 'motheme' ),
		'add_new_item'          => __( 'Add New Item', 'motheme' ),
		'add_new'               => __( 'Add New', 'motheme' ),
		'new_item'              => __( 'New Item', 'motheme' ),
		'edit_item'             => __( 'Edit Item', 'motheme' ),
		'update_item'           => __( 'Update Item', 'motheme' ),
		'view_item'             => __( 'View Item', 'motheme' ),
		'view_items'            => __( 'View Items', 'motheme' ),
		'search_items'          => __( 'Search Item', 'motheme' ),
		'not_found'             => __( 'Not found', 'motheme' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'motheme' ),
		'featured_image'        => __( 'Featured Image', 'motheme' ),
		'set_featured_image'    => __( 'Set featured image', 'motheme' ),
		'remove_featured_image' => __( 'Remove featured image', 'motheme' ),
		'use_featured_image'    => __( 'Use as featured image', 'motheme' ),
		'insert_into_item'      => __( 'Insert into item', 'motheme' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'motheme' ),
		'items_list'            => __( 'Items list', 'motheme' ),
		'items_list_navigation' => __( 'Items list navigation', 'motheme' ),
		'filter_items_list'     => __( 'Filter items list', 'motheme' ),
	);
	$rewrite = array(
		'slug'                  => 'blog',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Blog', 'motheme' ),
		'description'           => __( 'Blog posts', 'motheme' ),
		'labels'                => $labels,
		'supports' 				=> array('title','editor','author','excerpt','comments','revisions'),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'post',
	);
	register_post_type( 'blog', $args );

	$labels = array(
		'name'              => _x( 'Review Categories', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Review Category', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Review Categories', 'textdomain' ),
		'all_items'         => __( 'All Review Categories', 'textdomain' ),
		'parent_item'       => __( 'Parent Review Category', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Review Category:', 'textdomain' ),
		'edit_item'         => __( 'Edit Review Category', 'textdomain' ),
		'update_item'       => __( 'Update Review Category', 'textdomain' ),
		'add_new_item'      => __( 'Add New Review Category', 'textdomain' ),
		'new_item_name'     => __( 'New  Review Category Name', 'textdomain' ),
		'menu_name'         => __( ' Review Category', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'review-category' ),
	);

	register_taxonomy( 'review-category', array( 'post' ), $args );

	flush_rewrite_rules();
}
add_action( 'init', 'mo_blogs_post', 0 );

function mo_unregister_category_tags() {
    unregister_taxonomy_for_object_type( 'post_tag', 'post' );
    unregister_taxonomy_for_object_type( 'category', 'post' );
}
add_action( 'init', 'mo_unregister_category_tags' );

function mo_change_post_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Reviews';
    $submenu['edit.php'][5][0] = 'Reviews';
    $submenu['edit.php'][10][0] = 'Add Reviews';
    $submenu['edit.php'][16][0] = 'Reviews Tags';
}
function mo_change_post_object() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Reviews';
    $labels->singular_name = 'Reviews';
    $labels->add_new = 'Add Reviews';
    $labels->add_new_item = 'Add Reviews';
    $labels->edit_item = 'Edit Reviews';
    $labels->new_item = 'Reviews';
    $labels->view_item = 'View Reviews';
    $labels->search_items = 'Search Reviews';
    $labels->not_found = 'No Reviews found';
    $labels->not_found_in_trash = 'No Reviews found in Trash';
    $labels->all_items = 'All Reviews';
    $labels->menu_name = 'Reviews';
    $labels->name_admin_bar = 'Reviews';
}
 
add_action( 'admin_menu', 'mo_change_post_label' );
add_action( 'init', 'mo_change_post_object' );

function mo_compares_post() {
	$labels = array(
		'name'                  => _x( 'Comparisons', 'Post Type General Name', 'motheme' ),
		'singular_name'         => _x( 'Comparison', 'Post Type Singular Name', 'motheme' ),
		'menu_name'             => __( 'Comparisons', 'motheme' ),
		'name_admin_bar'        => __( 'Comparisons', 'motheme' ),
		'archives'              => __( 'Item Archives', 'motheme' ),
		'attributes'            => __( 'Item Attributes', 'motheme' ),
		'parent_item_colon'     => __( 'Parent Item:', 'motheme' ),
		'all_items'             => __( 'All Items', 'motheme' ),
		'add_new_item'          => __( 'Add New Item', 'motheme' ),
		'add_new'               => __( 'Add New', 'motheme' ),
		'new_item'              => __( 'New Item', 'motheme' ),
		'edit_item'             => __( 'Edit Item', 'motheme' ),
		'update_item'           => __( 'Update Item', 'motheme' ),
		'view_item'             => __( 'View Item', 'motheme' ),
		'view_items'            => __( 'View Items', 'motheme' ),
		'search_items'          => __( 'Search Item', 'motheme' ),
		'not_found'             => __( 'Not found', 'motheme' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'motheme' ),
		'featured_image'        => __( 'Featured Image', 'motheme' ),
		'set_featured_image'    => __( 'Set featured image', 'motheme' ),
		'remove_featured_image' => __( 'Remove featured image', 'motheme' ),
		'use_featured_image'    => __( 'Use as featured image', 'motheme' ),
		'insert_into_item'      => __( 'Insert into item', 'motheme' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'motheme' ),
		'items_list'            => __( 'Items list', 'motheme' ),
		'items_list_navigation' => __( 'Items list navigation', 'motheme' ),
		'filter_items_list'     => __( 'Filter items list', 'motheme' ),
	);
	$rewrite = array(
		'slug'                  => 'compare',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Comparison', 'motheme' ),
		'description'           => __( 'Comparison posts', 'motheme' ),
		'labels'                => $labels,
		'supports' 				=> array('title','editor','author','excerpt','comments','revisions'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'post',
	);
	register_post_type( 'compare', $args );

	flush_rewrite_rules();
}
add_action( 'init', 'mo_compares_post', 0 );

function default_comments_on( $data ) {
    if( $data['post_type'] == 'post' ) {
        $data['comment_status'] = "open";
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'default_comments_on' );

function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
	  	return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
	  	return array();
	}
}

function init_rating_schema($postID, $image, $title) {
	global $wpdb;
	$tablePrefix = $wpdb->prefix;
	$sql = "SELECT COUNT(*) AS ratingCount, AVG(`{$tablePrefix}commentmeta`.`meta_value`) as ratingValue
			FROM `{$tablePrefix}comments` 
			INNER JOIN `{$tablePrefix}commentmeta` ON `{$tablePrefix}commentmeta`.`comment_id` = `{$tablePrefix}comments`.`comment_ID`
			WHERE `{$tablePrefix}comments`.`comment_post_ID` = $postID 
				AND `{$tablePrefix}comments`.`comment_type` = 'wp_review_comment' 
				AND `{$tablePrefix}commentmeta`.`meta_key` = 'wp_review_comment_rating' 
				AND `{$tablePrefix}comments`.`comment_approved` = 1";

	$results = $wpdb->get_results($sql);

	if (!empty($results)) {
		$ratingCount = $results[0]->ratingCount;
		$ratingValue = $results[0]->ratingValue;

		$markup = array(
			'@context'					=> 'http://schema.org',
			'@type'							=> 'LocalBusiness',
			'name'							=> $title,
			'image'							=> $image,
			'aggregateRating'		=> array(
				'@type'				=> 'AggregateRating',
				'bestRating'	=> 5,
				'ratingCount'	=> $ratingCount,
				'ratingValue'	=> number_format($ratingValue, 2)
			)
		);

		printf( '<script type="application/ld+json">%s</script>', wp_json_encode( $markup ) );
	}
}

function set_posts_to_blog() {
	global $wpdb;
	$wpdb->query( "
		UPDATE $wpdb->comments 
		SET comment_karma = 1, comment_approved = 1" );
}
//set_posts_to_blog();