<?php /* Template Name: page-fortbildungenn */ ?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
   
	<main class="">
		<div class="bigbox ">
																			
			<?php 
			// args
			$args = array(
				'numberposts'   => -1,
				'post_type'     => 'dokumentation',
				'category_name' => 'fortbildungen',	
			);
			// query
			$the_query = new WP_Query( $args );
			if( $the_query->have_posts() ) {
				//include('templates/fp-galerie.php');
				include('templates/fp-liste-fortbildungen.php');	
				//include('templates/fp-liste.php');						
			}
			wp_reset_query();   // Restore global post data stomped by the_post().
			?>
						
		</div>
	</main>
    
<?php endwhile; endif; ?>
 
<?php get_footer(); ?>