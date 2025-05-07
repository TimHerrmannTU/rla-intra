<?php get_header(); ?>

<main>
	<div class="bigbox" id="planungsthemen">
		<ul class="galerie dokumente">
	
            <?php 
            if (have_posts()) : while (have_posts()) : the_post(); 
                $image = get_field('bild'); ?>
		        <li>
                    <a href="<?= esc_url(get_permalink()); ?>" title="<?= esc_attr(get_the_title()); ?>">
                        <div class="label">
                            <h4><?php the_title(); ?></h4>
                        </div>
                        <figure>
                            <img src="<?= esc_url($image['url']); ?>" class="vorschau" alt="<?= esc_attr($image['alt']); ?>" class=""/>
                        </figure>
                    </a>
                </li>
            <?php endwhile; endif; ?>

        </ul>
	</div>
</main>

<?php get_footer(); ?>