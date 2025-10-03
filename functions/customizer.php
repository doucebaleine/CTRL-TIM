<?php
// Fonction de validation pour les checkboxes
function sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

function theme_ctrltim_customize_register($wp_customize)
{  
  // Section Projets
  $wp_customize->add_section('projets_section', array(
    'title' => __('Ajouter un projet', 'theme_ctrltim'),
    'priority' => 30,
  ));

  // Titre du projet
  $wp_customize->add_setting('titre_projet', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('titre_projet', array(
    'label' => __('Titre projet', 'theme_ctrltim'),
    'section' => 'projets_section',
    'type' => 'text',
  ));

  // Image principale du projet
  $wp_customize->add_setting('image_principale_projet', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ));

  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'image_principale_projet', array(
    'label' => __('Image principale projet', 'theme_ctrltim'),
    'section' => 'projets_section',
  )));

  // Description du projet
  $wp_customize->add_setting('description_projet', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('description_projet', array(
    'label' => __('Description projet', 'theme_ctrltim'),
    'section' => 'projets_section',
    'type' => 'text',
  ));

  // Vidéo du projet
  $wp_customize->add_setting('video_projet', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ));

  $wp_customize->add_control('video_projet', array(
    'label' => __('Vidéo projet', 'theme_ctrltim'),
    'section' => 'projets_section',
    'type' => 'url',
  ));

  // Nombre d'images pour la galerie
  $wp_customize->add_setting('galerie_nombre_images', array(
    'default' => '3',
    'sanitize_callback' => 'absint'
  ));

  $wp_customize->add_control('galerie_nombre_images', array(
    'label' => __('Nombre d\'images dans la galerie (max 5)', 'theme_ctrltim'),
    'section' => 'projets_section',
    'type' => 'number',
    'input_attrs' => array(
      'min' => 1,
      'max' => 5,
      'step' => 1,
    ),
  ));

  // Images de la galerie (dynamique selon le nombre choisi)
  $nombre_images = get_theme_mod('galerie_nombre_images', 3);
  
  for ($i = 1; $i <= $nombre_images; $i++) {
    $wp_customize->add_setting('image_galerie_projet_' . $i, array(
      'default' => '',
      'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'image_galerie_projet_' . $i, array(
      'label' => __('Image galerie projet ' . $i, 'theme_ctrltim'),
      'section' => 'projets_section',
    )));
  }

  // Catégorie exposition
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

  // Filtres projet (checkboxes multiples)
  $wp_customize->add_setting('filtre_projet_jeux', array(
    'default' => false,
    'sanitize_callback' => 'sanitize_checkbox'
  ));

  $wp_customize->add_control('filtre_projet_jeux', array(
    'label' => __('Jeux vidéo', 'theme_ctrltim'),
    'section' => 'projets_section',
    'type' => 'checkbox',
  ));

  $wp_customize->add_setting('filtre_projet_3d', array(
    'default' => false,
    'sanitize_callback' => 'sanitize_checkbox'
  ));

  $wp_customize->add_control('filtre_projet_3d', array(
    'label' => __('3D', 'theme_ctrltim'),
    'section' => 'projets_section',
    'type' => 'checkbox',
  ));

  $wp_customize->add_setting('filtre_projet_video', array(
    'default' => false,
    'sanitize_callback' => 'sanitize_checkbox'
  ));

  $wp_customize->add_control('filtre_projet_video', array(
    'label' => __('Vidéo', 'theme_ctrltim'),
    'section' => 'projets_section',
    'type' => 'checkbox',
  ));

  $wp_customize->add_setting('filtre_projet_web', array(
    'default' => false,
    'sanitize_callback' => 'sanitize_checkbox'
  ));

  $wp_customize->add_control('filtre_projet_web', array(
    'label' => __('Web', 'theme_ctrltim'),
    'section' => 'projets_section',
    'type' => 'checkbox',
  ));

  // Bouton pour ajouter le projet
  $wp_customize->add_setting('ajouter_projet_action', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('ajouter_projet_action', array(
    'label' => __('Cliquez sur "Publier" pour ajouter ce projet à la base de données', 'theme_ctrltim'),
    'section' => 'projets_section',
    'type' => 'hidden',
  ));

  // Section pour afficher les projets existants
  $wp_customize->add_section('projets_liste_section', array(
    'title' => __('Projets Existants', 'theme_ctrltim'),
    'priority' => 31,
  ));

  // Section pour modifier/supprimer les projets existants
  $wp_customize->add_section('projets_liste_section', array(
    'title' => __('Projets Existants - Modifier/Supprimer', 'theme_ctrltim'),
    'priority' => 31,
  ));

  // Sélecteur de projet à modifier
  $projets_choices = array('' => __('Sélectionner un projet à modifier', 'theme_ctrltim'));
  
  $projets_existants = ctrltim_get_all_projects();
  if (!empty($projets_existants)) {
    foreach ($projets_existants as $projet) {
      // Afficher seulement le nom et la catégorie
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
          $cat_display = $projet->cat_exposition;
      }
      $projets_choices[$projet->id] = $projet->titre_projet . " (" . $cat_display . ")";
    }
  }

  $wp_customize->add_setting('projet_select_modifier', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('projet_select_modifier', array(
    'label' => __('Sélectionner un projet', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'select',
    'choices' => $projets_choices
  ));

  // Contrôles de modification avec les noms attendus par le JavaScript
  $wp_customize->add_setting('modify_projet_titre', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('modify_projet_titre', array(
    'label' => __('Modifier le titre', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'text',
  ));

  $wp_customize->add_setting('modify_projet_description', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_textarea_field'
  ));

  $wp_customize->add_control('modify_projet_description', array(
    'label' => __('Modifier la description', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'textarea',
    'input_attrs' => array(
      'rows' => 3,
    ),
  ));

  $wp_customize->add_setting('modify_projet_cat', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('modify_projet_cat', array(
    'label' => __('Modifier la catégorie', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'select',
    'choices' => array(
      '' => __('Ne pas modifier la catégorie', 'theme_ctrltim'),
      'cat_arcade' => __('Arcade', 'theme_ctrltim'),
      'cat_finissants' => __('Finissants', 'theme_ctrltim'),
      'cat_terre' => __('Terre', 'theme_ctrltim')
    )
  ));

  $wp_customize->add_setting('modify_projet_video', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw'
  ));

  $wp_customize->add_control('modify_projet_video', array(
    'label' => __('Modifier la vidéo', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'url',
  ));

  // Boutons d'action
  $wp_customize->add_setting('save_project_modify', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('save_project_modify', array(
    'label' => __('Modifier le projet', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'button',
    'input_attrs' => array(
      'class' => 'button-primary',
      'value' => __('Modifier le projet', 'theme_ctrltim'),
    ),
  ));

  $wp_customize->add_setting('delete_project', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('delete_project', array(
    'label' => __('Supprimer le projet', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'button',
    'input_attrs' => array(
      'class' => 'button-secondary',
      'value' => __('Supprimer le projet', 'theme_ctrltim'),
    ),
  ));

  // Anciens champs de modification (à conserver pour compatibilité)
  $wp_customize->add_setting('modifier_titre_projet', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('modifier_titre_projet', array(
    'label' => __('Nouveau titre (laisser vide pour conserver le titre)', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'text',
  ));

  $wp_customize->add_setting('modifier_image_principale_projet', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ));

  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'modifier_image_principale_projet', array(
    'label' => __('Nouvelle image principale (laisser vide pour conserver l\'image)', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
  )));

  $wp_customize->add_setting('modifier_description_projet', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('modifier_description_projet', array(
    'label' => __('Nouvelle description (laisser vide pour conserver la description)', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'text',
  ));

  $wp_customize->add_setting('modifier_video_projet', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ));

  $wp_customize->add_control('modifier_video_projet', array(
    'label' => __('Nouvelle vidéo (laisser vide pour conserver la vidéo)', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'url',
  ));

  $wp_customize->add_setting('modifier_cat_exposition', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('modifier_cat_exposition', array(
    'label' => __('Modifier la catégorie exposition', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'select',
    'choices' => array(
      '' => __('Ne pas modifier la catégorie', 'theme_ctrltim'),
      'cat_arcade' => __('Arcade', 'theme_ctrltim'),
      'cat_finissants' => __('Finissants', 'theme_ctrltim'),
      'cat_terre' => __('Terre', 'theme_ctrltim')
    )
  ));

  // Modifier les filtres projet (checkboxes multiples)
  $wp_customize->add_setting('modifier_filtre_projet_jeux', array(
    'default' => false,
    'sanitize_callback' => 'sanitize_checkbox'
  ));

  $wp_customize->add_control('modifier_filtre_projet_jeux', array(
    'label' => __('Jeux vidéo', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'checkbox',
  ));

  $wp_customize->add_setting('modifier_filtre_projet_3d', array(
    'default' => false,
    'sanitize_callback' => 'sanitize_checkbox'
  ));

  $wp_customize->add_control('modifier_filtre_projet_3d', array(
    'label' => __('3D', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'checkbox',
  ));

  $wp_customize->add_setting('modifier_filtre_projet_video', array(
    'default' => false,
    'sanitize_callback' => 'sanitize_checkbox'
  ));

  $wp_customize->add_control('modifier_filtre_projet_video', array(
    'label' => __('Vidéo', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'checkbox',
  ));

  $wp_customize->add_setting('modifier_filtre_projet_web', array(
    'default' => false,
    'sanitize_callback' => 'sanitize_checkbox'
  ));

  $wp_customize->add_control('modifier_filtre_projet_web', array(
    'label' => __('Web', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'checkbox',
  ));

  // Boutons d'action
  $wp_customize->add_setting('action_modifier_projet', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('action_modifier_projet', array(
    'label' => __('Cliquez sur "Publier" pour MODIFIER le projet sélectionné', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'hidden',
  ));

  $wp_customize->add_setting('action_supprimer_projet', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('action_supprimer_projet', array(
    'label' => __('Pour SUPPRIMER: cochez cette case puis cliquez "Publier"', 'theme_ctrltim'),
    'section' => 'projets_liste_section',
    'type' => 'checkbox',
  ));

  // ========================
  // SECTION ÉTUDIANTS
  // ========================
  
  // Section Étudiants
  $wp_customize->add_section('etudiants_section', array(
    'title' => __('Ajouter un étudiant', 'theme_ctrltim'),
    'priority' => 32,
  ));

  // Nom de l'étudiant
  $wp_customize->add_setting('etudiant_nom', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('etudiant_nom', array(
    'label' => __('Nom de l\'étudiant', 'theme_ctrltim'),
    'section' => 'etudiants_section',
    'type' => 'text',
  ));

  // Image de l'étudiant
  $wp_customize->add_setting('etudiant_image', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ));

  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'etudiant_image', array(
    'label' => __('Image de l\'étudiant', 'theme_ctrltim'),
    'section' => 'etudiants_section',
  )));

  // Année de l'étudiant (obligatoire)
  $wp_customize->add_setting('etudiant_annee', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('etudiant_annee', array(
    'label' => __('Année de l\'étudiant (obligatoire)', 'theme_ctrltim'),
    'section' => 'etudiants_section',
    'type' => 'select',
    'choices' => array(
      '' => __('Sélectionner une année', 'theme_ctrltim'),
      'premiere' => __('1ère année', 'theme_ctrltim'),
      'deuxieme' => __('2ème année', 'theme_ctrltim'),
      'troisieme' => __('3ème année', 'theme_ctrltim')
    )
  ));

  // Bouton pour ajouter l'étudiant
  $wp_customize->add_setting('ajouter_etudiant_action', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('ajouter_etudiant_action', array(
    'label' => __('Cliquez sur "Publier" pour ajouter cet étudiant à la base de données', 'theme_ctrltim'),
    'section' => 'etudiants_section',
    'type' => 'hidden',
  ));

  // Section pour afficher les étudiants existants
  $wp_customize->add_section('etudiants_liste_section', array(
    'title' => __('Étudiants Existants - Modifier/Supprimer', 'theme_ctrltim'),
    'priority' => 33,
  ));

  // Sélecteur d'étudiant à modifier
  $etudiants_choices = array('' => __('Sélectionner un étudiant à modifier', 'theme_ctrltim'));
  
  // Vérifier si la table existe avant d'essayer de récupérer les données
  global $wpdb;
  $table_name = $wpdb->prefix . 'ctrltim_etudiants';
  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
    $etudiants_existants = ctrltim_get_all_students();
    
    if (!empty($etudiants_existants)) {
      foreach ($etudiants_existants as $etudiant) {
        $annee_text = '';
        if (!empty($etudiant->annee)) {
          switch($etudiant->annee) {
            case 'premiere':
              $annee_text = ' (1ère année)';
              break;
            case 'deuxieme':
              $annee_text = ' (2ème année)';
              break;
            case 'troisieme':
              $annee_text = ' (3ème année)';
              break;
            default:
              $annee_text = ' (' . $etudiant->annee . ')';
          }
        }
        $etudiants_choices[$etudiant->id] = $etudiant->nom . $annee_text;
      }
    }
  }

  $wp_customize->add_setting('etudiant_select_modifier', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('etudiant_select_modifier', array(
    'label' => __('Sélectionner un étudiant', 'theme_ctrltim'),
    'section' => 'etudiants_liste_section',
    'type' => 'select',
    'choices' => $etudiants_choices
  ));

  // Affichage des valeurs (sera mis à jour via JavaScript)
  $wp_customize->add_setting('affichage_valeurs_actuelles', array(
    'default' => 'Sélectionnez un étudiant pour voir ses informations.',
    'sanitize_callback' => 'sanitize_textarea_field'
  ));

  $wp_customize->add_control('affichage_valeurs_actuelles', array(
    'label' => __('Informations de l\'étudiant sélectionné', 'theme_ctrltim'),
    'section' => 'etudiants_liste_section',
    'type' => 'textarea',
    'input_attrs' => array(
      'readonly' => 'readonly',
      'rows' => 4,
      'style' => 'background-color: #f9f9f9; border: 1px solid #ddd;'
    ),
  ));

  // Champs de modification
  $wp_customize->add_setting('modifier_etudiant_nom', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('modifier_etudiant_nom', array(
    'label' => __('Nouveau nom (laisser vide pour conserver le nom)', 'theme_ctrltim'),
    'section' => 'etudiants_liste_section',
    'type' => 'text',
  ));

  $wp_customize->add_setting('modifier_etudiant_image', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
  ));

  $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'modifier_etudiant_image', array(
    'label' => __('Nouvelle image (laisser vide pour conserver l\'image)', 'theme_ctrltim'),
    'section' => 'etudiants_liste_section',
  )));

  $wp_customize->add_setting('modifier_etudiant_annee', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('modifier_etudiant_annee', array(
    'label' => __('Modifier l\'année', 'theme_ctrltim'),
    'section' => 'etudiants_liste_section',
    'type' => 'select',
    'choices' => array(
      '' => __('Ne pas modifier l\'année', 'theme_ctrltim'),
      'premiere' => __('1ère année', 'theme_ctrltim'),
      'deuxieme' => __('2ème année', 'theme_ctrltim'),
      'troisieme' => __('3ème année', 'theme_ctrltim')
    )
  ));

  // Boutons d'action
  $wp_customize->add_setting('action_modifier_etudiant', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('action_modifier_etudiant', array(
    'label' => __('Cliquez sur "Publier" pour MODIFIER l\'étudiant sélectionné', 'theme_ctrltim'),
    'section' => 'etudiants_liste_section',
    'type' => 'hidden',
  ));

  $wp_customize->add_setting('action_supprimer_etudiant', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_text_field'
  ));

  $wp_customize->add_control('action_supprimer_etudiant', array(
    'label' => __('Pour SUPPRIMER: cochez cette case puis cliquez "Publier"', 'theme_ctrltim'),
    'section' => 'etudiants_liste_section',
    'type' => 'checkbox',
  ));

}

// Fonction pour ajouter le JavaScript au Customizer
function ctrltim_customizer_live_preview() {
    // Récupérer les données des étudiants pour le JavaScript
    $etudiants_data = array();
    $etudiants_existants = ctrltim_get_all_students();
    
    if (!empty($etudiants_existants)) {
        foreach ($etudiants_existants as $etudiant) {
            $annee_text_display = 'Aucune année définie';
            if (!empty($etudiant->annee)) {
                switch($etudiant->annee) {
                    case 'premiere':
                        $annee_text_display = '1ère année';
                        break;
                    case 'deuxieme':
                        $annee_text_display = '2ème année';
                        break;
                    case 'troisieme':
                        $annee_text_display = '3ème année';
                        break;
                    default:
                        $annee_text_display = $etudiant->annee;
                }
            }
            
            $etudiants_data[$etudiant->id] = array(
                'nom' => $etudiant->nom,
                'annee' => $etudiant->annee,
                'annee_display' => $annee_text_display
            );
        }
    }

    // Récupérer les données des projets pour le JavaScript
    $projets_data = array();
    $projets_existants = ctrltim_get_all_projects();
    
    if (!empty($projets_existants)) {
        foreach ($projets_existants as $projet) {
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
                    $cat_display = $projet->cat_exposition;
            }
            
            // Gérer les filtres multiples
            $filtres_array = json_decode($projet->filtre_projet, true);
            $filtre_display = '';
            
            if (is_array($filtres_array) && !empty($filtres_array)) {
                $filtres_labels = array();
                foreach ($filtres_array as $filtre) {
                    switch($filtre) {
                        case 'filtre_jeux':
                            $filtres_labels[] = 'Jeux vidéo';
                            break;
                        case 'filtre_3d':
                            $filtres_labels[] = '3D';
                            break;
                        case 'filtre_video':
                            $filtres_labels[] = 'Vidéo';
                            break;
                        case 'filtre_web':
                            $filtres_labels[] = 'Web';
                            break;
                    }
                }
                $filtre_display = implode(', ', $filtres_labels);
            } else {
                // Fallback pour l'ancien système (si c'est une chaîne)
                switch($projet->filtre_projet) {
                    case 'filtre_jeux':
                        $filtre_display = 'Jeux vidéo';
                        break;
                    case 'filtre_3d':
                        $filtre_display = '3D';
                        break;
                    case 'filtre_video':
                        $filtre_display = 'Vidéo';
                        break;
                    case 'filtre_web':
                        $filtre_display = 'Web';
                        break;
                    default:
                        $filtre_display = $projet->filtre_projet ? $projet->filtre_projet : 'Aucun filtre';
                }
            }
            
            $projets_data[$projet->id] = array(
                'titre_projet' => $projet->titre_projet,
                'description_projet' => $projet->description_projet ? $projet->description_projet : 'Aucune description',
                'cat_exposition_display' => $cat_display,
                'filtre_projet_display' => $filtre_display,
                'filtres_array' => $filtres_array, // Données brutes pour les checkboxes
                'video_projet' => $projet->video_projet ? $projet->video_projet : 'Aucune vidéo'
            );
        }
    }
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Données des étudiants et projets
        var etudiants_data = <?php echo json_encode($etudiants_data); ?>;
        var projets_data = <?php echo json_encode($projets_data); ?>;
        
        // === GESTION DES ÉTUDIANTS ===
        
        // Fonction pour afficher les informations d'un étudiant sélectionné
        $('#customize-control-etudiant_a_modifier select[data-customize-setting-link="etudiant_a_modifier"]').on('change', function() {
            var etudiant_id = $(this).val();
            
            if (etudiant_id && etudiants_data[etudiant_id]) {
                var etudiant = etudiants_data[etudiant_id];
                
                $('#customize-control-etudiant_info_nom .info-display').text(etudiant.nom);
                $('#customize-control-etudiant_info_annee .info-display').text(etudiant.annee_display);
                
                // Remplir les champs de modification
                $('#customize-control-modify_etudiant_nom input').val(etudiant.nom);
                $('#customize-control-modify_etudiant_annee select').val(etudiant.annee);
                
                // Afficher les sections d'info et de modification
                $('#customize-control-etudiant_info_nom, #customize-control-etudiant_info_annee').show();
                $('#customize-control-modify_etudiant_nom, #customize-control-modify_etudiant_annee, #customize-control-save_student_modify, #customize-control-delete_student').show();
            } else {
                // Cacher les sections d'info et de modification
                $('#customize-control-etudiant_info_nom, #customize-control-etudiant_info_annee').hide();
                $('#customize-control-modify_etudiant_nom, #customize-control-modify_etudiant_annee, #customize-control-save_student_modify, #customize-control-delete_student').hide();
            }
        });
        
        // Fonction pour le bouton Modifier l'étudiant
        $('#customize-control-save_student_modify .button-primary').on('click', function(e) {
            e.preventDefault();
            var confirm_msg = 'Êtes-vous sûr de vouloir modifier cet étudiant?';
            if (confirm(confirm_msg)) {
                var form_data = {
                    action: 'ctrltim_modify_student_ajax',
                    etudiant_id: $('#customize-control-etudiant_a_modifier select').val(),
                    modify_etudiant_nom: $('#customize-control-modify_etudiant_nom input').val(),
                    modify_etudiant_annee: $('#customize-control-modify_etudiant_annee select').val(),
                    security: '<?php echo wp_create_nonce("ctrltim_modify_student"); ?>'
                };
                
                $.post(ajaxurl, form_data, function(response) {
                    if (response.success) {
                        alert('Étudiant modifié avec succès!');
                        location.reload();
                    } else {
                        alert('Erreur lors de la modification: ' + response.data);
                    }
                });
            }
        });
        
        // Fonction pour le bouton Supprimer l'étudiant
        $('#customize-control-delete_student .button-secondary').on('click', function(e) {
            e.preventDefault();
            var confirm_msg = 'Êtes-vous sûr de vouloir supprimer cet étudiant? Cette action est irréversible.';
            if (confirm(confirm_msg)) {
                var form_data = {
                    action: 'ctrltim_delete_student_ajax',
                    etudiant_id: $('#customize-control-etudiant_a_modifier select').val(),
                    security: '<?php echo wp_create_nonce("ctrltim_delete_student"); ?>'
                };
                
                $.post(ajaxurl, form_data, function(response) {
                    if (response.success) {
                        alert('Étudiant supprimé avec succès!');
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression: ' + response.data);
                    }
                });
            }
        });
        
        // === GESTION DES PROJETS ===
        
        // Fonction pour afficher les informations d'un projet sélectionné
        $('#customize-control-projet_select_modifier select[data-customize-setting-link="projet_select_modifier"]').on('change', function() {
            var projet_id = $(this).val();
            
            if (projet_id && projets_data[projet_id]) {
                var projet = projets_data[projet_id];
                
                // Remplir directement les champs de modification avec les valeurs actuelles
                $('#customize-control-modify_projet_titre input').val(projet.titre_projet);
                $('#customize-control-modify_projet_description textarea').val(projet.description_projet !== 'Aucune description' ? projet.description_projet : '');
                $('#customize-control-modify_projet_video input').val(projet.video_projet !== 'Aucune vidéo' ? projet.video_projet : '');
                
                // Gérer le select de catégorie
                var cat_value = '';
                switch(projet.cat_exposition_display) {
                    case 'Arcade':
                        cat_value = 'cat_arcade';
                        break;
                    case 'Finissants':
                        cat_value = 'cat_finissants';
                        break;
                    case 'Terre':
                        cat_value = 'cat_terre';
                        break;
                }
                $('#customize-control-modify_projet_cat select').val(cat_value);
                
                // Gérer les checkboxes de filtres
                // Reset toutes les checkboxes
                $('#customize-control-modifier_filtre_projet_jeux input[type="checkbox"]').prop('checked', false);
                $('#customize-control-modifier_filtre_projet_3d input[type="checkbox"]').prop('checked', false);
                $('#customize-control-modifier_filtre_projet_video input[type="checkbox"]').prop('checked', false);
                $('#customize-control-modifier_filtre_projet_web input[type="checkbox"]').prop('checked', false);
                
                // Cocher les filtres actifs
                if (projet.filtres_array && Array.isArray(projet.filtres_array)) {
                    projet.filtres_array.forEach(function(filtre) {
                        if (filtre === 'filtre_jeux') {
                            $('#customize-control-modifier_filtre_projet_jeux input[type="checkbox"]').prop('checked', true);
                        } else if (filtre === 'filtre_3d') {
                            $('#customize-control-modifier_filtre_projet_3d input[type="checkbox"]').prop('checked', true);
                        } else if (filtre === 'filtre_video') {
                            $('#customize-control-modifier_filtre_projet_video input[type="checkbox"]').prop('checked', true);
                        } else if (filtre === 'filtre_web') {
                            $('#customize-control-modifier_filtre_projet_web input[type="checkbox"]').prop('checked', true);
                        }
                    });
                }
                
                // Afficher les sections de modification
                $('#customize-control-modify_projet_titre, #customize-control-modify_projet_description, #customize-control-modify_projet_cat, #customize-control-modifier_filtre_projet_jeux, #customize-control-modifier_filtre_projet_3d, #customize-control-modifier_filtre_projet_video, #customize-control-modifier_filtre_projet_web, #customize-control-modify_projet_video, #customize-control-save_project_modify, #customize-control-delete_project').show();
            } else {
                // Cacher les sections de modification
                $('#customize-control-modify_projet_titre, #customize-control-modify_projet_description, #customize-control-modify_projet_cat, #customize-control-modifier_filtre_projet_jeux, #customize-control-modifier_filtre_projet_3d, #customize-control-modifier_filtre_projet_video, #customize-control-modifier_filtre_projet_web, #customize-control-modify_projet_video, #customize-control-save_project_modify, #customize-control-delete_project').hide();
            }
        });
        
        // Fonction pour le bouton Modifier le projet
        $('#customize-control-save_project_modify .button-primary').on('click', function(e) {
            e.preventDefault();
            var confirm_msg = 'Êtes-vous sûr de vouloir modifier ce projet?';
            if (confirm(confirm_msg)) {
                // Collecter les filtres sélectionnés
                var filtres_selectionnes = [];
                if ($('#customize-control-modifier_filtre_projet_jeux input[type="checkbox"]').is(':checked')) {
                    filtres_selectionnes.push('filtre_jeux');
                }
                if ($('#customize-control-modifier_filtre_projet_3d input[type="checkbox"]').is(':checked')) {
                    filtres_selectionnes.push('filtre_3d');
                }
                if ($('#customize-control-modifier_filtre_projet_video input[type="checkbox"]').is(':checked')) {
                    filtres_selectionnes.push('filtre_video');
                }
                if ($('#customize-control-modifier_filtre_projet_web input[type="checkbox"]').is(':checked')) {
                    filtres_selectionnes.push('filtre_web');
                }
                
                var form_data = {
                    action: 'ctrltim_modify_project_ajax',
                    projet_id: $('#customize-control-projet_select_modifier select').val(),
                    modify_projet_titre: $('#customize-control-modify_projet_titre input').val(),
                    modify_projet_description: $('#customize-control-modify_projet_description textarea').val(),
                    modify_projet_cat: $('#customize-control-modify_projet_cat select').val(),
                    modify_projet_filtres: JSON.stringify(filtres_selectionnes),
                    modify_projet_video: $('#customize-control-modify_projet_video input').val(),
                    security: '<?php echo wp_create_nonce("ctrltim_modify_project"); ?>'
                };
                
                $.post(ajaxurl, form_data, function(response) {
                    if (response.success) {
                        alert('Projet modifié avec succès!');
                        location.reload();
                    } else {
                        alert('Erreur lors de la modification: ' + response.data);
                    }
                });
            }
        });
        
        // Fonction pour le bouton Supprimer le projet
        $('#customize-control-delete_project .button-secondary').on('click', function(e) {
            e.preventDefault();
            var confirm_msg = 'Êtes-vous sûr de vouloir supprimer ce projet? Cette action est irréversible.';
            if (confirm(confirm_msg)) {
                var form_data = {
                    action: 'ctrltim_delete_project_ajax',
                    projet_id: $('#customize-control-projet_select_modifier select').val(),
                    security: '<?php echo wp_create_nonce("ctrltim_delete_project"); ?>'
                };
                
                $.post(ajaxurl, form_data, function(response) {
                    if (response.success) {
                        alert('Projet supprimé avec succès!');
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression: ' + response.data);
                    }
                });
            }
        });
    });
    </script>
    <?php
}

// Ajouter le script au Customizer
add_action('customize_controls_print_footer_scripts', 'ctrltim_customizer_live_preview');
 




add_action('customize_register', 'theme_ctrltim_customize_register');

// Fonction pour créer la table des projets
function ctrltim_create_projects_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'ctrltim_projets';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        titre_projet varchar(255) NOT NULL,
        image_principale_projet varchar(500),
        description_projet text,
        video_projet varchar(500),
        images_galerie_projet text,
        cat_exposition varchar(50) DEFAULT 'cat_arcade',
        filtre_projet text DEFAULT NULL,
        date_creation datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Créer la table lors de l'activation du thème
add_action('after_switch_theme', 'ctrltim_create_projects_table');

// S'assurer que les tables sont créées lors de l'initialisation
add_action('init', function() {
    ctrltim_create_projects_table();
    ctrltim_create_students_table();
});

// Fonction pour sauvegarder un projet
function ctrltim_save_project_from_customizer() {
    // Vérifier si des données de projet ont été soumises
    $titre = get_theme_mod('titre_projet');
    $image_principale = get_theme_mod('image_principale_projet');
    $description = get_theme_mod('description_projet');
    $video = get_theme_mod('video_projet');
    
    // Collecter toutes les images de galerie
    $nombre_images = get_theme_mod('galerie_nombre_images', 3);
    $images_galerie = array();
    
    for ($i = 1; $i <= $nombre_images; $i++) {
        $image_url = get_theme_mod('image_galerie_projet_' . $i);
        if (!empty($image_url)) {
            $images_galerie[] = $image_url;
        }
    }
    
    // Convertir en JSON pour le stockage
    $images_galerie_json = json_encode($images_galerie);
    
    $cat_exposition = get_theme_mod('cat_exposition');
    
    // Collecter les filtres sélectionnés
    $filtres_selectionnes = array();
    if (get_theme_mod('filtre_projet_jeux')) {
        $filtres_selectionnes[] = 'filtre_jeux';
    }
    if (get_theme_mod('filtre_projet_3d')) {
        $filtres_selectionnes[] = 'filtre_3d';
    }
    if (get_theme_mod('filtre_projet_video')) {
        $filtres_selectionnes[] = 'filtre_video';
    }
    if (get_theme_mod('filtre_projet_web')) {
        $filtres_selectionnes[] = 'filtre_web';
    }
    
    // Convertir en JSON pour le stockage
    $filtres_json = json_encode($filtres_selectionnes);
    
    // Si au moins le titre est rempli, sauvegarder le projet
    if (!empty($titre)) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ctrltim_projets';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'titre_projet' => $titre,
                'image_principale_projet' => $image_principale,
                'description_projet' => $description,
                'video_projet' => $video,
                'images_galerie_projet' => $images_galerie_json,
                'cat_exposition' => $cat_exposition,
                'filtre_projet' => $filtres_json
            ),
            array(
                '%s',
                '%s', 
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            )
        );
        
        if ($result !== false) {
            // Réinitialiser les champs après sauvegarde
            remove_theme_mod('titre_projet');
            remove_theme_mod('image_principale_projet');
            remove_theme_mod('description_projet');
            remove_theme_mod('video_projet');
            
            // Réinitialiser toutes les images de galerie
            for ($i = 1; $i <= $nombre_images; $i++) {
                remove_theme_mod('image_galerie_projet_' . $i);
            }
            set_theme_mod('cat_exposition', 'cat_arcade'); // Remettre la valeur par défaut
            set_theme_mod('filtre_projet_jeux', false);
            set_theme_mod('filtre_projet_3d', false);
            set_theme_mod('filtre_projet_video', false);
            set_theme_mod('filtre_projet_web', false);
        }
    }
}

// Hook pour sauvegarder quand le customizer est sauvegardé
add_action('customize_save_after', 'ctrltim_save_project_from_customizer');

// Fonction pour récupérer les images de galerie d'un projet (convertit JSON en tableau)
function ctrltim_get_project_gallery_images($projet) {
    if (empty($projet->images_galerie_projet)) {
        return array();
    }
    
    $images = json_decode($projet->images_galerie_projet, true);
    return is_array($images) ? $images : array();
}

// Fonction pour récupérer tous les projets
function ctrltim_get_all_projects() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctrltim_projets';
    
    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date_creation DESC");
    
    return $results;
}

// Fonction pour récupérer un projet par ID
function ctrltim_get_project_by_id($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctrltim_projets';
    
    $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
    
    return $result;
}

// ========================
// FONCTIONS ÉTUDIANTS
// ========================

// Fonction pour créer la table des étudiants
function ctrltim_create_students_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'ctrltim_etudiants';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nom varchar(255) NOT NULL,
        image varchar(500),
        annee varchar(50),
        date_creation datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Créer la table des étudiants lors de l'activation du thème
add_action('after_switch_theme', 'ctrltim_create_students_table');

// Fonction pour sauvegarder un étudiant
function ctrltim_save_student_from_customizer() {
    // Vérifier si des données d'étudiant ont été soumises
    $nom = get_theme_mod('etudiant_nom');
    $image = get_theme_mod('etudiant_image');
    $annee = get_theme_mod('etudiant_annee');
    
    // Le nom ET l'année sont obligatoires pour sauvegarder l'étudiant
    if (!empty($nom) && !empty($annee)) {
        // S'assurer que la table existe
        ctrltim_create_students_table();
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'ctrltim_etudiants';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'nom' => $nom,
                'image' => $image,
                'annee' => $annee
            ),
            array(
                '%s',
                '%s',
                '%s'
            )
        );
        
        if ($result !== false) {
            // Réinitialiser les champs après sauvegarde
            remove_theme_mod('etudiant_nom');
            remove_theme_mod('etudiant_image');
            remove_theme_mod('etudiant_annee');
        }
    }
}

// Hook pour sauvegarder les étudiants quand le customizer est sauvegardé
add_action('customize_save_after', 'ctrltim_save_student_from_customizer');

// Fonction pour modifier/supprimer un étudiant
function ctrltim_modify_delete_student_from_customizer() {
    $etudiant_id = get_theme_mod('etudiant_select_modifier');
    $supprimer = get_theme_mod('action_supprimer_etudiant');
    
    if (!empty($etudiant_id)) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ctrltim_etudiants';
        
        if ($supprimer) {
            // SUPPRESSION
            $result = $wpdb->delete(
                $table_name,
                array('id' => $etudiant_id),
                array('%d')
            );
            
            if ($result !== false) {
                // Réinitialiser tous les champs de modification
                remove_theme_mod('etudiant_select_modifier');
                remove_theme_mod('modifier_etudiant_nom');
                remove_theme_mod('modifier_etudiant_image');
                remove_theme_mod('modifier_etudiant_annee');
                remove_theme_mod('action_supprimer_etudiant');
            }
        } else {
            // MODIFICATION
            $nouveau_nom = get_theme_mod('modifier_etudiant_nom');
            $nouvelle_image = get_theme_mod('modifier_etudiant_image');
            $nouvelle_annee = get_theme_mod('modifier_etudiant_annee');
            
            $data_update = array();
            $format_update = array();
            
            // Construire les données à mettre à jour seulement si les champs sont remplis
            if (!empty($nouveau_nom)) {
                $data_update['nom'] = $nouveau_nom;
                $format_update[] = '%s';
            }
            
            if (!empty($nouvelle_image)) {
                $data_update['image'] = $nouvelle_image;
                $format_update[] = '%s';
            }
            
            if (!empty($nouvelle_annee)) {
                $data_update['annee'] = $nouvelle_annee;
                $format_update[] = '%s';
            }
            
            // Mettre à jour seulement s'il y a des données à modifier
            if (!empty($data_update)) {
                $result = $wpdb->update(
                    $table_name,
                    $data_update,
                    array('id' => $etudiant_id),
                    $format_update,
                    array('%d')
                );
                
                if ($result !== false) {
                    // Réinitialiser les champs de modification après succès
                    remove_theme_mod('etudiant_select_modifier');
                    remove_theme_mod('modifier_etudiant_nom');
                    remove_theme_mod('modifier_etudiant_image');
                    remove_theme_mod('modifier_etudiant_annee');
                }
            }
        }
    }
}

// Hook pour modifier/supprimer les étudiants quand le customizer est sauvegardé
add_action('customize_save_after', 'ctrltim_modify_delete_student_from_customizer');

// Fonction pour récupérer tous les étudiants
function ctrltim_get_all_students() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctrltim_etudiants';
    
    // Vérifier si la table existe
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        // Créer la table si elle n'existe pas
        ctrltim_create_students_table();
        // Retourner un tableau vide car la table vient d'être créée
        return array();
    }
    
    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date_creation DESC");
    
    return $results ? $results : array();
}

// Fonction pour récupérer un étudiant par ID
function ctrltim_get_student_by_id($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctrltim_etudiants';
    
    $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
    
    return $result;
}

// ========================
// FONCTIONS MODIFICATION/SUPPRESSION PROJETS
// ========================

// Fonction pour modifier/supprimer un projet
function ctrltim_modify_delete_project_from_customizer() {
    $projet_id = get_theme_mod('projet_select_modifier');
    $supprimer = get_theme_mod('action_supprimer_projet');
    
    if (!empty($projet_id)) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ctrltim_projets';
        
        if ($supprimer) {
            // SUPPRESSION
            $result = $wpdb->delete(
                $table_name,
                array('id' => $projet_id),
                array('%d')
            );
            
            if ($result !== false) {
                // Réinitialiser tous les champs de modification
                remove_theme_mod('projet_select_modifier');
                remove_theme_mod('modifier_titre_projet');
                remove_theme_mod('modifier_image_principale_projet');
                remove_theme_mod('modifier_description_projet');
                remove_theme_mod('modifier_video_projet');
                remove_theme_mod('modifier_cat_exposition');
                remove_theme_mod('modifier_filtre_projet_jeux');
                remove_theme_mod('modifier_filtre_projet_3d');
                remove_theme_mod('modifier_filtre_projet_video');
                remove_theme_mod('modifier_filtre_projet_web');
                remove_theme_mod('action_supprimer_projet');
            }
        } else {
            // MODIFICATION
            $nouveau_titre = get_theme_mod('modifier_titre_projet');
            $nouvelle_image_principale = get_theme_mod('modifier_image_principale_projet');
            $nouvelle_description = get_theme_mod('modifier_description_projet');
            $nouvelle_video = get_theme_mod('modifier_video_projet');
            $nouvelle_cat_exposition = get_theme_mod('modifier_cat_exposition');
            
            // Collecter les filtres sélectionnés
            $filtres_selectionnes = array();
            if (get_theme_mod('modifier_filtre_projet_jeux')) {
                $filtres_selectionnes[] = 'filtre_jeux';
            }
            if (get_theme_mod('modifier_filtre_projet_3d')) {
                $filtres_selectionnes[] = 'filtre_3d';
            }
            if (get_theme_mod('modifier_filtre_projet_video')) {
                $filtres_selectionnes[] = 'filtre_video';
            }
            if (get_theme_mod('modifier_filtre_projet_web')) {
                $filtres_selectionnes[] = 'filtre_web';
            }
            
            $data_update = array();
            $format_update = array();
            
            // Construire les données à mettre à jour seulement si les champs sont remplis
            if (!empty($nouveau_titre)) {
                $data_update['titre_projet'] = $nouveau_titre;
                $format_update[] = '%s';
            }
            
            if (!empty($nouvelle_image_principale)) {
                $data_update['image_principale_projet'] = $nouvelle_image_principale;
                $format_update[] = '%s';
            }
            
            if (!empty($nouvelle_description)) {
                $data_update['description_projet'] = $nouvelle_description;
                $format_update[] = '%s';
            }
            
            if (!empty($nouvelle_video)) {
                $data_update['video_projet'] = $nouvelle_video;
                $format_update[] = '%s';
            }
            
            if (!empty($nouvelle_cat_exposition)) {
                $data_update['cat_exposition'] = $nouvelle_cat_exposition;
                $format_update[] = '%s';
            }
            
            if (!empty($filtres_selectionnes)) {
                $data_update['filtre_projet'] = json_encode($filtres_selectionnes);
                $format_update[] = '%s';
            }
            
            // Mettre à jour seulement s'il y a des données à modifier
            if (!empty($data_update)) {
                $result = $wpdb->update(
                    $table_name,
                    $data_update,
                    array('id' => $projet_id),
                    $format_update,
                    array('%d')
                );
                
                if ($result !== false) {
                    // Réinitialiser les champs de modification après succès
                    remove_theme_mod('projet_select_modifier');
                    remove_theme_mod('modifier_titre_projet');
                    remove_theme_mod('modifier_image_principale_projet');
                    remove_theme_mod('modifier_description_projet');
                    remove_theme_mod('modifier_video_projet');
                    remove_theme_mod('modifier_cat_exposition');
                    remove_theme_mod('modifier_filtre_projet_jeux');
                    remove_theme_mod('modifier_filtre_projet_3d');
                    remove_theme_mod('modifier_filtre_projet_video');
                    remove_theme_mod('modifier_filtre_projet_web');
                }
            }
        }
    }
}

// Hook pour modifier/supprimer les projets quand le customizer est sauvegardé
add_action('customize_save_after', 'ctrltim_modify_delete_project_from_customizer');

// ========================
// FONCTIONS AJAX POUR PROJETS
// ========================

// Fonction AJAX pour modifier un projet
function ctrltim_modify_project_ajax() {
    // Vérifier le nonce
    if (!wp_verify_nonce($_POST['security'], 'ctrltim_modify_project')) {
        wp_die('Erreur de sécurité');
    }
    
    $projet_id = intval($_POST['projet_id']);
    $nouveau_titre = sanitize_text_field($_POST['modify_projet_titre']);
    $nouvelle_description = sanitize_textarea_field($_POST['modify_projet_description']);
    $nouvelle_cat = sanitize_text_field($_POST['modify_projet_cat']);
    $filtres_json = sanitize_text_field($_POST['modify_projet_filtres']);
    $nouvelle_video = esc_url_raw($_POST['modify_projet_video']);
    
    if (empty($projet_id)) {
        wp_send_json_error('ID du projet manquant');
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctrltim_projets';
    
    // Vérifier que le projet existe
    $projet_existe = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE id = %d", 
        $projet_id
    ));
    
    if (!$projet_existe) {
        wp_send_json_error('Projet non trouvé');
    }
    
    // Préparer les données à mettre à jour
    $data_update = array();
    $format_update = array();
    
    if (!empty($nouveau_titre)) {
        $data_update['titre_projet'] = $nouveau_titre;
        $format_update[] = '%s';
    }
    
    if (!empty($nouvelle_description)) {
        $data_update['description_projet'] = $nouvelle_description;
        $format_update[] = '%s';
    }
    
    if (!empty($nouvelle_cat)) {
        $data_update['cat_exposition'] = $nouvelle_cat;
        $format_update[] = '%s';
    }
    
    if (!empty($filtres_json)) {
        $data_update['filtre_projet'] = $filtres_json;
        $format_update[] = '%s';
    }
    
    if (!empty($nouvelle_video)) {
        $data_update['video_projet'] = $nouvelle_video;
        $format_update[] = '%s';
    }
    
    // Mettre à jour
    if (!empty($data_update)) {
        $result = $wpdb->update(
            $table_name,
            $data_update,
            array('id' => $projet_id),
            $format_update,
            array('%d')
        );
        
        if ($result !== false) {
            wp_send_json_success('Projet modifié avec succès');
        } else {
            wp_send_json_error('Erreur lors de la modification');
        }
    } else {
        wp_send_json_error('Aucune donnée à modifier');
    }
}
add_action('wp_ajax_ctrltim_modify_project_ajax', 'ctrltim_modify_project_ajax');

// Fonction AJAX pour supprimer un projet
function ctrltim_delete_project_ajax() {
    // Vérifier le nonce
    if (!wp_verify_nonce($_POST['security'], 'ctrltim_delete_project')) {
        wp_die('Erreur de sécurité');
    }
    
    $projet_id = intval($_POST['projet_id']);
    
    if (empty($projet_id)) {
        wp_send_json_error('ID du projet manquant');
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'ctrltim_projets';
    
    $result = $wpdb->delete(
        $table_name,
        array('id' => $projet_id),
        array('%d')
    );
    
    if ($result !== false) {
        wp_send_json_success('Projet supprimé avec succès');
    } else {
        wp_send_json_error('Erreur lors de la suppression');
    }
}
add_action('wp_ajax_ctrltim_delete_project_ajax', 'ctrltim_delete_project_ajax');
