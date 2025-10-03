<?php
// Fonction de validation pour les checkboxes
function sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

function theme_ctrltim_customize_register($wp_customize) {
    
    // =====================
    // SECTION PROJETS
    // =====================
    
    $wp_customize->add_section('projets_section', array(
        'title' => __('Gestion des Projets', 'theme_ctrltim'),
        'priority' => 30,
    ));

    // === AJOUTER UN PROJET ===
    
    $wp_customize->add_setting('titre_projet', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('titre_projet', array(
        'label' => __('Titre du projet', 'theme_ctrltim'),
        'section' => 'projets_section',
        'type' => 'text',
    ));

    $wp_customize->add_setting('description_projet', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field'
    ));
    $wp_customize->add_control('description_projet', array(
        'label' => __('Description', 'theme_ctrltim'),
        'section' => 'projets_section',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('video_projet', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('video_projet', array(
        'label' => __('Vidéo (URL)', 'theme_ctrltim'),
        'section' => 'projets_section',
        'type' => 'url',
    ));

    $wp_customize->add_setting('cat_exposition', array(
        'default' => 'cat_arcade',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('cat_exposition', array(
        'label' => __('Catégorie exposition', 'theme_ctrltim'),
        'section' => 'projets_section',
        'type' => 'select',
        'choices' => array(
            'cat_arcade' => __('Arcade', 'theme_ctrltim'),
            'cat_finissants' => __('Finissants', 'theme_ctrltim'),
            'cat_terre' => __('Terre', 'theme_ctrltim')
        )
    ));

    // Filtres (checkboxes)
    $wp_customize->add_setting('filtre_jeux', array(
        'default' => false,
        'sanitize_callback' => 'sanitize_checkbox'
    ));
    $wp_customize->add_control('filtre_jeux', array(
        'label' => __('Jeux vidéo', 'theme_ctrltim'),
        'section' => 'projets_section',
        'type' => 'checkbox',
    ));

    $wp_customize->add_setting('filtre_3d', array(
        'default' => false,
        'sanitize_callback' => 'sanitize_checkbox'
    ));
    $wp_customize->add_control('filtre_3d', array(
        'label' => __('3D', 'theme_ctrltim'),
        'section' => 'projets_section',
        'type' => 'checkbox',
    ));

    $wp_customize->add_setting('filtre_video', array(
        'default' => false,
        'sanitize_callback' => 'sanitize_checkbox'
    ));
    $wp_customize->add_control('filtre_video', array(
        'label' => __('Vidéo', 'theme_ctrltim'),
        'section' => 'projets_section',
        'type' => 'checkbox',
    ));

    $wp_customize->add_setting('filtre_web', array(
        'default' => false,
        'sanitize_callback' => 'sanitize_checkbox'
    ));
    $wp_customize->add_control('filtre_web', array(
        'label' => __('Web', 'theme_ctrltim'),
        'section' => 'projets_section',
        'type' => 'checkbox',
    ));

    // === MODIFIER/SUPPRIMER UN PROJET ===
    
    // Sélecteur de projet
    $projets_choices = array('' => __('Sélectionner un projet', 'theme_ctrltim'));
    $projets = ctrltim_get_all_projects();
    if (!empty($projets)) {
        foreach ($projets as $projet) {
            $cat_display = str_replace('cat_', '', $projet->cat_exposition);
            $cat_display = ucfirst($cat_display);
            $projets_choices[$projet->id] = $projet->titre_projet . " (" . $cat_display . ")";
        }
    }

    $wp_customize->add_setting('projet_select', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('projet_select', array(
        'label' => __('Modifier/Supprimer un projet', 'theme_ctrltim'),
        'section' => 'projets_section',
        'type' => 'select',
        'choices' => $projets_choices
    ));

    $wp_customize->add_setting('action_projet', array(
        'default' => 'modifier',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('action_projet', array(
        'label' => __('Action', 'theme_ctrltim'),
        'section' => 'projets_section',
        'type' => 'select',
        'choices' => array(
            'modifier' => __('Modifier', 'theme_ctrltim'),
            'supprimer' => __('Supprimer', 'theme_ctrltim')
        )
    ));

    // =====================
    // SECTION ÉTUDIANTS
    // =====================
    
    $wp_customize->add_section('etudiants_section', array(
        'title' => __('Gestion des Étudiants', 'theme_ctrltim'),
        'priority' => 40,
    ));

    // === AJOUTER UN ÉTUDIANT ===
    
    $wp_customize->add_setting('nom_etudiant', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('nom_etudiant', array(
        'label' => __('Nom de l\'étudiant', 'theme_ctrltim'),
        'section' => 'etudiants_section',
        'type' => 'text',
    ));

    $wp_customize->add_setting('annee_etudiant', array(
        'default' => 'premiere',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('annee_etudiant', array(
        'label' => __('Année d\'étude', 'theme_ctrltim'),
        'section' => 'etudiants_section',
        'type' => 'select',
        'choices' => array(
            'premiere' => __('1ère année', 'theme_ctrltim'),
            'deuxieme' => __('2ème année', 'theme_ctrltim'),
            'troisieme' => __('3ème année', 'theme_ctrltim')
        )
    ));

    // === MODIFIER/SUPPRIMER UN ÉTUDIANT ===
    
    // Sélecteur d'étudiant
    $etudiants_choices = array('' => __('Sélectionner un étudiant', 'theme_ctrltim'));
    $etudiants = ctrltim_get_all_students();
    if (!empty($etudiants)) {
        foreach ($etudiants as $etudiant) {
            $annee_display = str_replace('eme', 'ème', $etudiant->annee);
            $annee_display = str_replace('premiere', '1ère', $annee_display);
            $annee_display = str_replace('deuxieme', '2ème', $annee_display);
            $annee_display = str_replace('troisieme', '3ème', $annee_display);
            $etudiants_choices[$etudiant->id] = $etudiant->nom . " (" . $annee_display . " année)";
        }
    }

    $wp_customize->add_setting('etudiant_select', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('etudiant_select', array(
        'label' => __('Modifier/Supprimer un étudiant', 'theme_ctrltim'),
        'section' => 'etudiants_section',
        'type' => 'select',
        'choices' => $etudiants_choices
    ));

    $wp_customize->add_setting('action_etudiant', array(
        'default' => 'modifier',
        'sanitize_callback' => 'sanitize_text_field'
    ));
    $wp_customize->add_control('action_etudiant', array(
        'label' => __('Action', 'theme_ctrltim'),
        'section' => 'etudiants_section',
        'type' => 'select',
        'choices' => array(
            'modifier' => __('Modifier', 'theme_ctrltim'),
            'supprimer' => __('Supprimer', 'theme_ctrltim')
        )
    ));

    // =====================
    // SECTION LISTE DES PROJETS
    // =====================
    
    $wp_customize->add_section('projets_liste_section', array(
        'title' => __('Liste des Projets', 'theme_ctrltim'),
        'priority' => 50,
    ));

    // Générer la liste des projets
    $projets_existants = ctrltim_get_all_projects();
    $liste_projets_text = "Aucun projet trouvé.";
    
    if (!empty($projets_existants)) {
        $liste_projets_text = "";
        foreach ($projets_existants as $projet) {
            // Formatage de la catégorie
            $cat_display = '';
            switch($projet->cat_exposition) {
                case 'cat_arcade':
                    $cat_display = 'Arcade';
                    break;
                case 'cat_finissants':
                    $cat_display = 'Finissants';
                    break;
                case 'cat_terre':
                    $cat_display = 'Terre';
                    break;
                default:
                    $cat_display = ucfirst(str_replace('cat_', '', $projet->cat_exposition));
            }
            
            $liste_projets_text .= "• " . $projet->titre_projet . " (" . $cat_display . ")\n";
        }
    }

    $wp_customize->add_setting('liste_projets_display', array(
        'default' => $liste_projets_text,
        'sanitize_callback' => 'sanitize_textarea_field'
    ));

    $wp_customize->add_control('liste_projets_display', array(
        'label' => __('Projets existants (Nom - Catégorie)', 'theme_ctrltim'),
        'section' => 'projets_liste_section',
        'type' => 'textarea',
        'input_attrs' => array(
            'readonly' => 'readonly',
            'rows' => 10,
            'style' => 'background-color: #f9f9f9; border: 1px solid #ddd; font-family: monospace;'
        ),
    ));

    // =====================
    // SECTION LISTE DES ÉTUDIANTS
    // =====================
    
    $wp_customize->add_section('etudiants_liste_section', array(
        'title' => __('Liste des Étudiants', 'theme_ctrltim'),
        'priority' => 60,
    ));

    // Générer la liste des étudiants
    $etudiants_existants = ctrltim_get_all_students();
    $liste_etudiants_text = "Aucun étudiant trouvé.";
    
    if (!empty($etudiants_existants)) {
        $liste_etudiants_text = "";
        foreach ($etudiants_existants as $etudiant) {
            // Formatage de l'année
            $annee_display = '';
            switch($etudiant->annee) {
                case 'premiere':
                    $annee_display = '1ère année';
                    break;
                case 'deuxieme':
                    $annee_display = '2ème année';
                    break;
                case 'troisieme':
                    $annee_display = '3ème année';
                    break;
                default:
                    $annee_display = $etudiant->annee;
            }
            
            $liste_etudiants_text .= "• " . $etudiant->nom . " (" . $annee_display . ")\n";
        }
    }

    $wp_customize->add_setting('liste_etudiants_display', array(
        'default' => $liste_etudiants_text,
        'sanitize_callback' => 'sanitize_textarea_field'
    ));

    $wp_customize->add_control('liste_etudiants_display', array(
        'label' => __('Étudiants existants (Nom - Année)', 'theme_ctrltim'),
        'section' => 'etudiants_liste_section',
        'type' => 'textarea',
        'input_attrs' => array(
            'readonly' => 'readonly',
            'rows' => 10,
            'style' => 'background-color: #f9f9f9; border: 1px solid #ddd; font-family: monospace;'
        ),
    ));
}

add_action('customize_register', 'theme_ctrltim_customize_register');

// =====================
// CRÉATION DES TABLES
// =====================

function ctrltim_create_projects_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctrltim_projets';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        titre_projet varchar(255) NOT NULL,
        description_projet text,
        video_projet varchar(500),
        cat_exposition varchar(50) DEFAULT 'cat_arcade',
        filtre_projet text DEFAULT NULL,
        date_creation datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function ctrltim_create_students_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctrltim_etudiants';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nom varchar(255) NOT NULL,
        annee varchar(50) NOT NULL,
        date_creation datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

add_action('after_switch_theme', 'ctrltim_create_projects_table');
add_action('after_switch_theme', 'ctrltim_create_students_table');

// =====================
// FONCTIONS DE LECTURE
// =====================

function ctrltim_get_all_projects() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctrltim_projets';
    return $wpdb->get_results("SELECT * FROM $table_name ORDER BY date_creation DESC");
}

function ctrltim_get_all_students() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctrltim_etudiants';
    return $wpdb->get_results("SELECT * FROM $table_name ORDER BY date_creation DESC");
}

// =====================
// SAUVEGARDE PROJETS
// =====================

function ctrltim_save_project() {
    $titre = get_theme_mod('titre_projet');
    $description = get_theme_mod('description_projet');
    $video = get_theme_mod('video_projet');
    $cat_exposition = get_theme_mod('cat_exposition');
    
    // Collecter les filtres sélectionnés
    $filtres = array();
    if (get_theme_mod('filtre_jeux')) $filtres[] = 'filtre_jeux';
    if (get_theme_mod('filtre_3d')) $filtres[] = 'filtre_3d';
    if (get_theme_mod('filtre_video')) $filtres[] = 'filtre_video';
    if (get_theme_mod('filtre_web')) $filtres[] = 'filtre_web';
    
    $projet_select = get_theme_mod('projet_select');
    $action = get_theme_mod('action_projet');
    
    if (!empty($titre) && empty($projet_select)) {
        // AJOUTER
        global $wpdb;
        $table_name = $wpdb->prefix . 'ctrltim_projets';
        
        $wpdb->insert(
            $table_name,
            array(
                'titre_projet' => $titre,
                'description_projet' => $description,
                'video_projet' => $video,
                'cat_exposition' => $cat_exposition,
                'filtre_projet' => json_encode($filtres)
            )
        );
        
        // Reset
        remove_theme_mod('titre_projet');
        remove_theme_mod('description_projet');
        remove_theme_mod('video_projet');
        remove_theme_mod('filtre_jeux');
        remove_theme_mod('filtre_3d');
        remove_theme_mod('filtre_video');
        remove_theme_mod('filtre_web');
        
    } elseif (!empty($projet_select)) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ctrltim_projets';
        
        if ($action === 'supprimer') {
            // SUPPRIMER
            $wpdb->delete($table_name, array('id' => $projet_select), array('%d'));
        } elseif ($action === 'modifier' && !empty($titre)) {
            // MODIFIER
            $wpdb->update(
                $table_name,
                array(
                    'titre_projet' => $titre,
                    'description_projet' => $description,
                    'video_projet' => $video,
                    'cat_exposition' => $cat_exposition,
                    'filtre_projet' => json_encode($filtres)
                ),
                array('id' => $projet_select),
                array('%s', '%s', '%s', '%s', '%s'),
                array('%d')
            );
        }
        
        // Reset
        remove_theme_mod('projet_select');
        remove_theme_mod('titre_projet');
        remove_theme_mod('description_projet');
        remove_theme_mod('video_projet');
        remove_theme_mod('filtre_jeux');
        remove_theme_mod('filtre_3d');
        remove_theme_mod('filtre_video');
        remove_theme_mod('filtre_web');
    }
}

// =====================
// SAUVEGARDE ÉTUDIANTS
// =====================

function ctrltim_save_student() {
    $nom = get_theme_mod('nom_etudiant');
    $annee = get_theme_mod('annee_etudiant');
    $etudiant_select = get_theme_mod('etudiant_select');
    $action = get_theme_mod('action_etudiant');
    
    if (!empty($nom) && empty($etudiant_select)) {
        // AJOUTER
        global $wpdb;
        $table_name = $wpdb->prefix . 'ctrltim_etudiants';
        
        $wpdb->insert(
            $table_name,
            array(
                'nom' => $nom,
                'annee' => $annee
            )
        );
        
        // Reset
        remove_theme_mod('nom_etudiant');
        
    } elseif (!empty($etudiant_select)) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ctrltim_etudiants';
        
        if ($action === 'supprimer') {
            // SUPPRIMER
            $wpdb->delete($table_name, array('id' => $etudiant_select), array('%d'));
        } elseif ($action === 'modifier' && !empty($nom)) {
            // MODIFIER
            $wpdb->update(
                $table_name,
                array(
                    'nom' => $nom,
                    'annee' => $annee
                ),
                array('id' => $etudiant_select),
                array('%s', '%s'),
                array('%d')
            );
        }
        
        // Reset
        remove_theme_mod('etudiant_select');
        remove_theme_mod('nom_etudiant');
    }
}

// Hooks de sauvegarde
add_action('customize_save_after', 'ctrltim_save_project');
add_action('customize_save_after', 'ctrltim_save_student');

?>