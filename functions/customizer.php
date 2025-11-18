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

    // Préparer les groupes et l'ordre souhaité
    $ordered_keys = array('finissant', 'arcade', 'graphisme');
    $groups = array();

    if ($projets_existing) {
        foreach ($projets_existing as $p) {
            $cat_label = '';
            // Si la valeur est numérique, on cherche dans la table categories
            if (is_numeric($p->cat_exposition) && intval($p->cat_exposition) > 0 && function_exists('ctrltim_get_nom_categorie')) {
                $cat_label = ctrltim_get_nom_categorie(intval($p->cat_exposition));
            } else {
                // Valeur non numérique (ancienne clé) — afficher telle quelle pour l'instant.
                $cat_label = is_string($p->cat_exposition) && !empty($p->cat_exposition) ? $p->cat_exposition : 'Non définie';
            }

            // Normaliser le libellé pour déterminer le groupe
            $normalized = mb_strtolower(trim($cat_label), 'UTF-8');
            $trans = @iconv('UTF-8', 'ASCII//TRANSLIT', $normalized);
            if ($trans !== false) $normalized = $trans;

            $matched = false;
            foreach ($ordered_keys as $key) {
                if (strpos($normalized, $key) !== false) {
                    $groups[$key][] = array('proj' => $p, 'label' => $cat_label);
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                // groupe par nom de catégorie non-ordonné
                $other_key = 'other_' . sanitize_title($cat_label);
                $groups[$other_key][] = array('proj' => $p, 'label' => $cat_label);
            }
        }
    }

    // Construire le liste de choix avec séparateurs
    $projets_choices = array('' => '-- Nouveau projet --');

    // Ajouter les groupes ordonnés d'abord
    foreach ($ordered_keys as $key) {
        if (!empty($groups[$key])) {
            foreach ($groups[$key] as $item) {
                $p = $item['proj'];
                $cat_label = $item['label'];
                $cat_label_lower = $cat_label ? mb_strtolower($cat_label, 'UTF-8') : '';
                $projets_choices[$p->id] = $p->titre_projet . ($cat_label_lower ? " (" . $cat_label_lower . ")" : '');
            }
        }
    }

    // Ajouter les autres groupes (ordre stable)
    foreach ($groups as $gkey => $items) {
        if (in_array($gkey, $ordered_keys) || strpos($gkey, 'other_') !== 0) continue;
        // extraire le nom lisible
        $label_sample = isset($items[0]['label']) ? $items[0]['label'] : $gkey;
        foreach ($items as $item) {
            $p = $item['proj'];
            $cat_label = $item['label'];
            $cat_label_lower = $cat_label ? mb_strtolower($cat_label, 'UTF-8') : '';
            $projets_choices[$p->id] = $p->titre_projet . ($cat_label_lower ? " (" . $cat_label_lower . ")" : '');
        }
    }

    // Contrôle pour sélectionner le projet à modifier
    $wp_customize->add_setting('projet_a_modifier', array(
        'default' => '',
        'sanitize_callback' => 'ctrltim_sanitize_projet_selector',
    ));
    $wp_customize->add_control('projet_a_modifier', array(
        'label' => __('Sélectionner le projet à modifier', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'select',
        'choices' => $projets_choices,
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

    // Images supplémentaires pour carrousel (jusqu'à 5)
    for ($i = 1; $i <= 5; $i++) {
        $setting = 'image_projet_' . $i;
        $wp_customize->add_setting($setting, array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $setting, array(
            'label' => sprintf(__('Image carrousel #%d', 'ctrltim'), $i),
            'section' => 'ctrltim_projets',
        )));
    }

    // Filtre d'année pour le projet — retiré

    // Catégorie d'exposition
    // Construire la liste des catégories depuis la table
    $categories_for_select = array('' => 'Non définie');
    if (function_exists('ctrltim_get_all_categories')) {
        $cats = ctrltim_get_all_categories();
        if ($cats) {
            foreach ($cats as $c) {
                $categories_for_select[$c->id] = $c->nom;
            }
        }
    }

    $wp_customize->add_setting('cat_exposition', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('cat_exposition', array(
        'label' => __('Catégorie d\'exposition', 'ctrltim'),
        'section' => 'ctrltim_projets',
        'type' => 'select',
        'choices' => $categories_for_select,
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

    // Récupérer les étudiants existants et créer les choix groupés par année
    $etudiants_existing = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_etudiants ORDER BY nom");

    // Ordre souhaité : 3ème, 2ème, 1ère
    $ordered_keys = array('troisieme', 'deuxieme', 'premiere');
    $year_labels = array(
        'troisieme' => '3ème année',
        'deuxieme' => '2ème année',
        'premiere' => '1ère année'
    );

    $groups = array();
    if ($etudiants_existing) {
        foreach ($etudiants_existing as $e) {
            $key = isset($e->annee) ? $e->annee : 'autre';
            if (in_array($key, $ordered_keys)) {
                $groups[$key][] = $e;
            } else {
                $other_key = 'other_' . sanitize_title($key);
                $groups[$other_key][] = $e;
            }
        }
    }

    // Construire les choix avec séparateurs
    $etudiants_choices = array('' => '-- Nouvel étudiant --');

    foreach ($ordered_keys as $k) {
        if (!empty($groups[$k])) {
            foreach ($groups[$k] as $e) {
                $label = isset($year_labels[$k]) ? $year_labels[$k] : '';
                $etudiants_choices[$e->id] = $e->nom . ($label ? " (" . mb_strtolower($label, 'UTF-8') . ")" : '');
            }
        }
    }

    // Ajouter les autres groupes
    foreach ($groups as $gkey => $items) {
        if (in_array($gkey, $ordered_keys) || strpos($gkey, 'other_') !== 0) continue;
        $label_sample = isset($items[0]->annee) ? $items[0]->annee : $gkey;
        foreach ($items as $e) {
            $etudiants_choices[$e->id] = $e->nom . ' (' . $e->annee . ')';
        }
    }

    // Contrôle pour sélectionner l'étudiant à modifier
    $wp_customize->add_setting('etudiant_a_modifier', array(
        'default' => '',
        'sanitize_callback' => 'ctrltim_sanitize_etudiant_selector',
    ));
    $wp_customize->add_control('etudiant_a_modifier', array(
        'label' => __('Sélectionner l\'étudiant à modifier', 'ctrltim'),
        'section' => 'ctrltim_etudiants',
        'type' => 'select',
        'choices' => $etudiants_choices,
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

    // SECTION MEDIAS SOCIAUX
    $wp_customize->add_section('ctrltim_medias_sociaux', array(
        'title' => __('Gestion des Médias sociaux', 'ctrltim'),
        'priority' => 36,
    ));

    // Récupérer les médias sociaux existants et créer les choix
    $medias_existing = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_medias_sociaux ORDER BY nom");
    $medias_choices = array('' => '-- Nouveau média social --');

    if ($medias_existing) {
        foreach ($medias_existing as $m) {
            $medias_choices[$m->id] = $m->nom;
        }
    }

    // Contrôle pour sélectionner le média à modifier
    $wp_customize->add_setting('media_a_modifier', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('media_a_modifier', array(
        'label' => __('Sélectionner le média à modifier', 'ctrltim'),
        'section' => 'ctrltim_medias_sociaux',
        'type' => 'select',
        'choices' => $medias_choices,
    ));

    // Nom du média
    $wp_customize->add_setting('nom_media', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('nom_media', array(
        'label' => __('Nom du média', 'ctrltim'),
        'section' => 'ctrltim_medias_sociaux',
        'type' => 'text',
    ));

    // Image du média
    $wp_customize->add_setting('image_media', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'image_media', array(
        'label' => __('Image du média', 'ctrltim'),
        'section' => 'ctrltim_medias_sociaux',
    )));

    // Lien du média
    $wp_customize->add_setting('lien_media', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('lien_media', array(
        'label' => __('Lien (URL)', 'ctrltim'),
        'section' => 'ctrltim_medias_sociaux',
        'type' => 'url',
    ));

    // Action pour le média
    $wp_customize->add_setting('action_media', array(
        'default' => 'sauvegarder',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('action_media', array(
        'label' => __('Action', 'ctrltim'),
        'section' => 'ctrltim_medias_sociaux',
        'type' => 'select',
        'choices' => array(
            'sauvegarder' => 'Sauvegarder',
            'supprimer' => 'Supprimer',
        ),
    ));

    // SECTION CATÉGORIES
    $wp_customize->add_section('ctrltim_categories', array(
        'title' => __('Gestion des Catégories', 'ctrltim'),
        'priority' => 37,
    ));

    // Récupérer les catégories existantes et créer les choix
    $categories_existing = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_categories ORDER BY nom");
    $categories_choices = array('' => '-- Nouvelle catégorie --');
    if ($categories_existing) {
        foreach ($categories_existing as $c) {
            $categories_choices[$c->id] = $c->nom;
        }
    }

    // Contrôle pour sélectionner la catégorie à modifier
    $wp_customize->add_setting('categorie_a_modifier', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('categorie_a_modifier', array(
        'label' => __('Sélectionner la catégorie à modifier', 'ctrltim'),
        'section' => 'ctrltim_categories',
        'type' => 'select',
        'choices' => $categories_choices,
    ));

    // Nom de la catégorie
    $wp_customize->add_setting('nom_categorie', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('nom_categorie', array(
        'label' => __('Nom de la catégorie', 'ctrltim'),
        'section' => 'ctrltim_categories',
        'type' => 'text',
    ));

    // Action pour la catégorie
    $wp_customize->add_setting('action_categorie', array(
        'default' => 'sauvegarder',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('action_categorie', array(
        'label' => __('Action', 'ctrltim'),
        'section' => 'ctrltim_categories',
        'type' => 'select',
        'choices' => array(
            'sauvegarder' => 'Sauvegarder',
            'supprimer' => 'Supprimer',
        ),
    ));

    // (Action immédiate supprimée — gestion via pub/sub ou AJAX externe possible)

    // (Le contrôle 'Exécuter l\'action' a été retiré — les médias sont gérés via la sauvegarde du Customizer)
}
add_action('customize_register', 'ctrltim_enregistrer_customizer');

    // Sanitize callback pour le sélecteur de projet
    function ctrltim_sanitize_projet_selector($value) {
        if (is_numeric($value) || is_string($value)) {
            return sanitize_text_field((string)$value);
        }
        return '';
    }

// Sanitize callback pour le sélecteur d'étudiant
function ctrltim_sanitize_etudiant_selector($value) {
    if (is_numeric($value) || is_string($value)) {
        return sanitize_text_field((string)$value);
    }
    return '';
}

// JavaScript pour améliorer l'expérience utilisateur
function ctrltim_script_customizer() {
    wp_enqueue_script('ctrltim-customizer', get_template_directory_uri() . '/js/customizer.js', array('jquery', 'customize-controls'), '1.0.0', true);
    
    // Passer les données nécessaires au JavaScript
    wp_localize_script('ctrltim-customizer', 'ctrlTimData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ctrltim_nonce')
    ));

    // Debug: log the number of social medias when the Customizer controls are loaded (admin only)
    if (current_user_can('edit_theme_options') && function_exists('ctrltim_get_all_medias')) {
        $medias = ctrltim_get_all_medias();
        // debug removed
    }
}
add_action('customize_controls_enqueue_scripts', 'ctrltim_script_customizer');

?>