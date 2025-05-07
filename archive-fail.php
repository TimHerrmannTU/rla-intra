<?php 
get_header();
//Daten aus Access Datenbank abfragen für Auflistung von Fails in bauarchiv-entry.php
include('templates/abfrage-projektname-jahr.php');
?>

<main>

	<p>
      Manchmal geht doch etwas schief... Aber aus Fehlern lernt man.
      Anbei eine kleine worst-practice-Sammlung, teils aus eigenen, teils aus fremden Beständen.
      Frische Beiträge willkommen.
	</p>
   
   <?php 
   if (have_posts()) {
      while (have_posts()) { 
         the_post();
         include('templates/bauarchiv-entry.php');
      }
		include('templates/bauarchiv-entry-functions.php');
   } 
   ?>
</main>
<?php get_footer(); ?>