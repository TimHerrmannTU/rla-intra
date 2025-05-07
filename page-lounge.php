<?php /* Template Name: page-lounge-2 */ ?>

<?php get_header(); ?>
<!-- Filterizr Scripts -->
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/lounge-filter.js"></script>
<script type="module" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/lounge-lightbox.js"></script>
<link rel="stylesheet" href="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/css/photoswipe.css">
<style>
.controls .misc {
    display: grid;
    grid-template-columns: 2fr 3fr;
}
</style>


<div id="control-panel" class="control-wrapper pt-1 pb-1" style="display: none;">
	<button id="filter-all" class="regenerate">⟲</button>
	<div class="controls col gap-05">
		<div id="types" class="row centered gap-05"  control-type="group">
			<?php
			$types = get_field_object("field_65771d7f91d11")["choices"];
			foreach ($types as $type) {
				?><button id="filter-<?= $type ?>" class='filter-button bordered' filter="<?= $type ?>" control-type="filter"><?= $type ?></button><?php
			}
			?>
		</div>
		<div class="misc">
			<div id="dd-toggles" class="row gap-05"  control-type="group" style="justify-content: flex-end">
				<button id="show-perspectives">Perspektivtyp <span class="icon-down-open-2"></span></button>
				<button id="show-ratios">Maßstab <span class="icon-down-open-2"></span></button>
				<button id="show-styles">Stil <span class="icon-down-open-2"></span></button>
				<button id="show-formats">Format <span class="icon-down-open-2"></span></button>
				<button id="show-tags">Tags <span class="icon-down-open-2"></span></button>
				<button id="show-lands">Land <span class="icon-down-open-2"></span></button>
			</div>
			<div class="row gap-05" control-type="group">

				<div class="searchform">
					<input id="lounge-search" name="suche" type="text" placeholder="Suche..."> 
					<div class="square-wrapper">
						<input type="submit" class="search-button-1" value="">
					</div>
				</div>
				
				<button id="show-info">Titel einblenden</button>

				<div class="dd-wrapper sort-items">
					<button id="sort-items" control-type="sort"><span class="arrow-after">Sortieren</span></button>
					<div class="dd sort-dd hidden">
						<button id="sort-title">ABC</button>
						<button id="sort-date">Datum</button>
						<!--<button id="sort-place">Platz</button>-->
						<button id="sort-random">Zufall</button>
					</div>
				</div>
			
			</div>
		</div>

		<?php
		// if new sub-filters should be created add a entry to this array
		// [0] should be the name which is also referenced in the lounge.js (see the var filters dictionary)
		// [1] should be the acf key
		// when setup both in the js & here it should work imidiatly
		$special_buttons = array(
			array("lands", "field_65c5f98660936"),
			array("styles", "field_65a7b9b8960a0"),
			array("perspectives", "field_65a7bbe213ec9"),
			array("ratios-Lageplan", "field_6597d4d0501db"),
			array("ratios-Detail", "field_65ba61f5f471d"),
			array("ratios-Schnitt", "field_65ba6222f471e"),
			array("formats", "field_65ba73832b50b"),
		);
		foreach ($special_buttons as $sb) {
			?><div id="<?= $sb[0] ?>" class="row centered gap-05" control-type="sub"><?php
			$options = get_field_object($sb[1])["choices"];
			foreach ($options as $option) {
				?><button id='filter-<?= $option ?>' class='filter-button bordered' filter='<?= $option ?>' control-type="filter"><?= $option ?></button><?php
			}
			?></div><?php
		}
		$tags = array(
			array("tags", "field_65a7c24d72bb9"),
			array("sub_tags", "field_65c0b5a950a74")
		);
		?>
		<div id="tags" class="row centered gap-05" control-type="sub"><?php	
			foreach ($tags as $tag) {
				$options = get_field_object($tag[1])["choices"];
				foreach ($options as $key => $value) {
					$cleaned_option = explode(" (", $value)[0];
					?><button id='filter-<?= $key ?>' class='filter-button bordered smooth' filter='<?= $key ?>' control-type="filter"><?= $cleaned_option ?></button><?php
				}
			}
			?>
		</div>

	</div>
</div>

<main id="lounge" class="col gap-1 mt-1 mb-1" style="display: none;">

	<?php
	// args
	$args = array(
		'numberposts' => -1,
		'post_type' => 'wettbewerb',
		'posts_per_page' => 1
	);
	// query
	$the_query = new WP_Query($args);
	if ($the_query->have_posts()): ?>
		<div id="gallery" class="row container filtr-container">
			<?php
			while ($the_query->have_posts()):
				$the_query->the_post();
				$date = get_field("datum");
				$super_tags = get_field("wb-schlagworte");
				if (have_rows('lounge-inhalte')):
					while (have_rows('lounge-inhalte')): the_row();
						$image = get_sub_field('bild');
						if ($image):
							// Thumbnail size attributes.
							$size = 'thumbnail';
							$thumb = $image['sizes'][$size];
							$width = $image['sizes'][$size . '-width'];
							$height = $image['sizes'][$size . '-height'];
							$ratio = 200 / $height;
							$fixed_width = $width * $ratio;
							// For filtering:
							$tags = "";
							if (get_sub_field("schlagworter-zeichnung")) {
								$tag_array = get_sub_field("schlagworter-zeichnung");
								foreach ($tag_array as $t) {
									$tags = $tags . "," . $t["value"];
								}
							}
							if (get_field("wb-schlagworte")) {
								$tag_array = get_field("wb-schlagworte");
								foreach ($tag_array as $t) {
									$tags = $tags . "," . $t["value"];
								}
							}
							// Generate images
							?>
							<a class="item" href="<?= esc_url($image['url']); ?>" 
								
								comment="Setup for PhotoSwipe"
								data-pswp-width="<?= $image['width'] ?>"
								data-pswp-height="<?= $image['height'] ?>" 
								target="_blank"
								
								comment="Setup for Filterizr"
								f-types="<?= get_sub_field("typ") ?>"
								f-project="<?= the_title() ?>"
								f-tags="<?= $tags ?>" 
								f-ratios-Lageplan="<?= get_sub_field("massstab_lageplan") ?>"
								f-ratios-Detail="<?= get_sub_field("massstab_detail") ?>"
								f-ratios-Schnitt="<?= get_sub_field("massstab_schnitt") ?>"
								f-styles="<?= get_sub_field("zeichnungsstil") ?>"
								f-perspectives="<?= get_sub_field("perspektive_art") ?>"
								f-formats="<?= get_sub_field("format") ?>"
								f-lands="<?= get_field("land") ?>"

								comment="Setup for Sorting"
								data-category=""
								data-title="<?= esc_attr($image['title']); ?>"
								data-date="<?= $date ?>"
								data-place="<?= get_field("wb-ergebnis") ?>"
								data-caption="<?= the_title() ?>"

								comment="Misc: debugging"
								data-thumb="<?= $thumb ?>"
								data-download-link="<?= get_sub_field('pfad') ?>"
								data-wb-link="<?= get_permalink() ?>"
							>
								<img style="width: <?= $fixed_width ?>px !important;" loading="lazy"/>
								<div class="image-mask"><?= the_title() ?></div>
							</a>
						<?php endif; ?>
					<?php endwhile; ?>

				<?php endif; ?>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
	<?php wp_reset_query();   // Restore global post data stomped by the_post(). ?>

</main>
<?php get_footer(); ?>