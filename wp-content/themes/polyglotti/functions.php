<?php

// Get the lesson pannel on admin interface
require 'lesson-pannel.php';


// Enable thumbnails
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size(200, 200, true); // Normal post thumbnails



// Remove the admin bar from the front end
add_filter( 'show_admin_bar', '__return_false' );


// Customise the footer in admin area
function wpfme_footer_admin () {
    echo 'Theme designed and developed by <a href="#" target="_blank">MMI 2013</a>.';
}
add_filter('admin_footer_text', 'wpfme_footer_admin');


// Set a maximum width for Oembedded objects
if ( ! isset( $content_width ) )
    $content_width = 660;


// Add default posts and comments RSS feed links to head
add_theme_support( 'automatic-feed-links' );


// Put post thumbnails into rss feed
function wpfme_feed_post_thumbnail($content) {
    global $post;
    if(has_post_thumbnail($post->ID)) {
        $content = '' . $content;
    }
    return $content;
}
add_filter('the_excerpt_rss', 'wpfme_feed_post_thumbnail');
add_filter('the_content_feed', 'wpfme_feed_post_thumbnail');


// Add custom menus
register_nav_menus( array(
    'primary' => __( 'Primary Navigation', 'wpfme' ),
    //'example' => __( 'Example Navigation', 'wpfme' ),
) );


// Randomly chosen placeholder text for post/page edit screen
function wpfme_writing_encouragement( $content ) {
    global $post_type;
    if($post_type == "post"){
        $encArray = array(
            // Placeholders for the posts editor
            "Test post message one.",
            "Test post message two.",
            "<h1>Test post heading!</h1>"
        );
        return $encArray[array_rand($encArray)];
    }
    else{ $encArray = array(
        // Placeholders for the pages editor
        "Test page message one.",
        "Test page message two.",
        "<h1>Test Page Heading</h1>"
    );
        return $encArray[array_rand($encArray)];
    }
}
add_filter( 'default_content', 'wpfme_writing_encouragement' );


//change amount of posts on the search page
function wpfme_search_results_per_page( $query ) {
    global $wp_the_query;
    if ( ( ! is_admin() ) && ( $query === $wp_the_query ) && ( $query->is_search() ) ) {
        $query->set( 'wpfme_search_results_per_page', 100 );
    }
    return $query;
}
add_action( 'pre_get_posts',  'wpfme_search_results_per_page'  );


//create a permalink after the excerpt
function wpfme_replace_excerpt($content) {
    return str_replace('[...]',
        '<a class="readmore" href="'. get_permalink() .'">suite...</a>',
        $content
    );
}
add_filter('the_excerpt', 'wpfme_replace_excerpt');


function wpfme_has_sidebar($classes) {
    if (is_active_sidebar('sidebar')) {
        // add 'class-name' to the $classes array
        $classes[] = 'has_sidebar';
    }
    // return the $classes array
    return $classes;
}
add_filter('body_class','wpfme_has_sidebar');


// Create custom sizes
// This is then pulled through to your theme useing the_post_thumbnail('custombig');
if ( function_exists( 'add_image_size' ) ) {
    add_image_size('customsmall', 300, 200, true); //narrow column
    add_image_size('custombig', 400, 500, true); //wide column
}


// Stop images getting wrapped up in p tags when they get dumped out with the_content() for easier theme styling
function wpfme_remove_img_ptags($content){
    return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', 'wpfme_remove_img_ptags');


// Call the google CDN version of jQuery for the frontend
// Make sure you use this with wp_enqueue_script('jquery'); in your header
function wpfme_jquery_enqueue() {
    wp_deregister_script('jquery');
    wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js", false, null);
    wp_enqueue_script('jquery');
}
if (!is_admin()) add_action("wp_enqueue_scripts", "wpfme_jquery_enqueue", 11);


//custom excerpt length
function wpfme_custom_excerpt_length( $length ) {
    //the amount of words to return
    return 20;
}
add_filter( 'excerpt_length', 'wpfme_custom_excerpt_length');


// Call Googles HTML5 Shim, but only for users on old versions of IE
function wpfme_IEhtml5_shim () {
    global $is_IE;
    if ($is_IE)
        echo '<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
}
add_action('wp_head', 'wpfme_IEhtml5_shim');


// Disable the theme / plugin text editor in Admin
define('DISALLOW_FILE_EDIT', true);



