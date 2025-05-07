<?php get_header(); ?>

<?php 
	//Daten aus Access Datenbank abfragen für Auflistung von Bauteilen in bauarchiv-entry.php
	include('templates/abfrage-projektname-jahr.php');
?>
   
<main>
	<ul class="galerie">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<li>
				<figure>
					<?php
					$image = get_field('bild');
					if( $image ) {
						// Image variables.
						$url = $image['url'];
						$title = $image['title'];
						$alt = $image['alt'];
						$caption = $image['caption'];

						// Thumbnail size attributes.
						$size = 'thumbnail';
						$thumb = $image['sizes'][ $size ];
						$width = $image['sizes'][ $size . '-width' ];
						$height = $image['sizes'][ $size . '-height' ];

						// Begin caption wrap.
						if( $caption ) { 
							?><div class="wp-caption"><?php
						}
						?>
								<a href="<?php the_permalink(); ?>" title="<?= esc_attr($title); ?>">
									<img src="<?= esc_url($thumb); ?>" alt="<?= esc_attr($alt); ?>" />
								</a>
						<?php 
						// End caption wrap.
						if( $caption ) { 
							?>
								<p class="wp-caption-text"><?= esc_html($caption); ?></p>
							</div>
							<?php
						}
					} 
					?>
				</figure>
				<figcaption><?php the_title(); ?></figcaption>
			</li>
		<?php endwhile; endif; ?>
	</ul>
</main>

<?php get_footer(); ?>