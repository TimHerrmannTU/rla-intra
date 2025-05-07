<?php get_header();
include('templates/abfrage-projektname-jahr.php');
include('templates/bauarchiv-entry-functions.php');
////////////////////////////////////////
// Display child cats of selected cat //
////////////////////////////////////////
$final_cat = get_category_by_slug(get_query_var("category_name"));
$sub_cats = get_categories( array( 'parent' => $final_cat->cat_ID ) );
// render all child cats if any are present
if ($sub_cats) { ?>
	<a id="sub-cats-trigger" onclick="$('#sub-cats').toggle()"><del></del>...</a>
	<div id="sub-cats" class='breadcrumb row' style='display: none; margin-top: 0.3rem !important; flex-wrap: wrap; gap: 0.2rem 1rem;'>
		<?php foreach($sub_cats as $sc) {
			?><a href="<?= $sc->slug ?>"><?= $sc->name; ?></a><?php
		} ?>
	</div>
<?php } ?>

<?php
$posts = get_posts([
	'post_type' => 'Bauinfo',
	'post_status' => 'publish',
	'numberposts' => -1
]);
$rel_post_ID;
foreach ($posts as $post) {
	$cats = get_the_category($post->ID);
	foreach ($cats as $cat) {
		if ($cat->slug == get_query_var("category_name")) {
			$rel_post_ID = $post->ID;
		}
	}
}
if (isset($rel_post_ID)) { ?>
	<div class="bauinfo col">
		<h3>Bauinfo</h3>
		<?php
		$author = get_field("verantwortlicher", $rel_post_ID);
		$doc_fields = array("dokumente-regelwerke", "dokumente");
		foreach ($doc_fields as $df) {
			$docs = get_field($df, $rel_post_ID);
			if ($docs) {
				foreach ($docs as $doc) {
					?><a href="<?= $doc["dokument"]["url"] ?>"><?= $doc["dokument"]["title"] ?></a><?php
				}
			}
		}
		?>
	</div>
<?php }?>

<main></main>

<script>
// insert breadcrumb into header
$("#sub-cats-trigger").appendTo("#breadcrumb")
$("#sub-cats").appendTo("#breadcrumb")
////////////////////////
// AJAX Page Creation //
////////////////////////
var page = 0; // Tracks what posts to fetch next
var scrolled = true; // "mutex" to prevent parallel fetching of new posts
function fetch_posts() {
	// ajax function to get 20 posts from wp
	// posts per refresh can be adjusted in:
	// functions.php query_posts_ajax())
	$.ajax({
		type : 'POST',
		url: '<?php echo admin_url('admin-ajax.php'); ?>',
		async: false,
		data : {
			page: page,
			action: "query_posts_ajax",
			cat: "<?= get_query_var("category_name") ?>"
		},
		success : function (result) {
			if (result != "BREAK") {
				$("main").append(result);
				scrolled = false; // release
			}
		},
		error : function () {
			console.log ('error');
		}
	})
}
fetch_posts() // initialize page
// infinite scrolling function
$(window).on("scroll", function() {
	var distance_bottom = $(document).height() - $(window).height() - $(window).scrollTop();
	if (distance_bottom < 1000 && !scrolled) {
		scrolled = true; // lock
		page += 1;
		fetch_posts();
	}
});
</script>

<?php get_footer(); ?>