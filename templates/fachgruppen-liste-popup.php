<?php 
$terms = get_field('fachgruppen');
if( $terms ): ?>
	<ul class="fachgruppen-liste">
	<?php foreach( $terms as $term ): if($term->slug != "fachgruppen"):?>
		<?php 
		// Mitarbeiter gemäß Fachgruppe abfrufen
		$fachgruppe_mitarbeiter = get_posts(array('numberposts' => -1, 'post_type' => 'mitarbeiter', 'tax_query' => array(array('taxonomy' => 'fachgruppen', 'include_children' => false, 'field' => 'slug', 'terms' => $term->slug))));	
		?>
		<li style="position:relative">
			<?php // onClick-Event parameter wird als jQuery-Objekt übergeben ?>
			<button class="bordered smooth" onclick="javascript:toggle_fachgruppe($(this))"><?php echo esc_html( $term->name ); ?></button>
			<div class="popup hidden">
				<button class="close" onclick="javascript:toggle_fachgruppe($(this).parent().parent().children('button'))">X</button>
				<?php
					foreach($fachgruppe_mitarbeiter as $mitarbeiter) {
						echo '<span>';
						$federführungen = get_field('federfuehrung', $term);
						foreach($federführungen as $federführung) {
							if($federführung == $mitarbeiter) echo '<span title="' . $mitarbeiter->post_title . ' hat die Federführung für diese Fachgruppe" style="display: inline; color:grey; font-size: 1.2rem; vertical-align: -10%;" class="dashicons dashicons-welcome-learn-more"></span>&nbsp;';
						}
						echo '<a href ="' . $mitarbeiter->guid . '">' . $mitarbeiter->post_title . '</a>';
						echo'</span>';
					}
				?>
			</div>
			
		</li>
	<?php endif; endforeach; ?>
	</ul>
<?php endif; ?>

<script>
jQuery(document).ready(function($) {
	// Ein- und Ausklappen der Fachgruppen
	function toggle_fachgruppe(theButton) {
		var theLI = $(theButton).parent();
		var theDiv = theLI.children('div');
		if (theDiv.hasClass('hidden')) {
			// alle anderen Fachgruppen einklappen
			$('button.bordered').removeClass('active');
			$('div.popup').addClass('hidden');
			// Fachgruppe ausklappen
			theButton.addClass('active');
			theDiv.removeClass('hidden');
		} else {
			// Fachgruppe einklappen
			theButton.removeClass('active');
			theDiv.addClass('hidden');
		}
	}
})
</script>