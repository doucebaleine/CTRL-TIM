<?php

// Définir le chemin vers le dossier "functions"
// Les helpers de base de données sont consolidés dans
// `functions/base-de-donnees.php`. Les fonctions publiques (fr) comme
// `ctrltim_get_all_projets`, `ctrltim_get_projet_by_id`, etc. y sont
// exposées. Cette section a été nettoyée pour éviter la duplication.
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
    wp_enqueue_script('ctrltim-arriere-plan', get_template_directory_uri() . '/js/arriere-plan.js', array(), filemtime(get_template_directory() . '/js/arriere-plan.js'), true);
    wp_enqueue_script('ctrltim-menu', get_template_directory_uri() . '/js/menu.js', array(), filemtime(get_template_directory() . '/js/menu.js'), true);
    wp_enqueue_script('ctrltim-cards', get_template_directory_uri() . '/js/cards.js', array(), filemtime(get_template_directory() . '/js/cards.js'), true);
    wp_enqueue_script('ctrltim-filter', get_template_directory_uri() . '/js/filter.js', array(), filemtime(get_template_directory() . '/js/filter.js'), true);
    wp_enqueue_script('ctrltim-description', get_template_directory_uri() . '/js/description.js', array(), filemtime(get_template_directory() . '/js/description.js'), true);
    wp_enqueue_script('ctrltim-galerie', get_template_directory_uri() . '/js/galerie.js', array(), filemtime(get_template_directory() . '/js/galerie.js'), true);
    wp_enqueue_script('ctrltim-carrousel', get_template_directory_uri() . '/js/carrousel.js', array(), filemtime(get_template_directory() . '/js/carrousel.js'), true);
    wp_enqueue_script('ctrltim-search-history', get_template_directory_uri() . '/js/search-history.js', array(), filemtime(get_template_directory() . '/js/search-history.js'), true);
    
    // Exposer l'URL du répertoire du thème au JS pour que les scripts puissent référencer les images de manière fiable
    wp_localize_script('ctrltim-cards', 'CTRL_TIM', array(
        'themeUrl' => get_template_directory_uri(),
    ));
}
add_action('wp_enqueue_scripts','ctrltim_enqueue_scripts');

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

// Enregistrez les emplacements de menus dans votre thème
function ctrltim_register_menus() {
    register_nav_menus(
        array(
            'principal' => __('Menu Principal', 'ctrl-tim')
        )
    );
}
add_action('init', 'ctrltim_register_menus');

// Définissez un menu de secours (fallback)
function ctrltim_fallback_menu() {
    echo '<ul class="menu-principal">';
    echo '<li><a href="' . home_url('/') . '">Accueil</a></li>';
    echo '<li><a href="#">Galerie</a></li>';
    echo '<li><a href="#">À propos</a></li>';
    echo '<li><a href="#">Contact</a></li>';
    echo '</ul>';
}

// Ajout d'un filtre pour ajouter des classes personnalisées aux éléments du menu
function ctrltim_add_menu_classes($classes, $item, $args, $depth) {
    if (isset($args->theme_location) && $args->theme_location === 'principal') {
        $classes[] = 'bouton-menu'; // Ajoute la classe personnalisée aux <li>
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'ctrltim_add_menu_classes', 10, 4);

function ctrltim_add_link_atts($atts, $item, $args, $depth) {
    if (isset($args->theme_location) && $args->theme_location === 'principal') {
        $atts['class'] = 'bouton-menu'; // Ajoute la classe personnalisée aux <a>
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'ctrltim_add_link_atts', 10, 4);

// Ajout de la classe "primaire" au premier élément du menu
function ctrltim_add_primary_class($items, $args) {
    if (isset($args->theme_location) && $args->theme_location === 'principal') {
        foreach ($items as $index => $item) {
            if ($index === 0) {
                $item->classes[] = 'primaire';
                break;
            }
        }
    }
    return $items;
}
add_filter('wp_nav_menu_objects', 'ctrltim_add_primary_class', 10, 2);

// Les helpers de base de données (fonctions CRUD) sont fournis par
// `functions/base-de-donnees.php` — ils exposent les API publiques en français
// (ex: `ctrltim_get_all_projets`, `ctrltim_get_projet_by_id`, ...).

// EOF - le reste des helpers est dans functions/base-de-donnees.php
// Étendre la recherche WordPress pour inclure projets et étudiants personnalisés
add_action('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        // On ne modifie pas la requête WP, mais on va injecter nos résultats personnalisés dans le template
        add_filter('the_posts', function($posts, $query) {
            $search = $query->get('s');
            global $wpdb;

            $custom_results = [];

            // Recherche dans les projets
                $projets = $wpdb->get_results($wpdb->prepare(
                    'SELECT * FROM ' . $wpdb->prefix . 'ctrltim_projets WHERE titre_projet LIKE %s',
                    '%' . $wpdb->esc_like($search) . '%'
                ));
            foreach ($projets as $projet) {
                $post = (object)[
                    'ID' => 900000 + intval($projet->id), // ID fictif pour éviter conflit
                    'post_title' => $projet->titre_projet,
                    'post_content' => $projet->description_projet ?? '',
                    'post_type' => 'ctrltim_projet',
                    'guid' => home_url('/?projet_id=' . intval($projet->id)),
                ];
                $custom_results[] = $post;
            }

            // Recherche dans les étudiants
                $etudiants = $wpdb->get_results($wpdb->prepare(
                    'SELECT * FROM ' . $wpdb->prefix . 'ctrltim_etudiants WHERE nom LIKE %s',
                    '%' . $wpdb->esc_like($search) . '%'
                ));
            foreach ($etudiants as $etudiant) {
                $post = (object)[
                    'ID' => 800000 + intval($etudiant->id),
                    'post_title' => $etudiant->nom,
                    'post_content' => $etudiant->description ?? '',
                    'post_type' => 'ctrltim_etudiant',
                    'guid' => home_url('/?etudiant_id=' . intval($etudiant->id)),
                ];
                $custom_results[] = $post;
            }

            // Fusionne les résultats WP natifs et personnalisés
            return array_merge($posts, $custom_results);
        }, 10, 2);
    }
    return $query;
});

// Affichage fiche projet ou étudiant si paramètre dans l'URL
add_action('template_redirect', function() {
    if (isset($_GET['projet_id'])) {
        $projet_id = intval($_GET['projet_id']);
        if (function_exists('ctrltim_get_projet_by_id')) {
            $projet = ctrltim_get_projet_by_id($projet_id);
            if ($projet) {
                // Affichage simple, à personnaliser selon vos besoins
                get_header();
                echo '<main class="fiche-projet"><h1>' . esc_html($projet->titre_projet) . '</h1>';
                if (!empty($projet->image_projet)) {
                    echo '<img src="' . esc_url($projet->image_projet) . '" alt="' . esc_attr($projet->titre_projet) . '" />';
                }
                echo '<div class="description-projet">' . esc_html($projet->description_projet ?? '') . '</div>';
                echo '<a href="' . esc_url(home_url('/')) . '">Retour à l\'accueil</a>';
                echo '</main>';
                get_footer();
                exit;
            }
        }
    }
    if (isset($_GET['etudiant_id'])) {
        $etudiant_id = intval($_GET['etudiant_id']);
        if (function_exists('ctrltim_get_all_etudiants')) {
            $etudiants = ctrltim_get_all_etudiants();
            foreach ($etudiants as $etudiant) {
                if (intval($etudiant->id) === $etudiant_id) {
                    get_header();
                    echo '<main class="fiche-etudiant"><h1>' . esc_html($etudiant->nom) . '</h1>';
                    if (!empty($etudiant->image_etudiant)) {
                        echo '<img src="' . esc_url($etudiant->image_etudiant) . '" alt="' . esc_attr($etudiant->nom) . '" />';
                    }
                    echo '<div class="description-etudiant">' . esc_html($etudiant->description ?? '') . '</div>';
                    echo '<a href="' . esc_url(home_url('/')) . '">Retour à l\'accueil</a>';
                    echo '</main>';
                    get_footer();
                    exit;
                }
            }
        }
    }
});

function get_arrierePlan() {
    include(get_template_directory() . '/arriere-plan.php');
}
