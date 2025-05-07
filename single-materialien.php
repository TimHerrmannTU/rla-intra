<?php get_header(); ?>

<!-- Daten aus Access Datenbank abfragen für Auflistung von Bauteilen in bauarchiv-entry.php -->
<?php include('templates/abfrage-projektname-jahr.php'); ?>

<main class="materialien">	
 
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<article class="bigbox container_single-post">
			
			<?php if(get_field('bild')) {
				$image = get_field('bild');
				?> 
				<figure class="main-figure">
					<img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>" class=""/>
				</figure>
			<?php } ?>	
	
			<div class="main-info">

				<div class="element-title">
					<h1><?php the_title(); ?></h1>
					<?php if (get_field('untertitel')) { ?>
						<span><?= esc_html(get_field('untertitel')); ?></span>
					<?php } ?>
				</div>
		
				<section>
					<?php the_content();?>
					<?php if (get_field('hinweise')) { ?>
						<div class="highlight-box">
							<h5>Besondere Hinweise</h5>
							<?= wp_kses_post ( get_field('hinweise') ); ?>
						</div>
					<?php } ?>
				</section>

				<?php
				// dynamic page creation
				$dokumenten_typen = array(
					array("dokumente-regelwerke", "Gesetze, Regelwerke, Vorschriften"),
					array("dokumente", "Weitere Dokumente: Beispiel, Studien...")
				);
				foreach ($dokumenten_typen as $doku): if( have_rows($doku[0]) ): ?>
				<section>
					<h3><?= $doku[1] ?></h3>
					<ul class="">
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
									<a href="<?= esc_attr($url); ?>" title="<?= esc_attr($title); ?>" target="blank">
										<span><?= esc_html(get_sub_field('titel') ); ?></span>
										<img class="file-icon" style="margin-left:1em" src="<?= esc_attr($icon); ?>" /><br/>
									</a>
									<span class="fs-s"><?= esc_html(get_sub_field('autor') ); ?></span>
								<?php endif; ?>
							</li>
						<?php endwhile; ?>
					</ul>
				</section>
				<?php endif; endforeach ?>
				
				<?php
				// "PHP_FIELD_NAME" => "HEADLINE_FOR_HTML",
				$linked_topics = array(
					"links" => "Allgemeine Links",
					"firmen" => "Firmen"
				);
				foreach ($linked_topics as $key => $value) {
					if( have_rows($key) ): ?>
					<section class="">
						<h3><?= $value ?></h3>
						<ul class="">
							<?php while( have_rows($key) ): the_row(); ?>
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
				<?php } ?>

			</div>

		</article> 
	<?php endwhile; endif; ?>

	<?php 
	// Bauteile suchen, die den Namen des Materials im Titel oder in der Beschreibung haben
	/*
	$args = array(
		'posts_per_page'    => -1,
		'post_type'     => 'bauteil',
		'meta_query'    => array(
			'relation'      => 'OR',
			array(
				'key'       => 'titel',
				'value'     => get_the_title(),
				'compare'   => 'LIKE'
			),
			array(
				'key'       => 'beschreibung',
				'value'     => get_the_title(),
				'compare'   => 'LIKE'
			)
		)
	);
	// Titel des Materials merken für Hervorhebung
	$material_title = get_the_title();
	// Query
	$the_query = new WP_Query( $args );
	*/

	// cat search
	/*
	$cat_slug = $post->post_name;
	$cat = get_category_by_slug($cat_slug);
	echo print_r($cat->cat_ID);
	$args = array(
        'post_type' => 'bauteil',// your post type,
        'category' =>  $cat->cat_ID;
	);
	*/
	?>
	
	<!--
	<section>
		<h2 class="section-title">Gefundene Bauteile für <?= $material_title ?> (<?= $the_query->found_posts ?>)</h2>
		<ul>
			<?php
			while ( $the_query->have_posts() ) : $the_query->the_post();
				// Variablen $suche und $suchbegriffe setzen für Hervorhebung
				$suche = $material_title;
				$suchbegriffe = array($material_title);
				// Bauteil Template einbinden
				include('templates/bauarchiv-entry.php');
			endwhile; 
			include('templates/bauarchiv-entry-functions.php');
			?>
		</ul>
	</section>
	-->
	<?php wp_reset_query(); ?>
	
</main>
       
 
<?php get_footer(); ?>