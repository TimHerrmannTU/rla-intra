<?php 
get_header(); 
include('templates/abfrage-projektname-jahr.php');
// --------------------------------------------------------------------------------------------------------------------------------------
// Hab mal alles was in allen Dokumentationstypen auftaucht hier her bewegt. Ist einfach sinnvoller, dass so zu strukturieren.
// Code duplication macht alles nur anstrengender in Zukunft >.<
// Der while posts wrapper erschien mir auch nutzlos, da ja eigentlich immer nur genau der eine Post exestieren sollte pro Dokumentation.
// Falls ich da falsch liege, lässt sich das aber auch easy wieder adden.
// --------------------------------------------------------------------------------------------------------------------------------------
?>
<main>
	<article class="bigbox dokumentation container_single-post">
		
			<?php 
			if(get_field('bild')):
				$image = get_field('bild');
				if(get_field('datei')):
					$file = get_field("datei"); ?>
					<a href="<?= esc_attr($file['url']); ?>" title="<?= esc_attr($file['title']); ?>" target="_blank">
				<?php endif; ?>
						<figure>
							<img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>" class=""/>
						</figure>
				<?php if($file): ?> 
					</a>
				<?php endif; ?>
			<?php endif; ?>	
			
			<div class="main-info">
			<section class="entry-header">
				<div class="element-title">
					<!-- Einfügen cat-name 
					<span class="cat-name> ... </span> 
					-->
					<h1><?php the_title(); ?></h1>
					<?php if (get_field('untertitel')): ?><span><?= esc_html(get_field('untertitel')); ?></span><?php endif; ?>
				</div>
				<div class="doku-info">
					<?php if (get_field('datum')): ?><span><?= esc_html( get_field('datum') ); ?></span><?php endif; ?>
					<?php if (get_field('veranstalter')): ?><span>Veranstalter: <?= esc_html( get_field('veranstalter') ); ?></span><?php endif; ?>
					<?php if (get_field('autor')): ?><span><?= esc_html( get_field('autor') ); ?></span><?php endif; ?>
				</div>
			</section>		
			
			
			
			
		
		

		<?php 
		// DYNAMIC SECTION START
		$cat = end(get_the_category()); //gets the name of the last category of this post
		if ($cat->slug == "planungsthemen") {
			include('templates/single-dokumentation-planungsthemen.php');
		}
		elseif ($cat->slug == "fortbildungen") {
			include('templates/single-dokumentation-fortbildungen.php');
		}
		// DYNAMIC SECTION END
		?>
		</div><!-- end .main-info -->
		
	</article>
</main>
<script>
	// breadcrumb fix
	$('.breadcrumb a[href*="dokumentation"]').each(function () {
		this.href = this.href.replace("dokumentation", "<?= $cat->slug ?>");
		$(this).text("<?= $cat->name ?>")
	})
</script>
<?php get_footer(); ?>