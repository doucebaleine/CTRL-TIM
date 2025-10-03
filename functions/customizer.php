<?php
function theme_ctrltim_customize_register($wp_customize) {
    
    // =====================
    // PROJETS
    // =====================
    
    $wp_customize->add_section('projets_section', array(
        'title' => __('Projets', 'theme_ctrltim'),
        'priority' => 30,
    ));

    // Ajouter
    $wp_customize->add_setting('titre_projet', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('titre_projet', array('label' => __('Titre', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'text'));

    $wp_customize->add_setting('description_projet', array('default' => '', 'sanitize_callback' => 'sanitize_textarea_field'));
    $wp_customize->add_control('description_projet', array('label' => __('Description', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'textarea'));

    $wp_customize->add_setting('video_projet', array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_control('video_projet', array('label' => __('Vidéo (URL)', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'url'));

    $wp_customize->add_setting('cat_exposition', array('default' => 'cat_arcade', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('cat_exposition', array('label' => __('Catégorie', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'select', 'choices' => array('cat_arcade' => 'Arcade', 'cat_finissants' => 'Finissants', 'cat_terre' => 'Terre')));

    // Filtres
    $wp_customize->add_setting('filtre_jeux', array('default' => false, 'sanitize_callback' => 'wp_validate_boolean'));
    $wp_customize->add_control('filtre_jeux', array('label' => __('Jeux vidéo', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'checkbox'));

    $wp_customize->add_setting('filtre_3d', array('default' => false, 'sanitize_callback' => 'wp_validate_boolean'));
    $wp_customize->add_control('filtre_3d', array('label' => __('3D', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'checkbox'));

    $wp_customize->add_setting('filtre_video', array('default' => false, 'sanitize_callback' => 'wp_validate_boolean'));
    $wp_customize->add_control('filtre_video', array('label' => __('Vidéo', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'checkbox'));

    $wp_customize->add_setting('filtre_web', array('default' => false, 'sanitize_callback' => 'wp_validate_boolean'));
    $wp_customize->add_control('filtre_web', array('label' => __('Web', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'checkbox'));

    // Modifier/Supprimer
    $projets = ctrltim_get_all_projects();
    $projets_choices = array('' => 'Nouveau projet');
    if (!empty($projets)) {
        foreach ($projets as $p) {
            $cat = str_replace('cat_', '', $p->cat_exposition);
            $projets_choices[$p->id] = $p->titre_projet . " (" . ucfirst($cat) . ")";
        }
    }

    $wp_customize->add_setting('projet_id', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('projet_id', array('label' => __('Sélectionner', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'select', 'choices' => $projets_choices));

    $wp_customize->add_setting('action_projet', array('default' => 'modifier', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('action_projet', array('label' => __('Action', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'select', 'choices' => array('modifier' => 'Modifier', 'supprimer' => 'Supprimer')));

    // =====================
    // ÉTUDIANTS
    // =====================
    
    $wp_customize->add_section('etudiants_section', array(
        'title' => __('Étudiants', 'theme_ctrltim'),
        'priority' => 40,
    ));

    // Ajouter
    $wp_customize->add_setting('nom_etudiant', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('nom_etudiant', array('label' => __('Nom', 'theme_ctrltim'), 'section' => 'etudiants_section', 'type' => 'text'));

    $wp_customize->add_setting('image_etudiant', array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'image_etudiant', array('label' => __('Photo de l\'étudiant', 'theme_ctrltim'), 'section' => 'etudiants_section')));

    $wp_customize->add_setting('annee_etudiant', array('default' => 'premiere', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('annee_etudiant', array('label' => __('Année', 'theme_ctrltim'), 'section' => 'etudiants_section', 'type' => 'select', 'choices' => array('premiere' => '1ère', 'deuxieme' => '2ème', 'troisieme' => '3ème')));

    // Modifier/Supprimer
    $etudiants = ctrltim_get_all_students();
    $etudiants_choices = array('' => 'Nouvel étudiant');
    if (!empty($etudiants)) {
        foreach ($etudiants as $e) {
            $annee = str_replace(array('premiere', 'deuxieme', 'troisieme'), array('1ère', '2ème', '3ème'), $e->annee);
            $etudiants_choices[$e->id] = $e->nom . " (" . $annee . ")";
        }
    }

    $wp_customize->add_setting('etudiant_id', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('etudiant_id', array('label' => __('Sélectionner', 'theme_ctrltim'), 'section' => 'etudiants_section', 'type' => 'select', 'choices' => $etudiants_choices));

    $wp_customize->add_setting('action_etudiant', array('default' => 'modifier', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('action_etudiant', array('label' => __('Action', 'theme_ctrltim'), 'section' => 'etudiants_section', 'type' => 'select', 'choices' => array('modifier' => 'Modifier', 'supprimer' => 'Supprimer')));
}

add_action('customize_register', 'theme_ctrltim_customize_register');

// =====================
// TABLES
// =====================

function ctrltim_create_tables() {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();
    
    // Projets
    $sql1 = "CREATE TABLE {$wpdb->prefix}ctrltim_projets (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        titre_projet varchar(255) NOT NULL,
        description_projet text,
        video_projet varchar(500),
        cat_exposition varchar(50) DEFAULT 'cat_arcade',
        filtre_projet text DEFAULT NULL,
        date_creation datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;";
    
    // Étudiants
    $sql2 = "CREATE TABLE {$wpdb->prefix}ctrltim_etudiants (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nom varchar(255) NOT NULL,
        image_etudiant varchar(500),
        annee varchar(50) NOT NULL,
        date_creation datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql1);
    dbDelta($sql2);
}

add_action('after_switch_theme', 'ctrltim_create_tables');

// =====================
// FONCTIONS
// =====================

function ctrltim_get_all_projects() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_projets ORDER BY id DESC");
}

function ctrltim_get_all_students() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_etudiants ORDER BY id DESC");
}

// =====================
// SAUVEGARDE
// =====================

function ctrltim_save_data() {
    global $wpdb;
    
    // PROJETS
    $titre = get_theme_mod('titre_projet');
    $description = get_theme_mod('description_projet');
    $video = get_theme_mod('video_projet');
    $cat = get_theme_mod('cat_exposition');
    $projet_id = get_theme_mod('projet_id');
    $action_p = get_theme_mod('action_projet');
    
    // Collecter les filtres
    $filtres = array();
    if (get_theme_mod('filtre_jeux')) $filtres[] = 'filtre_jeux';
    if (get_theme_mod('filtre_3d')) $filtres[] = 'filtre_3d';
    if (get_theme_mod('filtre_video')) $filtres[] = 'filtre_video';
    if (get_theme_mod('filtre_web')) $filtres[] = 'filtre_web';
    
    if (!empty($titre) && empty($projet_id)) {
        // Ajouter projet
        $wpdb->insert("{$wpdb->prefix}ctrltim_projets", array(
            'titre_projet' => $titre, 
            'description_projet' => $description,
            'video_projet' => $video,
            'cat_exposition' => $cat,
            'filtre_projet' => json_encode($filtres)
        ));
        remove_theme_mod('titre_projet');
        remove_theme_mod('description_projet');
        remove_theme_mod('video_projet');
        remove_theme_mod('filtre_jeux');
        remove_theme_mod('filtre_3d');
        remove_theme_mod('filtre_video');
        remove_theme_mod('filtre_web');
    } elseif (!empty($projet_id)) {
        if ($action_p === 'supprimer') {
            // Supprimer projet
            $wpdb->delete("{$wpdb->prefix}ctrltim_projets", array('id' => $projet_id), array('%d'));
        } elseif ($action_p === 'modifier') {
            // Modifier projet - récupérer les données existantes si certains champs sont vides
            $existing = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ctrltim_projets WHERE id = %d", $projet_id));
            if ($existing) {
                $wpdb->update("{$wpdb->prefix}ctrltim_projets", array(
                    'titre_projet' => !empty($titre) ? $titre : $existing->titre_projet, 
                    'description_projet' => $description, // Peut être vide
                    'video_projet' => $video, // Peut être vide
                    'cat_exposition' => $cat,
                    'filtre_projet' => json_encode($filtres)
                ), array('id' => $projet_id), array('%s', '%s', '%s', '%s', '%s'), array('%d'));
            }
        }
        remove_theme_mod('projet_id');
        remove_theme_mod('titre_projet');
        remove_theme_mod('description_projet');
        remove_theme_mod('video_projet');
        remove_theme_mod('filtre_jeux');
        remove_theme_mod('filtre_3d');
        remove_theme_mod('filtre_video');
        remove_theme_mod('filtre_web');
    }
    
    // ÉTUDIANTS
    $nom = get_theme_mod('nom_etudiant');
    $image = get_theme_mod('image_etudiant');
    $annee = get_theme_mod('annee_etudiant');
    $etudiant_id = get_theme_mod('etudiant_id');
    $action_e = get_theme_mod('action_etudiant');
    
    if (!empty($nom) && empty($etudiant_id)) {
        // Ajouter étudiant
        $wpdb->insert("{$wpdb->prefix}ctrltim_etudiants", array(
            'nom' => $nom, 
            'image_etudiant' => $image,
            'annee' => $annee
        ));
        remove_theme_mod('nom_etudiant');
        remove_theme_mod('image_etudiant');
    } elseif (!empty($etudiant_id)) {
        if ($action_e === 'supprimer') {
            // Supprimer étudiant
            $wpdb->delete("{$wpdb->prefix}ctrltim_etudiants", array('id' => $etudiant_id), array('%d'));
        } elseif ($action_e === 'modifier') {
            // Modifier étudiant - récupérer les données existantes si certains champs sont vides
            $existing = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ctrltim_etudiants WHERE id = %d", $etudiant_id));
            if ($existing) {
                $wpdb->update("{$wpdb->prefix}ctrltim_etudiants", array(
                    'nom' => !empty($nom) ? $nom : $existing->nom, 
                    'image_etudiant' => $image, // Peut être vide
                    'annee' => $annee
                ), array('id' => $etudiant_id), array('%s', '%s', '%s'), array('%d'));
            }
        }
        remove_theme_mod('etudiant_id');
        remove_theme_mod('nom_etudiant');
        remove_theme_mod('image_etudiant');
    }
}

add_action('customize_save_after', 'ctrltim_save_data');

?>