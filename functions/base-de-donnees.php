<?php
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
        image_projet varchar(500),
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
// FONCTIONS UTILITAIRES
// =====================

function ctrltim_get_all_projects() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_projets ORDER BY id DESC");
}

function ctrltim_get_all_students() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_etudiants ORDER BY id DESC");
}

// Fonction pour nettoyer les champs du formulaire projet
function ctrltim_clear_project_fields() {
    remove_theme_mod('projet_a_modifier');
    remove_theme_mod('titre_projet');
    remove_theme_mod('description_projet');
    remove_theme_mod('video_projet');
    remove_theme_mod('image_projet');
    remove_theme_mod('filtre_jeux');
    remove_theme_mod('filtre_3d');
    remove_theme_mod('filtre_video');
    remove_theme_mod('filtre_web');
}

// Fonction pour pré-remplir les champs lors de la modification
function ctrltim_load_project_data($project_id) {
    global $wpdb;
    $project = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ctrltim_projets WHERE id = %d", $project_id));
    
    if ($project) {
        set_theme_mod('titre_projet', $project->titre_projet);
        set_theme_mod('description_projet', $project->description_projet);
        set_theme_mod('video_projet', $project->video_projet);
        set_theme_mod('image_projet', $project->image_projet);
        set_theme_mod('cat_exposition', $project->cat_exposition);
        
        // Charger les filtres
        $filtres = json_decode($project->filtre_projet, true);
        if (is_array($filtres)) {
            set_theme_mod('filtre_jeux', in_array('filtre_jeux', $filtres));
            set_theme_mod('filtre_3d', in_array('filtre_3d', $filtres));
            set_theme_mod('filtre_video', in_array('filtre_video', $filtres));
            set_theme_mod('filtre_web', in_array('filtre_web', $filtres));
        }
    }
}

// =====================
// SAUVEGARDE SIMPLE
// =====================

function ctrltim_save_data() {
    global $wpdb;
    
    // PROJETS - Gestion simple
    $titre = get_theme_mod('titre_projet');
    $description = get_theme_mod('description_projet');
    $video = get_theme_mod('video_projet');
    $image = get_theme_mod('image_projet');
    $cat = get_theme_mod('cat_exposition');
    $projet_a_modifier = get_theme_mod('projet_a_modifier');
    $action = get_theme_mod('action_projet');
    
    // Collecter les filtres
    $filtres = array();
    if (get_theme_mod('filtre_jeux')) $filtres[] = 'filtre_jeux';
    if (get_theme_mod('filtre_3d')) $filtres[] = 'filtre_3d';
    if (get_theme_mod('filtre_video')) $filtres[] = 'filtre_video';
    if (get_theme_mod('filtre_web')) $filtres[] = 'filtre_web';
    
    // CAS 1: AJOUTER un nouveau projet (pas d'ID sélectionné + titre rempli)
    if (empty($projet_a_modifier) && !empty($titre)) {
        $wpdb->insert("{$wpdb->prefix}ctrltim_projets", array(
            'titre_projet' => $titre, 
            'description_projet' => $description,
            'video_projet' => $video,
            'image_projet' => $image,
            'cat_exposition' => $cat,
            'filtre_projet' => json_encode($filtres)
        ));
        
        // Nettoyer les champs après ajout
        ctrltim_clear_project_fields();
    } 
    // CAS 2: MODIFIER ou SUPPRIMER un projet existant (ID sélectionné)
    elseif (!empty($projet_a_modifier)) {
        if ($action === 'supprimer') {
            // Supprimer le projet
            $wpdb->delete("{$wpdb->prefix}ctrltim_projets", array('id' => $projet_a_modifier), array('%d'));
            
            // Nettoyer les champs après suppression
            ctrltim_clear_project_fields();
        } elseif ($action === 'modifier') {
            // Récupérer le projet existant
            $existing = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ctrltim_projets WHERE id = %d", $projet_a_modifier));
            
            if ($existing) {
                // Modifier avec les nouvelles données (garder les anciennes si champ vide)
                $wpdb->update("{$wpdb->prefix}ctrltim_projets", array(
                    'titre_projet' => !empty($titre) ? $titre : $existing->titre_projet, 
                    'description_projet' => $description !== '' ? $description : $existing->description_projet,
                    'video_projet' => $video !== '' ? $video : $existing->video_projet,
                    'image_projet' => $image !== '' ? $image : $existing->image_projet,
                    'cat_exposition' => $cat,
                    'filtre_projet' => json_encode($filtres)
                ), array('id' => $projet_a_modifier), array('%s', '%s', '%s', '%s', '%s', '%s'), array('%d'));
                
                // Nettoyer les champs après modification
                ctrltim_clear_project_fields();
            }
        }
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
        remove_theme_mod('annee_etudiant');
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
        remove_theme_mod('annee_etudiant');
    }
}

add_action('customize_save_after', 'ctrltim_save_data');

?>