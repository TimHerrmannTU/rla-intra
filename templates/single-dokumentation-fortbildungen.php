<?php // falls was vermisst wird ist es jetzt wahrscheinlich in der single-dokumentation.php ?>
<section class="">
	<?php if( have_rows('dokumente') ): ?>
		<ul class="liste">
			<?php while( have_rows('dokumente') ): the_row(); ?>
				<li>
					<?php $file = get_sub_field('dokument');
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
						<a style="display:flex;align-items:flex-start;gap:0.5rem" href="<?php echo esc_attr($url); ?>" title="<?php echo esc_attr($title); ?>" target="blank">
							<span><?php echo esc_html(get_sub_field('titel') ); ?></span>
							<img class="file-icon" style="margin-left:1em" src="<?php echo esc_attr($icon); ?>" /><br/>
						</a>
						<span class="fs-s"><?php echo esc_html(get_sub_field('autor') ); ?></span>
					<?php endif; ?>
				</li>
			<?php endwhile; ?>
		</ul>
	<?php endif; ?>
</section>
				
<div class="ordnerlink">			
	<?php $link = get_field('bilder_url');
	if( $link ): ?>
		<a class="" href="<?php echo esc_url( $link ); ?>">Bilder</a>
	<?php endif; 
	$link2 = get_field('plaene_url');
	if( $link ): ?>
		<a class="" href="<?php echo esc_url( $link2 ); ?>">Pläne</a>
	<?php endif; ?>
</div>