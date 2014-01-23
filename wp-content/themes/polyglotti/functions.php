<?php

// Get the lesson pannel on admin interface
require 'lesson-pannel.php';

//require_once (ABSPATH . 'wp-content/themes/framework/theme.php');

//$theme = new Theme(array(
//    'name' => 'Polyglotti',
//    'slug' => 'polyglotti',
//    'options' => array(
//        array(
//            'name' => 'phrases',
//            'slug' => 'lessons',
//            'pages'=> array(
//                'Generale'=>'general'
//            )
//        )
//    )
//));

function add_menu_icons_styles(){
?>

<style>
#adminmenu .menu-icon-events div.wp-menu-image:before {
  content: "\f101";
}
</style>

<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );


function add_menu_icons_styles2(){
?>

<style>
#adminmenu .menu-icon-events div.wp-menu-image:before {
    content: "\f118";
}
</style>

<?php
}
add_action( 'admin_head', 'add_menu_icons_styles2' );

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

// Register Custom Post Type
function phrases() {

    $labels = array(
        'name'                => _x( 'Phrases', 'Post Type General Name', 'text_domain' ),
        'singular_name'       => _x( 'Phrase', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'           => __( 'Phrases', 'text_domain' ),
        'parent_item_colon'   => __( '', 'text_domain' ),
        'all_items'           => __( 'Toutes les phrases', 'text_domain' ),
        'view_item'           => __( '', 'text_domain' ),
        'add_new_item'        => __( 'Ajouter une nouvelle phrase', 'text_domain' ),
        'add_new'             => __( 'Nouvelle phrase', 'text_domain' ),
        'edit_item'           => __( 'Editer phrase', 'text_domain' ),
        'update_item'         => __( 'Mettre à jour phrase', 'text_domain' ),
        'search_items'        => __( 'Rechercher dans les phrases', 'text_domain' ),
        'not_found'           => __( 'Aucune phrase trouvée', 'text_domain' ),
        'not_found_in_trash'  => __( 'No phrases found in Trash', 'text_domain' ),
    );

    $rewrite = array(
        'slug'                => 'Phrases',
        'with_front'          => true,
        'pages'               => true,
        'feeds'               => true,
    );
    $args = array(
        'label'               => __( 'phrases', 'text_domain' ),
        'description'         => __( 'Phrase information pages', 'text_domain' ),
        'labels'              => $labels,
        'supports'            => array('title', 'custom-fields'),
        'taxonomies'          => array(),
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-admin-comments',
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'rewrite'             => $rewrite,
        'capability_type'     => 'page',
    );
    register_post_type('phrases', $args );
}

// Hook into the 'init' action
add_action( 'init', 'phrases', 0 );

// function wpc_champs_personnalises_defaut($post_id)
// {
//     if ($_GET['post_type'] == 'phrases') {
//         add_post_meta($post_id, 'Numéro de leçon', '', true);
//         add_post_meta($post_id, 'Chinois', '', true);
//         add_post_meta($post_id, 'Chinois - Pinyin', '', true);
//         add_post_meta($post_id, 'Anglais', '', true);
//     }
//     return true;
//  }

// add_action('wp_insert_post', 'wpc_champs_personnalises_defaut');
add_action( 'init', 'register_cpt_lesson' );

function register_cpt_lesson() {

    $labels = array(
        'name' => _x( 'Leçons', 'lesson' ),
        'singular_name' => _x( 'Leçon', 'lesson' ),
        'add_new' => _x( 'Nouvelle leçon', 'lesson' ),
        'add_new_item' => _x( 'Ajouter une nouvelle leçon', 'lesson' ),
        'edit_item' => _x( 'Editer la leçon', 'lesson' ),
        'new_item' => _x( 'Nouvelle leçon', 'lesson' ),
        'view_item' => _x( 'Voir la leçon', 'lesson' ),
        'search_items' => _x( 'Rechercher dans les leçons', 'lesson' ),
        'not_found' => _x( 'Pas de leçons trouvée', 'lesson' ),
        'not_found_in_trash' => _x( 'No leçons found in Trash', 'lesson' ),
        'parent_item_colon' => _x( 'Parent Leçon:', 'lesson' ),
        'menu_name' => _x( 'Leçons', 'lesson' ),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'Entrez ici les divers contenus de votre leçon, tel que les commentaires culturels et grammaticaux !',
        'supports' => array( 'title', 'editor', 'author' ),

        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 20,
        'menu_icon'     => 'dashicons-welcome-learn-more',

        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type('lesson', $args);
}

if(function_exists("register_field_group"))
{
    register_field_group(array (
        'id' => 'acf_polyglotti',
        'title' => 'polyglotti',
        'fields' => array (
            array (
                'key' => 'field_52dfe04a15396',
                'label' => 'Numéro leçon',
                'name' => 'lesson',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe07515397',
                'label' => 'fr',
                'name' => 'fr',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe07c39820',
                'label' => 'fr_audio',
                'name' => 'fr_audio',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe085f8cd8',
                'label' => 'fr_number',
                'name' => 'fr_number',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe08f53206',
                'label' => 'fr_people',
                'name' => 'fr_people',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe098cf5f5',
                'label' => 'fr_time',
                'name' => 'fr_time',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe0c7386bc',
                'label' => 'fr_quality',
                'name' => 'fr_quality',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe0d03bd5e',
                'label' => 'fr_actions',
                'name' => 'fr_actions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe0e39664c',
                'label' => 'fr_space',
                'name' => 'fr_space',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe0e86417c',
                'label' => 'fr_negative',
                'name' => 'fr_negative',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe0f2aa59b',
                'label' => 'fr_objects',
                'name' => 'fr_objects',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe1038e70b',
                'label' => 'fr_questions',
                'name' => 'fr_questions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe108b8f37',
                'label' => 'en',
                'name' => 'en',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe118aa1c5',
                'label' => 'en_audio',
                'name' => 'en_audio',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe1239cf8a',
                'label' => 'en_number',
                'name' => 'en_number',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe16088d61',
                'label' => 'en_people',
                'name' => 'en_people',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe168393a1',
                'label' => 'en_time',
                'name' => 'en_time',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe1701c15b',
                'label' => 'en_quality',
                'name' => 'en_quality',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe176acaae',
                'label' => 'en_actions',
                'name' => 'en_actions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe187f5b15',
                'label' => 'en_space',
                'name' => 'en_space',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe1918d55d',
                'label' => 'en_negative',
                'name' => 'en_negative',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe19ef736b',
                'label' => 'en_objects',
                'name' => 'en_objects',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe1b0c59db',
                'label' => 'en_questions',
                'name' => 'en_questions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe1def9045',
                'label' => 'ch',
                'name' => 'ch',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe1e70523e',
                'label' => 'ch_audio',
                'name' => 'ch_audio',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe1fbb0f1a',
                'label' => 'ch_number',
                'name' => 'ch_number',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe2010e6f1',
                'label' => 'ch_people',
                'name' => 'ch_people',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe2c79c9cf',
                'label' => 'ch_time',
                'name' => 'ch_time',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe2daf0390',
                'label' => 'ch_quality',
                'name' => 'ch_quality',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe2ecb4288',
                'label' => 'ch_actions',
                'name' => 'ch_actions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe2f5bf1d2',
                'label' => 'ch_space',
                'name' => 'ch_space',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe2fc8f330',
                'label' => 'ch_negative',
                'name' => 'ch_negative',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe3d696ed8',
                'label' => 'ch_objects',
                'name' => 'ch_objects',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe3dc40712',
                'label' => 'ch_questions',
                'name' => 'ch_questions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe3e65b22b',
                'label' => 'pin',
                'name' => 'pin',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe3edc0b19',
                'label' => 'pin_audio',
                'name' => 'pin_audio',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe3f550957',
                'label' => 'pin_number',
                'name' => 'pin_number',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe3fd287dc',
                'label' => 'pin_people',
                'name' => 'pin_people',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe428f05c5',
                'label' => 'pin_time',
                'name' => 'pin_time',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4305d318',
                'label' => 'pin_quality',
                'name' => 'pin_quality',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe43814121',
                'label' => 'pin_actions',
                'name' => 'pin_actions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4441c013',
                'label' => 'pin_space',
                'name' => 'pin_space',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe44d70918',
                'label' => 'pin_negative',
                'name' => 'pin_negative',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe45664c9d',
                'label' => 'pin_objects',
                'name' => 'pin_objects',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe468b3960',
                'label' => 'pin_questions',
                'name' => 'pin_questions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4719c00c',
                'label' => 'es',
                'name' => 'es',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe47982870',
                'label' => 'es_audio',
                'name' => 'es_audio',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe48324f6e',
                'label' => 'es_number',
                'name' => 'es_number',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe48d4dbcc',
                'label' => 'es_people',
                'name' => 'es_people',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe497e69d2',
                'label' => 'es_time',
                'name' => 'es_time',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4a1edcbe',
                'label' => 'es_quality',
                'name' => 'es_quality',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4aa1c3b1',
                'label' => 'es_actions',
                'name' => 'es_actions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4b7a6133',
                'label' => 'es_space',
                'name' => 'es_space',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4bff30f0',
                'label' => 'es_negative',
                'name' => 'es_negative',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4c9dd295',
                'label' => 'es_objects',
                'name' => 'es_objects',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4d130c39',
                'label' => 'es_questions',
                'name' => 'es_questions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4da6b008',
                'label' => 'hin',
                'name' => 'hin',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4e4db538',
                'label' => 'hin_audio',
                'name' => 'hin_audio',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4e8db539',
                'label' => 'hin_number',
                'name' => 'hin_number',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4f3db53a',
                'label' => 'hin_people',
                'name' => 'hin_people',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe4fcdb53b',
                'label' => 'hin_time',
                'name' => 'hin_time',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe505db53c',
                'label' => 'hin_quality',
                'name' => 'hin_quality',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe50ddb53d',
                'label' => 'hin_actions',
                'name' => 'hin_actions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe516db53e',
                'label' => 'hin_space',
                'name' => 'hin_space',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe520db53f',
                'label' => 'hin_negative',
                'name' => 'hin_negative',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe540db540',
                'label' => 'hin_objects',
                'name' => 'hin_objects',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe544db541',
                'label' => 'hin_questions',
                'name' => 'hin_questions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe57b82e9c',
                'label' => 'ru',
                'name' => 'ru',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe58482e9d',
                'label' => 'ru_audio',
                'name' => 'ru_audio',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe59382e9e',
                'label' => 'ru_number',
                'name' => 'ru_number',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe59982e9f',
                'label' => 'ru_people',
                'name' => 'ru_people',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe5a182ea0',
                'label' => 'ru_time',
                'name' => 'ru_time',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe5a982ea1',
                'label' => 'ru_quality',
                'name' => 'ru_quality',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe5b182ea2',
                'label' => 'ru_actions',
                'name' => 'ru_actions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe5ce82ea3',
                'label' => 'ru_space',
                'name' => 'ru_space',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe5d682ea4',
                'label' => 'ru_negative',
                'name' => 'ru_negative',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe5e182ea5',
                'label' => 'ru_objects',
                'name' => 'ru_objects',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe5ea82ea6',
                'label' => 'ru_questions',
                'name' => 'ru_questions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe6f02c3d4',
                'label' => 'po',
                'name' => 'po',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe6fa2c3d5',
                'label' => 'po_audio',
                'name' => 'po_audio',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7032c3d6',
                'label' => 'po_number',
                'name' => 'po_number',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe70a2c3d7',
                'label' => 'po_people',
                'name' => 'po_people',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7132c3d8',
                'label' => 'po_time',
                'name' => 'po_time',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe71d2c3d9',
                'label' => 'po_quality',
                'name' => 'po_quality',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7262c3da',
                'label' => 'po_actions',
                'name' => 'po_actions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe72e2c3db',
                'label' => 'po_space',
                'name' => 'po_space',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe73b2c3dc',
                'label' => 'po_negative',
                'name' => 'po_negative',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7452c3dd',
                'label' => 'po_objects',
                'name' => 'po_objects',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe76b2c3de',
                'label' => 'po_questions',
                'name' => 'po_questions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe78b3b868',
                'label' => 'ar',
                'name' => 'ar',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7933b869',
                'label' => 'ar_audio',
                'name' => 'ar_audio',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7a03b86a',
                'label' => 'ar_number',
                'name' => 'ar_number',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7a93b86b',
                'label' => 'ar_people',
                'name' => 'ar_people',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7b03b86c',
                'label' => 'ar_time',
                'name' => 'ar_time',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7b93b86d',
                'label' => 'ar_quality',
                'name' => 'ar_quality',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7c13b86e',
                'label' => 'ar_actions',
                'name' => 'ar_actions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7c93b86f',
                'label' => 'ar_space',
                'name' => 'ar_space',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7d03b870',
                'label' => 'ar_negative',
                'name' => 'ar_negative',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7da3b871',
                'label' => 'ar_objects',
                'name' => 'ar_objects',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7e53b872',
                'label' => 'ar_questions',
                'name' => 'ar_questions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe7ef3b873',
                'label' => 'de',
                'name' => 'de',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe80408994',
                'label' => 'de_audio',
                'name' => 'de_audio',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe80c08995',
                'label' => 'de_number',
                'name' => 'de_number',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe81408996',
                'label' => 'de_people',
                'name' => 'de_people',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe81a08997',
                'label' => 'de_time',
                'name' => 'de_time',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe82008998',
                'label' => 'de_quality',
                'name' => 'de_quality',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe82508999',
                'label' => 'de_actions',
                'name' => 'de_actions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe82b0899a',
                'label' => 'de_space',
                'name' => 'de_space',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe8360899b',
                'label' => 'de_negative',
                'name' => 'de_negative',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe8540899c',
                'label' => 'de_objects',
                'name' => 'de_objects',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe85d0899d',
                'label' => 'de_questions',
                'name' => 'de_questions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe8630899e',
                'label' => 'it',
                'name' => 'it',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe887fcc36',
                'label' => 'it_audio',
                'name' => 'it_audio',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe88dfcc37',
                'label' => 'it_number',
                'name' => 'it_number',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe89cfcc38',
                'label' => 'it_people',
                'name' => 'it_people',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe8a3fcc39',
                'label' => 'it_time',
                'name' => 'it_time',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe8a9fcc3a',
                'label' => 'it_quality',
                'name' => 'it_quality',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe8b0fcc3b',
                'label' => 'it_actions',
                'name' => 'it_actions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe8b7fcc3c',
                'label' => 'it_space',
                'name' => 'it_space',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe8bffcc3d',
                'label' => 'it_negative',
                'name' => 'it_negative',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe8c5fcc3e',
                'label' => 'it_objects',
                'name' => 'it_objects',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
            array (
                'key' => 'field_52dfe8cdfcc3f',
                'label' => 'it_questions',
                'name' => 'it_questions',
                'type' => 'text',
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'formatting' => 'html',
                'maxlength' => '',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'phrases',
                    'order_no' => 0,
                    'group_no' => 0,
                ),
            ),
        ),
        'options' => array (
            'position' => 'normal',
            'layout' => 'default',
            'hide_on_screen' => array (
            ),
        ),
        'menu_order' => 0,
    ));
}

if(function_exists("register_field_group"))
{
    register_field_group(array (
        'id' => 'acf_contenu-de-lecon',
        'title' => 'Contenu de Leçon',
        'fields' => array (
            array (
                'key' => 'field_52e0e4f559fce',
                'label' => 'Numéro de leçon',
                'name' => 'numero_de_lecon',
                'type' => 'number',
                'instructions' => 'Le numéro de la leçon, utilisé pour faciliter les requêtes, à ne pas modifier !',
                'required' => 1,
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'min' => '',
                'max' => '',
                'step' => '',
            ),
            array (
                'key' => 'field_52e0e3d84700d',
                'label' => 'Commentaires Culturels',
                'name' => 'commentaires_culturels',
                'type' => 'wysiwyg',
                'default_value' => '',
                'toolbar' => 'full',
                'media_upload' => 'yes',
            ),
            array (
                'key' => 'field_52e0e4064700e',
                'label' => 'Commentaires Grammaticaux',
                'name' => 'commentaires_grammaticaux',
                'type' => 'wysiwyg',
                'default_value' => '',
                'toolbar' => 'full',
                'media_upload' => 'yes',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'lesson',
                    'order_no' => 0,
                    'group_no' => 0,
                ),
            ),
        ),
        'options' => array (
            'position' => 'normal',
            'layout' => 'default',
            'hide_on_screen' => array (
                0 => 'the_content',
            ),
        ),
        'menu_order' => 0,
    ));
}


function generate_lesson($number)
{
    echo "test";
    if(!($query = new WP_Query(array('post_type' => 'lesson'))))
        echo "erreur";

    for($i = 1; $i <= $number; $i++)
    {
        while($query->have_posts())
        {
            $query->the_post();

            if(get_post_meta($post->ID, "n_lecon", true) != $i)
            {
                $my_post = array(
                  'post_title'            => "Leçon ". $i,
                  'post_status'           => 'publish',
                  'post_type'             => 'lessons',
                  'post_author'           => $user_ID,
                  'ping_status'           => get_option('default_ping_status'),
                  'post_parent'           => 0,
                  'menu_order'            => 0,
                  'lang'                  => 'fr',
                  'to_ping'               =>  '',
                  'pinged'                => '',
                  'post_password'         => '',
                  'guid'                  => '',
                  'post_content_filtered' => '',
                  'post_excerpt'          => '',
                  'import_id'             => 0
                );

                $postid = wp_insert_post($my_post);
                add_post_meta($postid, "n_lecon", $i);
            }
        }

        $query->rewind_posts();
    }
}
