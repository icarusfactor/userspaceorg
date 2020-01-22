<?php 

// kill the admin nag ,snce Im stuck at version 5.x of php on host I cant upgrade past 5.1.x. So disabling this.
if (!current_user_can('edit_users')) {
	add_action('init', create_function('$a', "remove_action('init', 'wp_version_check');"), 2);
	add_filter('pre_option_update_core', create_function('$a', "return null;"));
}



// remove aggregate links from head, being a curator we only gather aggregate information and not create content.
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);



// Use Wordpress default title manager.
add_theme_support( 'title-tag' );

//adds a custom route for search
//https://benrobertson.io/wordpress/wordpress-custom-search-endpoint

function curator_register_search_route() {
   
    register_rest_route('wp/v2', '/search', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'curator_ajax_search',
        'args' => curator_get_search_args()
    ]);
}

//Enable SVG support for upload to media library.The images still need to be sanitized.
function cc_mime_types($mimes) {
 $mimes['svg'] = 'image/svg+xml';
 return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');



// Add custom css CLASS and jquery launcher in ID for collection menu
function add_collection_menu_atts( $atts, $item, $args ) {
    // check if the item is in the collection menu
    if( $args->theme_location == 'collection' ) {
      // add the desired attributes:
      $atts['class'] = 'collections';
      $atts['id'] = $item->title; 
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'add_collection_menu_atts', 10, 3 );


function register_menu() {
register_nav_menu('social',__( 'social' ));
register_nav_menu('collection',__( 'collection' ));
}
add_action( 'init', 'register_menu' );



add_action( 'rest_api_init', 'curator_register_search_route');

function curator_ajax_search( $request ) {
    $posts = [];
    $results = [];
    // check for a search term
 
    if( isset($request['s'])) :
        // get posts 

        $args = [
            'post_type' => array( 'post', 'page'), 
            's' => $request['s'], 
            'posts_per_page' => 10, 
            'paged' => $request['page']
        ];
        $query = new WP_Query( $args );
        $posts = $query->posts;

        $total = $query->found_posts;
        $totalPages = $query->max_num_pages;

        foreach($posts as $post):  
           
            $item = [
                'id' => $post->ID,
                'author_name' => get_the_author_meta('display_name', $post->post_author),                
                'slug' => $post->post_name,
                'type' => $post->post_type,
                'title' => array(
                    'rendered' => $post->post_title
                ),
                'content' => array(
                    'rendered' => $post->post_content
                ),
                'excerpt' => array(
                    'rendered' => $post->post_excerpt
                ),
            ];

            $categories = get_the_category($post->ID);
                     
            if(!empty($categories[0])){  
                $catArr = array();
                $catArr[] = $categories[0]->term_id;
                $item['category_name'] = $categories[0]->name; 
                $item['categories'] = $catArr;              
            }           

            $results[] = $item;
        endforeach; 

    endif;

    // if( empty($results) ) :
    //     return new WP_Error( 'front_end_ajax_search', 'No results');
    // endif;

    $response = new WP_REST_Response( $results );
    $response->header( 'X-WP-Total', $total);
    $response->header( 'X-WP-TotalPages', $totalPages );

    return $response;     
}

function curator_get_search_args() {

    $args = [];
    $args['s'] = [
       'description' => esc_html__( 'The search term.', 'curator_' ),
       'type'        => 'string',
   ]; 

   return $args;
}

function curator_allow_anonymous_comments() {
    return true;
}
add_filter('rest_allow_anonymous_comments','curator_allow_anonymous_comments');

function curator_add_to_post_api (){
    register_rest_field( 'post', 'author_name', array(
        'get_callback' => function( $post ) {
            return get_the_author_meta('display_name', $post['author']);
        }
    ));
    register_rest_field( 'post', 'category_name', array(
        'get_callback' => function( $post ) {
            $categories = get_the_category($post['id']);
            return $categories[0]->name; 
        }
    ));
}
add_action( 'rest_api_init', 'curator_add_to_post_api');

// Add filter for the page header Carousel Slider to show distros.  
add_filter('carousel_slider_load_scripts', 'carousel_slider_load_scripts');
function carousel_slider_load_scripts( $load_scripts ) {
	return true;
}


function uso_hook_javascript() {
 // if ( is_single ('100') ) { 
  ?>
  <!-- <?php echo get_template_directory_uri();  ?>  -->

<?php wp_enqueue_style( 'uso-style', get_stylesheet_uri() ); 
      // The main font for this theme. Baloo Bhai
      wp_enqueue_style( 'main-googlefont-theme', 'https://fonts.googleapis.com/css?family=Baloo+Bhai&display=swap' ,true ); ?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />

<?php echo '<link rel="stylesheet" href="' . get_template_directory_uri() . '/style.css" >' ?>

<?php echo '<link rel="stylesheet" href="' . get_template_directory_uri() . '/css/jquery.dataTables.min.css" >' ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<?php echo '<script src="'. get_template_directory_uri().'/js/dataTables.min.js"></script>' ?>
<?php echo '<script src="'. get_template_directory_uri().'/js/sparkle.min.js"></script>' ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  <?php
 // }
}
add_action( 'wp_head', 'uso_hook_javascript' );

// Register and setup Widget. 
if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Masthead Text',
    'before_widget' => '<div class = "mh_widget">',
    'after_widget' => '</div>'
  )
);

// Register and setup Widget. 
if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Banner Carousel',
    'before_widget' => '<div class = "bc_widget">',
    'after_widget' => '</div>'
  )
);

// Register and setup Widget. 
if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Main Attraction',
    'before_widget' => '<div class = "ma_widget">',
    'after_widget' => '</div>'
  )
);


// Register and setup Widget. 
if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Leftside Attraction',
    'before_widget' => '<div class = "ls_widget">',
    'after_widget' => '</div>'
  )
);

// Register and setup Widget. 
if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Rightside Attraction',
    'before_widget' => '<div class = "rs_widget">',
    'after_widget' => '</div>'
  )
);



// Register and setup Widget. 
if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Footer Citation',
    'before_widget' => '<div class = "copyleft">',
    'after_widget' => '</div>'
  )
);



/*--- Enqueue scripts and styles ---*/
function curator_scripts() {

	//wp_enqueue_script( 'curator-common', get_template_directory_uri() . '/js/common.js', array('jquery'), '20191202', true );
}
add_action( 'wp_enqueue_scripts', 'curator_scripts' );


// Making custom widget - need to make Words Quest a Widget

// Register and load the widget
function wpb_load_widget() {
    register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

// Creating the widget 
class wpb_widget extends WP_Widget {
 
function __construct() {
parent::__construct(
 
// Base ID of your widget
'wpb_widget', 
 
// Widget name will appear in UI
__('WPBeginner Widget', 'wpb_widget_domain'), 
 
// Widget description
array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain' ), ) 
);
}

// Creating widget front-end
 
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
 
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
 
// This is where you run the code and display the output
echo __( 'Hello, World!', 'wpb_widget_domain' );
echo $args['after_widget'];
}
         
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'wpb_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
     
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here

// End of Custom Widget