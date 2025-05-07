<?php get_header(); ?>

<main>
	<ul class="galerie">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <li>
                <?php
                /*
                $file = get_field('datei');
                if( $file ):
                    $url = wp_get_attachment_url( $file );
                    $thumb = get_pdf_thumbnail_image_src( $file );	?>
                    <a href="<?php echo esc_html($url); ?>" ><img src="<?php echo esc_url($thumb['url']); ?>"/>Download file</a>
                <?php endif; 

                $file = get_field('datei');
                if( $file ):
                    $attachment_id = array_keys(get_attached_media('*,*',$post->ID))[0]; ?>
                    <a href="<?php echo esc_html($file['url']); ?>" ><?php echo wp_get_attachment_image( $attachment_id, 'full', true, '' ); ?></a>
                <?php endif; 
                */	
                ?>

                <?php
                $file = get_field('datei');
                if( $file ):
                    $icon = $file['icon'];
                    // Display image thumbnail when possible.
                    if( $file['type'] == 'image' ) {
                        $icon =  $file['sizes']['thumbnail'];
                    } ?>
                    <a href="<?php echo esc_html($file['url']); ?>" title="<?php echo esc_attr($file['title']); ?>">
                        <img src="<?php echo esc_attr($icon); ?>" />
                        <span><?php echo esc_html($file['title']); ?></span>
                    </a>
                <?php endif; ?>
            </li>

        <?php endwhile; endif; ?>
	</ul>
</main>

<?php
    /*
    * Kommentare sind auf Seiten deaktiviert.
    * Möchtest du die Kommentarfunktion auf Seiten aktivieren, entferne einfach die beiden "//"-Zeichen vor "comments_template();"
    */

    //comments_template();
?>
 
<!-- content -->

<?php get_footer(); ?>