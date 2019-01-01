<?php
/**
 * The header template.
 * 
 * @package wp-beirut-customizer
 * @since   1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php wp_head(); ?>
				<?php
		// check if post or page types before doing the infinit scroll 
		if (!is_single() || !is_page()):
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
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
 
            function loadArticle(pageNumber){ 
            	    // show the preloader
            	    $('a#inifiniteLoader').show('fast');
            	    // call admin ajax best practice for wordpress ajax calls.
                    $.ajax({
                        url: "<?php bloginfo('wpurl') ?>/wp-admin/admin-ajax.php",
                        type:'POST',
                        data: "action=infinite_scroll&page_no="+ pageNumber + '&loop_file=loop', 
                        success: function(html){
                        	// on success before putting the info to html hide the preloader
                        	$('a#inifiniteLoader').hide('1000');
                            $("#content").append(html);   // This will be the div where our content will be loaded
                        }
                    });
                return false;
            }
		});
</script>
<?php endif; // end of condition check ?>
	</head>

<body <?php body_class(); ?>>

<div id="header">
<img src="<?php echo IMAGES ?>logo.png" />
<h1>InfinitScroll</h1>
</div>
