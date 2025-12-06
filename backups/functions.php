
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

// Les helpers de base de données (fonctions CRUD) sont fournis par
// `functions/base-de-donnees.php` — ils exposent les API publiques en français
// (ex: `ctrltim_get_all_projets`, `ctrltim_get_projet_by_id`, ...).

// EOF - le reste des helpers est dans functions/base-de-donnees.php

/**
 * Charge l'arrière-plan avec effet spotlight
 */


