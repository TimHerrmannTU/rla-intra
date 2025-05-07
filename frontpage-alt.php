<?php /* Template Name: frontpage */ ?>

<?php get_header(); ?>

<main class="frontpage">
		
	<section class="news-fenster" style="padding: 1em 0">
		
		<h2 class="section-title">just in</h2>
		<?php
		$featured_posts = get_field('featured_posts');
		if( $featured_posts ): ?>
			<div class="featured-posts">
				<ul class="flexgalerie">
					<?php foreach( $featured_posts as $post ): 
						// Setup this post for WP functions (variable must be named $post).
						setup_postdata($post); ?>
						<li>
							<a class="" href="<?php the_permalink(); ?>">
								<?php 
								if( get_field('galerie') ) {
									$image = get_field('galerie')[0];
								}
								elseif( get_field('galerie-pflanzung') ) {
									$image = get_field('galerie-pflanzung')[0];
								}
								else { 
									$image = get_field('bild');
								}
								if( !empty( $image ) ): ?>
									<figure>
										<img src="<?= $image['sizes']['large']; ?>" alt="<?= $image['alt']; ?>"></img>
									</figure>
								<?php endif; ?>
								<div class="label">
									<div class="label-info">
										<?php
										if( get_field('projektkurzel') ) {
											?><span class="label-kuerzel"><?= the_field( 'projektkurzel' ); ?></span><?php
										}
										if( get_field('titel') ) {
											?><span><h4> <?= the_field('titel'); ?></h4></span><?php
										}
										else { // field_name returned false
											?><span><h4><?= the_title(); ?></h4></span><?php
										}
										?>
									<div class="cat-name">
										<?php 
										$post_type = get_post_type_object(get_post_type())->labels->singular_name;
										if ($post_type == "Dokument") {
											$cats = get_the_category();
											echo $cats[count($cats)-1]->name;
										}
										else echo $post_type;
										?>
									</div>
									</div>
								</div>
							</a>
						</li>
					<?php endforeach; ?>
				<ul>
			</div>
			<?php 
			// Reset the global post object so that the rest of the page works correctly.
			wp_reset_postdata(); ?>
		<?php endif; ?>
		
	</section>
	
	<section id="rla-bauarchiv">
		<h2 class="section-title">Doku </h2>
		<div class="placeholder" ph-target="bauteile"></div>
		<div class="placeholder" ph-target="pflanzung"></div>
		<div class="placeholder" ph-target="materialien"></div>
		
		<div class="placeholder" ph-target="projekte"></div>
		<div class="placeholder" ph-target="lounge"></div>
	</section>
	
	<section id="rla-info">
		<h2 class="section-title">Info</h2>
		<div class="placeholder" ph-target="planungsthemen"></div>
		<div class="placeholder" ph-target="gesetze"></div>
		
		<div class="placeholder" ph-target="fortbildung"></div>

	</section>

	<section id="rla-intern">
		<h2 class="section-title">Intern</h2>	
		<div class="placeholder" ph-target="team"></div>
		<div class="placeholder" ph-target="anleitung"></div>
		<div class="placeholder" ph-target="planungswerkzeuge"></div>
		<div class="placeholder" ph-target="rla-luecke"></div>
		<div class="placeholder" ph-target="insights"></div>
	</section>

</main> 

<div id="page-creation" style="display: none">

	<div class="dropout" id="bauteile">
		<a class="do-title" onclick="toggle_dropdown(this)">
			<h3>Bauteile</h3>
			<!--<img src="<?php bloginfo('template_directory'); ?>/img/arrow-sq1.png" class="do-icon" id="icon-bauteile"> -->
		</a>
		<div class="dropout-hidden">
			<?php include("templates/cats-bauteil.php"); ?>
		</div>
	</div>
		
	<div class="dropout" id="pflanzung">
		<a class="do-title" onclick="toggle_dropdown(this)">
			<h3>Pflanzungen</h3>
		</a>
		<div class="dropout-hidden" >
			<?php include("templates/cats-pflanzung.php"); ?>
		</div>
	</div>

	<div class="dropout" id="materialien">
		<a class="do-title" onclick="toggle_dropdown(this)">
			<h3>Materialien</h3>
		</a>
		<div class="dropout-hidden" >
			<?php include("templates/cats-material.php"); ?>
		</div>
	</div>
	
	<div class="dropout" id="lounge">
		<a class="do-title link" href="<?php bloginfo('url'); ?>/lounge">
			<h3>Grafik-Lounge</h3>
		</a>
	</div>
	
	<div class="dropout" id="projekte">
		<a class="do-title link" href="<?php bloginfo('url'); ?>/projekt-archiv">
			<h3>Projektarchiv</h3>
		</a>
	</div>

	<div class="dropout" id="team">
		<a class="do-title link" href="<?php bloginfo('url'); ?>/team">
			<h3>Team</h3>
		</a>
	</div>
			
	<?php 
	// Array for dynamic page creation
	// element = (name, headline, target include, query)
	$intern_typen = array(
		array("planungsthemen", "Planungsthemen", "fp-galerie.php",
			array(
				'numberposts'   => -1,
				'post_type'     => 'planungsthema',
			)
		),
		array("gesetze", "Gesetze, Regeln, Vorschriften", "fp-liste-gesetze.php",
			array(
				'numberposts'   => -1,
				'meta_key'      => 'autor-kurz',
				'orderby'       => 'meta_value',
				'post_type'     => 'dokumentation',
				'category_name' => 'gesetze',
			)
		),
		array("fortbildung", "Fortbildungen, Fachtagungen...", "fp-liste-fortbildungen.php",
			array(
				'numberposts'   => -1,
				'post_type'     => 'dokumentation',
				'category_name' => 'fortbildungen',
			)
		),
		array("planungswerkzeuge", "Planungswerkzeuge", "fp-liste.php",
			array(
				'numberposts'   => -1,
				'post_type'     => 'dokumentation',
				'category_name' => 'planungswerkzeuge-dokumente',
			)
		),
		array("anleitung", "IT-Anleitungen", "fp-liste.php",
			array(
				'numberposts'   => -1,
				'post_type'     => 'dokumentation',
				'category_name' => 'it',
				'orderby' => 'thema',
			)
		),
		/*array("verwaltung", "Verwaltung", "fp-liste.php", 
			array(
				'numberposts'   => -1,
				'post_type'     => 'dokumentation',
				'category_name' => 'verwaltung',
				'orderby' => 'thema',
			)
		),*/
		array("rla-luecke", "RLA-Lücke", "fp-galerie.php",
			array(
				'numberposts'   => -1,
				'post_type'     => 'dokumentation',
				'category_name' => 'rla-luecke',
				'orderby' => 'datum',
				'order' => 'DESC',
			)
		),
		array("insights", "RLA-Insights", "fp-galerie.php",
			array(
				'numberposts'   => -1,
				'post_type'     => 'dokumentation',
				'category_name' => 'newsletter',
				//'orderby' => 'datum',
				'order' => 'DESC',
			)
		),
	);
	foreach($intern_typen as $it): ?>
		<!-- <?= $it[0] ?> -->
		<div class="dropout" id="<?= $it[0] ?>">

			<a class="do-title" onclick="toggle_dropdown(this)">
				<h3><?= $it[1] ?></h3>
			</a>
			
			<div class="dropout-hidden">
				<div class="bigbox">
					<?php 
					$the_query = new WP_Query( $it[3] ); 
					if( $the_query->have_posts() ):
						include('templates/' . $it[2]);
					endif;
					wp_reset_query();   // Restore global post data stomped by the_post().
					?>
				</div>
			</div>

		</div>
	<?php endforeach ?>

</div>

<script>
$(function() {
	$("#page-creation > *").each(function() {
		$(".placeholder[ph-target='"+$(this).attr("id")+"']").replaceWith($(this))
	})
	$("#page-creation").remove()
})
// remeber scroll position
$(function() {
	// init
	if (localStorage.getItem("rla-intra-scroll") != null) {
        $(window).scrollTop(localStorage.getItem("rla-intra-scroll"));
    }
	if (localStorage.getItem("rla-intra-last-dd") != null) {
		$("#"+localStorage.getItem("rla-intra-last-dd")+" a").click();
    }
	// save scroll & dd state
	$(window).on("scroll", function() {
		localStorage.setItem("rla-intra-scroll", $(window).scrollTop());
	})
	$(".dropout a").click(function() {
		localStorage.setItem("rla-intra-last-dd", $(this).closest(".dropout").attr("id"));
	})
})
</script>

<?php get_footer(); ?>