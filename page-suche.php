<?php 
	/*
		* Template Name: suche
	*/
get_header(); ?>

<main class="suche">
	
	<?php
		
		//###############################
		// Vorbehandlung der Sucheingabe
		//###############################
		
		// Wenn kein Suchbegriff eingegeben, nichts suchen
		if (str_replace(' ', '', $suche) == "")	{
			die("Bitte einen oder mehrere Suchbegriffe mit Komma getrennt eingeben");
		}
		
		// Array für Suchtreffer zurücksetzen, falls es noch Inhalte haben sollte
		unset($suchergebnisse);
		
		// Suchbegriffe aus URL Parameter (max. 20 Begriffe) in Array sprengen
		$suchbegriffe = explode(',', $suche);
		if (count($suchbegriffe) > 20) {
			echo("<span class='suche-highlight'>Die Suchfunktion ist auf maximal 20 Begriffe begrenzt</span>");
			$suchbegriffe = array_slice($suchbegriffe, 0, 20);
		}
		
		// wenn einer der Suchbefehle kürzer als 3 Zeichen ist, Fehler ausgeben
		// parallel Suchstring $suche wieder aufgeräumt aus einzelnen Suchbegriffen aufbauen
		$suche = "";
		foreach ($suchbegriffe as &$begriff) {
			//führende und nachlaufende Leerzeichen aus Begriff entfernen
			$begriff = trim($begriff);
			$suche = $suche . $begriff . ", ";
			if (strlen($begriff) < 3) {
				die("Alle Suchbegriffe müssen mindestens drei Zeichen lang sein");
			}
		}
		unset($begriff);
		$suche = substr($suche, 0, -2);
		
		// Überschrift Suchergebnisse ausgeben
		echo("<h1>Suchergebnisse für \"" . $suche . "\"</h1>");
		
		//#####################################################
		// Abfrage aus Access Datenbank für Suche in Projekten
		//#####################################################		
		
		// Datenbankinfo aus Globals abrufen --> zu finden in functions.php
		global $projDB_servername;
		global $projDB_username;
		global $projDB_password;
		global $projDB_dbname;
		global $projDB_tablename;
		
		// Query mit allen abzufragenden Feldern
		$query = 'SELECT Projektkürzel, Projektname, Auftraggeber, Kurzbeschreibung, `Jahr Fertigstellung` FROM `' . $projDB_tablename . '`';
		
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
		
		// Alle Zeilen durchgehen und nach Treffern suchen
		foreach ($result as $row) {
			
			// in jeder Zeile alle Felder nach Treffern mit einem der Suchbegriffe durchsuchen
			foreach ($suchbegriffe as $begriff) {
				
				//führende und nachlaufende Leerzeichen aus Begriff entfernen
				$begriff = trim($begriff);
				
				// nur zum debuggen
				/*
				echo "<BR>-------------------------------";
				echo "<BR>BEGRIFF: " . mb_strtolower($begriff);
				echo "<BR>Kürzel: " . mb_strtolower($row["Projektkürzel"]);
				echo "<BR>Ergebnis: "; echo str_contains(mb_strtolower($row["Projektkürzel"]), mb_strtolower($begriff)) ? 'true' : 'false';
				echo "<BR>Projektname: " . mb_strtolower($row["Projektname"]);
				echo "<BR>Ergebnis: "; echo str_contains(mb_strtolower($row["Projektname"]), mb_strtolower($begriff)) ? 'true' : 'false';
				echo "<BR>Beschreibung: " . mb_strtolower($row["Beschreibung"]);
				echo "<BR>Ergebnis: "; echo str_contains(mb_strtolower($row["Beschreibung"]), mb_strtolower($begriff)) ? 'true' : 'false';
				echo "<BR>Jahr Fertigstellung: " . mb_strtolower($row["Jahr Fertigstellung"]);
				echo "<BR>Ergebnis: "; echo str_contains(mb_strtolower($row["Jahr Fertigstellung"]), mb_strtolower($begriff)) ? 'true' : 'false';
				echo "<BR>Auftraggeber: " . mb_strtolower($row["Auftraggeber"]);
				echo "<BR>Ergebnis: "; echo str_contains(mb_strtolower($row["Auftraggeber"]), mb_strtolower($begriff)) ? 'true' : 'false';
				echo "<BR>Gesamtergebnis: "; echo (str_contains(mb_strtolower($row["Projektkürzel"]), mb_strtolower($begriff)) OR str_contains(mb_strtolower($row["Projektname"]), mb_strtolower($begriff)) 
				OR str_contains(mb_strtolower($row["Beschreibung"]), mb_strtolower($begriff)) OR str_contains(mb_strtolower($row["Jahr Fertigstellung"]), mb_strtolower($begriff))  
				OR str_contains(mb_strtolower($row["Auftraggeber"]), mb_strtolower($begriff))) ? 'true' : 'false';
				*/
				
				// wenn in einem Feld der Zeile ein Treffer war, diese zeile in Array $suchergebnisse merken und foreach abbrechen (mit nächster Zeile fortfahren)
				if ((str_contains(mb_strtolower($row["Projektkürzel"]), mb_strtolower($begriff)) OR str_contains(mb_strtolower($row["Projektname"]), mb_strtolower($begriff)) 
				OR str_contains(mb_strtolower($row["Kurzbeschreibung"]), mb_strtolower($begriff)) OR str_contains(mb_strtolower($row["Jahr Fertigstellung"]), mb_strtolower($begriff))  
				OR str_contains(mb_strtolower($row["Auftraggeber"]), mb_strtolower($begriff))))
				{
					$suchergebnisse[] = $row;
					break;
				}
			}
		}
		
		
		//#####################################
		// Darstellung der gefundenen Projekte
		//#####################################
		
		?>
		<div class="dropout" id="do-projekte">
			<a class="do-title" onclick="toggle_dropdown(this)">
				<h3><?php echo("Gefundene Projekte (" . ((is_null($suchergebnisse)) ? 0 : count($suchergebnisse)) . ")");?></h3>
			</a>
			<div class="dropout-hidden col">		
		<?php
		// nur Ergebnisse ausgeben, wenn  Ergebnisarray nicht leer ist
		if(!empty($suchergebnisse)) {
			
			//Suchergebnisse nach Fertigstellungsjahr in absteigender Reihenfolge sortieren
			$jahre = array_column($suchergebnisse, 'Jahr Fertigstellung');
			array_multisort($jahre, SORT_DESC, $suchergebnisse);
			
			foreach ($suchergebnisse as $row) {
				// Variablen zu Verwendung in templates/projekt-entry.php setzen
				$projekt_name = $row["Projektname"];
				$projekt_kuerzel = $row["Projektkürzel"];
				$projekt_beschreibung = $row["Kurzbeschreibung"];
				$projekt_fertigstellung = $row["Jahr Fertigstellung"];
				$projekt_auftraggeber = $row["Auftraggeber"];
				// Ergebnisfeld für ein Projekt ausgeben
				include('templates/projekt-entry.php');
			}
		}
		?>
			</div>
		</div>
		<?php
		
		//##########################################################################################
		// Suche nach Bauteilen, die einen der Suchbegriffe im Titel oder in der Beschreibung haben
		//##########################################################################################
		
		$args = array(
			'posts_per_page' => -1,
			'post_type'      => 'bauteil',
			'meta_key'       => 'projektkurzel',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				'relation' => 'OR',
			)		
		);	
		foreach ($suchbegriffe as $begriff) {
			$args['meta_query'][] = array(
			'key'     => 'titel',
			'value'   => $begriff,
			'compare' => 'LIKE'
			);
			
			$args['meta_query'][] = array(
			'key'     => 'beschreibung',
			'value'   => $begriff,
			'compare' => 'LIKE'
			);
			
			$args['meta_query'][] = array(
			'key'     => 'projektkurzel',
			'value'   => $begriff,
			'compare' => 'LIKE'
			);
		}
		
		// Query
		$the_query = new WP_Query($args);
		
		// falsche Suchtreffer durch fehlerhafte Behandlung von Umlauten wieder aus Query entfernen
		if ($the_query->have_posts()) {
			$modified_posts = array();
			while ($the_query->have_posts()) {
				$the_query->the_post();
				$addpost = false;
				foreach ($suchbegriffe as $begriff) {
					if(preg_match("/$begriff/i", get_field('titel')) || preg_match("/$begriff/i", get_field('beschreibung')) || preg_match("/$begriff/i", get_field('projektkurzel'))) {
						$addpost = true;
					}
				}
				if ($addpost) {$modified_posts[] = get_post();}
			}
			$the_query->posts = $modified_posts;
			$the_query->post_count = count($modified_posts);
			$the_query->found_posts = count($modified_posts);
			$the_query->rewind_posts();
		}
		
		wp_reset_postdata();
		
		?>
		<div class="dropout" id="do-bauteile">
			<a class="do-title" onclick="toggle_dropdown(this)">
				<h3><?php echo("Gefundene Bauteile (" . $the_query->found_posts . ")");?></h3>
			</a>
			<div class="dropout-hidden col">
		<?php

		// Bauteile auflisten, falls welche gefunden wurden
		if( $the_query->have_posts() ): 		
			echo '<ul>';
			while ( $the_query->have_posts() ) : $the_query->the_post();			
				include('templates/bauarchiv-entry.php');
			endwhile; 
			include('templates/bauarchiv-entry-functions.php');
			echo '</ul>';
		endif; 
		
		?>
			</div>
		</div>
		<?php
		
		wp_reset_query();
		
		
		//##############################################################################################
		// Suche nach Materialien, die einen der Suchbegriffe im Titel oder in der Beschreibung haben
		//##############################################################################################
		
		// je eine Wordpress query pro Suchbegriff ausführen und alle querys in eine mergen
		unset($the_query);
		unset($old_query);
		foreach ($suchbegriffe as $begriff) {		
			if (!is_null($the_query)) {
				$old_query = $the_query;
			}
			
			$args = array(
				'posts_per_page' => -1,
				'post_type'      => 'materialien',
				's'				 => $begriff
			);
			$the_query_new = new WP_Query($args);
			
			if ($the_query_new->have_posts()) {
				$the_query = $the_query_new;
				if (!is_null($old_query)) {
					$modified_posts = $old_query->posts;
					while ($the_query->have_posts()) {
						$the_query->the_post();
						$addpost = true;
						foreach ($old_query->posts as $old_post) {
							if (get_the_ID() == $old_post->ID) {
								$addpost = false;
								break;
							}
						}
						if ($addpost) {$modified_posts[] = get_post();}
					}
					$the_query->posts = $modified_posts;
					$the_query->post_count = count($modified_posts);
					$the_query->found_posts = count($modified_posts);
				}
			}
		}	
		
		// gefundene Posts nach Titel sortieren		
		if($the_query->post_count >= 2) {
			// ein Array mit allen gefundenen Posts füllen, wobei der key jeweils dem Titel des Posts entspricht
			$posts_array = array();
			while ($the_query->have_posts()) {
				$the_query->the_post();
				$posts_array[get_the_title()] = $post;
			}
			// das Array entsprechend seiner keys aplhabetisch sortieren
			uksort($posts_array, 'strcasecmp');
			// neues "sauberes" Array anlegen, bei dem die keys wieder fortlaufende int sind anstatt Titel des jeweiligen Posts, damit sie wieder der WP_Query zugewiesen werden können
			$posts_array_clean = array();
			// "sauberes" Array mit Inhalten aus vorher sortiertem Array füllen
			foreach (array_values($posts_array) as $one_post) {
				$posts_array_clean[] = $one_post;
			}
			wp_reset_postdata();
			
			// das "saubere" Array als posts der WP_Query setzen
			$the_query->posts = $posts_array_clean;
		}		
		
		if (isset($the_query)) {
			$anzPosts = $the_query->post_count;
		} else {
			$anzPosts = 0;
		}

		?>
		<div class="dropout" id="do-materialien">
			<a class="do-title" onclick="toggle_dropdown(this)">
				<h3><?php echo("Gefundene Materialien (" . $anzPosts . ")");?></h3>
			</a>
			<div class="dropout-hidden">
		<?php
		
		// Materialien auflisten, falls welche gefunden wurden
		if($the_query->have_posts()): 
			echo '<ul>';
			while ( $the_query->have_posts() ) : $the_query->the_post();
				include('templates/material-entry.php');
			endwhile; 
			echo '</ul>';
		endif; 
		
		?>
			</div>
		</div>
		<?php
		
		wp_reset_query();
	
	?>
	
</main>
	
<?php get_footer(); ?>			