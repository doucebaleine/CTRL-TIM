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
        cat_exposition varchar(50) DEFAULT 'cat_arcade',
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
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql1);
    dbDelta($sql2);
}

add_action('after_switch_theme', 'ctrltim_creer_tables');

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

add_action('after_switch_theme', 'ctrltim_mettre_a_jour_tables');

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
    
    $student_ids = json_decode($project->etudiants_associes, true);
    
    if (is_array($student_ids) && !empty($student_ids)) {
        $etudiants_associes = $student_ids;
        
        if (is_array($etudiants_associes)) {
            
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
    $cat = get_theme_mod('cat_exposition');
    $projet_a_modifier = get_theme_mod('projet_a_modifier');
    $action_projet = get_theme_mod('action_projet');
    
    // Récupérer les filtres
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
            'cat_exposition' => $cat,
            'filtres' => json_encode($filtres),
            'etudiants_associes' => json_encode(array())
        );
        
        $result = $wpdb->insert($wpdb->prefix . 'ctrltim_projets', $project_data);
        ctrltim_vider_champs(['projet_a_modifier', 'titre_projet', 'description_projet', 'video_projet', 'image_projet', 'filtre_jeux', 'filtre_3d', 'filtre_video', 'filtre_web']);
    }
    
    // CAS 2: MODIFIER un projet existant (ID sélectionné + titre rempli)
    elseif (!empty($projet_a_modifier) && !empty($titre)) {
        if ($action_projet === 'supprimer') {
            $wpdb->delete($wpdb->prefix . 'ctrltim_projets', array('id' => $projet_a_modifier));
            ctrltim_vider_champs(['projet_a_modifier', 'titre_projet', 'description_projet', 'video_projet', 'image_projet', 'filtre_jeux', 'filtre_3d', 'filtre_video', 'filtre_web']);
        } else {
            $project_data = array(
                'titre_projet' => $titre,
                'description_projet' => $description,
                'video_projet' => $video,
                'image_projet' => $image,
                'cat_exposition' => $cat,
                'filtres' => json_encode($filtres)
            );
            
            $wpdb->update(
                $wpdb->prefix . 'ctrltim_projets', 
                $project_data,
                array('id' => $projet_a_modifier)
            );
            
            ctrltim_vider_champs(['projet_a_modifier', 'titre_projet', 'description_projet', 'video_projet', 'image_projet', 'filtre_jeux', 'filtre_3d', 'filtre_video', 'filtre_web']);
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
}

// Hook pour sauvegarder les données
add_action('customize_save_after', 'ctrltim_sauvegarder_donnees');

// AJAX pour charger les données d'un projet
function ctrltim_ajax_charger_donnees_projet() {
    if (!wp_verify_nonce($_POST['nonce'], 'ctrltim_nonce')) {
        wp_die('Erreur de sécurité');
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
        wp_die('Erreur de sécurité');
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

// AJAX pour gérer l'association des étudiants aux projets
function ctrltim_ajax_gerer_etudiants_projet() {
    if (!wp_verify_nonce($_POST['nonce'], 'ctrltim_nonce')) {
        wp_die('Erreur de sécurité');
    }

    global $wpdb;
    $project_id = intval($_POST['project_id']);
    $student_id = intval($_POST['student_id']);
    $action = sanitize_text_field($_POST['action']);

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
add_action('wp_ajax_manage_project_students', 'ctrltim_ajax_gerer_etudiants_projet');

?>