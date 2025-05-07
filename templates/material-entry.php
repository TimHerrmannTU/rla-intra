<article class="smallbox bauteil">	
	
	<?php
		// Nur wenn material-entry für die Anzeige von Suchergebnissen verwendet wird:
		// --------------------------------------------------------------------------
		// getroffene Suchbegriffe hervorheben und in anderen Variablen mit "_highlight" speichern --> erhalten span-Tag mit class 'suche-highlight'
		// die ursprünglichen Variablen ohne html-Tags werden bewahrt
		$material_titel_highlight = get_the_title();
		$material_beschreibung_highlight = get_the_content();
		if (!empty($suche)) {
			foreach ($suchbegriffe as $begriff) {
				$material_titel_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $material_titel_highlight);
				$material_beschreibung_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $material_beschreibung_highlight);
			}
		}
	?>
	
	<div class="element-title">
		<h3><a href="<?php the_permalink() ?>"><?php echo ($material_titel_highlight); ?></a></h3>
	</div>
	<?php 
		$image = get_field('bild');
		if(!empty($image)) {
			echo('<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) .'" width="10%" />');
		}
	?>
	<p><?php echo ($material_beschreibung_highlight); ?></p>
	
</article>