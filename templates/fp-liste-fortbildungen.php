 <ul class="liste dokumente">
    <?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>
        <li>
            <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
                <h3><?php the_title(); ?></h3>
			</a>
            <!-- php if cat=fortbildungen einbauen?-->		
            <div class="label-info">
                <?php if (get_field('datum')): ?><span><?php echo esc_html(get_field('datum')); ?></span><?php endif; ?>
                <?php if (get_field('veranstalter')): ?><span><?php echo esc_html(get_field('veranstalter')); ?></span><?php endif; ?>
            </div>        
        </li>
    <?php endwhile; ?>
</ul>