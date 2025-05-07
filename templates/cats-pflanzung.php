<div class="bigbox wrapper fancy-categories plant">
    <?php
    wp_list_categories( array(
        'title_li' => '', // Entfernt den Titel "Kategorien"
        'show_count' => true, // Zeigt die Anzahl der Beiträge pro Kategorie an
        'orderby' => 'id', // Sortiert die Kategorien nach ID
        'hide_empty' => 0,
        'child_of' => get_cat_ID( 'pflanzungen' ) // Zeigt nur Unterkategorien von "pflanzung"
    ) );
    ?>
</div>
<?php include("cats-count-fix.php") ?>