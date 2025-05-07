<article class="smallbox bauteil">		
	
	<div class="wrap nowrap">
		
		<?php
			// getroffene Suchbegriffe hervorheben und in anderen Variablen mit "_highlight" speichern --> erhalten span-Tag mit class 'suche-highlight'
			// die ursprünglichen Variablen ohne html-Tags werden bewahrt
			$projekt_name_highlight = $projekt_name;
			$projekt_kuerzel_highlight = $projekt_kuerzel;
			$projekt_beschreibung_highlight = $projekt_beschreibung;
			$projekt_fertigstellung_highlight = $projekt_fertigstellung;
			$projekt_auftraggeber_highlight = $projekt_auftraggeber;
			foreach ($suchbegriffe as $begriff) {
				// zur korrekten Behandlung von Umlauten muss der Begriff einmal mit Großbuchstaben und einmal in Kleinbuchstaben verarbeitet werden
				$begriff = mb_strtolower($begriff);
				$projekt_name_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $projekt_name_highlight);
				$projekt_kuerzel_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $projekt_kuerzel_highlight);
				$projekt_beschreibung_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $projekt_beschreibung_highlight);
				$projekt_fertigstellung_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $projekt_fertigstellung_highlight);
				$projekt_auftraggeber_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $projekt_auftraggeber_highlight);
				$begriff = mb_strtoupper($begriff);
				$projekt_name_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $projekt_name_highlight);
				$projekt_kuerzel_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $projekt_kuerzel_highlight);
				$projekt_beschreibung_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $projekt_beschreibung_highlight);
				$projekt_fertigstellung_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $projekt_fertigstellung_highlight);
				$projekt_auftraggeber_highlight = preg_replace("/$begriff/i", "<span class='suche-highlight'>$0</span>", $projekt_auftraggeber_highlight);
			}
		?>
		
		<div>	
			<a href="<?=get_site_url();?>/projektanzeige/?proj=<?php echo($projekt_kuerzel); ?>">
				<span class="kuerzel">
					<?php echo($projekt_kuerzel_highlight); 
					if ($projekt_projektleiter != "") {
						echo '&nbsp;<span title="' . $projekt_projektleiter . ' hat die Projektleitung" style="color:grey; font-size: 1.2rem; vertical-align: -10%;" class="dashicons dashicons-welcome-learn-more"></span>';
					}?>
				</span>
				<h3><?php echo($projekt_name_highlight); ?></h3>
			</a>
			
			<?php if ($projekt_beschreibung) echo"<p style='margin-bottom:0.2em'>".($projekt_beschreibung_highlight)."</p>"; ?>
			<?php if ($projekt_fertigstellung) echo "<p class='fs-s'>Fertigstellung: ".($projekt_fertigstellung_highlight)."</p>"; ?>
			<?php if ($projekt_auftraggeber) echo "<p class='fs-s'>Auftraggeber: ".($projekt_auftraggeber_highlight)."</p>"; ?>
		</div>
		
		<a href="<?=get_site_url();?>/projektanzeige/?proj=<?php echo($projekt_kuerzel); ?>">		
			<?php
				$projekturl = 'https://rehwaldt.de/projekt.php?proj=' . $projekt_kuerzel;
				$projekturlimg = 'http://webserver/website_rla/1_php/projekt.php?proj=' . $projekt_kuerzel;
				
				// Projektbild von interner Offlinefassung der Website anzeigen, wenn vorhanden
				$html = file_get_contents($projekturlimg);
				if (!empty($html)) {
					preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $html, $matches);
					echo '<figure class=""><img src="http://webserver/website_rla/website_rla/' . $matches[1][0] . '" style="width: 239px;"></figure>';
				}
				else // falls Projekt nicht auf Website gefunden, Bild aus Projekt Post anzeigen, falls vorhanden
				{
					// nach entsprechendem Projekt Post suchen
					$projekt_post_query = new WP_Query(array('post_type' => 'projekt', 'meta_key' => 'projektkurzel', 'meta_value' => $projekt_kuerzel));
					$projekt_post = $projekt_post_query -> get_posts();
					if (!empty($projekt_post && get_field('bild', $projekt_post[0] -> ID))) {
						echo '<figure class=""><img src="' . get_field('bild', $projekt_post[0] -> ID) . '" style="width: 239px;"></figure>';
					}
				}
			?>
		</a>
		
	</div>
</article>