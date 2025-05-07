<?php
/* Register stylesheets */

/*-----------------------------------------------------------------------------------*/
/*  Enqueue scripts and styles
/*-----------------------------------------------------------------------------------*/

/* Globale Angaben zur Projektdatenbankabfrage */
global $projDB_servername;
global $projDB_username;
global $projDB_password;
global $projDB_dbname;
global $projDB_tablename;
$projDB_servername = "localhost";
$projDB_username = "intranet";
$projDB_password = "intra8119690rla";
$projDB_dbname = "projektdatenbank";
$projDB_tablename = "vollständige Liste";


/* RLA Intranet Scripts einbinden */
function rlaintra_scripts() {
	global $wp_styles;

	// Loads main stylesheet.
	wp_enqueue_style( 'rlaintra-style', get_stylesheet_uri(), array(), null );
	wp_enqueue_style( 'style-nav', get_template_directory_uri() . '/static/css/style-nav.css', array(), null );
	wp_enqueue_style( 'rlaintra-style-weiss-saans', get_template_directory_uri() . '/static/css/style_weiss_saans.css', array(), null );
	wp_enqueue_style( 'lounge', get_template_directory_uri() . '/static/css/photoswipe.css', array(), null );
	wp_enqueue_style( 'rla-fontello24', get_template_directory_uri() . '/static/css/style-fontello24.css', array(), null );
	
	// Loads jQuery
	wp_enqueue_script( 'jquery-3.7.1.min', get_template_directory_uri() . '/static/js/jquery-3.7.1.min.js', array(), null );
	wp_enqueue_script( 'jquery.filterizr.min.js', get_template_directory_uri() . '/static/js/jquery.filterizr.min.js', array("jquery"), null );

	// Icons
	wp_enqueue_script( 'jquery.iconify.js', get_template_directory_uri() . '/static/js/iconify.js', array("jquery"), null );
	
	// Loads Custom JavaScript functionality
	wp_enqueue_script( 'functions-js', get_template_directory_uri() . '/static/js/functions.js', array(), null );
	
	//smooth-scroll (ERROR)
	//wp_enqueue_script( 'smooth-scroll', get_template_directory_uri() . '/scripts/smooth-scroll.polyfills.min.js', array(), null );
}
add_action( 'wp_enqueue_scripts', 'rlaintra_scripts' );


/* Large Image Resize Threshhold hoch setzen */
/* Wird vom Plugin Imsanity übernommen! */
/*function rla_big_image_size_threshold( $threshold ) {
 return 4000;
}
add_filter( 'big_image_size_threshold', 'rla_big_image_size_threshold', 999, 1);*/


/* Nicht benötigte Menüpunkte in Dashboard Menü am linken Rand ausblenden */
function remove_menu_links() {
	//remove_menu_page('edit.php'); // Menülink Artikel
	remove_submenu_page('edit.php', 'edit.php'); // Untermenü Alle Beiträge
	remove_submenu_page('edit.php', 'post-new.php'); // Untermenü Neuen Beitrag erstellen
	remove_menu_page('edit-comments.php'); // Menülink Kommentare
	//remove_menu_page('upload.php'); // Menülink Mediathek
	//remove_menu_page('users.php'); // Menülink Benutzer
	//remove_menu_page('user-new.php'); // Menülink Benutzer neu hinzufügen
	//remove_menu_page('profile.php'); // Menülink Benutzer Dein Profil
	//remove_menu_page('tools.php'); // Menülink Werkzeuge
	//remove_menu_page('export.php'); // Menülink Daten exportieren
	//remove_menu_page('themes.php'); // Menülink Design
	//remove_menu_page('options-general.php'); // Menülink Einstellungen
}
add_action( 'admin_menu', 'remove_menu_links' );


/* Menüpunkte in Dashboard Menü am linken Rand umbenennen */
function wd_admin_menu_rename() {
     global $menu; // Global to get menu array
     $menu[5][0] = 'Alle Taxonomien'; // Change name of posts
}
add_action( 'admin_menu', 'wd_admin_menu_rename' );


/* Dashicons im Frontend verfügbar machen */
function use_dashicons_on_front_end() {
wp_enqueue_style( 'dashicons' );
}
add_action( 'wp_enqueue_scripts', 'use_dashicons_on_front_end' );


/* Zur Übergabe des Projektkürzels als URL Parameter */
function wp_custom_query_vars_filter($vars) {
    $vars[] = 'proj';
	$vars[] = 'suche';
	$vars[] = 'new_post_krz';
	$vars[] = 'new_post_name';
    return $vars;
}
add_filter( 'query_vars', 'wp_custom_query_vars_filter' );


/* Register menus */
add_action( 'init', 'register_my_menus' );
function register_my_menus() {
        register_nav_menus(
                array(	'primary' => __( 'Primary' ),
						'secondary' => __( 'Secondary' ),
						'rla-intern' => __( 'rla-intern' ),
						'rla-archiv' => __( 'rla-archiv' ),
						'rla-info' => __( 'rla-info' )
                )
        );
}

/* Custom posts */
function my_cptui_add_post_types_to_archives( $query ) {
	// We do not want unintended consequences.
	if ( is_admin() || ! $query->is_main_query() ) {
		return;    
	}

	if ( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
		$cptui_post_types = cptui_get_post_type_slugs();

		$query->set(
			'post_type',
			array_merge(
				array( 'post' ),
				$cptui_post_types
			)
		);

		$query->set('posts_per_page', 20); // 20 Elemente pro Seite anzeigen
	}
}
add_filter( 'pre_get_posts', 'my_cptui_add_post_types_to_archives' );


function new_subcategory_hierarchy() { 
    $category = get_queried_object();
 
    $parent_id = $category->category_parent;
 
    $templates = array();
     
    if ( $parent_id == 0 ) {

        $templates[] = "category-{$category->bauteile}.php";
        $templates[] = "category-{$category->term_id}.php";
        $templates[] = 'category.php';     
    } else {

        $parent = get_category( $parent_id );
 

        $templates[] = "category-{$category->slug}.php";
        $templates[] = "category-{$category->term_id}.php";
 

        $templates[] = "category-{$parent->slug}.php";
        $templates[] = "category-{$parent->term_id}.php";
        $templates[] = 'category.php'; 
    }
    return locate_template( $templates );
}
 add_filter( 'category_template', 'new_subcategory_hierarchy' );


// breadcrumb
function nav_breadcrumb() {
	
	$delimiter = '<del></del>'; //'&raquo;';
	$home = 'Home'; 
	$before = '<span class="current-page">'; 
	$after = '</span>'; 
	
	if ( !is_home() && !is_front_page() || is_paged() ) {
	
		echo '<nav id="breadcrumb" class="breadcrumb">';
		
		global $post;
		$homeLink = get_bloginfo('url');
		echo '<a href="' . $homeLink . '">' . $home . '</a>' . $delimiter;

		if ( is_category()) { 
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0){
				echo(get_category_parents($parentCat, TRUE, $delimiter)); // all parent cats
			}
			echo $before . single_cat_title('', false) . $after; // final cat
		}
		elseif ( is_day() ) {
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter;
			echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $delimiter;
			echo $before . get_the_time('d') . $after;
		}
		elseif ( is_month() ) {
			echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter;
			echo $before . get_the_time('F') . $after;
		} 
		elseif ( is_year() ) {
			echo $before . get_the_time('Y') . $after;
		} 
		elseif ( is_single() && !is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite["slug"];
				$name = $post_type->labels->singular_name;
				switch ($slug) {
					case "mitarbeiter":
						$slug = "team";
						$name = "Team";
						break;
					case "bauteil":
						$slug = "bauteile";
						$name = "Bauteile";
						break;
					case "pflanzung":
						$slug = "pflanzungen";
						$name = "Pflanzungen";
						break;
					case "planungsthema":
						$name = "Planungsthemen";
						break;
					case "standort":
						$name = "Standorte";
						break;
					case "firma":
						$slug = "firmenverzeichnis";
						$name = "Firmenverzeichnis";
						break;
					default:
						break;
				}
				echo '<a href="' . $homeLink . '/' . $slug . '/">' . $name . '</a>' . $delimiter;
				echo $before . get_the_title() . $after;
			} 
			else {
				$cat = get_the_category(); $cat = $cat[0];
				echo get_category_parents($cat, TRUE, $delimiter);
				echo $before . get_the_title() . $after;
			}
		}
		elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;
		}
		elseif ( is_attachment() ) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID); $cat = $cat[0];
			echo get_category_parents($cat, TRUE, $delimiter);
			echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>' . $delimiter;
			echo $before . get_the_title() . $after;
		}
		elseif ( is_page() && !$post->post_parent ) {
			$title = get_the_title();
			switch ($title) {
				case "Projektanzeige":
					$title = "Projekte";
				case "Projekt-Archiv":
					$title = "Projekte";
			}
			echo $before . $title . $after;
		} 
		elseif ( is_page() && $post->post_parent ) {
			$parent_id = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) echo $crumb . $delimiter;
			echo $before . get_the_title() . $after;
		} 
		elseif ( is_search() ) {
			echo $before . 'Ergebnisse für Ihre Suche nach "' . get_search_query() . '"' . $after;
		} 
		elseif ( is_tag() ) {
			echo $before . 'Beiträge mit dem Schlagwort "' . single_tag_title('', false) . '"' . $after;
		} 
		elseif ( is_404() ) {
			echo $before . 'Fehler 404' . $after;
		}
		
		echo '</nav>';
	
	} 
} 

/////////////////////////////////////////////
// AJAX Page creation for the category.php //
/////////////////////////////////////////////
function query_posts_ajax() {
	// Loading Post Data
	$page = $_POST["page"];
	$ppp = 20; // posts per page
	$cat = get_category_by_slug($_POST["cat"])->term_id;
	// create wp query
	$args = array(
		'post_type'		 => array('bauteil', 'pflanzung'),
		'cat' 			 => $cat,
		'posts_per_page' => -1, # remove limit
	);
	try {
		$ajax_posts = new WP_Query( $args );
		if ( $ajax_posts->have_posts() ) {
			// pagination variables
			$index = 0;
			$pc = $ajax_posts->post_count;
			$start = $page*$ppp;
			// Loading Project Data
			include('templates/abfrage-projektname-jahr.php');
			$pros = array();
			foreach ($result as $pro) {
				$fin_date = $pro["2"];
				if (!is_numeric($fin_date)) $fin_date = 9999; // overwriting "in Planung"
				$pros[$pro["0"]] = $fin_date;
			}
			// Sorting Posts
			usort($ajax_posts->posts, function($pt1, $pt2) use ($pros) {
				$pro1 = $pros[get_field("projektkurzel", $pt1->ID)];
				$pro2 = $pros[get_field("projektkurzel", $pt2->ID)];
				return strcasecmp($pro2, $pro1);
			});
			// Rendering Posts
			$response = "";
			if ($start < $pc) {
				while ( $ajax_posts->have_posts() ) {
					$ajax_posts->the_post();
					// only processing the relevant posts that are contained in the current interval
					if ( ($start <= $index) && ($index < $start+$ppp) && ($index <= $pc) ) {
						// $result needs to be passed as a parameter otherwise it is out of scope
						$response .= get_template_part("templates/bauarchiv-entry", null, array("result" => $result));
					}
					$index += 1;
				}
			}
			else {
				$response = "BREAK"; // prevents further requests from being send
			}
			echo $response;
		}
		else { echo "No posts found..."; }
	}
	catch(PDOException $e) {
		echo "ajax query failed: ".$e->getMessage();
	}
	exit;
}
add_action('wp_ajax_query_posts_ajax', 'query_posts_ajax');
add_action('wp_ajax_nopriv_query_posts_ajax', 'query_posts_ajax');

?>