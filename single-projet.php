<?php
get_header();
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section class="pageProjet__titre">
    <div class="pageProjet__conteneur">
        <div class="crochets">
            <div class="pageProjet__titre__boiteTitre">            
                <div class="pageProjet__titre__boiteTitre__texte">
                    <h2><?php the_title(); ?></h2>
                </div>
                <div class="pageProjet__titre__boiteTitre_etudiants">
                    <?php
                    $etudiants_meta = get_post_meta( get_the_ID(), 'etudiants', true );
                    if ( ! empty( $etudiants_meta ) ) {
                        $names = array_map( 'trim', explode( ',', $etudiants_meta ) );
                        foreach ( $names as $name ) {
                            if ( $name !== '' ) {
                                echo '<div class="pageProjet__titre__boiteTitre__etudiants__contenant"><span>' . esc_html( $name ) . '</span></div>';
                            }
                        }
                    } else {
                        $terms = get_the_terms( get_the_ID(), 'etudiant' );
                        if ( $terms && ! is_wp_error( $terms ) ) {
                            foreach ( $terms as $t ) {
                                echo '<div class="pageProjet__titre__boiteTitre__etudiants__contenant"><span>' . esc_html( $t->name ) . '</span></div>';
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pageProjet__contenu">
    <div class="pageProjet__contenu__affiche">
        <?php
        if ( has_post_thumbnail() ) {
            the_post_thumbnail( 'large', array( 'alt' => get_the_title() ) );
        } else {
            $project_image = get_post_meta( get_the_ID(), 'project_image', true );
            if ( $project_image ) {
                echo '<img src="' . esc_url( $project_image ) . '" alt="' . esc_attr( get_the_title() ) . '">';
            } else {
                $attachments = get_posts( array(
                    'post_type'      => 'attachment',
                    'posts_per_page' => 1,
                    'post_parent'    => get_the_ID(),
                    'post_mime_type' => 'image',
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ) );
                if ( $attachments ) {
                    $url = wp_get_attachment_url( $attachments[0]->ID );
                    echo '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( get_the_title() ) . '">';
                }
            }
        }
        ?>
    </div>

    <div class="pageProjet__contenu__information">
        <div class="pageProjet__contenu__information__description">
            <h4>Description</h4>
            <div class="pageProjet__contenu__information__texte">
                <?php the_content(); ?>
            </div>
        </div>

        <div class="pageProjet__contenu__information__video">
            <?php
            $video_meta = get_post_meta( get_the_ID(), 'project_video', true );
            if ( $video_meta ) {
                if ( is_numeric( $video_meta ) ) {
                    $video_url = wp_get_attachment_url( intval( $video_meta ) );
                } else {
                    $video_url = $video_meta;
                }
                if ( $video_url ) {
                    echo '<video controls src="' . esc_url( $video_url ) . '"></video>';
                }
            } else {
                $video_attach = get_posts( array(
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'video',
                    'posts_per_page' => 1,
                    'post_parent'    => get_the_ID(),
                ) );
                if ( $video_attach ) {
                    $vurl = wp_get_attachment_url( $video_attach[0]->ID );
                    echo '<video controls src="' . esc_url( $vurl ) . '"></video>';
                }
            }
            ?>
            <div class="pageProjet__contenu__information__video__boiteFond">
                <div class="pageProjet__contenu__information__video__boiteFond__boiteTexte">
                    <p><?php echo esc_html( get_post_meta( get_the_ID(), 'project_video_desc', true ) ); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pageProjet__carrousel">
    <div class="pageProjet__carrousel__images">
        <button class="pageProjet__carrousel__images__fleche"></button>
        <?php
        $gallery = get_posts( array(
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => -1,
            'post_parent'    => get_the_ID(),
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ) );

        if ( $gallery ) {
            foreach ( $gallery as $img ) {
                $url = wp_get_attachment_url( $img->ID );
                echo '<img src="' . esc_url( $url ) . '" alt="">';
            }
        } else {
            preg_match_all( '/<img[^>]+src=["\']([^"\']+)["\']/i', get_the_content(), $matches );
            if ( ! empty( $matches[1] ) ) {
                foreach ( $matches[1] as $m ) {
                    echo '<img src="' . esc_url( $m ) . '" alt="">';
                }
            } else {
                echo '<!-- Pas d\'images pour la galerie -->';
            }
        }
        ?>
        <button class="pageProjet__carrousel__images__fleche"></button>
    </div>

    <div class="pageProjet__carrousel__boutons">
        <?php
        $count = $gallery ? count( $gallery ) : max(1, count( $matches[1] ?? array() ));
        for ( $i = 0; $i < $count; $i++ ) {
            echo '<input type="radio" ' . ( $i === 0 ? 'checked' : '' ) . '>';
        }
        ?>
    </div>
</section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>