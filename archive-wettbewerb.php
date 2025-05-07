<?php get_header(); ?>

<main class="wettbewerbe">
	<div class="bigbox">
	
		<ul class="galerie">
		
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
				<li>
					<a href="<?php the_permalink(); ?>">
						<?php 
						$image = get_field('bild');
						if( !empty( $image ) ): ?>
							<figure><img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class=""/></figure>
						<?php endif; ?>
						<h3><?php the_title(); ?></h3>
					</a>
					<div class="label-info">
						<?php if( get_field('datum') ): ?><div><?php echo esc_html( get_field('datum') ); ?></div><?php endif; ?>
						<?php if( get_field('ort') ): ?><div><?php echo esc_html( get_field('ort') ); ?></div><?php endif; ?>
						<?php if( get_field('wb-ergebnis') ): ?><div><?php echo esc_html( get_field('wb-ergebnis') ); ?></div><?php endif; ?>
					</div>
				</li>
			<?php endwhile; endif; ?>
		</ul>
	
	</div>
</main>

<?php get_footer(); ?>