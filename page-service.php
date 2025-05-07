<?php /* Template Name: page-service */ ?>

<?php get_header(); ?>	


<main class="fachgruppen">

	<div class="bigbox">
	<?php
	// Liste aller Kategorien erstellen, aber noch nicht ausgeben
	$cats = wp_list_categories( array(
		'taxonomy' => 'fachgruppen',
		'style' => 'list',
		'hierarchical' => true,	
		'title_li' => "",
		'hide_empty'          => 0,
		'hide_title_if_empty' => false,
		'show_count'          => 0,	
		'exclude_tree' => array(669, 670),
		'echo' => false
	));
	
	// Kategorien der obersten Ebene in Taxonomie "Fachgruppen" ermitteln, da hinter diesen keine Mitarbeiter angezeigt werden sollen
	$fachgruppen_top_lvl = get_categories(array('taxonomy' => 'fachgruppen', 'parent' => 663));
	$fachgruppen_top_lvl_str = "";
	// diese Kategorien in einen String zusammenfassen für späteren Abgleich
	foreach($fachgruppen_top_lvl as $fachgruppe_top_lvl) {
		$fachgruppen_top_lvl_str = $fachgruppen_top_lvl_str . $fachgruppe_top_lvl->name . ",";
	}
	
	// alle Kategorien der Taxonomie "Fachgruppen" ermitteln und durchgehen
	$fachgruppen = get_categories(array('taxonomy' => 'fachgruppen'));
	foreach($fachgruppen as $fachgruppe) {
		// wenn aktuelle Kategorie zur obersten Ebene gehört, keine Mitarbeiter dahinter schreiben
		if(!str_contains($fachgruppen_top_lvl_str, $fachgruppe->name))  {
			// Mitarbeiter-Posts ermitteln, die die aktuell betrachtete Kategorie haben
			$arr_mitarbeiter = get_posts(
				array(
					'post_type' => 'mitarbeiter',
					'tax_query' => array(
						array(
							'include_children' => false,
							'taxonomy' => 'fachgruppen',
							'field' => 'slug',
							'terms' => $fachgruppe->slug
						)
					)
				)
			);
			
			// gefundene Mitarbeiter in einen String zusammmenfassen
			$str_mitarbeiter = "";
			foreach($arr_mitarbeiter as $mitarbeiter) {
				$str_mitarbeiter = $str_mitarbeiter . $mitarbeiter->post_title . ", ";
			}
			if($str_mitarbeiter != "") {
				$str_mitarbeiter = rtrim($str_mitarbeiter, ", ");
				// in der Kategorienliste den Mitarbeiter-String hinter die entsprechende Kategorie hängen
				// zur Fehlervermeidung: Kategoriename nur ersetzen, wenn nicht von Leerzeichen gefolgt, da z.B. "Website" auch in "Website und Projektdokumentation tschechisch" vorkommt
				$cats = preg_replace('/' . preg_quote($fachgruppe->name, '/') . '(?!\s)/', $fachgruppe->name . ": " . $str_mitarbeiter, $cats);
			}
		}
	}
	echo $cats;
	
	?>
	</div>
	
</main>
 
 <script>	
	// Kategorienliste mittels jQuery formatieren
	$(document).ready(function(){
		// sämtliche Links entfernen
        $("main.fachgruppen a").each(function(){
            $(this).replaceWith($(this).text());
        });
		
		// Hauptkategorie "Fachgruppen" aus Liste entfernen, children unwrappen
		var mainCat = $("main.fachgruppen li.cat-item-663");
		mainCat.children().first().children().each(function(){
            $(this).removeClass();
			$(this).addClass("cat-main");
        });
		mainCat.contents().first().remove();
		mainCat.children().contents().unwrap();
		mainCat.contents().unwrap();
    });
 </script>
 
<?php get_footer(); ?>