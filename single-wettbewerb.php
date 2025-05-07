<?php get_header(); ?>
   
<main>
     <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<article class="bigbox container_single-post">
		
			<?php 
			$image = get_field('bild');
			if( !empty( $image ) ): ?>
				<figure class="main-figure"><img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>" class=""/></figure>
			<?php endif; ?>
			
			<div class="main-info">
				<div class="entry-header">

					<div class="element-title"><h1><?php the_title(); ?></h1></div>

					<?php if( get_field('datum') ): ?>
						<div><span class="label">Datum:</span> <?= esc_html( get_field('datum') ); ?></div>
					<?php endif; ?>

					<?php $price = get_field('wb-ergebnis');
					if( $price AND $price != "keine Auszeichnung" ): ?>
						<div><span class="label">Wettbewerbsergebnis:</span> <?= $price ?></div>
					<?php endif;
					
					if( get_field('auslober') ): ?>
						<div><span class="label">Auslober:</span> <?= esc_html( get_field('auslober') ); ?></div>
					<?php endif; ?>
					
					<?php if( have_rows('partner') ): ?>
						<div><span class="label">Planungspartner:</span> 
							<?php while( have_rows('partner') ): the_row(); ?>	
							<span><?= get_sub_field('name_partner'); ?>, </span>
							<?php endwhile; ?>
						</div>
					<?php endif; ?>
					
					<?php
					$field = get_field('Bearbeiter-2');
					if($field): ?>
						<div>
							<span class="label">Bearbeiter: </span>
							<?php foreach ($field as $key => $worker) {
								?><a href="<?= $worker->guid ?>"><?= $worker->post_title ?></a><?php
								if ($key != array_key_last($field)) {
									echo ", ";
								}
							} ?>
						</div>
					<?php endif; ?>
					
					<?php if( have_rows('weblinks') ): ?>
						<ul class="weblinks" style="display:block;margin:1em 0" >
							<span class="label">Weblinks:</span>
							<?php
							while( have_rows('weblinks') ): the_row(); 
								// Load sub field value.
								$link = get_sub_field('link');
								if( $link ): 
									$link_url = $link['url'];
									$link_title = $link['title'];
									$link_target = $link['target'] ? $link['target'] : '_self';
									?><li><a href="<?= esc_url( $link_url ); ?>" target="<?= esc_attr( $link_target ); ?>"><?= esc_html( $link_title ); ?></a></li><?php
								endif;
							endwhile;
							?>
						</ul>
					<?php endif; ?>
					
					<?php
					$schlagworte = get_field('wb-schlagworte');
					if( $schlagworte ): ?>
						<ul class="tags" style="" >
							<?php foreach( $schlagworte as $schlagwort ): ?>
								<li><button class="bordered smooth"><?= $schlagwort['value']; ?></button></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					
				</div>
			</div>	

			<section class="dokumente">
				<?php if( have_rows('lounge-inhalte') ): ?>

					<ul class="liste">
						<?php while( have_rows('lounge-inhalte') ): the_row(); ?>

							<li style="display:flex; gap:1em; align-items:flex-end">
								<?php 
								$image = get_sub_field('bild');
								if( !empty( $image ) ): ?>
									<figure><img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>" class="medium-img"/></figure>
								<?php endif; ?>
								<div class="info">
									<a href="<?= esc_attr($url); ?>" title="<?= esc_attr($title); ?>" target="blank">
										<span><?= esc_html(get_sub_field('titel') ); ?></span> <img class="file-icon" style="margin-left:1em" src="<?= esc_attr($icon); ?>" /><br/>
									</a>
									<div class="fs-s"><?php
										$colors = get_sub_field( 'farbgebung' );
										// Create a comma-separated list from selected values.
										if( $colors ): ?>
										Farbgebung: <?= implode( ', ', $colors ); ?>
										<?php endif; ?>
									</div>
									<div class="fs-s">Zeichnungstyp: <?= esc_html(get_sub_field('typ') ); ?></div>
								</div>
							</li>

							<?php endwhile; ?>
						</ul>

					<?php endif; ?>	
				</div>	
			</section>

		</article>
	<?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>