<?php /* Template Name: page-firmen */ ?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>  
	<div id="export-modal" style="display:none">
		<div class="modal-wrapper">
			<h2>Daten exportieren</h2>
			<div class="toggle-parent col gap-1">
				<h3>Verwendungszweck</h3>
				<div class="text-toggle" f-key="use">
					<div class="option left selected" f-val="intern">Intern</div>
					<div class="slider"></div>
					<div class="option right" f-val="extern">Extern</div>
				</div>
				<p>Alle Daten und Anmerkungen</p>
				<p style="display:none">Nur Name und Kontaktdaten</p>
			</div>
			<div class="toggle-parent col gap-1">
				<h3>Dateiart</h3>
				<div class="text-toggle" f-key="file-type">
					<div class="option left selected" f-val="docx">Word</div>
					<div class="slider"></div>
					<div class="option right" f-val="xlsx">Excel</div>
				</div>
				<h4 id="filename"><span class="type">firmen</span>_<span class="use">intern</span>_liste.<span class="file-type">docx</span></h4>
			</div>
			<div class="row gap-2">
				<button id="export" class="filter-button bordered">EXPORTIEREN</button>	
				<button class="filter-button bordered" onclick="$('#export-modal').hide()">ABBRECHEN</button>	
			</div>	
		</div>
	</div>
	<main class="col gap-1">
		<div class="controls gap-2 w-100 mb-2">
			<h2 class="toggle-me">Firmen</h2>
			<h2 class="toggle-me" style="display:none">Hersteller</h2>
			<a class="option-button" onclick="$('#export-modal').show()"><span class="iconify" data-icon="mdi-download"></a>
			<div id="type-toggle" class="text-toggle">
				<div class="option left selected" f-val="firma">Firmen</div>
				<div class="slider"></div>
				<div class="option right" f-val="hersteller">Hersteller</div>
			</div>
			<div class="selector col toggle-me" ui="firma">
				<select class="fs-l" type="tag">
					<option value="default">Gewerke</option>
					<?php
					$types = get_field_object("field_671a024c5fcd9")["choices"];
					foreach ($types as $type) {
						?><option val="<?= $type ?>"><?= $type ?></option><?php
					}
					?>
				</select>
				<span class="icon-down-open-2"></span>
			</div>
			<div class="selector col toggle-me" ui="hersteller" style="display:none">
				<select class="fs-l" type="tag">
					<option value="default">Produktkategorie</option>
					<?php
					$types = get_field_object("field_673f4baaeb46c")["choices"];
					foreach ($types as $type) {
						?><option val="<?= $type ?>"><?= $type ?></option><?php
					}
					?>
				</select>
				<span class="icon-down-open-2"></span>
			</div>
			<div class="selector col">
				<select class="fs-l" type="bundesland">
					<option value="default">Bundesland</option>
				</select>
				<span class="icon-down-open-2"></span>
			</div>
			<div class="selector col">
				<select class="fs-l" type="ort">
					<option value="default">Ort</option>
				</select>
				<span class="icon-down-open-2"></span>
			</div>
			<div class="searchform">
				<input id="pro-search" name="suche" type="text" placeholder="Suche..." > 
				<div class="square-wrapper">
					<input type="submit" class="search-button-1" value="">
				</div>
			</div>
			<a class="option-button" onclick="location.reload();"><span class="iconify" data-icon="mdi-circle-arrows"></a>
		</div>
		<?php 
		$custom_types = array("firma", "hersteller");
		foreach ($custom_types as $ct) { 
			$custom_type = $ct;
			// args
			$args = array(
				'numberposts'   => -1,
				'orderby'       => 'title',
				'order'			=> 'ASC',
				'post_type'     => 'firma',
				'meta_query' => array(
					array(
						'key'     => 'firmahersteller',
						'value'   => $ct, // The specific checkbox value you want to filter by
						'compare' => 'LIKE', // Since checkboxes are stored as serialized arrays
					),
				),
			);
			?>
			<div class="pro-table toggle-me"  ui="<?= $custom_type ?>" style="display:<?= ($custom_type == 'firma') ? "" : "none" ?>">
				<?php
				// query
				$the_query = new WP_Query( $args );
				if( $the_query->have_posts() ) {
					include('templates/fp-liste-firmen.php');				
				}
				wp_reset_query();
				?>
			</div>
			
		<?php } ?>

	</main>
<?php endwhile; endif; ?>

<style>
/* EXPORT MODAL */
#export-modal {
	position: fixed;
	top: 0;
	left: 0;
	z-index: 100;
	height: 100vh;
	width: 100vw;
	background-color: rgba(0,0,0,0.8);
	display: flex;
	justify-content: center;
}
#export-modal .modal-wrapper {
	margin: auto;
	padding: 2rem;
	border-radius: 0.5rem;
	background-color: white;
	width: fit-content;
	display: flex;
	flex-direction: column;
	gap: 3rem;
}
#export-modal * {
	margin: 0;
}
#export-modal button {
	height: 50px;
	width: 100%;
	border-width: 0.2rem;
}

/* CONTROLS */
.controls {
	display: grid;
	grid-template-columns: auto repeat(3, minmax(0, 1fr)) auto auto;
}
.controls h2 {
	margin: 0;
	grid-column: 1/6;
}
.searchform {
	width: 450px;
}

/* TEXT TOGGLE */
.text-toggle {
	border: 0.2rem solid black;
	display: flex;
	flex-direction: row;
	min-width: 15rem;
	width: 100%;
	text-align: center;
	position: relative;
}
.text-toggle:hover {
	cursor: pointer;
}
.text-toggle .option {
	flex: 1;
	padding: 0.5rem 0.7rem;
	z-index: 1;
	mix-blend-mode: black;
	transition: color 0.3s ease-in-out;
}
.text-toggle .slider {
	background-color: black;
	border: 0.1rem solid white;
	height: 100%;
	width: 50%;
	position: absolute;
	left: 0;
	transition: transform 0.3s ease-in-out;
}
.text-toggle.active .slider {
	left: unset;
	transform: translateX(100%);
	transition: transform 0.3s ease-in-out;
}
.text-toggle.active .option.right  {
	color: white;
}
.text-toggle:not(.active) .option.left  {
	color: white;
}

/* DROPDOWN */
.selector {
	position: relative;
}
.selector .icon-down-open-2 {
	position: absolute;
	right: 10px;
	top: 10px;
}
.selector select {
	height: 100%; 
	background-color: transparent;
	border: thin solid black;
	display: inline-block;
	font: inherit;
	line-height: 1.5em;
	padding: 0.2em 0.5em;
	z-index: 1;
	
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	-webkit-appearance: none;
	-moz-appearance: none;
}
.selector select:focus {
	outline: thin solid black;
}
.radio .radio-label {
	height: 15px;
	line-height: 15px;
}
.option-button {
	height: fit-content;
	width: fit-content;
	font-size: 30px;
	margin: auto 0 auto auto;
	border: 0.15rem solid black;
	border-radius: 100%;
	padding: 4px;
	transition: all 0.2s linear;
}
.option-button:hover {
	background-color: black;
	color: white;
}
</style>

<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/xlsx.full.min.js"></script>
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/firmen-archive.js"></script>
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/docxtemplater.js"></script>
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/pizzip.js"></script>
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/pizzip-utils.js"></script>
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/FileSaver.js"></script>
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/firmen-export.js"></script>

<?php get_footer(); ?>