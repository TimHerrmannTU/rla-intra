<?php
// dynamic page creation
$dokumenten_typen = array(
	array("dokumente-regelwerke", "Gesetze, Regelwerke, Vorschriften"),
	array("dokumente", "Weitere Dokumente: Beispiel, Studien...")
);
foreach ($dokumenten_typen as $doku): if( have_rows($doku[0]) ): ?>
<section>
	<h3><?= $doku[1] ?></h3>
	<ul class="liste">
		<?php while( have_rows($doku[0]) ): the_row(); ?>
			<li>
			<?php
				$file = get_sub_field('dokument');
				if( $file ):
					// Extract variables.
					$url = $file['url'];
					$title = $file['title'];
					$icon = $file['icon'];
					// Display image thumbnail when possible.
					if( $file['type'] == 'image' ) {
						$icon =  $file['sizes']['thumbnail'];
					}
				?>
					<a class="wrap" href="<?= esc_attr($url); ?>" title="<?= esc_attr($title); ?>" target="blank">
						<span><?= esc_html(get_sub_field('titel') ); ?></span>
						<img class="file-icon" style="margin-left:1em" src="<?= esc_attr($icon); ?>" /><br/>
					</a>
					<span class="fs-s"><?= esc_html(get_sub_field('autor') ); ?></span>
				<?php endif; ?>
			</li>
		<?php endwhile; ?>
	</ul>
</section>
<?php endif; endforeach ?>


<section class="">
	<?php
	$featured_posts = get_field('fortbildungen');
	if( $featured_posts ): ?>
		<h3>Fortbildungen, Fachtagungen oder Seminare zum Thema</h3>
		<ul class="liste">
			<?php foreach( $featured_posts as $featured_post ): 
				$permalink = get_permalink( $featured_post->ID );
				$title = get_the_title( $featured_post->ID );
				$veranstalter = get_field( 'veranstalter', $featured_post->ID );
				$datum = get_field( 'datum', $featured_post->ID );
				?>
				<li>
					<a href="<?php echo esc_url( $permalink ); ?>"><h4><?php echo esc_html( $title ); ?></h4></a>
					
					<div class="label-info">
					<?php if ($datum): ?><span><?php echo esc_html( $datum ); ?></span><?php endif; ?>
					<?php if ($veranstalter): ?><span><?php echo esc_html( $veranstalter ); ?></span><?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>
		
<section class="">
	<?php if( have_rows('links') ): ?>
		<h3>Links</h3>
		<ul class="">
			<?php while( have_rows('links') ): the_row(); ?>
				<li>
					<?php 
					$link = get_sub_field('link');
					if( $link ): 
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_blank';
						?>
						<a class="extern" href="<?= esc_url( $link_url ); ?>" target="<?= esc_attr( $link_target ); ?>"><?= esc_html( $link_title ); ?></a>
					<?php endif; ?>
				</li>
			<?php endwhile; ?>
		</ul>
	<?php endif; ?>
</section>

<section>
	<?php if( have_rows('projekte') ): ?>
		<?php // project query setup
		global $projDB_servername;
		global $projDB_username;
		global $projDB_password;
		global $projDB_dbname;
		global $projDB_tablename;
		// Verbindung zur Datenbank aufbauen
		try {
			$conn = new PDO("mysql:host=$projDB_servername;dbname=$projDB_dbname", $projDB_username, $projDB_password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch(PDOException $e) {
			die("Verbindung zur Projektdatenbank fehlgeschlagen: " . $e->getMessage());
		}
		?>
		<h3>Eigene Projekte</h3>
		<ul>
			<?php while( have_rows('projekte') ): 
				$row = the_row();
				$pro = strtoupper(get_sub_field('kuerzel'));
				if (!empty($pro)):
					?><li><?php
						// Query mit WHERE Klausel, damit nur das Projekt mit entsprechendem Projektkürzel abgefragt wird
						$query = "SELECT Projektkürzel, Projektname, Beschreibung, 'Jahr Fertigstellung' ".
							"FROM `".$projDB_tablename."` WHERE Projektkürzel='$pro'";
						// Query ausführen
						try {
							$results = $conn->query($query);
						}
						catch(PDOException $e) {
							die("Query fehlgeschlagen: " . $e->getMessage());
						}
						// Daten (in diesem Fall nur eine Zeile) aus Query extrahieren
						$res = ($results->fetchAll())[0];
						foreach ($res as $r) {
							echo $r." | ";
						}
						?>
					</li>
				<?php endif; ?>
			<?php endwhile; ?>
		</ul>
	<?php endif; ?>
</section>

<section>
	<?php if( have_rows('bauteile') ): ?>
		<h3>Bauteile</h3>
		<ul>
			<?php 
			while( have_rows('bauteile') ): the_row();
				$post = get_sub_field('bauteil');
				setup_postdata($post); // Setup this post for WP functions (variable must be named $post).
				include(dirname(dirname(__FILE__)).'/bauarchiv-entry.php');
				wp_reset_postdata(); // Reset the global post object so that the rest of the page works correctly.
			endwhile;
			?>
		</ul>
	<?php endif; ?>	
</section>