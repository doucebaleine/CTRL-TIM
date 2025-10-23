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
    wp_enqueue_style('ctrltim-normalize', get_template_directory_uri() . '/sass/normalize.css');
    wp_enqueue_style('ctrltim-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'ctrltim_enqueue_styles');

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
// ?>

