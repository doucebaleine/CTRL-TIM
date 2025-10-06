<?php
/**
 * Configuration du WordPress Customizer pour CTRL-TIM
 * Gestion des projets et étudiants via l'interface d'administration
 */

function theme_ctrltim_customize_register($wp_customize) {
    
    // =====================
    // PROJETS
    // =====================
    
    $wp_customize->add_section('projets_section', array(
        'title' => __('Projets', 'theme_ctrltim'),
        'priority' => 30,
    ));

    // Champs pour ajouter/modifier un projet
    $wp_customize->add_setting('titre_projet', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('titre_projet', array('label' => __('Titre', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'text'));

    $wp_customize->add_setting('description_projet', array('default' => '', 'sanitize_callback' => 'sanitize_textarea_field'));
    $wp_customize->add_control('description_projet', array('label' => __('Description', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'textarea'));

    $wp_customize->add_setting('video_projet', array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_control('video_projet', array('label' => __('Vidéo (URL)', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'url'));

    $wp_customize->add_setting('image_projet', array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'image_projet', array('label' => __('Image principale', 'theme_ctrltim'), 'section' => 'projets_section')));

    $wp_customize->add_setting('cat_exposition', array('default' => 'cat_arcade', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('cat_exposition', array('label' => __('Catégorie', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'select', 'choices' => array('cat_arcade' => 'Arcade', 'cat_finissants' => 'Finissants', 'cat_terre' => 'Terre')));

    // Filtres (checkboxes multiples)
    $wp_customize->add_setting('filtre_jeux', array('default' => false, 'sanitize_callback' => 'wp_validate_boolean'));
    $wp_customize->add_control('filtre_jeux', array('label' => __('Jeux vidéo', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'checkbox'));

    $wp_customize->add_setting('filtre_3d', array('default' => false, 'sanitize_callback' => 'wp_validate_boolean'));
    $wp_customize->add_control('filtre_3d', array('label' => __('3D', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'checkbox'));

    $wp_customize->add_setting('filtre_video', array('default' => false, 'sanitize_callback' => 'wp_validate_boolean'));
    $wp_customize->add_control('filtre_video', array('label' => __('Vidéo', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'checkbox'));

    $wp_customize->add_setting('filtre_web', array('default' => false, 'sanitize_callback' => 'wp_validate_boolean'));
    $wp_customize->add_control('filtre_web', array('label' => __('Web', 'theme_ctrltim'), 'section' => 'projets_section', 'type' => 'checkbox'));

    // Sélection et actions pour modifier/supprimer
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

    // Champs pour ajouter/modifier un étudiant
    $wp_customize->add_setting('nom_etudiant', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('nom_etudiant', array('label' => __('Nom', 'theme_ctrltim'), 'section' => 'etudiants_section', 'type' => 'text'));

    $wp_customize->add_setting('image_etudiant', array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'image_etudiant', array('label' => __('Photo de l\'étudiant', 'theme_ctrltim'), 'section' => 'etudiants_section')));

    $wp_customize->add_setting('annee_etudiant', array('default' => 'premiere', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('annee_etudiant', array('label' => __('Année', 'theme_ctrltim'), 'section' => 'etudiants_section', 'type' => 'select', 'choices' => array('premiere' => '1ère', 'deuxieme' => '2ème', 'troisieme' => '3ème')));

    // Sélection et actions pour modifier/supprimer
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

// Enregistrer la fonction de customizer
add_action('customize_register', 'theme_ctrltim_customize_register');

?>