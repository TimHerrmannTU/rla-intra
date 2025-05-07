<ul>
    <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
        <li>
            <?php
            if (get_field('datei')): // Überprüfen, ob das Dateifeld vorhanden ist
                $file = get_field('datei');
                // Vorschaubild anzeigen, wenn verfügbar
                $icon = $file['icon'];
                if ($file['type'] == 'image') $icon = $file['sizes']['thumbnail'];
                // Thema  esc_html(get_field('thema'));
                if ($the_query->query["category_name"] == "anleitungen"): 
                    $cats = get_the_category($post->ID);
                    $fin_cat = $cats[0];
                    foreach ($cats as $cat){
                        if (intval($cat->term_id) > intval($fin_cat->term_id)) {
                            $fin_cat = $cat;
                        }
                    }
                    ?>
                    <span class="cat-name"><?= $fin_cat->name ?></span>
                <?php endif; ?>
                <!-- Link zur Datei -->
                <a href="<?= esc_url($file['url']); ?>" title="<?= esc_attr($file['title']); ?>" target="_blank">
                    <span><?php the_title(); ?></span> <!--<img class="file-icon" src="<?= esc_attr($icon); ?>" />-->
                </a>
				<?php if (get_field('anmerkungen')): ?>
                    <span class="anmerkung"><?= esc_html(get_field('anmerkungen')); ?></span>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (get_field("link")): ?>
                <?php if (str_contains(get_field("link"), "http")): ?>
                    <a href="<?= get_field("link") ?>" target="_blank"><?php the_title(); ?> <span class="dashicons dashicons-external"></span></a>
                <?php else: ?>
                    <a onclick="copyInput(this)"><?php the_title(); ?> <span class="dashicons dashicons-external"></span></a>
                    <input class="local-path" type="text" readonly value="<?= get_field("link") ?>" style="display: none; width: 100%;">
                    <span class="success" style="display: none">kopiert!</span>
                <?php endif; ?>
            <?php endif; ?>
        </li>
    <?php endwhile; ?>
</ul>