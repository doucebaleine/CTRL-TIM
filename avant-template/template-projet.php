<?php
    /*
    Template Name: Page projet
    */
    // projet
    get_header();
?>

<section class="pageProjet__titre">
    <div class="crochets">
        <div class="pageProjet__titre__boiteTitre">
            <div class="pageProjet__titre__boiteTitre__texte">
                <h2>Hachiman</h2>
            </div>
            <div class="pageProjet__titre__boiteTitre__etudiants">
                <div class="pageProjet__titre__boiteTitre__etudiants__contenant">
                    <span>Malaïka Abevi</span>
                </div>
                <div class="pageProjet__titre__boiteTitre__etudiants__contenant">
                    <span>Sébastien Malo</span>
                </div>
                <div class="pageProjet__titre__boiteTitre__etudiants__contenant">
                    <span>Yanis Oulmane</span>
                </div>
                <div class="pageProjet__titre__boiteTitre__etudiants__contenant">
                    <span>Matys Voisin</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pageProjet__contenu">
    <div class="pageProjet__contenu__affiche">
        <?php
        if (has_post_thumbnail()) {
            the_post_thumbnail('full', array('class' => 'pageProjet__contenu__affiche__image'));
        } else {
            echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default.jpg' ) . '" alt="Image par défaut" class="pageProjet__contenu__affiche__image">';
        }
        ?>
    </div>

    <div class="pageProjet__contenu__information">
        <div class="pageProjet__contenu__information__description">
            <h4>Description</h4>
            <p><?php the_excerpt(); // ou le contenu custom ?></p>
        </div>

        <div class="pageProjet__contenu__information__video">
            <?php 
            $video = get_field('video_url'); // 🔹 Récupère le champ ACF (oEmbed)

            if ( $video ) : ?>
                <div class="pageProjet__contenu__information__video__conteneur">
                    <?php echo $video; // 🔹 ACF retourne déjà l'iframe complet ?>
                </div>
            <?php else : ?>
                <div class="pageProjet__contenu__information__video__boiteFond">
                    <div class="pageProjet__contenu__information__video__boiteFond__boiteTexte">
                        <p>Aucune vidéo disponible pour ce projet.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>


    </div>
</section>

<section class="pageProjet__carrousel">
    <?php
    $post_id = get_the_ID();
    $pageProjet_images = array();

    // Récupérer les images depuis le contenu du post
    $post = get_post($post_id);
    $content = $post->post_content;
    
    // Chercher les IDs des images dans le contenu
    preg_match_all('/wp-image-(\d+)/', $content, $matches);
    
    if (!empty($matches[1])) {
        // Utiliser toutes les images trouvées (sans limite)
        $image_ids = $matches[1];
        
        foreach ($image_ids as $image_id) {
            $image_url = wp_get_attachment_url($image_id);
            if ($image_url) {
                $pageProjet_images[] = $image_url;
            }
        }
    }

    $pageProjet_nb_images = count($pageProjet_images);
    
    ?>

    <?php if ( $pageProjet_nb_images > 0 ) : ?>
        <div class="pageProjet__carrousel__images">
            <?php foreach ( $pageProjet_images as $k => $image_url ) : ?>
                <div class="pageProjet__carrousel__images__conteneur <?php echo $k === 0 ? 'pageProjet__carrousel__images__conteneur--active' : ''; ?>">
                    <img src="<?php echo esc_url( $image_url ); ?>" alt="Image <?php echo $k + 1; ?>" class="pageProjet__carrousel__images__image">
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pageProjet__carrousel__nav">
            <button type="button" class="pageProjet__carrousel__nav__btn pageProjet__carrousel__nav__btn--prev" aria-label="Précédent">
                <?php if ( function_exists('theme_get_svg') ) theme_get_svg( 'fleche_gauche', array( 'class' => 'icon', 'aria-hidden' => 'true' ) ); ?>
            </button>

            <button type="button" class="pageProjet__carrousel__nav__btn pageProjet__carrousel__nav__btn--next" aria-label="Suivant">
                <?php if ( function_exists('theme_get_svg') ) theme_get_svg( 'fleche_droite', array( 'class' => 'icon', 'aria-hidden' => 'true' ) ); ?>
            </button>
        </div>

        <div class="pageProjet__carrousel__boutons">
            <?php for ( $k = 0; $k < $pageProjet_nb_images; $k++ ) : ?>
                <input
                    class="pageProjet__carrousel__boutons__input hero__radio__input"
                    type="radio"
                    name="carrousel"
                    id="slide<?php echo $k; ?>"
                    <?php echo $k === 0 ? 'checked' : ''; ?>>
                <label
                    class="pageProjet__carrousel__boutons__label hero__radio__label"
                    for="slide<?php echo $k; ?>"
                    aria-label="Aller à la diapositive <?php echo $k + 1; ?>"></label>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</section>

<?php
get_footer();
?>