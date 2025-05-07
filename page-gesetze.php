<?php /* Template Name: page-gesetze */ ?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
   
	<main class="">
		<div class="bigbox ">
																			
			<?php 
			// args
			$args = array(
				'numberposts'   => -1,
				'meta_key'      => 'autor-kurz',
				'orderby'       => 'meta_value',
				'post_type'     => 'dokumentation',
				'category_name' => 'gesetze',	
			);
			// query
			$the_query = new WP_Query( $args );
			if( $the_query->have_posts() ) {
				include('templates/fp-liste-gesetze.php');				
			}
			wp_reset_query();
			?>
		
		</div>
	</main>
    
<?php endwhile; endif; ?>
 
<?php get_footer(); ?>