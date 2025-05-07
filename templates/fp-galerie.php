 <ul class="galerie dokumente">
    <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
        <li>
            <?php
            $image = get_field('bild');
            /*$cat = end(get_the_category())->name;*/
			// Holt den Namen der letzten Kategorie dieses Beitrags
			$categories = get_the_category(); // Store categories in a variable
            $cat = !empty($categories) ? end($categories)->name : ''; // Check if categories is not empty and then get the name
			
			$file = get_field('datei');
           
            if ($file): // Überprüfen, ob das Dateifeld vorhanden ist
                // Variablen extrahieren
                $url = $file['url'];
                $title = $file['title'];
            ?>
			    <a href="<?= esc_url($url); ?>" title="<?= esc_attr($title); ?>" target="_blank">
			<?php else: ?>
                <a href="<?= esc_url(get_permalink()); ?>" title="<?= esc_attr(get_the_title()); ?>">
			<?php endif; ?>
                
                <figure>
                    <img src="<?= esc_url($image['url']); ?>" class="vorschau" alt="<?= esc_attr($image['alt']); ?>" class=""/>
                </figure>
				<div class="label">
                    <h4><?php the_title(); ?></h4>
                    <?php 
					//if ($cat == "Fortbildungen"): 
					if (get_field('veranstalter')): ?>
                        <div class="label-info">
                            <?= esc_html(get_field('datum')); ?>
                            <?= esc_html(get_field('veranstalter')); ?>
                        </div>
                    <?php elseif (get_field('autor')): ?>
                        <div class="label-info" >
                            <?= esc_html(get_field('datum')); ?>
                            <?= esc_html(get_field('autor')); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </a>
        </li>
    <?php endwhile; ?>
</ul>
