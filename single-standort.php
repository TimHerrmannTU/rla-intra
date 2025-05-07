<?php get_header(); ?>

<main>
	<article class="bigbox dokumentation container_single-post">
		
		<?php if(get_field('bild')):
			$image = get_field('bild');
			$file = get_field("datei");
			if($file): ?>
				<a href="<?= esc_attr($file['url']); ?>" title="<?= esc_attr($file['title']); ?>" target="_blank">
			<?php endif; ?>
					<figure class="main-figure">
						<img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>" class="medium-img"/>
					</figure>
			<?php if($file): ?> 
				</a>
			<?php endif; ?>
		<?php endif; ?>	

		<div class="main-info">
			<div class="element-title">
				<h1><?php the_title(); ?></h1>
			</div>
			<section class="">
				<?php
				$featured_posts = get_field('büroleitung');
				if( $featured_posts ): ?>
					<span>
						Büroleitung:
						<?php foreach( $featured_posts as $key => $featured_post ): 
							$permalink = get_permalink( $featured_post->ID );
							$title = get_the_title( $featured_post->ID );
							$custom_field = get_field( 'field_name', $featured_post->ID );
							?>
							<a href="<?= esc_url( $permalink ); ?>"><?= esc_html( $title )?>
								<?php // nachfolgende Zeile muss in einer Zeile bleiben, da Zeilenumbruch im HTML sonst als Leerzeichen dargestellt wird ?>
								<span style="color:grey; font-size: 1.2rem; vertical-align: -10%; display: inline-block" class="dashicons dashicons-admin-users"></span></a><?php if(!($key === array_key_last($featured_posts))) echo ","; ?>
						<?php endforeach; ?>
					</span>
				<?php endif;?>
				
				<?php if( get_field('adresse') ): ?><?= wp_kses_post ( get_field('adresse') ); ?><?php endif;?>
				<?php if( get_field('telefon') ): ?><p><?= esc_html( get_field('telefon') ); ?></p><?php endif;?>
				<?php if( get_field('email') ): ?><p><?= esc_html( get_field('email') ); ?></p><?php endif;?>
				<?php if( get_field('sammeladresse') ): ?><p> <span class=" ">Sammeladresse team: </span><span><?= esc_html( get_field('sammeladresse') ); ?></span><?php endif;?>
			
			</section>
		</div>
	</article>	
</main>
<?php get_footer(); ?>