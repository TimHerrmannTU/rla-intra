<?php 
get_header();
$the_type = get_field("firmahersteller")[0];
?>
<main>
   <h1><?= the_title() ?></h1>
   <ul>
      <?php if ($the_type == "Firma") {
         ?><li><b>Gewerk:</b> <?= implode(",", get_field('gewerk')); ?></li><?php
      } ?>
      <?php if ($the_type == "Hersteller") {
         ?><li><b>Produktkategorien:</b> <?= implode(",", get_field('produktkategorie')); ?></li><?php
      } ?>
      
      <li><br></li>

      <li><b>Bundesland: </b><?= get_field('bundesland') ?></li>
      <li>
         <?php
         $full_adress = get_field('strasse').", ".get_field('plz')." ".get_field('ort');
         $google_maps_link  = "https://www.google.com/maps/search/?api=1&query=";
         $google_maps_link .= get_field("plz")."+";
         $google_maps_link .= get_field("ort")."+";
         $google_maps_link .= get_field("strasse")."+";
         $google_maps_link .= str_replace(" ", "+", get_the_title());
         ?>
         <b>Addresse:</b>
         <?= $full_adress ?>
         (<a href="<?= $google_maps_link ?>" target="_blank" style="color:var(--activeColor)">google maps<span class="dashicons dashicons-external"></span></a>)
      </li>
      <li><b>Telefon: </b><a href="tel: <?= str_replace(" ", "", get_field('telefon')) ?>"><?= esc_html(get_field('telefon')); ?></li>
      <li><b>Email: </b><a href="mailto: <?= get_field('mail')?>"><?= get_field('mail'); ?></li>
      <li><b>Website:</b> <a href="<?= esc_html(get_field('website')); ?>"><?= esc_html(get_field('website')); ?></a></li>
	  <li><b>Land:</b> <a href="<?= esc_html(get_field('land')); ?>"><?= esc_html(get_field('land')); ?></a></li>
      
      <li><br></li>

      <li><b>Ansprechpartner:</b> <?= esc_html(get_field('ansprechpartner')); ?></li>
      <li><b>Projekte mit RLA:</b> <?= esc_html(get_field('projekte_mit_rla')); ?></li>
      <li><b>Leistungsfahigkeit:</b> <?= esc_html(get_field('leistungsfahigkeit')); ?></li>
      <li><b>Anmerkung:</b> <?= esc_html(get_field('anmerkungen')); ?></li>
   </ul>
</main>
<?php get_footer(); ?>