<?php /* Template Name: frontpage */ ?>

<?php get_header(); ?>
<script>
$(function() {
	console.log("Creating page")
	$("#page-creation > *").each(function() {
		$(".placeholder[ph-target='"+$(this).attr("id")+"']").replaceWith($(this))
	})
	$("#page-creation").remove()
	// dropdown script
	$("[dd-type='trigger']").click(function() {
		$(this).parent().find("[dd-type='target']").toggleClass("hidden")
		$(this).find("span").toggleClass("hidden")
	})
})
// remeber scroll position
$(function() {
	const last_dd = localStorage.getItem("rla-intra-last-dd") 
	const last_site = localStorage.getItem("rla-intra-history")
	if (last_dd != null && last_site != "http://webserver/intranet_rla/wp_intra24/") {
		$("#"+last_dd+" > a").click();
    }
	else {
		localStorage.setItem("rla-intra-last-dd", null);
	}
	$(".dropout a").not(".do-title").click(function() {
		localStorage.setItem("rla-intra-last-dd", $(this).closest(".dropout").attr("id"));
	})
})
</script>

<main class="frontpage">
		
	<section class="news-fenster" style="padding: 1em 0">
		
		<h2 class="section-title">just in</h2>
		<?php
		$featured_posts = get_field('featured_posts');
		if( $featured_posts ): ?>
			<div class="featured-posts col">
				<?php
				$preview_count = 6;
				$more = false;
				$split_arr = array(array_slice($featured_posts, 0, $preview_count), array_slice($featured_posts, $preview_count));
				foreach ($split_arr as $sub_arr):
					if($more) { ?><ul class="flexgalerie hidden" dd-type="target"><?php }
					else { ?><ul class="flexgalerie"><?php }
					?> 
						<?php foreach( $sub_arr as $post ): 
							// Setup this post for WP functions (variable must be named $post).
							setup_postdata($post); ?>
							<li>
								<a class="" href="<?php the_permalink(); ?>">
									<?php 
									$more = true;
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
					</ul>
				<?php endforeach ?>
				<a style="font-size: 1rem;" dd-type="trigger">
					<span>Mehr Posts</span>
					<span class="hidden">Weniger Posts</span>
				</a>
			</div>
			<?php 
			// Reset the global post object so that the rest of the page works correctly.
			wp_reset_postdata(); ?>
		<?php endif; ?>
		
	</section>
	
	<section id="rla-bauarchiv">
		<h2 class="section-title">Doku </h2>
		<div class="fp-section">
			<div class="block-left">
				<div class="placeholder" ph-target="bauteile"></div>
				<div class="placeholder" ph-target="pflanzung"></div>
				<div class="placeholder" ph-target="materialien"></div>
			</div>
			<div class="block-right">
				<div class="placeholder" ph-target="projekte"></div>
				<div class="placeholder" ph-target="lounge"></div>
				<div class="placeholder" ph-target="firmen"></div>
			</div>
		</div>
	</section>
	
	<section id="rla-info">
		<h2 class="section-title">Info</h2>
		<div class="fp-section">
			<div class="block-left">
				<div class="placeholder" ph-target="planungsthemen"></div>
				<div class="placeholder" ph-target="gesetze"></div>
				<div class="placeholder" ph-target="fortbildung"></div>
			</div>
			<div class="block-right">
				
			</div>
		</div>
	</section>
	
	<section id="rla-intern">
		<h2 class="section-title">Intern </h2>
		<div class="fp-section">
			<div class="block-right">
				<div class="placeholder" ph-target="team"></div>
				<div class="placeholder" ph-target="fachgruppen"></div>
				<div class="placeholder" ph-target="service"></div>
			</div>
			<div class="block-left">
				<div class="placeholder" ph-target="standorte"></div>
				<div class="placeholder" ph-target="planungswerkzeuge"></div>
				<div class="placeholder" ph-target="anleitung"></div>
				<div class="placeholder" ph-target="rla-luecke"></div>
				<div class="placeholder" ph-target="insights"></div>
			</div>
			
		</div>
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
			<h3>Projekte</h3>
		</a>
	</div>
	
	<div class="dropout" id="firmen">
		<a class="do-title link" href="<?php bloginfo('url'); ?>/firmenverzeichnis">
			<h3>Firmenverzeichnis</h3>
		</a>
	</div>

	<div class="dropout" id="team">
		<a class="do-title link" href="<?php bloginfo('url'); ?>/team">
			<h3>Team</h3>
		</a>
	</div>
	
	<div class="dropout" id="fachgruppen">
		<a class="do-title link" href="<?php bloginfo('url'); ?>/rla-fachgruppen-2">
			<h3>Fachgruppen</h3>
		</a>
	</div>

	<div class="dropout" id="service">
		<a class="do-title link" href="<?php bloginfo('url'); ?>/rla-service">
			<h3>Service</h3>
		</a>
	</div>
			
	<?php 
	// Array for dynamic page creation
	// element = (name, headline, target include, query)
	$intern_typen = array(
		array("standorte", "Standorte", "fp-liste-standorte.php",
			array(
				'numberposts'   => -1,
				'post_type'     => 'standort',
			)
		),
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
		array("anleitung", "Anleitungen: E-Mail, Cloud, etc...", "fp-liste.php",
			array(
				'numberposts'   => -1,
				'post_type'     => 'dokumentation',
				'category_name' => 'anleitungen',
				'orderby' => 'menu_order'
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
				'order' => 'ASC',
			)
		),
	);
	
	foreach($intern_typen as $it): ?>
		<!-- <?= $it[0] ?> -->
		<div class="dropout <?= ($it[1] == 'Planungsthemen') ? 'active' : ''; ?>" id="<?= $it[0] ?>">

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

<footer>
	<ul class="footer-links">
		<?php 
		$footer_links = get_field("links");
		foreach ($footer_links as $fl) {
			?><li><a href="<?= $fl["link"]["url"] ?>" target="_blank"><?= $fl["link"]["title"] ?></a></li><?php
		}
		?>
	</ul>
</footer>


<?php get_footer(); ?>