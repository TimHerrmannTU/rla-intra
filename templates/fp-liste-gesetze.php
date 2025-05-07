<ul class="liste dokumente">
    <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
        <li class="inline">
		
			<?php
            if (get_field('datei')): // Überprüfen, ob das Dateifeld vorhanden ist
                $file = get_field('datei');
			endif; ?>
            
            <span style="width:7rem"><?php echo esc_html(get_field('autor-kurz')); ?></span>
			<a href="<?= esc_url($file['url']); ?>" title="<?= esc_attr($file['title']); ?>" target="_blank" >
				<h3><?php the_title(); ?></h3>
			</a>
                 
        </li>
    <?php endwhile; ?>
</ul>

<?php $post = get_post(63498) ?>

<ul class="liste">
    <?php while( have_rows('dokumente') ) : the_row();?>
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