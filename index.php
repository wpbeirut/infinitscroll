<?php
/**
 * The main index template.
 * 
 * @package wp-beirut-customizer
 * @since   1.0.0
 */
?>
<?php get_header(); ?>
	
  <section id="content" class="latest-block">
    <?php 
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); 
		//
		// Post Content here
		//
		?>
		<div class="card-wrapper">
  <div class="img-banner"><img src="https://images.unsplash.com/photo-1543872981-578a0310c83a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80"></div>
  
  <div class="main-content">
    <div class="header"><?php echo get_the_title() ?></div>
  </div>
  <div class="btn">Read More</div>
  <div class="footer"></div>
</div>
		<?php
	} // end while
} // end if
?>
    
    
    </section>
	
<?php get_footer(); ?>