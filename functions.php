
<?php

// Définir le chemin vers le dossier "functions"
$functions_dir = get_template_directory() . '/functions/';

// Liste des fichiers à inclure
$function_files = array(
    'customizer.php',
    'base-de-donnees.php',
);

// Boucle pour inclure tous les fichiers
foreach ($function_files as $file) {
    include_once $functions_dir . $file;
}

// Créer un alias global $ctrltim_db pointant sur $wpdb pour les helpers qui l'utilisent
add_action('init', function() {
    global $ctrltim_db, $wpdb;
    if (empty($ctrltim_db) && !empty($wpdb)) {
        $ctrltim_db = $wpdb;
    }
});

function mon_theme_supports() {
  add_theme_support('title-tag');
  add_theme_support('menus');
  add_theme_support('post-thumbnails');

  add_theme_support("custom-logo", array(
    "height"      => 150,
    "width"       => 150,
    "flex-height" => true,
    "flex-width"  => true,
));

  // Enregistrer les emplacements de menu
  register_nav_menus(array(
    'principal' => __('Menu Principal', 'ctrltim'),
  ));

}
add_action( 'after_setup_theme', 'mon_theme_supports' );

function ctrltim_enqueue_styles() {
    // En mode développement, vérifier si le SCSS est plus récent que le CSS
    if (WP_DEBUG) {
        $scss_file = get_template_directory() . '/sass/style.scss';
        $css_file = get_template_directory() . '/style.css';
        
        if (file_exists($scss_file) && file_exists($css_file)) {
            $scss_time = filemtime($scss_file);
            $css_time = filemtime($css_file);
            
            if ($scss_time > $css_time) {
                // Ajouter un commentaire admin si le SCSS est plus récent
                add_action('admin_notices', function() {
                    echo '<div class="notice notice-warning"><p>⚠️ Les fichiers SCSS sont plus récents que le CSS compilé. Pensez à recompiler le SASS.</p></div>';
                });
            }
        }
    }
    
    wp_enqueue_style('ctrltim-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'));
}
add_action('wp_enqueue_scripts', 'ctrltim_enqueue_styles');

function ctrltim_enqueue_scripts(){
    wp_enqueue_script('ctrltim-menu', get_template_directory_uri() . '/js/menu.js', array(), filemtime(get_template_directory() . '/js/menu.js'), true);
    wp_enqueue_script('ctrltim-cards', get_template_directory_uri() . '/js/cards.js', array(), filemtime(get_template_directory() . '/js/cards.js'), true);
    wp_enqueue_script('ctrltim-filter', get_template_directory_uri() . '/js/filter.js', array(), filemtime(get_template_directory() . '/js/filter.js'), true);
    wp_enqueue_script('ctrltim-galerie', get_template_directory_uri() . '/js/galerie.js', array(), filemtime(get_template_directory() . '/js/galerie.js'), true);
    wp_enqueue_script('ctrltim-carrousel', get_template_directory_uri() . '/js/carrousel.js', array(), filemtime(get_template_directory() . '/js/carrousel.js'), true);
    
    // Exposer l'URL du répertoire du thème au JS pour que les scripts puissent référencer les images de manière fiable
    wp_localize_script('ctrltim-cards', 'CTRL_TIM', array(
        'themeUrl' => get_template_directory_uri(),
    ));
}
add_action('wp_enqueue_scripts','ctrltim_enqueue_scripts');

// function enqueue_custom_scripts() {
//   wp_enqueue_script(
//       'destination_restapi',
//       get_template_directory_uri() . '/js/destination.js',
//       array(),
//       filemtime(get_template_directory() . 
//       '/js/destination.js'),
//       true
//   );

//   wp_enqueue_script(
//     'carrousel_restapi',
//     get_template_directory_uri() . '/js/carrousel.js',
//     array(),
//     filemtime(get_template_directory() . 
//     '/js/carrousel.js'),
//     true
// );
// }


// add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');


/**
 * Modifie la requete principale de WordPress avant qu'elle soit exécuté
 * le hook « pre_get_posts » se manifeste juste avant d'exécuter la requête principal
 * Dépendant de la condition initiale on peut filtrer un type particulier de requête
 * Dans ce cas ci nous filtrons la requête de la page d'accueil
 * @param WP_query  $query la requête principal de WP
 */

//* ///////////////////////////////////
// À RÉACTIVER
////////////////////////////////////*//
// function modifie_requete_principal( $query ) {
//   if ( $query->is_home() && $query->is_main_query() && ! is_admin() ) {
//     $query->set( 'category_name', 'populaire' );
//     $query->set( 'orderby', 'title' );
//     $query->set( 'order', 'ASC' );
//     }
//    }
//    add_action( 'pre_get_posts', 'modifie_requete_principal' );

// Fonction de fallback pour le menu principal
function ctrltim_fallback_menu() {
    echo '<ul class="menu-principal">';
    echo '<li><a class="bouton-menu primaire" href="' . esc_url(home_url('/')) . '">Accueil</a></li>';
    echo '<li><a class="bouton-menu" href="' . esc_url(home_url('/galerie')) . '">Galerie</a></li>';
    echo '<li><a class="bouton-menu" href="' . esc_url(home_url('/a-propos')) . '">À propos</a></li>';
    echo '<li><a class="bouton-menu" href="' . esc_url(home_url('/contact')) . '">Contact</a></li>';
    echo '</ul>';
}

/**
 * Récupère et renvoie/affiche un fichier SVG depuis /images/svg/<name>.svg
 * $name sans extension, $atts tableau d'attributs à injecter dans la balise <svg>
 */
function theme_get_svg( $name, $atts = array(), $echo = true ) {
    $file = get_template_directory() . '/images/svg/' . $name . '.svg';
    if ( ! file_exists( $file ) ) {
        return '';
    }

    $svg = file_get_contents( $file );

    if ( ! empty( $atts ) ) {
        $attr_string = '';
        foreach ( $atts as $k => $v ) {
            $attr_string .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
        }
        $svg = preg_replace( '/<svg([^>]*)/', '<svg$1' . $attr_string, $svg, 1 );
    }

    if ( $echo ) {
        echo $svg;
        return;
    }
    return $svg;
}

/**
 * Renvoie l'URL d'un SVG dans /images/svg/
 */
function theme_svg_url( $name ) {
    return get_template_directory_uri() . '/images/svg/' . $name . '.svg';
}

// 
// 
// ?>
<?php
/**
 *
 * ////////////////////////BASE DE DONNÉES/////////////////////////
 *
 * Référence et snippets rapides pour récupérer et utiliser les variables des bases de données.
 */

if (!defined('ABSPATH')) {
    exit; // Sécurité : accès direct interdit
}

// ------------------------------------------------------------------
// 1) BASE DE DONNÉES PROJETS
// Colonnes : id, titre_projet, description_projet, video_projet, image_projet, images_projet, annee_projet, lien, cours, cat_exposition, filtres, etudiants_associes, date_creation
// ------------------------------------------------------------------

// Récupérer tous les projets (retourne tableau d'objets)
function ctrltim_get_all_projets() {
    global $ctrltim_db;
    $nom_table = ctrltim_table_name('ctrltim_projets');
    // Trier par titre_projet A->Z
  $sql = "SELECT id, titre_projet, description_projet, video_projet, image_projet, images_projet, annee_projet, lien, cours, cat_exposition, filtres, etudiants_associes FROM {$nom_table} ORDER BY titre_projet ASC";
    return $ctrltim_db->get_results($sql);
}

// Récupérer un projet par id (retourne un objet)
function ctrltim_get_projet_by_id($id) {
  global $ctrltim_db;
    $nom_table = ctrltim_table_name('ctrltim_projets');
  $colonnes = 'id, titre_projet, description_projet, video_projet, image_projet, images_projet, annee_projet, lien, cours, cat_exposition, filtres, etudiants_associes';
    return $ctrltim_db->get_row($ctrltim_db->prepare("SELECT {$colonnes} FROM {$nom_table} WHERE id = %d", $id));
}

// Exemple : comment référencer chaque variable d'un projet dans le HTML 
/*
$project = ctrltim_get_projet_by_id(12);
if ($project) {
    $id = $project->id;                     // int
    $titre = $project->titre_projet;        // string
    $description = $project->description_projet; // string
    $video = $project->video_projet;        // string (url ou identifiant)
    $image = $project->image_projet;        // string (url)
    $lien = $project->lien;                 // string (url)
    $cours = $project->cours;               // string
    $cat = $project->cat_exposition;        // string (valeur de catégorie)
    $filtres = json_decode($project->filtres, true) ?: array(); // array
    $etudiants_associes = json_decode($project->etudiants_associes, true) ?: array(); // array d'IDs, voir fonction en dessous pour récupérer les détails
}
*/

// ----- Exemple : utiliser les images pour un carrousel  -----
/*
$project = ctrltim_get_projet_by_id(12);
if ($project) {
    // Images pour carrousel : stockées en JSON dans images_projet
    $images = json_decode($project->images_projet, true) ?: array();


    // Exemple HTML simple (sans JS vu qu'il est déjà fait) pour référencer les variables de la base de données
    if (!empty($images)) : ?>
      <div class="project-carousel">
        <?php foreach ($images as $image => $img_url) : if ($image >= 5) break; ?>
          <div class="carousel-slide">
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($project->titre_projet); ?>">
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif;
}
*/

///Exemple: Récupérer tous les projets et les afficher en HTML
/*

<?php
$projets = ctrltim_get_all_projets();
if ($projets) :
?>
  <section class="liste-projets">
  <?php foreach ($projets as $projet) : ?>
    <section class="projet">
      <h3><?php echo esc_html($projet->titre_projet ); ?></h3>
        <img class="image-projet" src="<?php echo esc_url( $projet->image_projet ); ?>" alt="<?php echo esc_attr( $projet->titre_projet ); ?>">
      <p><?php echo wp_kses_post( $projet->description_projet ); ?></p>
      <?php if (!empty($projet->lien)) : ?>
        <p><a href="<?php echo esc_url( $projet->lien ); ?>" target="_blank" rel="noopener"><?php echo esc_html__('Voir le projet', 'ctrl-tim'); ?></a></p>
      <?php endif; ?>
    </section>
  <?php endforeach; ?>
  </section>
<?php else: ?>
  <p><?php echo esc_html__('Aucun projet trouvé.', 'ctrl-tim'); ?></p>
<?php endif; ?>


// Exemple pratique : récupérer les étudiants détaillés pour un projet*/
function ctrltim_get_etudiants_for_projet($project_id) {
  global $ctrltim_db;
    // Récupère l'objet projet
    $projet = ctrltim_get_projet_by_id($project_id);
    if (!$projet) return array();

    // IDs d'étudiants (tableau d'entiers)
    $ids_etudiants = json_decode($projet->etudiants_associes, true) ?: array();
    $ids_etudiants = array_map('intval', $ids_etudiants);
    $ids_etudiants = array_filter($ids_etudiants);
    if (empty($ids_etudiants)) return array();

    // Préparer la requête (placeholders selon le nombre d'IDs)
    $placeholders_etudiants = implode(',', array_fill(0, count($ids_etudiants), '%d'));
    $table_etudiants = ctrltim_table_name('ctrltim_etudiants');
    $colonnes_etudiants = 'id, nom, image_etudiant, annee';

  return $ctrltim_db->get_results($ctrltim_db->prepare("SELECT {$colonnes_etudiants} FROM {$table_etudiants} WHERE id IN ({$placeholders_etudiants}) ORDER BY nom ASC", ...$ids_etudiants));
}


// ------------------------------------------------------------------
// 2) BASE DE DONNÉES ÉTUDIANTS (Au cas où, normalement pas utilisé pour l'instant!!)
// Colonnes : id, nom, image_etudiant, annee, date_creation
// ------------------------------------------------------------------

function ctrltim_get_all_etudiants() {
  global $ctrltim_db;
  $nom_table = ctrltim_table_name('ctrltim_etudiants');
  $colonnes = 'id, nom, image_etudiant, annee';
  return $ctrltim_db->get_results("SELECT {$colonnes} FROM {$nom_table} ORDER BY nom ASC");
}

function ctrltim_get_etudiant_by_id($id) {
  global $ctrltim_db;
  $nom_table = ctrltim_table_name('ctrltim_etudiants');
  // Exclure date_creation
  $colonnes = 'id, nom, image_etudiant, annee';
  return $ctrltim_db->get_row($ctrltim_db->prepare("SELECT {$colonnes} FROM {$nom_table} WHERE id = %d", $id));
}

// Exemple d'accès aux variables d'un étudiant (objet)
/*
$student = ctrltim_get_etudiant_by_id(5);
if ($student) {
    $id = $student->id;
    $nom = $student->nom;
    $image = $student->image_etudiant;
    $annee = $student->annee; // 'premiere', 'deuxieme', etc.
}
*/


// ------------------------------------------------------------------
// 3) BASE DE DONNÉES MÉDIAS SOCIAUX
// Colonnes : id, nom, image_media, lien, date_creation
// ------------------------------------------------------------------

function ctrltim_get_all_medias() {
  global $ctrltim_db;
  $nom_table = ctrltim_table_name('ctrltim_medias_sociaux');
  // Exclure date_creation
  $colonnes = 'id, nom, image_media, lien';
  return $ctrltim_db->get_results("SELECT {$colonnes} FROM {$nom_table} ORDER BY nom ASC");
}

function ctrltim_get_media_by_id($id) {
  global $ctrltim_db;
  $nom_table = ctrltim_table_name('ctrltim_medias_sociaux');
  // Exclure date_creation
  $colonnes = 'id, nom, image_media, lien';
  return $ctrltim_db->get_row($ctrltim_db->prepare("SELECT {$colonnes} FROM {$nom_table} WHERE id = %d", $id));
}

// Exemple d'accès à un média (objet)
/*
$media = ctrltim_get_media_by_id(3);
if ($media) {
    $id = $media->id;
    $nom = $media->nom;
    $image = $media->image_media;
    $lien = $media->lien;
}
*/

?>
<?php
/**
 * ////////////////////////BASE DE DONNÉES CATÉGORIES/////////////////////////
 *
 * @param int|string $cat_val id de la catégorie ou clé legacy
 * @return string nom lisible de la catégorie (échappé)
 */
function ctrltim_get_category_label($cat_val) {
    global $ctrltim_db, $wpdb;

    // Si vide
    if (empty($cat_val) && $cat_val !== '0') return '';

    // Si valeur numérique => chercher dans la table
    if (is_numeric($cat_val) && intval($cat_val) > 0) {
        $id = intval($cat_val);
        // Utiliser $ctrltim_db si disponible, sinon $wpdb
        $db = !empty($ctrltim_db) ? $ctrltim_db : $wpdb;
        $table = $wpdb->prefix . 'ctrltim_categories';
        $row = $db->get_row($db->prepare("SELECT nom FROM {$table} WHERE id = %d", $id));
        if ($row && !empty($row->nom)) {
            return esc_html($row->nom);
        }
        return '';
    }
}

/**
 * Exemple: Afficher la catégorie d'un projet
 */
/*
<?php
  $project = ctrltim_get_projet_by_id(12);
  if ($project) :
    $cat_val = $project->cat_exposition; // peut être id numérique ou clé text
    $cat_label = ctrltim_get_category_label($cat_val);
?>
  <?php if (!empty($cat_label)) : ?>
    <p class="project-category"><?php echo $cat_label; ?></p>
  <?php endif; ?>
<?php endif; ?>
*/

?>

