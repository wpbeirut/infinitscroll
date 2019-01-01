# InfinitScroll
developed by Wordpress Beirut Community.

# Objective
Best practice in using infinitscroll on wordpress, and how to use the admin-ajax.php for ajax calls.

# how to clone it.
just clone the link such git clone (url) on your wp-content/themes folder.
go to apearance , themes , and activate the wpbeirut-infinitscroll theme.

## Building the Ajax Function
We will use WordPress' ajax functionality to make the call for this pagination. First we prepare the basic function for our pagination, please insert the following code to your theme's functions.php

`function wp_infinitepaginate(){ 
    $loopFile        = $_POST['loop_file'];
    $paged           = $_POST['page_no'];
    $posts_per_page  = get_option('posts_per_page');
 
    # Load the posts
    query_posts(array('paged' => $paged )); 
    get_template_part( $loopFile );
 
    exit;
}`
This function will be used to make the call for our pagination, basically we send two variables to this function via ajax, one is the page number and another is the file template we are going to use for our pagination. To enable this function to be used with WordPress ajax, we need the following code.

`add_action('wp_ajax_infinite_scroll', 'wp_infinitepaginate');           // for logged in user
add_action('wp_ajax_nopriv_infinite_scroll', 'wp_infinitepaginate');    // if user not logged in`

The default action for WordPress ajax would be wp_ajax_(our action name), hence why the name infinite_scroll being used in the code example. We need to add two actions, one for logged in users and another is for users that are not logged in.

Next we will need to build the ajax function that will send the two variables we need for our pagination. You can use WordPress hooks to insert this jQuery ajax function or straight away insert it into your theme header.php
`<script type="text/javascript">
function loadArticle(pageNumber) {
    $.ajax({
        url: "<?php bloginfo('wpurl') ?>/wp-admin/admin-ajax.php",
        type:'POST',
        data: "action=infinite_scroll&page_no="+ pageNumber + '&loop_file=loop', 
        success: function(html){
            $("#content").append(html);    // This will be the div where our content will be loaded
        }
    });
    return false;
}
</script>`

This will be the basic ajax call that we are going to make and we use "infinite_scroll" as our action name. WordPress will automatically call our function wp_infinitepaginate(); because we define it in our theme functions.php previously.

## Step 3 Determine When the User Scroll to Bottom of Page
To enable the infinite scroll pagination, we need to determine when the user hits the bottom of the page. This can be achieved easily via jQuery using the following code.

`<script type="text/javascript">
            $(window).scroll(function(){
                    if  ($(window).scrollTop() == $(document).height() - $(window).height()){
                          // run our call for pagination
                    }
            }); 
</script>`
Now we can know when the user hits the bottom of the page. Next we need to call the loadArticle function within the scroll function. I'm adding a counter to be used as the page number of our call.

`<script type="text/javascript">
            var count = 2;
            $(window).scroll(function(){
                    if  ($(window).scrollTop() == $(document).height() - $(window).height()){
                       loadArticle(count);
                       count++;
                    }
            }); 
 
            function loadArticle(pageNumber){    
                    $.ajax({
                        url: "<?php bloginfo('wpurl') ?>/wp-admin/admin-ajax.php",
                        type:'POST',
                        data: "action=infinite_scroll&page_no="+ pageNumber + '&loop_file=loop', 
                        success: function(html){
                            $("#content").append(html);   // This will be the div where our content will be loaded
                        }
                    });
                return false;
            }
</script>`
Each time the user scrolls to bottom of the page, the counter will increase and this will enable us to have the page number pass to our wp_infinitepage() function within our theme's functions.php. With the scroll and loadArticle functions, we can now do the ajax function call within our WordPress theme, but the result may not appear if we haven't defined the loop file within our theme folder.

## Step 4 Setting Up Our Theme
Most important thing, we need to setup the div that will hold the new content that's been requested using our ajax function. In the Twenty Ten theme, there is already a div we can use, which is the div with id="content" so we will include the div id in our ajax function. If you use other themes that don't wrap their loop in a div, you can simply wrap the loop function like the example code below to achieve the same result.


`<div id="content"> loop content </div>`
Next we will need a loop file for this. The Twenty Ten theme already has a loop file included, this is the main reason why I chose Twenty Ten for this example, because it is easier for anyone who wants to reference this later. If you don't have any loop.php, simply create a new loop file, and copy the loop function within your index.php into the new file and uploaded it into your theme's folder. For anyone using the Twenty Ten theme, you would want to comment out the pagination included in the file because we won't need it anymore (please refer to the tutorial source file on how to do this).

## Step 5 Adding Ajax Loader
This is optional, just to give a nice touch to our infinite scroll pagination. We will add an ajax loader image as we hit the bottom of the page. You can add the following code to your footer.php

<a id="inifiniteLoader">Loading... <img src="<?php bloginfo('template_directory'); ?>/images/ajax-loader.gif" /></a>
and then add the following CSS to your stylesheet.

`a#inifiniteLoader{
    position: fixed;  
    z-index: 2;  
    bottom: 15px;   
    right: 10px; 
    display:none;
}`
Next we will add a few lines of code to our jQuery function to show and hide this ajax loader element.

`<script type="text/javascript">
      jQuery(document).ready(function($) {
          var count = 2;
          $(window).scroll(function(){
                  if  ($(window).scrollTop() == $(document).height() - $(window).height()){
                     loadArticle(count);
                     count++;
                  }
          }); 
 
          function loadArticle(pageNumber){    
                  $('a#inifiniteLoader').show('fast');
                  $.ajax({
                      url: "<?php bloginfo('wpurl') ?>/wp-admin/admin-ajax.php",
                      type:'POST',
                      data: "action=infinite_scroll&page_no="+ pageNumber + '&loop_file=loop', 
                      success: function(html){
                          $('a#inifiniteLoader').hide('1000');
                          $("#content").append(html);    // This will be the div where our content will be loaded
                      }
                  });
              return false;
          }
   
      });
      
  </script>`
  
The ajax loader will be shown once the user hits the bottom of the page and will be hide once the ajax request has finished.

## Step 6 Additional Limitation to Enhance the Infinite Scroll
Up till now, we already have a working infinite scroll, but one thing is missing. The function will keep triggering each time a user hits the bottom page even though there are no more post to be shown. This is something we don't want to have. We will add a control in our scroll function so when there no more pages to be shown, it will stop.

`<script type="text/javascript">
var count = 2;
var total = <?php echo $wp_query->max_num_pages; ?>;
$(window).scroll(function(){
    if  ($(window).scrollTop() == $(document).height() - $(window).height()){
        if (count > total){
            return false;
        }else{
            loadArticle(count);
        }
        count++;
    }
}); 
</script>`

We add a new var total to the function which will return the total pages available on our site. This will be used to ensure no additional calls will be made to the page if the maximum page has been reached. Another thing we would want to add is a restriction where this function will be loaded. We just want this on our home page, archive or maybe search, but not on our single post and page. So we wrap a simple PHP if else function in our jQuery code.


`if (!is_single() || !is_page()):
// our jQuery function here
endif;`
That's pretty much everything you need for the pagination, please refer to the source files for example code used in this tutorial. The files are based on the Twenty Ten theme.