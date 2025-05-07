<?php 
	
get_header(); ?>

<?php 
	//Daten aus Access Datenbank abfragen für Auflistung von Bauteilen in bauarchiv-entry.php
	include('templates/abfrage-projektname-jahr.php');
?>

<main>
	
	<div class="breadcrumb"><a href="<?php bloginfo('url')?>/#do-bauteile" <!-- javascript einbauen : Bauteilfenster öffnen /#do-bauteile class "active" zuweisen -->Bauteile</a>/<?php single_cat_title(); ?></div>

	<?php 
	if (have_posts()) {
		while (have_posts()) : the_post();
			include('templates/bauarchiv-entry.php');
		}
		include('templates/bauarchiv-entry-functions.php');
	} 
	?>

</main>

<?php
	/*
		* Kommentare sind auf Seiten deaktiviert.
		* Möchtest du die Kommentarfunktion auf Seiten aktivieren, entferne einfach die beiden "//"-Zeichen vor "comments_template();"
	*/
	
	//comments_template();
?>

<!-- content -->


<?php get_footer(); ?>

