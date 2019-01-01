<?php

/* define constant of the theme */
define("IMAGES", get_template_directory_uri() . "/images/");

/* adding jquery to our theme */
wp_enqueue_script("jquery"); 

/* connect the style.css of the theme */
function wp_beirut_infinitscroll_styles() {
    $parent_style = 'wordpress-beirut'; // This is 'parent-style' for wordpress beirut theme.
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts',  'wp_beirut_infinitscroll_styles');

/*
* We will use WordPress' ajax functionality to make the call for this pagination.
* This function will be used to make the call for our pagination, 
* basically we send two variables to this function via ajax, 
* one is the page number and another is the file template we are going to use for our pagination.
*
*/

function wp_infinitepaginate(){ 
    $loopFile        = $_POST['loop_file'];
    $paged           = $_POST['page_no'];
    $posts_per_page  = get_option('posts_per_page');
 
    # Load the posts
    query_posts(array('content_type'=>'posts','paged' => $paged )); 
    get_template_part( "templates/".$loopFile );
 
    exit;
}

add_action('wp_ajax_infinite_scroll', 'wp_infinitepaginate');           // for logged in user
add_action('wp_ajax_nopriv_infinite_scroll', 'wp_infinitepaginate');    // if user not logged in