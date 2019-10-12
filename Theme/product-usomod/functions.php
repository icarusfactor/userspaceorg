<?php

/*--- Basic setup function ---*/
function product_setup() {
	
	//Register a theme image size.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'product-large-thumb', 830 );
	add_image_size( 'product-medium-thumb', 550, 400, true );
	add_image_size( 'product-small-thumb', 230 );
	
	//Register nav menu used in theme.
	register_nav_menus( array(
		'primary' 	=> __( 'Primary Menu', 'product' )
	) );
	
	//Enable support for Post Formats.
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );
	
	//Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
	
	//Theme support for custom background feature.
	$args = array(
		'default-color' => 'ffffff',
		'default-image' => '',
	);
	add_theme_support( 'custom-background', $args );
	
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );
	
	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'       => 250,
		'height'      => 250,
		'flex-width'  => true,
	) );

}
add_action( 'after_setup_theme', 'product_setup' );


/*--- Blog layout ---*/
function product_blog_layout() {
	$layout = get_theme_mod( 'theme_blog_type' , 'special' );
	return $layout;
}


/*--- Show meta info for the post ---*/
function product_posted_on() {
	$layout = product_blog_layout();
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		_x( '%s', 'post date', 'product' ),
		'<i class="fa fa-calendar"></i><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( '%s', 'post author', 'product' ),
		'<i class="fa fa-user"></i><span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);
	$tags = get_the_tags();
	$singletag = '';
	$tagsline = '';
	
	if( $tags) :
		
		foreach( $tags as $tag ){
			
			$singletag .= '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">'. esc_html( $tag->name ) .'</a>';
		}
		$tagsline = '<span class="tags"><i class="fa fa-tag"></i><span class="blog-category-url">'.$singletag .'</span></span>';
		
	endif;
	
	$category = get_the_category();
	$singlecategory = '';
	$categoryline = '';
	$comments = '';
	if($category) :
		foreach( $category as $cat ){
			
			$singlecategory .= '<a href="' . esc_url( get_tag_link( $cat->term_id ) ) . '">'. esc_html( $cat->name ) .'</a>';
		}
		$categoryline = '<span class="blog-category"><i class="fa fa-list"></i><span class="blog-category-url">' . $singlecategory . '</span></span>';
	endif;
	
	if($layout == 'special'){
		$comments = '<i class="fa fa-commenting"></i><span class="comment-count"><a href="' . esc_url( get_permalink() ) . '">' . esc_html(get_comments_number()) . ' comments</a></span>';
		
	}

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>' . $comments . $categoryline . $tagsline;
}


/*--- Changing excerpt more text ---*/
function product_excerpt_more($more) {
	global $post;
	$layout = product_blog_layout();
	return ' ....&nbsp;&nbsp;<a class=" '.$layout.'" href="'. get_permalink($post->ID) . '">' . __('Read More', 'product') . '</a>';
}
add_filter('excerpt_more', 'product_excerpt_more');


/*--- Enqueue scripts and styles ---*/
function product_scripts() {

        wp_enqueue_style( 'csshakestyle', get_template_directory_uri() . '/css/csshake.min.css' );	


	wp_enqueue_style( 'customizer-style', get_stylesheet_uri() );
	
	wp_enqueue_style( 'style', get_template_directory_uri() . '/css/style.css' );

        wp_enqueue_style( 'accordion8-css', get_template_directory_uri() . '/css/accordion8.css' );

	wp_enqueue_style( 'jquery-ui-css', get_template_directory_uri() . '/css/jquery-ui.css' );

        wp_enqueue_style( 'jquery-ui-theme-css', get_template_directory_uri() . '/css/jquery-ui.theme.min.css' );

        wp_enqueue_script( 'jquery-ui-js', get_template_directory_uri() . '/js/jquery-ui.js', array('jquery'), '20190913', true );

        wp_enqueue_script( 'modernizr-js', get_template_directory_uri() . '/js/modernizr-custom.js', array('jquery'), '20190913', true );

	wp_enqueue_script( 'product-common86', get_template_directory_uri() . '/js/common86.js', array('jquery'), '20180215', true );


        wp_enqueue_style( 'minitogglecss', get_template_directory_uri() . '/css/minitoggle.css' );

        wp_enqueue_script( 'minitogglejs', get_template_directory_uri() . '/js/minitoggle.js', array('jquery'), '20180213', false );	

	wp_enqueue_style( 'product-font-awesome', get_template_directory_uri() . '/fonts/font-awesome.min.css' );

    wp_enqueue_style( 'tablepress-css', get_template_directory_uri() . '/css/jquery.dataTables.min.css' );

    wp_enqueue_script( 'tablepress-js', get_template_directory_uri() . '/js/jquery.dataTables.min.js', array('jquery'), '20180213', false );

   wp_enqueue_style( 'animsition-css', get_template_directory_uri() . '/css/animsition.min.css' );

    wp_enqueue_script( 'animsition-js', get_template_directory_uri() . '/js/animsition-min.js', array('jquery'), '20180213',false );
    // The main font for this theme. Baloo Bhai
    wp_enqueue_style( 'main-googlefont-theme', 'https://fonts.googleapis.com/css?family=Baloo+Bhai&display=swap' ,true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
}
add_action( 'wp_enqueue_scripts', 'product_scripts' );

if ( ! isset( $content_width ) ) $content_width = 900;

// Enqueue Bootstrap
function product_enqueue_bootstrap() {
	wp_enqueue_style( 'product-bootstrap', get_template_directory_uri() . '/css/bootstrap/bootstrap.min.css', array(), true );
}
add_action( 'wp_enqueue_scripts', 'product_enqueue_bootstrap', 9 );



/*--- Register widget area ---*/
add_action( 'widgets_init', 'product_widgets_init' );
function product_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Main Sidebar', 'product' ),
        'id' => 'sidebar-1',
        'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'product' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>',
    ) );
	
	
	for ($i=1; $i<=3; $i++){
		register_sidebar( array(
			'name' => __( 'Footer-widget-area-', 'product' ) . $i,
			'id' => 'footer-widget-area' . $i,
			'description' => __( 'Widgets in this area will be shown on footer.', 'product' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );
	}
}


function product_edit_link() {
	edit_post_link(
		sprintf( __( '<i class="fa fa-edit"></i>Edit<span class="screen-reader-text"> "%s"</span>', 'product' ), get_the_title() ),
		'<span class="edit-link">',
		'</span>'
	);
}


/*--- Generate breadcrumbs ---*/
function product_get_breadcrumb() {
    echo '<a href="'.home_url().'" rel="nofollow">'.__('Home', 'product').'</a>';
    if ( is_category() || is_single() ) {
        echo "&nbsp;&nbsp;&#47;&nbsp;&nbsp;";
        the_category(' &bull; ');
            if (is_single()) {
                echo " &nbsp;&nbsp;&#47;&nbsp;&nbsp; ";
                the_title();
            }
    } elseif ( is_page() ) {
        echo "&nbsp;&nbsp;&#47;&nbsp;&nbsp;";
        echo the_title();
    } elseif ( is_search() ) {
        echo __("&nbsp;&nbsp;&#47;&nbsp;&nbsp;Search Results for... ", "product");
        echo '"<em>';
        echo the_search_query();
        echo '</em>"';
    }
}


/*--- Convert hex color with or without hash into rgb with alpha ---*/
function twx_hex2rgba( $hex_color, $alpha = 1 ) {

	$values = str_replace( '#', '', $hex_color );

	switch( strlen( $values ) ){
		case 3:
			list( $r, $g, $b ) = sscanf( $values, '%1s%1s%1s' );
			$rgba = 'rgba(' . hexdec( '$r$r' ) . ', ' . hexdec( '$g$g' ) . ', ' . hexdec( '$b$b' ) . ', ' . $alpha . ')';
			break;
		case 6:
			$rgb =  array_map('hexdec', sscanf( $values, '%2s%2s%2s' ));
			$rgba = 'rgba(' . implode( ",", $rgb ) . ',' . $alpha . ')';
			break;
		default:
			$rgba = false;
	}

	return $rgba;
}

// Custom-Header
require get_template_directory() . '/includes/custom-header.php';

// Customizer additions.
require get_template_directory() . '/includes/customizer.php';

// Banner background
require get_template_directory() . '/includes/banner-background.php';

// Styles
require get_template_directory() . '/includes/customizer-styles.php';


//Recommended plugins
require get_template_directory() . '/recommend/class-tgm-plugin-activation.php';

// Upsell
require get_template_directory() . '/upsell/class-customize.php';

//Demo content
require_once dirname( __FILE__ ) . '/dummy-data/dummy-data-setup.php';


/*--- Notice for recommended plugins ---*/
function product_recommended_plugin() {
 
	
	$plugins[] = array(
			'name'               => 'PageLayer',
			'slug'               => 'pagelayer',
			'required'           => false,
	);

    $plugins[] = array(
            'name'               => 'Contact Form 7',
            'slug'               => 'contact-form-7',
            'required'           => false,
    );

	$plugins[] = array(
            'name'               => 'One Click Demo Import',
            'slug'               => 'one-click-demo-import',
            'required'           => false,
    );

    tgmpa( $plugins);
 
}
add_action( 'tgmpa_register', 'product_recommended_plugin' );

// Update the theme
require_once dirname( __FILE__ ) . '/includes/popularfx.php';

// Enable update check on every request. Normally you don't need this! This is for testing only!
//set_site_transient('update_themes', null);
add_filter( 'pre_set_site_transient_update_themes', 'product_check_for_update' );
function product_check_for_update( $checked_data ){
	return popularfx_check_for_update(popularfx_get_current_theme_slug(__FILE__), $checked_data);
}

// Show the theme promo
popularfx_maybe_promo([
	'after' => 1,// In days
	'interval' => 10,// In days
	'pro_url' => 'https://popularfx.com/themes/wordpress/corporate/Product-pro',
	'rating' => 'https://popularfx.com/themes/wordpress/corporate/Product',
	'twitter' => 'https://twitter.com/',
	'facebook' => 'https://www.facebook.com/',
	'website' => 'http://suavethemes.com',
]);



add_filter('carousel_slider_load_scripts', 'carousel_slider_load_scripts');
function carousel_slider_load_scripts( $load_scripts ) {
	// To use only for front page
	if ( is_front_page() ) {
		return true;
	}
	return $load_scripts;
}

