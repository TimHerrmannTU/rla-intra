<ul class="liste dokumente">
    <?php //if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
        <li class="">
            <h3><?php the_title(); ?></h3>
            <div class="entry-standort">

                <?php if(get_field('bild')): 
                    $image = get_field('bild'); ?>
                    <figure class="main-figure">
                        <img src="<?= esc_url($image['url']); ?>" alt="<?= esc_attr($image['alt']); ?>" class="medium-img"/>
                    </figure>
                <?php endif; ?>	

                <div class="main-info">
                    <section class="">
                        <?php if( get_field('adresse') ): ?><p><?= wp_kses_post ( get_field('adresse') ); ?></p><?php endif;?>
                        <?php if( get_field('telefon') ): ?><p><?= esc_html( get_field('telefon') ); ?></p><?php endif;?>
                    </section>
                </div>
            
                <div class="info2">
                    <?php if( get_field('email') ): ?><p style="margin-bottom:0.1em"><?= esc_html( get_field('email') ); ?></p><?php endif;?>
                    <?php if( get_field('sammeladresse') ): ?><p><?= esc_html( get_field('sammeladresse') ); ?></p><?php endif;?>
                    <?php
                    $leader = get_field('buroleitung');
                    if( $leader ): ?>
                        <p>
                            Büroleitung:
                            <?php
                            $permalink = get_permalink( $leader->ID );
                            $custom_field = get_field( 'field_name', $leader->ID );
                            ?>
                            <a href="<?= esc_url( $permalink ); ?>"><?= esc_html( $leader->post_title )?>
                                <span style="color:grey; font-size: 1.2rem; vertical-align: -10%; display: inline-block" class="dashicons dashicons-admin-users"></span>
                            </a>
                        </p>
                    <?php endif;?>
                </div>

            </div>
        </li>
    <?php endwhile; ?>
</ul>