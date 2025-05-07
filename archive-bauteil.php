<?php 
get_header();
//Daten aus Access Datenbank abfragen für Auflistung von Bauteilen in bauarchiv-entry.php
include('templates/abfrage-projektname-jahr.php');
?>

<main>
   <?php 
   if (have_posts()) {
      while (have_posts()) { the_post();
         include('templates/bauarchiv-entry.php');
      }
		include('templates/bauarchiv-entry-functions.php');
   } 
   ?>
</main>
<?php get_footer(); ?>