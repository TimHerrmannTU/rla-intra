<?php 

get_header(); 
?>
   
<main class="single-team col gap-3">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
		<article class="w-100 p-05"> <!--used to have class="bickbox"-->
			
			<div class="wrap">
				<div class="col v-centered">

					<div class="mb-1">
						<h3 class="fs-l m-0"><?php the_title(); ?></h3>
						<div class="fs-m">
							<?php // ---------- Standort einfügen ----------
							$post_type = get_post_type_object(get_post_type())->labels->singular_name;
							if ($post_type == "Mitarbeiter") {
								
								// Sortierfunktion für hierarchische Kategorien
								function cmpCategories($category_1,$category_2) {
									foreach(get_categories(array("parent" => $category_1->cat_ID)) AS $sub) {
										if($category_2->cat_ID == $sub->cat_ID) return -1;
									}
									return 1;
								}
								
								// Standort-Kategorien abfragen und sortieren
								$cats = get_the_category();
								usort($cats, "cmpCategories");									
								
								// Standort darstellen											
								foreach($cats as $key => $cat) {
									if($cat->name != "Standorte") {
										echo $cat->name;
										if(!($key === array_key_last($cats))) echo " > ";
									}
								}
							}
							else echo $post_type;
							?>
						</div>
					</div>
					
					<?php // ---------- weitere Infos einfügen ----------
					if( get_field('email') ): ?>
						<div><span class="label">E-Mail:</span> <?= esc_html( get_field('email') ); ?></div>
						<div><span class="label">Microsoft Teams:</span> <a href="https://teams.microsoft.com/l/chat/0/0?users=<?= esc_html( get_field('email') ); ?>" rel="noreferrer noopener" target="_blank">Chat starten <del></del></a></div>
					<?php endif; ?>
					
					<?php
					$bonus_info = array("telefon", "mobil-telefon", "team");
					foreach ($bonus_info as $bi) {
						$field = get_field_object($bi);
						if($field["value"]) {
							$tel_arr = explode(" ", trim($field["value"]));
							$tel_fancy = array_pop($tel_arr);
							$tel_fancy = implode(" ", $tel_arr) . " <b>" . $tel_fancy . "</b>";
							?><div><span class="label"><?= $field["label"] ?>:</span> <?= $tel_fancy ?></div><?php
						}
					}
					?>
					
					<?php // ---------- Fachgruppen einfügen ----------
					if( get_field('fachgruppen') ): ?>
						<div class="mt-1">
							<span class="label">Fachgruppen:</span> 
							<?php include("templates/fachgruppen-liste-popup.php"); ?>
						</div>
					<?php endif; ?>
					
				</div>
				
				<?php // BILD
				$image = get_field('bild');
				if( !empty( $image ) ):
					?><figure><img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>" class="medium-img"/></figure><?php
				endif;
				?>

			</div>

		</article>

		<line></line>

		<?php 
		$wbs = array();
		// Wettbewerbe darstellen
		$args = array(
			'numberposts' => -1,
			'post_type'   => 'wettbewerb',
			'orderby' => 'datum',
			'order' => 'ASC',
		);
		$the_query = new WP_Query($args);
		foreach ($the_query->posts as $wb) {
			$participants = get_field("Bearbeiter-2", $wb->ID);
			$took_part = false;
			foreach ($participants as $par) {
				if ($par->post_title == get_the_title()) {
					$took_part = true;
				}
			}
			if ($took_part) { 
				array_push($wbs, $wb);
			}
		}
		wp_reset_query();
		if (!empty($wbs)): ?>
			<div>
				<h3 class="section-title">Wettbewerbe (<?= count($wbs) ?>)</h3>
				<ul class="wettbewerb galerie m-0" style="gap: 0.5rem 1rem;">
					<?php foreach ($wbs as $wb): ?>
						<li class="smallbox m-0">
							<a href="<?= $wb->guid ?>">
								<?php 
								$image = get_field('bild', $wb->ID);
								if( !empty( $image ) ): ?>
									<figure><img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>" class=""/></figure>
								<?php endif; ?>
								<h3><?= $wb->post_title ?></h3>
							</a>
							<div class="label-info">
								<?php
								$bonus_info = array("datum", "ort", "wb-ergebnis");
								foreach ($bonus_info as $bi) {
									$field = get_field($bi, $wb->ID);
									if($field) {
										?><div><?= esc_html($field) ?></div><?php
									}
								}
								?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
		
		<?php
		// ---------- Projekte anzeigen, bei denen der Mitarbeiter Projektleiter oder Bearbeiter ist ----------
		
		//######################################################
		// Abfrage aus Access Datenbank für Suche nach Projekten
		//######################################################

		// Datenbankinfo aus Globals abrufen --> zu finden in functions.php
		global $projDB_servername;
		global $projDB_username;
		global $projDB_password;
		global $projDB_dbname;
		global $projDB_tablename;
		
		// Nachnamen von Mitarbeiter ermitteln
		$nachname = trim(end(explode(" ", get_the_title())));
		
		// Query mit allen abzufragenden Feldern
		$query = "SELECT Projektkürzel, Projektname, Auftraggeber, Kurzbeschreibung, `Jahr Fertigstellung`, Projektleiter, Mitarbeiter FROM `" . $projDB_tablename . "` WHERE Projektleiter LIKE '%" . $nachname . "%' OR Mitarbeiter LIKE '%" . $nachname . "%'";

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
			$result = $conn->query($query)->fetchAll();
		}
		catch(PDOException $e) {
			die("Query fehlgeschlagen: " . $e->getMessage());
		}	
		
		//#####################################
		// Darstellung der gefundenen Projekte
		//#####################################
		
		// nur Überschrift darstellen, wenn Ergebnisse gefunden
		if(!empty($result)): ?>
		<div>
			<h3 class="section-title"><?=("Projektleitung und Mitarbeit (" . ((is_null($result)) ? 0 : count($result)) . ")");?></h3>
			<div>
				<?php
				endif;
				
				// nur Ergebnisse ausgeben, wenn Ergebnisse gefunden
				if(!empty($result)) {
					
					//Suchergebnisse nach Fertigstellungsjahr in absteigender Reihenfolge sortieren
					$jahre = array_column($result, 'Jahr Fertigstellung');
					array_multisort($jahre, SORT_DESC, $result);
					
					foreach ($result as $row) {
						// Variablen zu Verwendung in templates/projekt-entry.php setzen
						$projekt_name = $row["Projektname"];
						$projekt_kuerzel = $row["Projektkürzel"];
						$projekt_beschreibung = $row["Kurzbeschreibung"];
						$projekt_fertigstellung = $row["Jahr Fertigstellung"];
						$projekt_auftraggeber = $row["Auftraggeber"];
						// falls Mitarbeiter Projektleiter ist, den Namen an projekt-entry template übergeben
						$projekt_projektleiter = "";
						if (str_contains($row["Projektleiter"], $nachname)) $projekt_projektleiter = get_the_title();
						// Ergebnisfeld für ein Projekt ausgeben
						include('templates/projekt-entry.php');
					}
				}
				?>
			</div>
		</div>

	<?php endwhile; endif; ?>
</main>  
 
<?php get_footer(); ?>

