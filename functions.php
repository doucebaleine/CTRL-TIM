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

// ?>
