<?php 
get_header(); 
include('templates/abfrage-projektname-jahr.php');
$cat = end(get_the_category())->slug;
?>

<main>
	<article class="bigbox dokumentation">
		<div class="wrap">

			<div class="dokumentation-header">
				<div class="element-title">
					<h1><?php the_title(); ?></h1>
					<?php if (get_field('untertitel')): ?>
						<span><?= esc_html(get_field('untertitel')); ?></span>
					<?php endif; ?>
				</div>
				<div class="doku-info">
					<?php
					$featured_posts = get_field('verantwortlicher');
					if( $featured_posts ): ?>
						<span>
							Redakteur:
							<?php foreach( $featured_posts as $key => $featured_post ): 
								$permalink = get_permalink( $featured_post->ID );
								$title = get_the_title( $featured_post->ID );
								$custom_field = get_field( 'field_name', $featured_post->ID );
								?>
								<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title )?>
									<?php // nachfolgende Zeile muss in einer Zeile bleiben, da Zeilenumbruch im HTML sonst als Leerzeichen dargestellt wird ?>
									<span style="color:grey; font-size: 1.2rem; vertical-align: -10%; display: inline-block" class="dashicons dashicons-admin-users"></span></a><?php if(!($key === array_key_last($featured_posts))) echo ","; ?>
							<?php endforeach; ?>
						</span>
					<?php endif;?>

					
				</div>
			</div>		
			
						
		</div>
		
		<?php
		// dynamic page creation
		$dokumenten_typen = array(
			array("dokumente-regelwerke", "Gesetze, Regelwerke, Vorschriften"),
			array("dokumente", "Weitere Dokumente: Beispiel, Studien...")
		);
		foreach ($dokumenten_typen as $doku): if( have_rows($doku[0]) ): ?>
			<section class="text-block">
				<h3><?= $doku[1] ?></h3>
				<ul class="liste">
					<?php while( have_rows($doku[0]) ): the_row(); ?>
						<li>
						<?php
							$file = get_sub_field('dokument');
							if( $file ):
								// Extract variables.
								$url = $file['url'];
								$title = $file['title'];
								$icon = $file['icon'];
								// Display image thumbnail when possible.
								if( $file['type'] == 'image' ) {
									$icon =  $file['sizes']['thumbnail'];
								}
							?>
								<a style="display:flex;align-items:flex-start;gap:0.5rem" href="<?= esc_attr($url); ?>" title="<?= esc_attr($title); ?>" target="blank">
									<span><?= esc_html(get_sub_field('titel') ); ?></span>
									<img class="file-icon" src="<?= esc_attr($icon); ?>" /><br/>
								</a>
								<div class="inline-liste">
								<?php if( get_sub_field('autor') ): ?><span class="fs-s"><?= esc_html(get_sub_field('autor') ); ?></span><?php endif; ?>
								<?php if( get_sub_field('datum') ): ?><span class="fs-s"><?= esc_html(get_sub_field('datum') ); ?></span><?php endif; ?>
								</div>
								<?php if( get_sub_field('anmerkungen') ): ?><span class="fs-s"><?= esc_html(get_sub_field('anmerkungen') ); ?></span><?php endif; ?>
								
							<?php endif; ?>
						</li>
					<?php endwhile; ?>
				</ul>
			</section>
		<?php endif; endforeach ?>

		<?php if( have_rows('firmen') ): ?>
			<section class="text-block">
				<h3>Firmen/Hersteller</h3>
				<ul class="">
					<?php while( have_rows('firmen') ): the_row(); ?>
					<li>
						<a class="extern" href="<?php echo esc_attr( get_sub_field('website') ); ?>" target="blank"><?php echo esc_html( get_sub_field('name') ); ?></a>
						<div class="fs-s">
						<?php if( get_sub_field('ansprechpartner') ): ?><span><?= esc_html(get_sub_field('ansprechpartner') ); ?></span><?php endif; ?>
						<?php if( get_sub_field('anmerkungen') ): ?><span><?= esc_html(get_sub_field('anmerkungen') ); ?></span><?php endif; ?>
						</div>
					</li>
					<?php endwhile; ?>
				</ul>
			</section>
		<?php endif; ?>

		<?php
		$featured_posts = get_field('fortbildungen');
		if( $featured_posts ): ?>
			<section class="text-block">
				<h3>Fortbildungen, Fachtagungen oder Seminare zum Thema</h3>
				<ul class="liste">
					<?php foreach( $featured_posts as $featured_post ): 
						$permalink = get_permalink( $featured_post->ID );
						$title = get_the_title( $featured_post->ID );
						$veranstalter = get_field( 'veranstalter', $featured_post->ID );
						$datum = get_field( 'datum', $featured_post->ID );
						?>
						<li>
							<a href="<?php echo esc_url( $permalink ); ?>"><h4><?php echo esc_html( $title ); ?></h4></a>
							
							<div class="label-info">
							<?php if ($datum): ?><span><?php echo esc_html( $datum ); ?></span><?php endif; ?>
							<?php if ($veranstalter): ?><span><?php echo esc_html( $veranstalter ); ?></span><?php endif; ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>

		<?php if( have_rows('links') ): ?>
			<section class="text-block">
				<h3>Links</h3>
				<ul class="">
					<?php while( have_rows('links') ): the_row(); ?>
						<li>
							<?php 
							$link = get_sub_field('link');
							if( $link ): 
								$link_url = $link['url'];
								$link_title = $link['title'];
								$link_target = $link['target'] ? $link['target'] : '_blank';
								?>
								<a class="extern" href="<?= esc_url( $link_url ); ?>" target="<?= esc_attr( $link_target ); ?>"><?= esc_html( $link_title ); ?></a>
							<?php endif; ?>
						</li>
					<?php endwhile; ?>
				</ul>
			</section>
		<?php endif; ?>

		

	</article>
</main>
<?php get_footer(); ?>