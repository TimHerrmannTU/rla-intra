<div class="bigbox wrapper fancy-categories mat">

    <?php
    wp_list_categories( array(
        'title_li' => '', // Entfernt den Titel "Kategorien"
        'hide_empty' => 0, // Zeigt leere Kategorien
        // 'show_count' => true, // debugging
        'orderby' => 'id', // Sortiert die Kategorien nach Namen
        'child_of' => get_cat_ID( 'materialien' ) // Zeigt nur Unterkategorien von "bauteile"
    ) );

    $cats = get_categories(
        array(
            "child_of" => get_category_by_slug('materialien')->term_id,
            'hide_empty' => 0
        )
    );


/*	ALTE no_page VARIANTE (get_page_by_title ist deprecated!)
	
    $no_page = array();
    foreach ($cats as $cat) {
        // check if there is a post related to the cat
        if (!get_page_by_title($cat->name, OBJECT, 'materialien')) {
            array_push($no_page, $cat->name);
        }
    }
*/

/*	NEUE no_page VARIANTE */

	$no_page = array();

    foreach ($cats as $cat) {
        // Use WP_Query to check if a page with the category name exists
        $query = new WP_Query(array(
            'post_type' => 'materialien',
            'title' => $cat->name,
            'posts_per_page' => 1
        ));

        // If no posts are found, add the category name to the no_page array
        if (!$query->have_posts()) {
            array_push($no_page, $cat->name);
        }

        // Reset post data
        wp_reset_postdata();
    }

    ?>

</div>
<script>
    function slugify(str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim leading/trailing white space
        str = str.toLowerCase(); // convert string to lowercase
        str = str.replace(/[^a-z0-9 -]/g, '') // remove any non-alphanumeric characters
                .replace(/\s+/g, '-') // replace spaces with hyphens
                .replace(/-+/g, '-'); // remove consecutive hyphens
        return str;
    }
	$(".mat .cat-item a").each( function() {
        //var link = $(this).attr("href")
        //link = link.split("/")
        //link = link[link.length -2]
        var link = slugify($(this).text())
        var new_link = "<?= get_site_url() ?>/materialien/"+link+"/";
		$(this).attr("href", new_link)
	})
	var no_page = <?= json_encode($no_page) ?>;
	no_page.forEach(e => {
		var useless_link = $(".mat a:contains("+e+")")
		useless_link.removeAttr("href")
		useless_link.addClass("disabled")
	})
</script>