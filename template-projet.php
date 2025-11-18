<?php
    /*
    Template Name: Page projet
    */
    // projet
    get_header();
?>

<?php
// Try to get a project id from query string, otherwise use the first project
$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;
// If no id passed, try to get the first project (helper name in French)
if (empty($project_id) && function_exists('ctrltim_get_all_projets')) {
    $all = ctrltim_get_all_projets();
    if (!empty($all) && is_array($all) && count($all) > 0) {
        $project_id = intval($all[0]->id);
    }
}

// Load project object using helper (keep this DB connection logic)
$project = null;
if ($project_id && function_exists('ctrltim_get_projet_by_id')) {
    $project = ctrltim_get_projet_by_id($project_id);
}
?>

<section class="pageProjet__titre">
    <div class="crochets">
        <div class="pageProjet__titre__boiteTitre">
            <div class="pageProjet__titre__boiteTitre__texte">
                <h2><?php echo $project ? esc_html($project->titre_projet) : esc_html(get_the_title()); ?></h2>
            </div>
            <div class="pageProjet__titre__boiteTitre__etudiants">
                <?php
                // If project exists, list associated students (helper returns detailed students)
                if ($project && function_exists('ctrltim_get_etudiants_for_projet')) {
                    $students = ctrltim_get_etudiants_for_projet($project->id);
                    if (!empty($students)) {
                        foreach ($students as $s) {
                            echo '<div class="pageProjet__titre__boiteTitre__etudiants__contenant"><span>' . esc_html($s->nom) . '</span></div>';
                        }
                    }
                } else {
                    // fallback to post author or static content
                    echo '<div class="pageProjet__titre__boiteTitre__etudiants__contenant"><span>' . esc_html(get_bloginfo('name')) . '</span></div>';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<section class="pageProjet__contenu">
    <div class="pageProjet__contenu__affiche">
        <?php
        if ($project && !empty($project->image_projet)) {
            echo '<img src="' . esc_url($project->image_projet) . '" alt="' . esc_attr($project->titre_projet) . '" class="pageProjet__contenu__affiche__image">';
        } else {
            if (has_post_thumbnail()) {
                the_post_thumbnail('full', array('class' => 'pageProjet__contenu__affiche__image'));
            } else {
                echo '<img src="' . esc_url( get_template_directory_uri() . '/images/default.jpg' ) . '" alt="Image par défaut" class="pageProjet__contenu__affiche__image">';
            }
        }
        ?>
    </div>

    <div class="pageProjet__contenu__information">
        <div class="pageProjet__contenu__information__description">
            <h4>Description</h4>
            <p><?php echo $project ? wp_kses_post($project->description_projet) : get_the_excerpt(); ?></p>
        </div>

        <div class="pageProjet__contenu__information__video">
            <?php
            // Video can be stored in project->video_projet (url or embed)
            $video_html = '';
            if ($project && !empty($project->video_projet)) {
                // try to get oEmbed HTML
                if (function_exists('wp_oembed_get')) {
                    $video_html = wp_oembed_get($project->video_projet);
                }
                // fallback: raw output
                if (empty($video_html)) {
                    $video_html = esc_url($project->video_projet);
                }
            }

            if ($video_html) : ?>
                <div class="pageProjet__contenu__information__video__conteneur">
                    <?php echo $video_html; ?>
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

    // Priorité aux images enregistrées dans la colonne images_projet (JSON) du projet
    if (isset($project) && $project && !empty($project->images_projet)) {
        $stored = json_decode($project->images_projet, true);
        if (is_array($stored) && !empty($stored)) {
            foreach ($stored as $img) {
                // Si la valeur est un ID numérique, essayer de récupérer l'URL
                if (is_numeric($img)) {
                    $url = wp_get_attachment_url(intval($img));
                    if ($url) $pageProjet_images[] = $url;
                } else {
                    // sinon on suppose une URL
                    if (!empty($img)) $pageProjet_images[] = $img;
                }
            }
        }
    }

    // // Si aucune image depuis le customizer, retomber sur les images présentes dans le contenu du post
    // if (empty($pageProjet_images)) {
    //     // Récupérer les images depuis le contenu du post
    //     $post = get_post($post_id);
    //     $content = $post ? $post->post_content : '';
    //     // Chercher les IDs des images dans le contenu
    //     preg_match_all('/wp-image-(\d+)/', $content, $matches);
    //     if (!empty($matches[1])) {
    //         $image_ids = $matches[1];
    //         foreach ($image_ids as $image_id) {
    //             $image_url = wp_get_attachment_url($image_id);
    //             if ($image_url) {
    //                 $pageProjet_images[] = $image_url;
    //             }
    //         }
    //     }
    // }

    // Nombre d'images trouvées
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