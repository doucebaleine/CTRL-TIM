<?php
// =====================
// TABLES
// =====================

function ctrltim_creer_tables() {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();
    
    // Projets
    $sql1 = "CREATE TABLE {$wpdb->prefix}ctrltim_projets (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        titre_projet varchar(255) NOT NULL,
        description_projet text,
        video_projet varchar(500),
        image_projet varchar(500),
        images_projet text DEFAULT NULL,
        lien varchar(500),
        cours varchar(255),
    cat_exposition varchar(50) DEFAULT '',
        filtres text DEFAULT NULL,
        etudiants_associes text DEFAULT NULL,
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
    
    // Médias sociaux
    $sql3 = "CREATE TABLE {$wpdb->prefix}ctrltim_medias_sociaux (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nom varchar(255) NOT NULL,
        image_media varchar(500),
        lien varchar(500),
        date_creation datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;";

    // Catégories
    $sql4 = "CREATE TABLE {$wpdb->prefix}ctrltim_categories (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nom varchar(255) NOT NULL,
        date_creation datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql1);
    dbDelta($sql2);
    dbDelta($sql3);
    dbDelta($sql4);
}

// Table creation is hooked below (after updates) to avoid duplicate registrations

// Fonction pour mettre à jour les tables existantes
function ctrltim_mettre_a_jour_tables() {
    global $wpdb;
    
    // Vérifier si la colonne etudiants_associes existe
    $column_exists = $wpdb->get_results($wpdb->prepare(
        "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
         WHERE TABLE_SCHEMA = %s 
         AND TABLE_NAME = %s 
         AND COLUMN_NAME = 'etudiants_associes'",
        DB_NAME,
        $wpdb->prefix . 'ctrltim_projets'
    ));
    
    if (empty($column_exists)) {
        $wpdb->query("ALTER TABLE {$wpdb->prefix}ctrltim_projets ADD COLUMN etudiants_associes text DEFAULT NULL");
    }
    
    // Vérifier si la colonne lien existe
    $lien_column = $wpdb->get_results($wpdb->prepare(
        "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
         WHERE TABLE_SCHEMA = %s 
         AND TABLE_NAME = %s 
         AND COLUMN_NAME = 'lien'",
        DB_NAME,
        $wpdb->prefix . 'ctrltim_projets'
    ));
    
    if (empty($lien_column)) {
        $wpdb->query("ALTER TABLE {$wpdb->prefix}ctrltim_projets ADD COLUMN lien varchar(500) DEFAULT NULL");
    }
    
    // Vérifier si la colonne cours existe
    $cours_column = $wpdb->get_results($wpdb->prepare(
        "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
         WHERE TABLE_SCHEMA = %s 
         AND TABLE_NAME = %s 
         AND COLUMN_NAME = 'cours'",
        DB_NAME,
        $wpdb->prefix . 'ctrltim_projets'
    ));
    
    if (empty($cours_column)) {
        $wpdb->query("ALTER TABLE {$wpdb->prefix}ctrltim_projets ADD COLUMN cours varchar(255) DEFAULT NULL");
    }

    // Vérifier si la colonne images_projet existe (nouveau: stocke JSON d'URLs pour carrousel)
    $images_column = $wpdb->get_results($wpdb->prepare(
        "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
         WHERE TABLE_SCHEMA = %s 
         AND TABLE_NAME = %s 
         AND COLUMN_NAME = 'images_projet'",
        DB_NAME,
        $wpdb->prefix . 'ctrltim_projets'
    ));

    if (empty($images_column)) {
        $wpdb->query("ALTER TABLE {$wpdb->prefix}ctrltim_projets ADD COLUMN images_projet text DEFAULT NULL");
    }

    // NOTE: project-level "annee_projet" column was removed from the schema.
    // A one-time migration will back up existing values and drop the column.
    // See ctrltim_migrer_et_supprimer_annee_projet() hooked to admin_init below.
    
    // Vérifier et corriger le nom de la colonne filtres
    $old_column = $wpdb->get_results($wpdb->prepare(
        "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
         WHERE TABLE_SCHEMA = %s 
         AND TABLE_NAME = %s 
         AND COLUMN_NAME = 'filtre_projet'",
        DB_NAME,
        $wpdb->prefix . 'ctrltim_projets'
    ));
    
    if (!empty($old_column)) {
        $wpdb->query("ALTER TABLE {$wpdb->prefix}ctrltim_projets CHANGE filtre_projet filtres text DEFAULT NULL");
    }
}

add_action('after_switch_theme', 'ctrltim_creer_tables');
add_action('after_switch_theme', 'ctrltim_mettre_a_jour_tables');

// The project-level `annee_projet` column and automatic migration were removed.
// Migration code has been deleted since the site no longer requires it and
// a backup was taken during the initial migration run.

// Fonction pour récupérer les étudiants associés à un projet
function ctrltim_obtenir_etudiants_projet($project_id) {
    global $wpdb;
    
    // Vérifier d'abord si la colonne existe
    $columns = $wpdb->get_col("DESCRIBE {$wpdb->prefix}ctrltim_projets");
    if (!in_array('etudiants_associes', $columns)) {
        return array();
    }
    
    $project = $wpdb->get_row($wpdb->prepare("SELECT etudiants_associes FROM {$wpdb->prefix}ctrltim_projets WHERE id = %d", $project_id));
    
    if (!$project) {
        return array();
    }
    
    if (!empty($project->etudiants_associes) && $project->etudiants_associes !== 'null') {
        $student_ids = json_decode($project->etudiants_associes, true);
        
        if (is_array($student_ids) && !empty($student_ids)) {
            // Nettoyer les IDs (s'assurer qu'ils sont des entiers)
            $student_ids = array_map('intval', $student_ids);
            $student_ids = array_filter($student_ids); // Enlever les 0 et valeurs invalides
            
            if (!empty($student_ids)) {
                $placeholders = implode(',', array_fill(0, count($student_ids), '%d'));
                $students = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ctrltim_etudiants WHERE id IN ($placeholders)", ...$student_ids));
                return $students;
            }
        }
    }
    
    return array();
}

// Fonction pour nettoyer les champs du formulaire
function ctrltim_vider_champs($fields) {
    foreach ($fields as $field) {
        remove_theme_mod($field);
    }
}

// Fonction pour récupérer tous les projets
// English-name wrapper -> calls French primary
function ctrltim_get_all_projects() {
    if (function_exists('ctrltim_get_all_projets')) return ctrltim_get_all_projets();
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_projets ORDER BY date_creation DESC");
}

// French primary implementation
function ctrltim_get_all_projets() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_projets ORDER BY date_creation DESC");
}

// Fonction pour récupérer tous les étudiants
// English-name wrapper -> calls French primary
function ctrltim_get_all_students() {
    if (function_exists('ctrltim_get_all_etudiants')) return ctrltim_get_all_etudiants();
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_etudiants ORDER BY nom ASC");
}

// French primary implementation
function ctrltim_get_all_etudiants() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_etudiants ORDER BY nom ASC");
}

// Fonction pour récupérer tous les médias sociaux
// English-name wrapper -> calls French primary
function ctrltim_get_all_social_medias() {
    if (function_exists('ctrltim_get_all_medias')) return ctrltim_get_all_medias();
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_medias_sociaux ORDER BY nom ASC");
}

// French primary implementation
function ctrltim_get_all_medias() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_medias_sociaux ORDER BY nom ASC");
}

// English-name wrapper -> calls French primary
function ctrltim_get_all_categories() {
    if (function_exists('ctrltim_get_toutes_categories')) return ctrltim_get_toutes_categories();
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_categories ORDER BY nom ASC");
}

// French primary implementation
function ctrltim_get_toutes_categories() {
    global $wpdb;
    return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}ctrltim_categories ORDER BY nom ASC");
}

// English wrapper -> calls French primary
function ctrltim_get_category_name($id) {
    if (function_exists('ctrltim_get_nom_categorie')) return ctrltim_get_nom_categorie($id);
    global $wpdb;
    $id = intval($id);
    if (!$id) return '';
    $row = $wpdb->get_row($wpdb->prepare("SELECT nom FROM {$wpdb->prefix}ctrltim_categories WHERE id = %d", $id));
    return $row ? $row->nom : '';
}

// French primary implementation: retourne le nom d'une catégorie par son id
function ctrltim_get_nom_categorie($id) {
    global $wpdb;
    $id = intval($id);
    if (!$id) return '';
    $row = $wpdb->get_row($wpdb->prepare("SELECT nom FROM {$wpdb->prefix}ctrltim_categories WHERE id = %d", $id));
    return $row ? $row->nom : '';
}



// =====================
// FONCTION PRINCIPALE DE SAUVEGARDE
// =====================

function ctrltim_sauvegarder_donnees() {
    global $wpdb;
    
    // PROJETS - Gestion simple
    $titre = get_theme_mod('titre_projet');
    $description = get_theme_mod('description_projet');
    $video = get_theme_mod('video_projet');
    $image = get_theme_mod('image_projet');
    // Images pour carrousel (jusqu'à 5)
    $images = array();
    for ($i = 1; $i <= 5; $i++) {
        $img = get_theme_mod('image_projet_' . $i);
        if (!empty($img)) $images[] = $img;
    }
    // Année / filtre (supprimé) — ne plus récupérer la valeur depuis le Customizer
    $lien = get_theme_mod('lien_projet');
    $cours = get_theme_mod('cours_projet');
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
        $project_data = array(
            'titre_projet' => $titre, 
            'description_projet' => $description,
            'video_projet' => $video,
            'image_projet' => $image,
            'images_projet' => json_encode($images),
            'lien' => $lien,
            'cours' => $cours,
            'cat_exposition' => $cat,
            'filtres' => json_encode($filtres),
            'etudiants_associes' => json_encode(array())
        );
        
        $wpdb->insert($wpdb->prefix . 'ctrltim_projets', $project_data);
        ctrltim_vider_champs(['projet_a_modifier', 'titre_projet', 'description_projet', 'video_projet', 'image_projet', 'lien_projet', 'cours_projet', 'filtre_jeux', 'filtre_3d', 'filtre_video', 'filtre_web']);
    }
    
    // CAS 2: MODIFIER un projet existant (ID sélectionné + titre rempli)
    elseif (!empty($projet_a_modifier) && !empty($titre)) {
        if ($action === 'supprimer') {
            // Supprimer le projet
            $wpdb->delete($wpdb->prefix . 'ctrltim_projets', array('id' => $projet_a_modifier));
            ctrltim_vider_champs(['projet_a_modifier', 'titre_projet', 'description_projet', 'video_projet', 'image_projet', 'lien_projet', 'cours_projet', 'filtre_jeux', 'filtre_3d', 'filtre_video', 'filtre_web']);
        } else {
            // Mettre à jour le projet
            $project_data = array(
                'titre_projet' => $titre,
                'description_projet' => $description,
                'video_projet' => $video,
                'image_projet' => $image,
                'images_projet' => json_encode($images),
                'lien' => $lien,
                'cours' => $cours,
                'cat_exposition' => $cat,
                'filtres' => json_encode($filtres)
            );
            
            $wpdb->update(
                $wpdb->prefix . 'ctrltim_projets', 
                $project_data,
                array('id' => $projet_a_modifier)
            );
            
            ctrltim_vider_champs(['projet_a_modifier', 'titre_projet', 'description_projet', 'video_projet', 'image_projet', 'image_projet_1', 'image_projet_2', 'image_projet_3', 'image_projet_4', 'image_projet_5', 'lien_projet', 'cours_projet', 'filtre_jeux', 'filtre_3d', 'filtre_video', 'filtre_web']);
        }
    }
    
    // ÉTUDIANTS - Gestion simple
    $nom = get_theme_mod('nom_etudiant');
    $image_etudiant = get_theme_mod('image_etudiant');
    $annee = get_theme_mod('annee_etudiant');
    $etudiant_a_modifier = get_theme_mod('etudiant_a_modifier');
    $action_etudiant = get_theme_mod('action_etudiant');
    
    // CAS 1: AJOUTER un nouvel étudiant (pas d'ID sélectionné + nom rempli)
    if (empty($etudiant_a_modifier) && !empty($nom)) {
        $student_data = array(
            'nom' => $nom,
            'image_etudiant' => $image_etudiant,
            'annee' => $annee
        );
        
        $wpdb->insert($wpdb->prefix . 'ctrltim_etudiants', $student_data);
        ctrltim_vider_champs(['etudiant_a_modifier', 'nom_etudiant', 'image_etudiant', 'annee_etudiant']);
    }
    
    // CAS 2: MODIFIER un étudiant existant (ID sélectionné + nom rempli)
    elseif (!empty($etudiant_a_modifier) && !empty($nom)) {
        if ($action_etudiant === 'supprimer') {
            // Supprimer l'étudiant
            $wpdb->delete($wpdb->prefix . 'ctrltim_etudiants', array('id' => $etudiant_a_modifier));
            ctrltim_vider_champs(['etudiant_a_modifier', 'nom_etudiant', 'image_etudiant', 'annee_etudiant']);
        } else {
            // Mettre à jour l'étudiant
            $student_data = array(
                'nom' => $nom,
                'image_etudiant' => $image_etudiant,
                'annee' => $annee
            );
            
            $wpdb->update(
                $wpdb->prefix . 'ctrltim_etudiants', 
                $student_data,
                array('id' => $etudiant_a_modifier)
            );
            
            ctrltim_vider_champs(['etudiant_a_modifier', 'nom_etudiant', 'image_etudiant', 'annee_etudiant']);
        }
    }

    // MÉDIAS SOCIAUX - Gestion simple
    $nom_media = get_theme_mod('nom_media');
    $image_media = get_theme_mod('image_media');
    $lien_media = get_theme_mod('lien_media');
    $media_a_modifier = get_theme_mod('media_a_modifier');
    $action_media = get_theme_mod('action_media');

    // CAS 1: AJOUTER un nouveau média social (pas d'ID sélectionné + nom rempli)
    if (empty($media_a_modifier) && !empty($nom_media)) {
        $media_data = array(
            'nom' => $nom_media,
            'image_media' => $image_media,
            'lien' => $lien_media
        );

        $wpdb->insert($wpdb->prefix . 'ctrltim_medias_sociaux', $media_data);
        ctrltim_vider_champs(['media_a_modifier', 'nom_media', 'image_media', 'lien_media']);
    }

    // CAS 2: MODIFIER un média social existant (ID sélectionné + nom rempli)
    elseif (!empty($media_a_modifier) && !empty($nom_media)) {
        if ($action_media === 'supprimer') {
            // Supprimer le média
            $wpdb->delete($wpdb->prefix . 'ctrltim_medias_sociaux', array('id' => $media_a_modifier));
            ctrltim_vider_champs(['media_a_modifier', 'nom_media', 'image_media', 'lien_media']);
        } else {
            // Mettre à jour le média
            $media_data = array(
                'nom' => $nom_media,
                'image_media' => $image_media,
                'lien' => $lien_media
            );

            $wpdb->update(
                $wpdb->prefix . 'ctrltim_medias_sociaux',
                $media_data,
                array('id' => $media_a_modifier)
            );

            ctrltim_vider_champs(['media_a_modifier', 'nom_media', 'image_media', 'lien_media']);
        }
    }

    // CATÉGORIES - Gestion simple (uniquement 'nom')
    $nom_categorie = get_theme_mod('nom_categorie');
    $categorie_a_modifier = get_theme_mod('categorie_a_modifier');
    $action_categorie = get_theme_mod('action_categorie');

    // CAS 1: AJOUTER une nouvelle catégorie (pas d'ID sélectionné + nom rempli)
    if (empty($categorie_a_modifier) && !empty($nom_categorie)) {
        $cat_data = array(
            'nom' => $nom_categorie
        );

        $wpdb->insert($wpdb->prefix . 'ctrltim_categories', $cat_data);
        ctrltim_vider_champs(['categorie_a_modifier', 'nom_categorie']);
    }

    // CAS 2: MODIFIER une catégorie existante (ID sélectionné + nom rempli)
    elseif (!empty($categorie_a_modifier) && !empty($nom_categorie)) {
        if ($action_categorie === 'supprimer') {
            // Supprimer la catégorie
            $wpdb->delete($wpdb->prefix . 'ctrltim_categories', array('id' => $categorie_a_modifier));
            ctrltim_vider_champs(['categorie_a_modifier', 'nom_categorie']);
        } else {
            // Mettre à jour la catégorie
            $cat_data = array(
                'nom' => $nom_categorie
            );

            $wpdb->update(
                $wpdb->prefix . 'ctrltim_categories',
                $cat_data,
                array('id' => $categorie_a_modifier)
            );

            ctrltim_vider_champs(['categorie_a_modifier', 'nom_categorie']);
        }
    }
}

// Hook pour sauvegarder les données
add_action('customize_save_after', 'ctrltim_sauvegarder_donnees');

// AJAX pour charger les données d'un projet
function ctrltim_ajax_charger_donnees_projet() {
    if (!wp_verify_nonce($_POST['nonce'], 'ctrltim_nonce')) {
        wp_send_json_error('Accès refusé', 403);
        return;
    }

    global $wpdb;
    $project_id = intval($_POST['project_id']);
    
    $project = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}ctrltim_projets WHERE id = %d",
        $project_id
    ));

    if ($project) {
        // Récupérer les étudiants associés
        $etudiants_associes = json_decode($project->etudiants_associes, true) ?: array();
        $etudiants_html = '';
        
        if (!empty($etudiants_associes)) {
            $etudiants_html = '<ul style="margin-left: 20px;">';
            foreach ($etudiants_associes as $etudiant_id) {
                $etudiant = $wpdb->get_row($wpdb->prepare(
                    "SELECT nom, annee FROM {$wpdb->prefix}ctrltim_etudiants WHERE id = %d",
                    $etudiant_id
                ));
                if ($etudiant) {
                    $annee = ($etudiant->annee == 'premiere') ? '1ère année' : (($etudiant->annee == 'deuxieme') ? '2ème année' : '3ème année');
                    $etudiants_html .= '<li style="margin-bottom: 5px;">' . esc_html($etudiant->nom) . ' <span style="color: #666;">(' . $annee . ')</span></li>';
                }
            }
            $etudiants_html .= '</ul>';
        } else {
            $etudiants_html = '<p><em>Aucun étudiant associé pour le moment</em></p>';
        }

        $data = array(
            'titre_projet' => $project->titre_projet,
            'description_projet' => $project->description_projet,
            'video_projet' => $project->video_projet,
            'image_projet' => $project->image_projet,
            'images_projet' => json_decode($project->images_projet, true) ?: array(),
            'lien' => $project->lien,
            'cours' => $project->cours,
            'cat_exposition' => $project->cat_exposition,
            'filtres' => json_decode($project->filtres, true) ?: array(),
            'etudiants_associes' => $etudiants_html
        );
        
        wp_send_json_success($data);
    } else {
        wp_send_json_error('Projet non trouvé');
    }
}
add_action('wp_ajax_load_project_data', 'ctrltim_ajax_charger_donnees_projet');

// AJAX pour charger les données d'un étudiant
function ctrltim_ajax_charger_donnees_etudiant() {
    if (!wp_verify_nonce($_POST['nonce'], 'ctrltim_nonce')) {
        wp_send_json_error('Accès refusé', 403);
        return;
    }

    global $wpdb;
    $student_id = intval($_POST['student_id']);
    
    $student = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}ctrltim_etudiants WHERE id = %d",
        $student_id
    ));

    if ($student) {
        wp_send_json_success($student);
    } else {
        wp_send_json_error('Étudiant non trouvé');
    }
}
add_action('wp_ajax_load_student_data', 'ctrltim_ajax_charger_donnees_etudiant');

// AJAX pour charger les données d'un média social
function ctrltim_ajax_charger_donnees_media() {
    if (!wp_verify_nonce($_POST['nonce'], 'ctrltim_nonce')) {
        wp_send_json_error('Accès refusé', 403);
        return;
    }

    global $wpdb;
    $media_id = intval($_POST['media_id']);
    
    $media = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}ctrltim_medias_sociaux WHERE id = %d",
        $media_id
    ));

    if ($media) {
        wp_send_json_success($media);
    } else {
        wp_send_json_error('Média social non trouvé');
    }
}
add_action('wp_ajax_load_media_data', 'ctrltim_ajax_charger_donnees_media');

// AJAX pour gérer les associations étudiants-projets
function ctrltim_ajax_manage_project_students() {
    if (!wp_verify_nonce($_POST['nonce'], 'ctrltim_nonce')) {
        wp_send_json_error('Accès refusé', 403);
        return;
    }

    global $wpdb;
    $project_id = intval($_POST['project_id']);
    $student_id = intval($_POST['student_id']);
    $action = sanitize_text_field($_POST['student_action']);

    if (!$project_id || !$student_id) {
        wp_send_json_error('IDs manquants');
        return;
    }

    $project = $wpdb->get_row($wpdb->prepare(
        "SELECT etudiants_associes FROM {$wpdb->prefix}ctrltim_projets WHERE id = %d",
        $project_id
    ));

    if ($project) {
        $current_students = json_decode($project->etudiants_associes, true) ?: array();
        
        if ($action === 'ajouter') {
            if (!in_array($student_id, $current_students)) {
                $current_students[] = $student_id;
            }
        } elseif ($action === 'retirer') {
            $current_students = array_diff($current_students, array($student_id));
        }

        $result = $wpdb->update(
            $wpdb->prefix . 'ctrltim_projets',
            array('etudiants_associes' => json_encode(array_values($current_students))),
            array('id' => $project_id),
            array('%s'),
            array('%d')
        );
        
        if ($result !== false) {
            wp_send_json_success('Association mise à jour');
        } else {
            wp_send_json_error('Erreur lors de la mise à jour de la base de données');
        }
    } else {
        wp_send_json_error('Projet non trouvé');
    }
}
add_action('wp_ajax_manage_project_students', 'ctrltim_ajax_manage_project_students');

// Hook pour sauvegarder les données
add_action('customize_save_after', 'ctrltim_sauvegarder_donnees');

// AJAX pour gérer un média social (utilisé par le Customizer pour actions rapides)
function ctrltim_ajax_manage_media() {
    if (!wp_verify_nonce($_POST['nonce'], 'ctrltim_nonce')) {
        wp_send_json_error('Accès refusé', 403);
        return;
    }

    global $wpdb;
    $media_id = isset($_POST['media_id']) ? intval($_POST['media_id']) : 0;
    $action = isset($_POST['media_action']) ? sanitize_text_field($_POST['media_action']) : 'sauvegarder';
    $nom = isset($_POST['nom_media']) ? sanitize_text_field($_POST['nom_media']) : '';
    $image = isset($_POST['image_media']) ? esc_url_raw($_POST['image_media']) : '';
    $lien = isset($_POST['lien_media']) ? esc_url_raw($_POST['lien_media']) : '';

    if ($action === 'supprimer') {
        if (!$media_id) {
            wp_send_json_error('ID manquant pour suppression');
        }

        $deleted = $wpdb->delete($wpdb->prefix . 'ctrltim_medias_sociaux', array('id' => $media_id));
        if ($deleted !== false) {
            wp_send_json_success('Média supprimé');
        } else {
            wp_send_json_error('Erreur lors de la suppression');
        }
    } else {
        // sauvegarder (insert ou update)
        if (empty($nom)) {
            wp_send_json_error('Le nom est requis');
        }

        $data = array(
            'nom' => $nom,
            'image_media' => $image,
            'lien' => $lien
        );

        if ($media_id) {
            $updated = $wpdb->update($wpdb->prefix . 'ctrltim_medias_sociaux', $data, array('id' => $media_id));
            if ($updated !== false) {
                wp_send_json_success('Média mis à jour');
            } else {
                wp_send_json_error('Erreur lors de la mise à jour');
            }
        } else {
            $inserted = $wpdb->insert($wpdb->prefix . 'ctrltim_medias_sociaux', $data);
            if ($inserted !== false) {
                wp_send_json_success('Média ajouté');
            } else {
                wp_send_json_error('Erreur lors de l\'insertion');
            }
        }
    }
}
add_action('wp_ajax_manage_media', 'ctrltim_ajax_manage_media');

// AJAX pour charger les données d'une catégorie
function ctrltim_ajax_charger_donnees_categorie() {
    if (!wp_verify_nonce($_POST['nonce'], 'ctrltim_nonce')) {
        wp_send_json_error('Accès refusé', 403);
        return;
    }

    global $wpdb;
    $cat_id = intval($_POST['category_id']);

    $cat = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}ctrltim_categories WHERE id = %d",
        $cat_id
    ));

    if ($cat) {
        wp_send_json_success($cat);
    } else {
        wp_send_json_error('Catégorie non trouvée');
    }
}
add_action('wp_ajax_load_category_data', 'ctrltim_ajax_charger_donnees_categorie');

// AJAX pour gérer une catégorie (insert/update/delete)
function ctrltim_ajax_manage_category() {
    if (!wp_verify_nonce($_POST['nonce'], 'ctrltim_nonce')) {
        wp_send_json_error('Accès refusé', 403);
        return;
    }

    global $wpdb;
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $action = isset($_POST['category_action']) ? sanitize_text_field($_POST['category_action']) : 'sauvegarder';
    $nom = isset($_POST['nom_categorie']) ? sanitize_text_field($_POST['nom_categorie']) : '';

    if ($action === 'supprimer') {
        if (!$category_id) {
            wp_send_json_error('ID manquant pour suppression');
        }

        $deleted = $wpdb->delete($wpdb->prefix . 'ctrltim_categories', array('id' => $category_id));
        if ($deleted !== false) {
            wp_send_json_success('Catégorie supprimée');
        } else {
            wp_send_json_error('Erreur lors de la suppression');
        }
    } else {
        // sauvegarder (insert ou update)
        if (empty($nom)) {
            wp_send_json_error('Le nom est requis');
        }

        $data = array('nom' => $nom);

        if ($category_id) {
            $updated = $wpdb->update($wpdb->prefix . 'ctrltim_categories', $data, array('id' => $category_id));
            if ($updated !== false) {
                wp_send_json_success('Catégorie mise à jour');
            } else {
                wp_send_json_error('Erreur lors de la mise à jour');
            }
        } else {
            $inserted = $wpdb->insert($wpdb->prefix . 'ctrltim_categories', $data);
            if ($inserted !== false) {
                wp_send_json_success('Catégorie ajoutée');
            } else {
                wp_send_json_error('Erreur lors de l\'insertion');
            }
        }
    }
}
add_action('wp_ajax_manage_category', 'ctrltim_ajax_manage_category');


?>

<?php
// Ensure tables exist on admin load (helps when theme wasn't re-activated)
function ctrltim_ensure_tables() {
    global $wpdb;
    $required = array(
        $wpdb->prefix . 'ctrltim_projets',
        $wpdb->prefix . 'ctrltim_etudiants',
        $wpdb->prefix . 'ctrltim_medias_sociaux',
        $wpdb->prefix . 'ctrltim_categories'
    );

    $missing = false;
    foreach ($required as $table) {
        $exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table));
        if (empty($exists)) {
            $missing = true;
            break;
        }
    }

    if ($missing) {
        // Try to create missing tables (will run dbDelta for all defined SQL)
        ctrltim_creer_tables();
    }
}
add_action('admin_init', 'ctrltim_ensure_tables');
// AJAX pour récupérer les choix dynamiques (projets / etudiants / medias)
function ctrltim_ajax_get_choices() {
    if (!wp_verify_nonce($_POST['nonce'], 'ctrltim_nonce')) {
        wp_send_json_error('Accès refusé', 403);
        return;
    }

    global $wpdb;
    $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

    $result = array();

    if ($type === 'medias') {
        $rows = $wpdb->get_results("SELECT id, nom FROM {$wpdb->prefix}ctrltim_medias_sociaux ORDER BY nom ASC");
        foreach ($rows as $r) {
            $result[intval($r->id)] = $r->nom;
        }
    } elseif ($type === 'etudiants') {
        $rows = $wpdb->get_results("SELECT id, nom, annee FROM {$wpdb->prefix}ctrltim_etudiants ORDER BY nom ASC");
        foreach ($rows as $r) {
            $annee = ($r->annee == 'premiere') ? '1ère année' : (($r->annee == 'deuxieme') ? '2ème année' : '3ème année');
            $result[intval($r->id)] = $r->nom . ' (' . $annee . ')';
        }
    } elseif ($type === 'projets') {
        $rows = $wpdb->get_results("SELECT id, titre_projet, cat_exposition FROM {$wpdb->prefix}ctrltim_projets ORDER BY id DESC");
        foreach ($rows as $r) {
            $cat_label = '';
            // si la valeur est numérique, chercher dans les catégories
            if (is_numeric($r->cat_exposition) && intval($r->cat_exposition) > 0 && function_exists('ctrltim_get_category_name')) {
                $cat_label = ctrltim_get_category_name(intval($r->cat_exposition));
            } else {
                // valeur non numérique (ancienne clé) — afficher telle quelle (migration automatique prévue)
                $cat_label = is_string($r->cat_exposition) ? $r->cat_exposition : '';
            }
            $label = $r->titre_projet . ($cat_label ? ' (' . $cat_label . ')' : '');
            $result[intval($r->id)] = $label;
        }
    } elseif ($type === 'categories') {
        $rows = $wpdb->get_results("SELECT id, nom FROM {$wpdb->prefix}ctrltim_categories ORDER BY nom ASC");
        foreach ($rows as $r) {
            $result[intval($r->id)] = $r->nom;
        }
    } else {
        wp_send_json_error('Type invalide');
        return;
    }

    wp_send_json_success($result);
}
add_action('wp_ajax_get_choices', 'ctrltim_ajax_get_choices');
