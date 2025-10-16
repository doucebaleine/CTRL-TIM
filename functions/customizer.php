<?php
// Ajouter les sections et contrôles dans le Customizer WordPress
function ctrltim_customize_register($wp_customize) {
    global $wpdb;
    
    // SECTION PROJETS
    $wp_customize->add_section('ctrltim_projets', array(
        'title' => __('Gestion des Projets', 'ctrltim'),
        'priority' => 30,
    ));

    // Récupérer les projets existants
    $projets_existing = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_projets ORDER BY id DESC");
    
    $projets_list = "<h4>Projets existants :</h4>";
    if ($projets_existing) {
        $projets_list .= "<ul style='margin-left: 20px;'>";
        foreach ($projets_existing as $p) {
            $cat = ($p->cat_exposition == 'cat_arcade') ? 'arcade' : (($p->cat_exposition == 'cat_bibliotheque') ? 'bibliothèque' : 'événement');
            $projets_list .= "<li style='margin-bottom: 5px;'>" . esc_html($p->titre_projet) . " <span style='color: #666;'>(" . ucfirst($cat) . ")</span></li>";
        }
        $projets_list .= "</ul>";
    }

    // Créer les choix pour le select de modification
    $projets_choices = array('' => '-- Nouveau projet --');
    if ($projets_existing) {
        foreach ($projets_existing as $p) {
            $cat = ($p->cat_exposition == 'cat_arcade') ? 'arcade' : (($p->cat_exposition == 'cat_bibliotheque') ? 'bibliothèque' : 'événement');
            $projets_choices[$p->id] = $p->titre_projet . " (" . ucfirst($cat) . ")";
        }
    }

    // Contrôle pour sélectionner le projet à modifier
    $wp_customize->add_setting('projet_a_modifier', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('projet_a_modifier', array(
        'label' => __('Sélectionner le projet à modifier', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'select',
        'choices' => $projets_choices,
        'description' => $projets_list,
    ));

    // Titre du projet
    $wp_customize->add_setting('titre_projet', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('titre_projet', array(
        'label' => __('Titre du projet', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'text',
    ));

    // Description du projet
    $wp_customize->add_setting('description_projet', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('description_projet', array(
        'label' => __('Description du projet', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'textarea',
    ));

    // Vidéo du projet
    $wp_customize->add_setting('video_projet', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('video_projet', array(
        'label' => __('URL de la vidéo', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'url',
    ));

    // Image du projet
    $wp_customize->add_setting('image_projet', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'image_projet', array(
        'label' => __('Image du projet', 'ctrltim'),
        'section' => 'ctrltim_projets',
    )));

    // Catégorie d'exposition
    $wp_customize->add_setting('cat_exposition', array(
        'default' => 'cat_arcade',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('cat_exposition', array(
        'label' => __('Catégorie d\'exposition', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'select',
        'choices' => array(
            'cat_arcade' => 'Arcade',
            'cat_bibliotheque' => 'Bibliothèque',
            'cat_evenement' => 'Événement',
        ),
    ));

    // Filtres
    $filtres = array('filtre_jeux', 'filtre_3d', 'filtre_video', 'filtre_web');
    $filtres_labels = array('Jeux', '3D', 'Vidéo', 'Web');
    
    for ($i = 0; $i < count($filtres); $i++) {
        $wp_customize->add_setting($filtres[$i], array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        $wp_customize->add_control($filtres[$i], array(
            'label' => __($filtres_labels[$i], 'ctrltim'),
            'section' => 'ctrltim_projets',
            'type' => 'checkbox',
        ));
    }

    // Bouton pour sauvegarder/supprimer le projet
    $wp_customize->add_setting('action_projet', array(
        'default' => 'sauvegarder',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('action_projet', array(
        'label' => __('Action', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'select',
        'choices' => array(
            'sauvegarder' => 'Sauvegarder',
            'supprimer' => 'Supprimer',
        ),
    ));

    // ASSOCIATION ÉTUDIANTS-PROJETS
    $students = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_etudiants ORDER BY nom");
    $students_choices = array('' => '-- Sélectionner un étudiant --');
    if ($students) {
        foreach ($students as $student) {
            $annee = ($student->annee == 'premiere') ? '1ère année' : (($student->annee == 'deuxieme') ? '2ème année' : '3ème année');
            $students_choices[$student->id] = $student->nom . " (" . $annee . ")";
        }
    }

    $wp_customize->add_setting('etudiants_selectionnes', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('etudiants_selectionnes', array(
        'label' => __('Associer/Retirer un étudiant', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'select',
        'choices' => $students_choices,
    ));

    $wp_customize->add_setting('action_etudiant_projet', array(
        'default' => 'ajouter',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('action_etudiant_projet', array(
        'label' => __('Action sur l\'étudiant', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'select',
        'choices' => array(
            'ajouter' => 'Associer au projet',
            'retirer' => 'Retirer du projet',
        ),
    ));

    $wp_customize->add_setting('trigger_student_action', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('trigger_student_action', array(
        'label' => __('Exécuter l\'action', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'checkbox',
    ));

    // Affichage des étudiants associés
    $wp_customize->add_setting('etudiants_associes_info', array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('etudiants_associes_info', array(
        'label' => __('Étudiants associés à ce projet', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'hidden',
        'description' => '<p><em>Sélectionnez un projet pour voir ses étudiants associés</em></p>',
    ));

    // SECTION ÉTUDIANTS
    $wp_customize->add_section('ctrltim_etudiants', array(
        'title' => __('Gestion des Étudiants', 'ctrltim'),
        'priority' => 35,
    ));

    // Récupérer les étudiants existants
    $etudiants_existing = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_etudiants ORDER BY nom");
    
    $etudiants_list = "<h4>Étudiants existants :</h4>";
    if ($etudiants_existing) {
        $etudiants_list .= "<ul style='margin-left: 20px;'>";
        foreach ($etudiants_existing as $e) {
            $annee = ($e->annee == 'premiere') ? '1ère année' : (($e->annee == 'deuxieme') ? '2ème année' : '3ème année');
            $etudiants_list .= "<li style='margin-bottom: 5px;'>" . esc_html($e->nom) . " <span style='color: #666;'>(" . $annee . ")</span></li>";
        }
        $etudiants_list .= "</ul>";
    }

    // Créer les choix pour le select de modification
    $etudiants_choices = array('' => '-- Nouvel étudiant --');
    if ($etudiants_existing) {
        foreach ($etudiants_existing as $e) {
            $annee = ($e->annee == 'premiere') ? '1ère année' : (($e->annee == 'deuxieme') ? '2ème année' : '3ème année');
            $etudiants_choices[$e->id] = $e->nom . " (" . $annee . ")";
        }
    }

    // Contrôle pour sélectionner l'étudiant à modifier
    $wp_customize->add_setting('etudiant_a_modifier', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('etudiant_a_modifier', array(
        'label' => __('Sélectionner l\'étudiant à modifier', 'ctrltim'),
        'section' => 'ctrltim_etudiants',
        'type' => 'select',
        'choices' => $etudiants_choices,
        'description' => $etudiants_list,
    ));

    // Nom de l'étudiant
    $wp_customize->add_setting('nom_etudiant', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('nom_etudiant', array(
        'label' => __('Nom de l\'étudiant', 'ctrltim'),
        'section' => 'ctrltim_etudiants',
        'type' => 'text',
    ));

    // Image de l'étudiant
    $wp_customize->add_setting('image_etudiant', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'image_etudiant', array(
        'label' => __('Photo de l\'étudiant', 'ctrltim'),
        'section' => 'ctrltim_etudiants',
    )));

    // Année de l'étudiant
    $wp_customize->add_setting('annee_etudiant', array(
        'default' => 'premiere',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('annee_etudiant', array(
        'label' => __('Année d\'étude', 'ctrltim'),
        'section' => 'ctrltim_etudiants',
        'type' => 'select',
        'choices' => array(
            'premiere' => '1ère année',
            'deuxieme' => '2ème année',
            'troisieme' => '3ème année',
        ),
    ));

    // Action pour l'étudiant
    $wp_customize->add_setting('action_etudiant', array(
        'default' => 'sauvegarder',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('action_etudiant', array(
        'label' => __('Action', 'ctrltim'),
        'section' => 'ctrltim_etudiants',
        'type' => 'select',
        'choices' => array(
            'sauvegarder' => 'Sauvegarder',
            'supprimer' => 'Supprimer',
        ),
    ));
}
add_action('customize_register', 'ctrltim_customize_register');

// JavaScript pour améliorer l'expérience utilisateur
function ctrltim_customizer_script() {
    wp_enqueue_script('ctrltim-customizer', get_template_directory_uri() . '/js/customizer.js', array('jquery', 'customize-controls'), '1.0.0', true);
    
    // Passer les données nécessaires au JavaScript
    wp_localize_script('ctrltim-customizer', 'ctrlTimData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ctrltim_nonce')
    ));
}
add_action('customize_controls_enqueue_scripts', 'ctrltim_customizer_script');

?>