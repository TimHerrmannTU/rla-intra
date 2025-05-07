<?php /* Template Name: page-projektarchiv */ ?>

<?php
// Datenbankinfo aus Globals abrufen --> zu finden in functions.php
global $projDB_servername;
global $projDB_username;
global $projDB_password;
global $projDB_dbname;
global $projDB_tablename;

// Query mit allen abzufragenden Feldern
$cat_query = 'SELECT DISTINCT `Kategorie` FROM `'.$projDB_tablename.'` ORDER BY `Kategorie` ASC';

// the table columns will be generated in order of the array => move around array elements to adjust the order
// def => columns that will be displayed right on initilisation
// hide => columns that have no relavence for the user (but for the code)
// target => determines where the fitting buttons will be displayed in the control section
$cols = array(
	array("def" => 1, "prodo" => 0, "hidden" => 0, "target" => 0, "field" => "Projektkürzel", "name" => "Kürzel"),
	array("def" => 1, "prodo" => 1, "hidden" => 0, "target" => 0, "field" => "Projektname", "name" => "Name"),
	array("def" => 1, "prodo" => 1, "hidden" => 0, "target" => 0, "field" => "Ort", "name" => ""),
	array("def" => 0, "prodo" => 0, "hidden" => 0, "target" => 0, "field" => "Land", "name" => ""),
	array("def" => 1, "prodo" => 1, "hidden" => 0, "target" => 0, "field" => "Jahr Fertigstellung", "name" => "Fertigstellung"),
	array("def" => 0, "prodo" => 0, "hidden" => 0, "target" => 0, "field" => "VGV-Planungszeitraum", "name" => "Planungszeit"),
	array("def" => 0, "prodo" => 1, "hidden" => 0, "target" => 0, "field" => "Fläche in ha", "name" => "Fläche"),
	array("def" => 0, "prodo" => 1, "hidden" => 0, "target" => 0, "field" => "Bausumme gesamt", "name" => "Bausumme"),
	array("def" => 0, "prodo" => 1, "hidden" => 0, "target" => 0, "field" => "Beauftragte Leistungphasen/1", "name" => "Leistungsphasen"),
	array("def" => 0, "prodo" => 1, "hidden" => 0, "target" => 0, "field" => "Auftraggeber", "name" => ""),
	array("def" => 0, "prodo" => 0, "hidden" => 0, "target" => 0, "field" => "Ansprechpartner", "name" => ""),
	array("def" => 0, "prodo" => 1, "hidden" => 0, "target" => 0, "field" => "Planungspartner", "name" => ""),
	array("def" => 1, "prodo" => 0, "hidden" => 0, "target" => 0, "field" => "Projektleiter", "name" => "Projektleitung"),
	array("def" => 0, "prodo" => 0, "hidden" => 0, "target" => 0, "field" => "Mitarbeiter", "name" => ""),
	array("def" => 0, "prodo" => 0, "hidden" => 0, "target" => 0, "field" => "Art der Planung", "name" => "Planungsart"),

	array("def" => 0, "prodo" => 0, "hidden" => 0, "target" => 1, "field" => "Fördermittel", "name" => ""),
	array("def" => 0, "prodo" => 0, "hidden" => 0, "target" => 1, "field" => "Auszeichnungen", "name" => ""),
	array("def" => 0, "prodo" => 0, "hidden" => 0, "target" => 1, "field" => "Zertifikate", "name" => ""),
 
	array("def" => 0, "prodo" => 0, "hidden" => 1, "target" => 2, "field" => "Projektdokumentation anlegen", "name" => ""),
	array("def" => 0, "prodo" => 0, "hidden" => 1, "target" => 2, "field" => "in die Website aufnehmen", "name" => ""),
	array("def" => 0, "prodo" => 0, "hidden" => 1, "target" => 2, "field" => "Projektfotos machen", "name" => ""),
	array("def" => 0, "prodo" => 0, "hidden" => 1, "target" => 2, "field" => "Projektdaten klären", "name" => ""),
 
	array("def" => 0, "prodo" => 0, "hidden" => 1, "target" => null, "field" => "Kategorie", "name" => ""),
	array("def" => 0, "prodo" => 0, "hidden" => 1, "target" => null, "field" => "Beginn Planung", "name" => ""),
	array("def" => 0, "prodo" => 0, "hidden" => 1, "target" => null, "field" => "Ende Planung", "name" => ""),
);

// generate part of the query
$field_array = array_map(function($col) {
	return $col["field"];
}, $cols);
$fields = "`" . implode("`, `", $field_array) . "`";
// finalise query
$pro_query = "SELECT " . $fields . " FROM `" . $projDB_tablename . "` ORDER BY `Jahr Fertigstellung` DESC";

// Verbindung zur Datenbank aufbauen
try {
	$conn = new PDO("mysql:host=$projDB_servername;dbname=$projDB_dbname", $projDB_username, $projDB_password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $e) {
	die("Verbindung zur Projektdatenbank fehlgeschlagen: " . $e->getMessage());
}

// Query ausführen und Daten extrahieren
try {
	$cats = $conn->query($cat_query)->fetchAll();
	$pros = $conn->query($pro_query)->fetchAll();
}
catch(PDOException $e) {
	die("pro_query fehlgeschlagen: " . $e->getMessage());
}
unset($pro_query);
unset($cats[0]); // useless first element
?>

<?php get_header(); ?>

<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">

<div class="side-menu row">
	
	<button class="close-options" id="options-trigger">
		<img class="flip" src="<?php bloginfo('template_directory');?>/static/img/chevron-left-solid.svg"></a>
	</button>

	<div id="pro-controls" class="col gap-1">
		
		<h3>Projekte filtern</h3>
		<div class="control-buttons row gap-1">
			<a class="option-button" onclick="location.reload();"><span class="iconify" data-icon="mdi-circle-arrows"></a>
			<a class="option-button close-options"><span class="iconify" data-icon="mdi-close"></a>
		</div>
		
		<div class="searchform">
			<input id="pro-search" name="suche" type="text" placeholder="Suche..." > 
			<div class="square-wrapper">
				<input type="submit" class="search-button-1" value="">
			</div>
		</div>
			
		<div class="cats-pro bigbox">
			<div class="row gap-1">
				<a id="show-all" class="active" f-cat="Alle">Alle</a>
				<button id="show-cats">Kategorien <span class="icon-down-open-2"></span></button>
			</div>
			<div id="cats" class="row gap-05 w-100" style="display:none">
				<?php 
				$col_count = 4;
				$cats_per_col = ceil(count($cats) / $col_count);
				for ($i = 0; $i < $col_count; $i++) {
					$col_cats = array_slice($cats, $cats_per_col*$i, $cats_per_col);
					?><div class="col w-25"><?php
						foreach($col_cats as $cat) {
							if ($cat[0]) {
								?><a f-cat="<?= $cat[0] ?>"><?= $cat[0] ?></a><?php
							}
						}
					?></div><?php
				}
				?>
			</div>
		</div>


		<div class="col">
			<h4>Länder:</h4>
			<div class="row gap-05 wrapped" f-radio f-col="Land"></div>
		</div>
		<div class="col">
			<h4>Orte:</h4>
			<div class="row gap-05 wrapped" f-radio f-col="Ort"></div>
		</div>
		<div class="col">
			<h4>Art der Planung:</h4>
			<div class="row gap-05 wrapped" f-radio f-col="Art der Planung"></div>
		</div>

		<?php 
		/*
		// AREA
		$title = "Fläche:";
		$id = "area";
		$unit = " ha";
		$min = 0;
		$max = 2000000;
		include("templates/range-input.php");
		$title = "Bausumme:";
		$id = "cash";
		$unit = " €";
		$min = 0;
		$max = 50000000;
		include("templates/range-input.php");
		*/
		// TIME
		$title = "Fertigstellung:";
		$id = "time";
		$unit = "";
		$min = 1991;
		$max = 2024;
		include("templates/range-input.php");
		?>
		<div class="row gap-05 v-centered">
			<input class="fancy-cb" id="planned" type="checkbox" checked=true><label>in Planung</label>
		</div>

		<div class="row gap-1 wrapped" f-type="hide-row-if-col-empty">
			<?php
			foreach ($cols as $col) {
				if ($col["target"] == 1) {
					?>
					<div class="row gap-05 centered">
						<input class="fancy-cb" f-col="<?= $col["field"] ?>" type="checkbox"><label>hat <?= ($col["name"] == "") ? $col["field"] : $col["name"] ?></label>
					</div>
					<?php
				}
			} 
			?>
		</div>

		<div id="bonus-cols">
			<div>Angezeigte Spalten:</div>
			<div class="row gap-05 wrapped">
				<?php
				foreach ($cols as $col) {
					if ($col["hidden"] == 0) {
						?>
						<button 
							class="filter-button bordered <?= (($col["def"] == 1) ? "active" : ""); ?>" 
							f-target="<?= $col["field"] ?>"
							f-prodo="<?= $col["prodo"] ?>"
							style="<?= ($col['def'] == 0) ? 'display:none' : '' ?>"
						>
							<?= ($col["name"] == "") ? $col["field"] : $col["name"]; ?>
						</button>
						<?php
					}
				}
				?>
				<button id="toggle-col-buttons" class="filter-button bordered" hide="true">
					<span class="iconify" data-icon="mdi-add">
				</button>
			</div>
		</div>
		
		<div>
			<button class="filter-button" onclick="$(this).find('span').toggleClass('up'); $('#dev-options').toggle();" style="border-bottom:1px solid black">
				Projektdokumentation <span class="icon-down-open-2"></span>
			</button>
		</div>
		<div id="dev-options" class="row gap-1 wrapped" f-type="hide-row-if-col-empty" style="display:none">
			<?php
			foreach ($cols as $col) { 
				if ($col["target"] == 2) {
					?>
					<div class="row gap-05 centered">
						<input class="fancy-cb" f-col="<?= $col["field"] ?>" type="checkbox"><label><?= ($col["name"] == "") ? $col["field"] : $col["name"] ?></label>
					</div>
					<?php 
				}
			}
			?>
		</div>
		
		<div>
			<button class="filter-button bordered" onclick="$('#export-modal').show()" style="height:fit-content">EXPORTIEREN</button>
		</div>
		
		<div class="templates" style="display: none">
			<button class="filter-button bordered"></button>
		</div>

	</div>
	
</div>


<main id="projekt-archiv">
	<div class="pro-table smallbox">
		<table class="w-100">
			<thead>
				<tr>
					<th style="width: 2rem;" f-hidden="1"></th>
					<?php foreach ($cols as $col) { ?>
						<th
							f-filtered=<?= $col["def"] ?>
							f-state="0"
							f-index="<?= $col["field"] ?>"
							f-hidden=<?= $col["hidden"] ?>
							<?= ($col["def"] == 0) ? 'style="display: none"' : ""; ?>
						>
							<?= ($col["name"] == "") ? $col["field"] : $col["name"] ?>
						</th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($pros as $pro): 
					// Zeitraumgenerierung
					$zeitraum = $pro["Beginn Planung"];
					if ($zeitraum) {
						if (!$pro["Ende Planung"]) {
							$zeitraum .= " - ausstehend";
						}
						else {
							$zeitraum .= " - ".$pro["Ende Planung"];
						}
						$pro["VGV-Planungszeitraum"] = $zeitraum;
					}
					// Flächenfix
					/*
					if (!empty($pro["Fläche in ha"])) {
						$pro["Fläche in ha"] .= "ha";
					}
					*/
					// Priorität fürs sortieren
					$prio = array(
						"abgebrochen",
						"fertig",
						"pausiert",
						"in Planung",
					);
					$prefix = 0;
					if (in_array($pro["Jahr Fertigstellung"], $prio)) {
						$prefix = array_search($pro["Jahr Fertigstellung"], $prio) + 1;
					}
					$pro["Jahr Fertigstellung"] = "<span style='display:none'>" . $prefix . "</span>" . $pro["Jahr Fertigstellung"]; 
					if(!str_contains($pro[0], "zz WB")): ?>
						<tr class="pro-row" f-cat="<?= $pro["Kategorie"] ?>" href="<?= get_site_url() . "/projektanzeige/?proj=" . $pro["Projektkürzel"] ?>" f-filtered=1 f-disabled=0>
							<td class="hide-row" f-hidden="1"><span>+</span></td>
							<?php foreach ($cols as $col) { 
								$col_val = ($pro[$col["field"]] == "0") ? "": $pro[$col["field"]];
								?>
								<td 
									f-index="<?= $col["field"] ?>" 
									<?= ($col["def"] == 0) ? "style='display: none'" : ""; ?>
									f-filtered=<?= $col["def"] ?>
									f-hidden=<?= $col["hidden"] ?>
								>
									<?= $col_val ?>
								</td>
							<?php } ?>
						</tr>
					<?php endif ?>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</main>

<div id="export-modal" style="display:none">
	<div id="export-settings" class="modal-wrapper">
		<h2 class="m-0">Exporteinstellungen</h2>
		<div class="row gap-2">
			<div class="col">
				<h4 class="m-0">Vorlagen</h4>
				<div class="row gap-05">
					<input class="fancy-cb" type="radio" name="export-type" value="selected" checked><label>Ausgewählte Daten</label>
				</div>
				<div class="row gap-05">
					<input class="fancy-cb" type="radio" name="export-type" value="all"><label>Alle Daten</label>
				</div>
				<div class="row gap-05">
					<input class="fancy-cb" type="radio" name="export-type" value="doku"><label>Dokublatt</label>
				</div>
			</div>

			<div class="col">
				<h4 class="m-0">Dateiart</h4>
				<div class="row gap-05">
					<input class="fancy-cb" type="radio" name="file-type" value="docx" checked><label>.docx</label>
				</div>
				<div class="row gap-05">
					<input class="fancy-cb" type="radio" name="file-type" value="xlsx"><label>.xlsx</label>
				</div>
			</div>

			<div class="col">
				<h4 class="m-0">Einstellungen</h4>
				<div class="row gap-05 v-centered">
					<input class="fancy-cb" id="empty-fields" type="checkbox" checked=true><label>leere Felder exportieren</label>
				</div>
			</div>
		</div>
		<div class="row gap-1">
			<input type="file" id="doc" style="display: none"/>
			<button id="export" class="filter-button bordered">EXPORTIEREN</button>
			<button class="filter-button bordered" onclick="$('#export-modal').hide()">ABBRECHEN</button>
		</div>
	</div>
</div>

<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/xlsx.full.min.js"></script>
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/project-archive.js"></script>
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/docxtemplater.js"></script>
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/pizzip.js"></script>
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/pizzip-utils.js"></script>
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/FileSaver.js"></script>
<script type="text/javascript" src="/intranet_rla/wp_intra24/wp-content/themes/rlaintra24/static/js/project-export.js"></script>

<?php get_footer(); ?>

<style>
	<?php include("static/css/projekt.css"); ?>
</style>