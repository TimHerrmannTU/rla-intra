<?php 
get_header(); 
include('templates/abfrage-projektname-jahr.php');
$cat = end(get_the_category())->slug;
?>

<main>
	<article class="bigbox dokumentation container_single-post">
		
		<?php if(get_field('bild')):
			$image = get_field('bild');
			$file = get_field("datei");
			if($file): ?>
				<a href="<?= esc_attr($file['url']); ?>" title="<?= esc_attr($file['title']); ?>" target="_blank">
			<?php endif; ?>
					<figure class="main-figure">
						<img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>" class="medium-img"/>
					</figure>
			<?php if($file): ?> 
				</a>
			<?php endif; ?>
		<?php endif; ?>	

		<div class="main-info">
			<div class="element-title">
				<h1><?php the_title(); ?></h1>
				<?php if (get_field('untertitel')): ?><span><?= esc_html(get_field('untertitel')); ?></span><?php endif; ?>
			</div>
			<section class="doku-info">
				<?php
				$featured_posts = get_field('verantwortlicher');
				if( $featured_posts ): ?>
					<span>
						Redakteur(in):
						<?php foreach( $featured_posts as $key => $featured_post ): 
							$permalink = get_permalink( $featured_post->ID );
							$title = get_the_title( $featured_post->ID );
							$custom_field = get_field( 'field_name', $featured_post->ID );
							?>
							<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title )?>
								<?php // nachfolgende Zeile muss in einer Zeile bleiben, da Zeilenumbruch im HTML sonst als Leerzeichen dargestellt wird ?>
								<span style="color:grey; font-size: 1.2rem; vertical-align: -10%; display: inline-block" class="dashicons dashicons-admin-users"></span></a><?php if(!($key === array_key_last($featured_posts))) echo ","; ?>
						<?php endforeach; ?>
					</span>
				<?php endif;?>

				<?php 					
				// ---------- Fachgruppen einfügen ----------
				if( get_field('fachgruppen') ): ?>
					<div class="fachgruppen tags" style="margin-top:1rem">
						Fachgruppen
						<?php include("templates/fachgruppen-liste-popup.php"); ?>
					</div>
				<?php endif; ?>
			</section>

			<section class="gesetze-normen">
				<?php
				$featured_posts = get_field('gesetze-normen');
				if( $featured_posts ): ?>
					<h3>Gesetze, Regelwerke, Vorschriften</h3>
					<ul class="liste">
						<?php foreach( $featured_posts as $featured_post ): 
							$title = get_the_title( $featured_post->ID );
							$autork = get_field( 'autor', $featured_post->ID );
							$file = get_field( 'datei', $featured_post->ID );
							?>
							<li>
								<a class="inline" href="<?= esc_url( $file["url"] ); ?>">
									<?= esc_html( $title )?>
									<img class="file-icon" src="<?= esc_html($file["icon"]) ?>"/>
								</a>
								<div style="font-size: 1rem;"><?= esc_html($autork) ?></div>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif;?>

				
			</section>
			<?php
			// dynamic page creation
			$dokumenten_typen = array(
				//array("dokumente-regelwerke", "Gesetze, Regelwerke, Vorschriften"),
				array("dokumente", "Weitere Dokumente: Beispiel, Studien...")
			);
			foreach ($dokumenten_typen as $doku): if( have_rows($doku[0]) ): ?>
				<section class="text-block">
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
									<a class="inline" href="<?= esc_attr($url); ?>" title="<?= esc_attr($title); ?>" target="blank">
										<span><?= esc_html(get_sub_field('titel') ); ?></span>
										<img class="file-icon" src="<?= esc_attr($icon); ?>" /><br/>
									</a>
									<div class="inline-liste">
									<?php if( get_sub_field('autor') ): ?><span class="fs-s"><?= esc_html(get_sub_field('autor') ); ?></span><?php endif; ?>
									<?php if( get_sub_field('datum') ): ?><span class="fs-s"><?= esc_html(get_sub_field('datum') ); ?></span><?php endif; ?>
									</div>
									<?php if( get_sub_field('anmerkungen') ): ?><span class="fs-s"><?= esc_html(get_sub_field('anmerkungen') ); ?></span><?php endif; ?>
									
								<?php endif; ?>
							</li>
						<?php endwhile; ?>
					</ul>
				</section>
			<?php endif; endforeach ?>


			<?php if( have_rows('firmen') ): ?>
				<section class="text-block">
					<h3>Firmen/Hersteller</h3>
					<ul class="">
						<?php while( have_rows('firmen') ): the_row(); ?>
						<li>
							<a class="extern" href="<?php echo esc_attr( get_sub_field('website') ); ?>" target="blank"><?php echo esc_html( get_sub_field('name') ); ?></a>
							<div class="fs-s">
								<?php if( get_sub_field('ansprechpartner') ): ?><span><?= esc_html(get_sub_field('ansprechpartner') ); ?></span><?php endif; ?>
								<?php if( get_sub_field('anmerkungen') ): ?><span><?= esc_html(get_sub_field('anmerkungen') ); ?></span><?php endif; ?>
							</div>
						</li>
						<?php endwhile; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php
			$featured_posts = get_field('fortbildungen');
			if( $featured_posts ): ?>
				<section class="text-block">
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
				</section>
			<?php endif; ?>

			<?php if( have_rows('links') ): ?>
				<section class="text-block">
					<h3>Links</h3>
					<ul class="">
						<?php while( have_rows('links') ): the_row(); ?>
							<li>
								<?php 
								$link = get_sub_field('link');
								if( $link ) {
									$link_target = $link['target'] ? $link['target'] : '_blank';
									?><a class="extern" href="<?= esc_url( $link['url'] ); ?>" target="<?= esc_attr( $link_target ); ?>"><?= esc_html( $link['title'] ); ?></a><?php
								} ?>
							</li>
						<?php endwhile; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php
			if(have_rows("lucke")):
				?><ul class="galerie dokumente"><?php
				$lucken = get_field("lucke");
				foreach($lucken as $lucke): 
					$file = get_field("datei", $lucke->ID);
					$image = get_field("bild", $lucke->ID);
					?>
					<li>
						<a href="<?= $file["url"] ?>">
							<figure>
								<img src="<?= esc_url($image['url']); ?>" class="vorschau" alt="<?= esc_attr($image['alt']); ?>" class=""/>
							</figure>
							<div class="label">
								<h4><?= $lucke->post_title ?></h4>
								<div class="label-info" >
									<?= get_field("datum", $lucke->ID); ?>
									<?= get_field("autor", $lucke->ID); ?>
								</div>
							</div>
						</a>
					</li>
				<?php endforeach; ?>
				</ul>
			<?php endif; ?>

		</div>
	</article>
	<?php if( have_rows('bauteile') ): ?>
		<section style="margin-bottom:9vw">
			<h3 class="section-title">Bauteile</h3>
			<ul>
				<?php 
				while( have_rows('bauteile') ): 
					$row = the_row();
					$post = get_post(get_sub_field('bauteil')->ID);
					setup_postdata($post); // Setup this post for WP functions (variable must be named $post).
					include('templates/bauarchiv-entry.php');
					wp_reset_postdata(); // Reset the global post object so that the rest of the page works correctly.
				endwhile;
				include('templates/bauarchiv-entry-functions.php');
				?>
			</ul>
		</section >
	<?php endif; ?>	

	<?php if( have_rows('projekte') ): ?>
		<section id="projektbeispiele">
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
			
			<h3 class="section-title">Eigene Projekte</h3>
			<ul class="galerie">
				<?php while( have_rows('projekte') ): 
					$row = the_row();
					$pro = strtoupper(get_sub_field('kuerzel'));
					if (!empty($pro)):
						// Query mit WHERE Klausel, damit nur das Projekt mit entsprechendem Projektkürzel abgefragt wird
						$query = "SELECT Projektkürzel, Projektname, Kurzbeschreibung, `Jahr Fertigstellung` ".
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
						$krzl = $res[0];
						?>
						<li>
							<a href="<?=get_site_url();?>/projektanzeige/?proj=<?= $krzl ?>">
								<?php
								// Projektbild von interner Offlinefassung der Website anzeigen, wenn vorhanden
								$html = file_get_contents('http://webserver/website_rla/1_php/projekt.php?proj=' . $krzl);
								if (!empty($html))
								{
									preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $html, $matches);
									if (str_contains($matches[1][0], 'projekte')) {
										?>
										<figure>
											<img src="http://webserver/website_rla/website_rla/<?= $matches[1][0] ?>" class="vorschau">
										</figure>
										<?php
									}
								}
								else // falls Projekt nicht auf Website gefunden, Bild aus Projekt Post anzeigen, falls vorhanden
								{
									// nach entsprechendem Projekt Post suchen
									$projekt_post_query = new WP_Query(array('post_type' => 'projekt', 'meta_key' => 'projektkurzel', 'meta_value' => $krzl));
									$projekt_post = $projekt_post_query -> get_posts();
									if (!empty($projekt_post && get_field('bild', $projekt_post[0] -> ID))) {
										echo '<figure class="vorschau"><img src="' . get_field('bild', $projekt_post[0] -> ID) . '" ></figure>';
									}
									else {
										echo '<figure class="vorschau"><img class="bordered" src="' . get_site_url() . '/wp-content/uploads/placeholder.jpg"></figure>';
									}
								}
								?>
								<div class="label">
									<div class="label-kuerzel"><?= $krzl ?></div>
									<div class="label-info">
										<span><?= $res[1] ?></span>
										<span><?= $res[3] ?></span>
									</div>
								</div>
							</a>
						</li>
					<?php endif; ?>
				<?php endwhile; ?>
			</ul>
		</section>
	<?php endif; ?>	
	
	
</main>
<?php get_footer(); ?>