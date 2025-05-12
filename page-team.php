<?php /* Template Name: page-team */ ?>

<?php get_header(); ?>
<!-- Filterizr Scripts -->
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/lounge-team.js"></script>


<div class="control-wrapper" style="padding-top: 1.5rem; padding-bottom: 1.5rem;">
	<button id="filter-all" class="regenerate">⟲</button>
	<div class="controls col gap-05">
		
		<!--1st line of the control GUI-->
		<div class="row centered gap-05">
			<div id="standorte" class="row centered gap-05">
				<?php
				$p_cat = get_category_by_slug('standorte');
				if ($p_cat) {
					$c_cat = get_categories(array(
						'parent' => $p_cat->term_id,
						'hide_empty' => false,
					));
				}
				if (!empty($c_cat)) {
					foreach ($c_cat as $cc) {
						?><button class="bordered" filter="<?= $cc->slug ?>" control-type="filter"><?= $cc->name ?></button><?php
					}
				}
				?>
			</div>

			<div class="searchform">
				<input id="team-search" name="suche" type="text" placeholder="Suche..." > 
				<div class="square-wrapper">
					<input type="submit" class="search-button-1" value="">
				</div>
			</div>

			<button onclick="jQuery('#tags').toggle(); jQuery(this).find('.icon-down-open-2').toggleClass('up');">
				Mehr Filter<span class="icon-down-open-2"></span>
			</button>

		</div>

		<!--2nd line of the control GUI-->
		<div class="row centered gap-05" filter-parent="dresden" filter-target="standorte" style="display: none">
			<?php
			$p_cat = get_category_by_slug('dresden');
			if ($p_cat) {
				$c_cat = get_categories(array(
					'parent' => $p_cat->term_id,
					'hide_empty' => false,
				));
			}
			if (!empty($c_cat)) {
				foreach ($c_cat as $cc) {
					?><button id="filter-<?= $cc->slug ?>" class="bordered" filter="<?= $cc->slug ?>" control-type="filter"><?= $cc->name ?></button><?php
				}
			}
			?>
		</div>

		<!--3rd line of the control GUI-->
		<div id="tags" class="row centered gap-1"  style="align-items: flex-start; flex-wrap: nowrap; display: none;">
			<?php
			$fas = array(
				"service" => array("id" => 0, "subs" => array(), "object" => 0),
				"fachthemen" => array("id" => 0, "subs" => array(), "object" => 0),
				"planen" => array("id" => 0, "subs" => array(), "object" => 0),
			);
			$map = array();
			$taxes = get_categories( array(
				'taxonomy' => 'fachgruppen',
				'hide_empty' => 0,
				'hierarchical' => true
			));
			foreach ($taxes as $tax) {
				if (in_array($tax->slug, array_keys($fas))) {
					$fas[$tax->slug]["id"] = $tax->cat_ID;
					$fas[$tax->slug]["object"] = $tax;
					$map[$tax->cat_ID] = $tax->slug;
				}
			}
			foreach ($taxes as $tax) {
				if (in_array($tax->parent, array_keys($map))) {
					array_push($fas[$map[$tax->parent]]["subs"], $tax);
				}
				else {
				}
			}
			foreach ($fas as $fa) {
				?>
				<div class="row gap-05 centered" filter-target="fachgruppe">
					<h5 class="w-100"><?= $fa["object"]->name ?></h5>
					<?php foreach ($fa["subs"] as $sub) {
						?><button class="bordered smooth" filter="<?= $sub->slug ?>" control-type="filter"><?= $sub->name ?></button><?php
					}?>
				</div>
				<?php
			}
			?>
		</div>

	</div>
</div>

<main class="team mt-1">	
	<div id="team">

		<?php
		$p_cat = get_category_by_slug('standorte');
		if ($p_cat) {
			$c_cat = get_categories(array(
				'parent' => $p_cat->term_id,
				'hide_empty' => false,
			));
		}
		if (!empty($c_cat)) {
			foreach ($c_cat as $cc) {
				?>
				<div class="teams-wrapper" f-place-parent="<?= $cc->name ?>">
					<div class="row">
						<span class="mr-1" style="flex-grow: 1; border-bottom: 1px solid black; height: 15px"></span>
						<h3 style="display: inline-block"><?= $cc->name ?></h3>
						<span class="ml-1" style="flex-grow: 1; border-bottom: 1px solid black; height: 15px"></span>
					</div>
					<div class="teams teams-<?= $cc->name ?>" f-place="<?= $cc->name ?>"></div>
				</div>
				<?php
			}
		}
		?>

		<?php 
		// args
		$args = array(
			'numberposts' => -1,
			'post_type' => 'mitarbeiter',
		);
		// query
		$the_query = new WP_Query($args);
		if ($the_query->have_posts()): while ($the_query->have_posts()): $the_query->the_post();
				if (!get_field("retired")):
				$tags = "";
				if (get_field("fachgruppen")) {
					$tag_array = get_field("fachgruppen");
					foreach ($tag_array as $t) {
						$tags = $tags . " | " . $t->slug;
					}
					$tags = $tags . " | ";
				}
				$location_array = get_the_category();
				$city = "";
				$location = "";
				if ($location_array) {
					foreach($location_array as $loc) {
						$location = $location . " | " . $loc->slug;
						if ($loc->parent == 624) $city = $loc->name; // #624 is the id of standorte
					}
					$location = $location . " | ";
				} ?>
				<div class="item"
					data-category=""
					data-name="<?= get_field('name') ?> "
					data-full-name="<?= the_title() ?>"
					data-standorte="<?= $location ?>"
					data-id="<?= the_id() ?>"
					f-standorte="<?= $location ?>"
					f-fachgruppe="<?= $tags ?>"
					f-team="<?= get_field('team') ?>"
					f-city="<?= $city ?>"
				>
					<a href="<?php the_permalink();?>">
						<?php 
						$image = get_field('bild');
						if( !empty( $image ) ): ?>
							<img class="cover" src="<?= esc_url($image['sizes']['thumbnail']); ?>" alt="<?= esc_attr($image['alt']); ?>" class=""/>
						<?php endif; ?>
						
						<div class="info trigger">
							<h3 class="m-0"><?php the_title(); ?></h3>
							<span><?= $city ?><span>
						</div>
					</a>
				</div>
			<?php endif; ?>
		<?php endwhile; endif; ?>
	
	</div>
</main>

<?php get_footer(); ?>

<script>
// sort buttons & people by location
var order = {
	"Dresden": {"button": 0, "people": []},
	"Berlin": {"button": 0, "people": []},
	"Prag": {"button": 0, "people": []},
	"Peking": {"button": 0, "people": []},
	"Guangzhou": {"button": 0, "people": []},
	"extern": {"button": 0, "people": []},
}
// search html for relevant elements
$("#standorte").find("button").each(function() {
	order[$(this).text()]["button"] = this
})
$("#team .item").each(function() {
	var city = $(this).attr("f-city")
	try {
		order[city]["people"].push(this)
	}
	catch {
		console.log("IDK this location:", city)
		console.log($(this))
	}
})
// resort
var index = 1;
for (var place in order) {
	$("#standorte").append(order[place]["button"])
	$("#team").append($("[f-place-parent='"+place+"']"))
	// sort people alphabetically
	order[place]["people"].sort(function(a, b) {
		var textA = $(a).data("name");
    	var textB = $(b).data("name");
    	return (textA < textB) ? -1 : (textA > textB) ? 1 : 0;
	})
	order[place]["people"].forEach(e => {
		$(e).attr("data-index", index)
		$("#team div[f-place='"+place+"']").append(e)
		index += 1
	})
}
</script>