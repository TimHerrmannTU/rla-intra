<?php
//Daten auslesen - Template "abfrage-projektname-jahr.php" muss in übergeordneter .php Page includiert werden
if (!$result) { // this is needed if this template gets called via the query_posts_ajax()
	$result = $args['result'];
}
$matched = false;
if (get_field('projektkurzel')) {
	foreach ($result as $row) {
		// Zeile entsprechend Projektkürzel aus URL Parametern wählen
		if ($row["Projektkürzel"] == get_field('projektkurzel')) {
			// Variablen zu Verwendung weiter unten setzen
			$projekt_name = $row["Projektname"];
			$projekt_fertigstellung = $row["Jahr Fertigstellung"];
			$matched = true;
		}
	}
}
if (!$matched) {
	$projekt_name = "";
	$projekt_fertigstellung = "";
}

// Nur wenn bauarchiv-entry für die Anzeige von Suchergebnissen verwendet wird:
$bauteil_titel = esc_html(get_field('titel'));
$bauteil_beschreibung = esc_html(get_field('beschreibung'));
$bauteil_projektkuerzel = get_field('projektkurzel');
if (!empty($suche)) {
    foreach ($suchbegriffe as $begriff) {
        $bauteil_titel_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $bauteil_titel);
        $bauteil_beschreibung = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $bauteil_beschreibung);
        $bauteil_projektkuerzel = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $bauteil_projektkuerzel);
    }
}

$default_icon = "mdi-puzzle";
$galerie_field = "galerie";
$datei_field = "cad-dateien";
// Unterscheidung zwischen Bauteil/Pflanzung/Fail
if (get_post_type() == "bauteil") {
}
if (get_post_type() == "pflanzung") {
    $galerie_field = "galerie-pflanzung";
    $datei_field = "dokumente-pflanzung";
	$default_icon = "mdi-flower-tulip";
}
if (get_post_type() == "fail") {
}

// Filtern nach der letzten Kategorie
$final_cats = array();
$mats = array();
$cats = get_the_category();
if (!empty($cats)) {
	foreach ($cats as $cat) {
		$child_cats = get_categories(array("parent"=>$cat->cat_ID));
		if (count($child_cats) == 0) {
			$parent = $cat;
			while ($parent->category_parent != 0) {
				$parent = get_category($parent->parent);
			}
			if ($parent->slug == "materialien") {
				array_push($mats, $cat);
			}
			else {
				array_push($final_cats, $cat);
			}
		}
	}
}
?>

<article class="smallbox bauteil col gap-05" f-date="<?= $projekt_fertigstellung ?>">
	<div>
		<h3 class="m-0">
			<a href="<?php the_permalink() ?>"><?= ($bauteil_titel); ?></a>
			<?php if(current_user_can('edit_post', get_the_id())) { ?>
				<a href ="<?=get_edit_post_link()?>">
					<span style="color:grey; font-size: 1.4rem; vertical-align: -10%;" class="dashicons dashicons-welcome-write-blog"></span>
				</a>
			<?php } ?>
		</h3>
		<div class="row gap-1 fs-s">
			<?php foreach ($final_cats as $f_cat) { ?>
				<a class="row" href="http://webserver/intranet_rla/wp_intra24/category/<?=$f_cat->category_nicename?>" style="color:grey">
					<?=$f_cat->name?><span class="iconify" data-icon="<?= $default_icon ?>" style="margin-left: 3px;"></span>
				</a>
			<?php } ?>
			<?php foreach ($mats as $f_cat) { ?>
				<a class="row" href="http://webserver/intranet_rla/wp_intra24/materialien/<?=$f_cat->category_nicename?>" style="color:grey">
					<?=$f_cat->name?><span class="iconify" data-icon="mdi-bricks" style="margin-left: 3px;"></span>
				</a>
			<?php } ?>
		</div>
	</div>

	<div><?= nl2br($bauteil_beschreibung); ?></div>
    
	<?php if (get_post_type() == "pflanzung"):
		?><div class="pflanzeninfo"><?php // habitat information
			$habitat_info = array("feuchtigkeit", "lebensbereiche", "licht", "bepflanzungstyp");
			foreach ($habitat_info as $hi) {
				if (get_field($hi)) {
					?><div><span class="label"><?= get_field_object($hi)["label"] ?></span>: <?= implode(", ", get_field($hi)) ?></div><?php					
				}
			}
		?></div><?php

		if( get_field('pflanzenliste') ): ?>
			<div class="pflanzenliste dd-wrapper" style="display: block;">
				<a onclick="pop_up(this)"><h4>Pflanzenliste</h4></a>
				<div class="dd pflanzenliste hidden" style="white-space: pre-line;">
					<?= the_field('pflanzenliste'); ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="projektlink">
		<a href="<?=get_site_url();?>/projektanzeige/?proj=<?=($bauteil_projektkuerzel);?>"><?=($bauteil_projektkuerzel . " · " . $projekt_name . ""); ?></a>
		<br>
		<?=($projekt_fertigstellung); ?>
	</div>

	<div class="wrap bauteile">

		<div class="bauteil-grid">	
			<?php
			$images = get_field($galerie_field);
			if( $images ) { ?>			
				<div class="thumbnails">
					<?php 
					// Render the thumbnails differently
					foreach( array_slice($images, 0, 2) as $image ) { ?>
						<a href="<?= $image['sizes']['large']; ?>" data-pswp-width="<?= $image['width'] ?>" data-pswp-height="<?= $image['height'] ?>" class="gallery-item"><img src="<?= $image['sizes']['thumbnail']; ?>" alt="<?= $image['alt']; ?>" /></a>
					<?php } ?>
				</div>
				<div class="small-galerie">
					<?php 
					// Normal images
					foreach(array_reverse( array_slice($images, 2)) as $image ) { ?>
						<a href="<?= $image['sizes']['large']; ?>" data-pswp-width="<?= $image['width'] ?>" data-pswp-height="<?= $image['height'] ?>" class="gallery-item"><img src="<?= $image['sizes']['thumbnail'];  ?>" loading="lazy" alt="<?= $image['alt']; ?>" /></a>				
					<?php } ?>
				</div>
			<?php } ?>
		</div>

		<?php if( get_field($datei_field) ): ?>
		<div class="download-links dd-wrapper">
			<a onclick="pop_up(this)"><h4>Datei-Downloads</h4></a>
			<div class="dd downloads hidden">
				<?php
				// Check rows existexists.
				if( have_rows($datei_field) ) {
					// Loop through rows.
					while( have_rows($datei_field) ) { the_row();
						// Load sub field value.
						$file = get_sub_field('datei');
						// Do something...
						if( $file ) {
							// Extract variables.
							$url = $file['url'];
							$title = $file['title'];
							?>
							<a href="<?= esc_attr($url); ?>" title="<?= esc_attr($title); ?>" target="blank">
								<span><?= esc_attr($title); ?></span>
							</a>
							<?php
						}
					}
				}
				?>
			</div>
		</div>
		<?php endif ?>
		
	</div>

</article>
