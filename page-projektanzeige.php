<?php 
/* Template Name: page-projektanzeige */
get_header(); 

//################################################ 
//Abfrage der Projektdaten aus Access Projektliste 
//################################################

//phpinfo();

// Datenbankinfo aus Globals abrufen --> zu finden in functions.php
global $projDB_servername;
global $projDB_username;
global $projDB_password;
global $projDB_dbname;
global $projDB_tablename;

// Query mit WHERE Klausel, damit nur das Projekt mit entsprechendem Projektkürzel abgefragt wird
$query = "SELECT * FROM `" . $projDB_tablename . "` WHERE Projektkürzel='" . strtoupper(get_query_var('proj')) . "'";

// Verbindung zur Datenbank aufbauen
try {
	$conn = new PDO("mysql:host=$projDB_servername;dbname=$projDB_dbname", $projDB_username, $projDB_password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $e) {
	die("Verbindung zur Projektdatenbank fehlgeschlagen: " . $e->getMessage());
}

// Query ausführen
try {
	$results = $conn->query($query);
}
catch(PDOException $e) {
	die("Query fehlgeschlagen: " . $e->getMessage());
}

// Daten (in diesem Fall nur eine Zeile) aus Query extrahieren
$row = ($results->fetchAll())[0];

// Variablen zu Verwendung weiter unten setzen
$projekt_name = $row["Projektname"];
$projekt_kuerzel = $row["Projektkürzel"];
$projekt_beschreibung = $row["Kurzbeschreibung"];
$projekt_fertigstellung = $row["Jahr Fertigstellung"];
$projekt_leistungsphasen = $row["Beauftragte Leistungphasen/1"];
$projekt_flaeche = $row["Fläche in ha"];
$projekt_bausumme = $row["Bausumme gesamt"];
$projekt_auftraggeber = $row["Auftraggeber"];
$projekt_planungspartner = $row["Planungspartner"];
$projekt_projektleiter = $row["Projektleiter"];
$projekt_mitarbeiter = $row["Mitarbeiter"];

// Fehlerbehandlung, falls Projektkürzel unbekannt
if (empty($projekt_kuerzel))
{
	$projekt_kuerzel = strtoupper(get_query_var('proj')) . " nicht gefunden";
}

// Query zum Abfragen aller Mitarbeiter posts
$alle_mitarbeiter_posts_query = new WP_Query(array('post_type' => 'mitarbeiter'));
$alle_mitarbeiter_posts = $alle_mitarbeiter_posts_query -> get_posts();

$post_id = 0;
$posts = get_posts(array(
	'post_type'     => 'projekt',
	'meta_key'      => 'projektkurzel',
	'meta_value'    => $projekt_kuerzel
));
if ($posts) {
	$post_id = $posts[0]->ID;
}
// Zeitraumgenerierung
$zeitraum = $row["Beginn Planung"];
if ($zeitraum) {
	if (!$row["Ende Planung"]){
		$zeitraum .= " - ausstehend";
	}
	else {
		$zeitraum .= " - ".$row["Ende Planung"];
	}
	$row["Zeitraum Planung"] = $zeitraum;
}
// Statusgenerierung TODO
//#################################### 
//Darstellung der Projektinformationen 
//####################################
?>

<main class="projektanzeige">
	
	<section>
		<div class="bigbox">
			<div class="element-title inline">
				<h1 class="kuerzel"><?= $projekt_kuerzel?></h1> · <h1><?= $projekt_name?> </h1>
			</div>

			<?php
			//Link zur Website nur anzeigen, wenn Projekt auf Website existiert & Projektbild einsetzen				
			$projekturl = 'https://rehwaldt.de/projekt.php?proj=' . $projekt_kuerzel;
			$projekturlimg = 'http://webserver/website_rla/1_php/projekt.php?proj=' . $projekt_kuerzel;
			$site_headers = @get_headers($projekturl);
			// bei fehlendem Header oder HTTP-Error 404 oder 400
			if(!$site_headers || (str_contains($site_headers[0], 'HTTP/') && (str_contains($site_headers[0], '404') || str_contains($site_headers[0], '400')))) {
				// Link nicht anzeigen
			}
			else {
				// Online-Link zur Website anzeigen
				?><a href="<?= $projekturl ?>" class="projektlink icon-right-1" target="blank">zur Website</a><?php
			}
			?>
			
			<div class="wrap">
				
				<div class='projektinfo'>

					<section>
						<?php
						$content = array(
							"Ort",
							"Land",
							"Kurzbeschreibung",
							"Art der Planung",
							"Kategorie",
							"Fläche in ha",
							"Bausumme gesamt",
							"Honorar gesamt",
							"Auszeichnung",
							"Fördermittel"
						);
						?>
						<table>
							<tbody>
								<?php
								foreach ($content as $con) {
									if (!empty($row[$con])) { 
										?>
										<tr>
											<td><?= $con ?></td>
											<td><?= $row[$con] ?></td>
										</tr>
										<?php
									}
								}
								?>
							</tbody>
						</table>
					</section>
					
					<section>
						<h3>Status/Planungszeitraum</h3>
						<?php
						$content = array(
							"Status",
							"Jahr Fertigstellung",
							"Jahr Wettbewerb",
							"Tag der Eröffnung"
						); 
						?>
						<div class="row gap-1">
							<table>
								<tbody>
									<?php
									foreach ($content as $con) {
										if (!empty($row[$con])) { 
											?>
											<tr>
												<td><?= $con ?></td>
												<td><?= $row[$con] ?></td>
											</tr>
											<?php
										}
									}
									?>
								</tbody>
							</table>
							<?php
							$content = array(
								"Zeitraum Planung",
								"Abgabe LP3",
								"Ablauf LP8",
								"Ablauf LP9",
								"Abschluss vorherige Bauabschnitte"
							); 
							?>
							<table>
								<tbody>
									<?php
									foreach ($content as $con) {
										if (!empty($row[$con])) { 
											?>
											<tr>
												<td><?= $con ?></td>
												<td><?= $row[$con] ?></td>
											</tr>
											<?php
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</section>
					
					<section>
						<h3>Leistungen</h3>
						<div class="row gap-1">
							<?php 
							$iterations = array("1", "2", "3");
							$content = array(
								"Leistungsbild nach HOAI/",
								"anrechenbare Kosten/",
								"Honorarsatz/",
								"Beauftragte Leistungphasen/",
								"Honorar/",
								"Honorarzone/"
							);
							foreach ($iterations as $iteration) { ?>
								<table>
									<tbody>
										<?php 
										foreach ($content as $con) {
											if (!empty($row[$con.$iteration])) { 
												?>
												<tr>
													<td><?= $con.$iteration ?></td>
													<td><?= $row[$con.$iteration] ?></td>
												</tr>
												<?php
											}
										} 
										?>
									</tbody>
								</table>
							<?php } ?>
						</div>
						<?php 
						if (!empty($row["Sonstige Leistungen"])) {
							?><p>Sonstige Leistungen: <?= $row["Sonstige Leistungen"] ?></p><?php
						}
						?>
					</section>

					<section>
						<h3>Planungspartner</h3>
						<?php
						$content = array(
							"Auftraggeber",
							"Ansprechpartner",
							"Planungspartner",
						);
						?>
						<table>
							<tbody>
								<?php
								foreach ($content as $con) {
									if (!empty($row[$con])) { 
										?>
										<tr>
											<td><?= $con ?></td>
											<td><?= $row[$con] ?></td>
										</tr>
										<?php
									}
								}
								?>
							</tbody>
						</table>
					</section>
					
					<section class="col gap-1">
						<h3>Team</h3>
						<div>
							<?php
							// Projektleiter anzeigen und mit Link zum Mitarbeiter Post versehen, falls vorhanden
							if (!empty($projekt_projektleiter)) {
								?><h3>Projektleiter</h3><?php
								$projektleiter_gefunden = false;
								foreach ($alle_mitarbeiter_posts as $mitarbeiter_post) {
									if (str_contains($projekt_projektleiter, end(explode(" ", $mitarbeiter_post -> post_title)))) {
										echo '<p><a href="' . $mitarbeiter_post -> guid . '">' . $projekt_projektleiter . '&nbsp;<span style="color:grey; font-size: 1.2rem; vertical-align: -10%;" class="dashicons dashicons-admin-users"></span></a></p>';
										$projektleiter_gefunden = true;
										break;
									}
								}
								if (!$projektleiter_gefunden) {
									echo '<p>' . $projekt_projektleiter . '</p>';
								}
							}
							?>
						</div>
						
						<div>
						<?php
						// Mitarbeiter anzeigen und mit Link zum Mitarbeiter Post versehen, falls vorhanden
						if (!empty($projekt_mitarbeiter)) {					
							$arr_mitarbeiter = explode(PHP_EOL, $projekt_mitarbeiter);
							?><h3>Mitarbeiter</h3><?php
							foreach ($arr_mitarbeiter as $mitarbeiter) {
								$mitarbeiter_gefunden = false;
								foreach ($alle_mitarbeiter_posts as $mitarbeiter_post) {
									if (str_contains($mitarbeiter, end(explode(" ", $mitarbeiter_post -> post_title)))) {
										echo '<a href="' . $mitarbeiter_post -> guid . '">' . str_replace(',', '', $mitarbeiter) . '&nbsp;<span style="color:grey; font-size: 1.2rem; vertical-align: -10%;" class="dashicons dashicons-admin-users"></span></a><br>';
										$mitarbeiter_gefunden = true;
										break;
									}
								}
								if (!$mitarbeiter_gefunden) {
									echo str_replace(',', '', $mitarbeiter)  . '<br>';
								}
							}
						}
						?>
						</div>
					</section>
					
				</div>

				<figure class="projektimg col gap-1">
					<?php
					// Projektbild von interner Offlinefassung der Website anzeigen, wenn vorhanden
					$html = file_get_contents($projekturlimg);
					if (!empty($html)) {
						preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $html, $matches);
						if (str_contains($matches[1][0], 'projekte')) {
							?><img src="http://webserver/website_rla/website_rla/<?= $matches[1][0] ?>"><?php
						}
					}
					else { // falls Projekt nicht auf Website gefunden, Bild aus Projekt Post anzeigen, falls vorhanden
						// nach entsprechendem Projekt Post suchen
						$projekt_post_query = new WP_Query(array('post_type' => 'projekt', 'meta_key' => 'projektkurzel', 'meta_value' => $projekt_kuerzel));
						$projekt_post = $projekt_post_query -> get_posts();
						if (!empty($projekt_post && get_field('bild', $projekt_post[0] -> ID))) {
							?><img src="<?= get_field('bild', $projekt_post[0] -> ID) ?>"><?php
						}
					}
					$content = array(
						"Regenwassermanagement",
						"Grüne Infrastruktur",
						"Beteiligung",
						"Historische Kontext",
						"Temporär",
						"Topographie",
						"Wasserspiel",
						"Uferpromenade/Waterfront",
						"Radweg",
						"Leitsystem/Infotainment",
						"Dachbegrünung",
						"Fassadenbegrünung",
						"Innenbegrünung",
						"Denkmalpflegerische Zielsetzung",
						"Ökologische Begleitplanung",
						"Hochwasserschutz",
						"Verkehrsanlagen",
						"Anmerkungen Projektdoku",
						"Projektdokumentation anlegen",
						"in die Website aufnehmen",
						"Projektfotos machen",
						"Projektdaten klären",
						"Campuskonzept",
						"Park und Garten",
						"Kultur und Wissenschaft",
						"Spielplatz",
						"Verwaltung und Gewerbe",
						"Stadtraum",
						"Uferpromenade",
						"Stadtplanung",
						"Wohnumfeld",
						"Gewässer",
						"Verkehr",
						"Gestaltungshandbuch",
						"Landschaft und Umwelt",
						"Parkanlage",
						"Eingriff und Ausgleich",
						"Gartenschau",
						"Zoologischer Garten",
						"Friedhof",
						"Forschung und Hochschule",
						"Museum und Kultur",
						"Büro und Gewerbe",
						"Gesundheitswesen",
						"Schule",
						"Stadtplatz",
						"Straßenraum",
						"Grünordnungsplanung",
						"Bürgerbeteiligung",
						"Siedlung und Quartier",
						"Gewässerentwicklung",
						"Ökologische Baubegleitung",
						"Historische Anlagen",
						"Renaturierung",
						"Gutachten",
						"Landschaftsentwicklung",
						"Workshop"
					)
					?>
					<div class="row gap-1">
						<?php 
						foreach ($content as $con) { 
							if ($row[$con]) {
								?><button class='filter-button bordered smooth'><?= $con ?></button><?php
							}
						} 
						?>
					</div>
				</figure>
			
			</div>

		</div>
	</section>
	
	
	<?php 
	//zugewiesene Bauteile auflsiten
	//abfrage-projektname-jahr.php muss nicht includiert werden, da weiter oben die Datenbankabfrage schon ausgeführt wurde
	
	$posts = get_posts(array(
		'posts_per_page'    => -1,
		'post_type'     => 'bauteil',
		'meta_key'      => 'projektkurzel',
		'meta_value'    => $projekt_kuerzel	
	));
		
	if( $posts ): ?>
		<section>
			<ul>
				<h2 class="section-title">Zugeordnete Bauteile</h2>
				<?php 
				foreach( $posts as $post ) {
					setup_postdata( $post );
					include('templates/bauarchiv-entry.php');
				}
				include('templates/bauarchiv-entry-functions.php');
				?>
			</ul>
		</section>
    	<?php wp_reset_postdata(); ?>
	<?php endif; ?>

</main> 

<div id="breadcrumb-fix" style="display: none">
	<del></del>
	<span class="current-page"><?= $projekt_kuerzel ?>
</div>

<script>
$(function() {
	// breadcrumb fix
	var prev_page = $("#breadcrumb .current-page")
	$(prev_page).replaceWith($("<a href='<?= get_bloginfo('url')."/projekt-archiv" ?>'>"+$(prev_page).text()+"</a>"));
	$("#breadcrumb-fix > *").appendTo("#breadcrumb")
	$("#breadcrumb-fix").remove()
	// adminbar fix
	const post_id = <?= $post_id ?>;
	$edit_button = $("#wp-admin-bar-edit a")
	if (post_id != 0) {
		$edit_button.text("Projektpost bearbeiten")
		$edit_button.attr("href", "<?= get_admin_url() ?>post.php?post=<?= $post_id ?>&action=edit")
	}
	else {
		$edit_button.text("neuer Projektpost")
		$edit_button.attr("href", "<?= get_admin_url() ?>post-new.php?post_type=projekt")
	}
})
</script>

<style>
	table {
		border-spacing: 1rem 0px;
	}
	td:first-of-type {
		width: 10em !important;
	}
	figure.projektimg div.row {
		flex-wrap: wrap;
	}
</style>

<?php get_footer(); ?>