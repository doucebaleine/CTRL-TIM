<?php
// Ajouter les sections et contrôles dans le Customizer WordPress
function ctrltim_enregistrer_customizer($wp_customize) {
    global $wpdb;
    
    // SECTION PROJETS
    $wp_customize->add_section('ctrltim_projets', array(
        'title' => __('Gestion des Projets', 'ctrltim'),
        'priority' => 30,
    ));

    // Récupérer les projets existants et créer les choix
    $projets_existing = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_projets ORDER BY id DESC");
    
    $projets_choices = array('' => '-- Nouveau projet --');
    $projets_list = "<h4>Projets existants :</h4>";
    
    if ($projets_existing) {
        $projets_list .= "<ul style='margin-left: 20px;'>";
        foreach ($projets_existing as $p) {
            $cat = '';
            switch ($p->cat_exposition) {
                case 'cat_premiere_annee': $cat = '1ère année'; break;
                case 'cat_arcade': $cat = 'Arcade'; break;
                case 'cat_finissants': $cat = 'Finissants'; break;
                // Compatibilité avec les anciennes catégories
                case 'cat_bibliotheque': $cat = 'Bibliothèque (ancien)'; break;
                case 'cat_evenement': $cat = 'Événement (ancien)'; break;
                default: $cat = 'Non définie';
            }
            $projets_list .= "<li style='margin-bottom: 5px;'>" . esc_html($p->titre_projet) . " <span style='color: #666;'>(" . $cat . ")</span></li>";
            $projets_choices[$p->id] = $p->titre_projet . " (" . $cat . ")";
        }
        $projets_list .= "</ul>";
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

    // Champs du projet
    $project_fields = array(
        'titre_projet' => array('label' => 'Titre du projet', 'type' => 'text', 'sanitize' => 'sanitize_text_field'),
        'description_projet' => array('label' => 'Description du projet', 'type' => 'textarea', 'sanitize' => 'sanitize_textarea_field'),
        'video_projet' => array('label' => 'URL de la vidéo', 'type' => 'url', 'sanitize' => 'esc_url_raw'),
        'lien_projet' => array('label' => 'Lien du projet', 'type' => 'url', 'sanitize' => 'esc_url_raw'),
        'cours_projet' => array('label' => 'Cours', 'type' => 'text', 'sanitize' => 'sanitize_text_field'),
    );

    foreach ($project_fields as $field => $config) {
        $wp_customize->add_setting($field, array(
            'default' => '',
            'sanitize_callback' => $config['sanitize'],
        ));
        $wp_customize->add_control($field, array(
            'label' => __($config['label'], 'ctrltim'),
            'section' => 'ctrltim_projets',
            'type' => $config['type'],
        ));
    }

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
        'default' => 'cat_premiere_annee',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('cat_exposition', array(
        'label' => __('Catégorie d\'exposition', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'select',
        'choices' => array(
            'cat_premiere_annee' => '1ère année',
            'cat_arcade' => 'Arcade',
            'cat_finissants' => 'Finissants',
        ),
    ));

    // Filtres
    $filtres = array('filtre_jeux' => 'Jeux', 'filtre_3d' => '3D', 'filtre_video' => 'Vidéo', 'filtre_web' => 'Web');
    
    foreach ($filtres as $filtre => $label) {
        $wp_customize->add_setting($filtre, array(
            'default' => false,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        $wp_customize->add_control($filtre, array(
            'label' => __($label, 'ctrltim'),
            'section' => 'ctrltim_projets',
            'type' => 'checkbox',
        ));
    }

    // Action projet
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

    $association_fields = array(
        'etudiants_selectionnes' => array('label' => 'Associer/Retirer un étudiant', 'type' => 'select', 'choices' => $students_choices),
        'action_etudiant_projet' => array('label' => 'Action sur l\'étudiant', 'type' => 'select', 'choices' => array('ajouter' => 'Associer au projet', 'retirer' => 'Retirer du projet')),
    );

    foreach ($association_fields as $field => $config) {
        $wp_customize->add_setting($field, array(
            'default' => $field == 'action_etudiant_projet' ? 'ajouter' : '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control($field, array(
            'label' => __($config['label'], 'ctrltim'),
            'section' => 'ctrltim_projets',
            'type' => $config['type'],
            'choices' => $config['choices'],
        ));
    }

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

    // Récupérer les étudiants existants et créer les choix
    $etudiants_existing = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_etudiants ORDER BY nom");
    
    $etudiants_choices = array('' => '-- Nouvel étudiant --');
    $etudiants_list = "<h4>Étudiants existants :</h4>";
    
    if ($etudiants_existing) {
        $etudiants_list .= "<ul style='margin-left: 20px;'>";
        foreach ($etudiants_existing as $e) {
            $annee = ($e->annee == 'premiere') ? '1ère année' : (($e->annee == 'deuxieme') ? '2ème année' : '3ème année');
            $etudiants_list .= "<li style='margin-bottom: 5px;'>" . esc_html($e->nom) . " <span style='color: #666;'>(" . $annee . ")</span></li>";
            $etudiants_choices[$e->id] = $e->nom . " (" . $annee . ")";
        }
        $etudiants_list .= "</ul>";
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
add_action('customize_register', 'ctrltim_enregistrer_customizer');

// JavaScript pour améliorer l'expérience utilisateur
function ctrltim_script_customizer() {
    wp_enqueue_script('ctrltim-customizer', get_template_directory_uri() . '/js/customizer.js', array('jquery', 'customize-controls'), '1.0.0', true);
    
    // Passer les données nécessaires au JavaScript
    wp_localize_script('ctrltim-customizer', 'ctrlTimData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ctrltim_nonce')
    ));
}
add_action('customize_controls_enqueue_scripts', 'ctrltim_script_customizer');

?>