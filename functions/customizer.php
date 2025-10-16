<?php
/**
 * Configuration du WordPress Customizer pour CTRL-TIM
 * Gestion simple des projets et étudiants
 */

function theme_ctrltim_customize_register($wp_customize) {
    
    // =====================
    // PROJETS - LISTE ET GESTION
    // =====================
    
    $wp_customize->add_section('projets_section', array(
        'title' => __('Projets', 'theme_ctrltim'),
        'priority' => 30,
    ));

    // Liste des projets existants
    $projets = ctrltim_get_all_projects();
    $projets_list = "";
    if (empty($projets)) {
        $projets_list = "<p><em>Aucun projet pour le moment</em></p>";
    } else {
        $projets_list .= "<p><strong>Projets existants :</strong></p><ul style='margin-left: 20px;'>";
        foreach ($projets as $p) {
            $cat = str_replace('cat_', '', $p->cat_exposition);
            $projets_list .= "<li style='margin-bottom: 5px;'>" . esc_html($p->titre_projet) . " <span style='color: #666;'>(" . ucfirst($cat) . ")</span></li>";
        }
        $projets_list .= "</ul>";
    }

    $wp_customize->add_setting('projets_info', array('default' => '', 'sanitize_callback' => 'wp_kses_post'));
    $wp_customize->add_control('projets_info', array(
        'label' => __('Projets', 'theme_ctrltim'), 
        'section' => 'projets_section', 
        'type' => 'textarea',
        'description' => $projets_list,
        'input_attrs' => array('readonly' => 'readonly', 'style' => 'display:none;')
    ));

    // Sélection pour modifier/supprimer un projet existant
    $projets_choices = array('' => '+ Nouveau projet');
    if (!empty($projets)) {
        foreach ($projets as $p) {
            $cat = str_replace('cat_', '', $p->cat_exposition);
            $projets_choices[$p->id] = $p->titre_projet . " (" . ucfirst($cat) . ")";
        }
    }

    $wp_customize->add_setting('projet_a_modifier', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('projet_a_modifier', array(
        'label' => __('Projet à modifier/supprimer', 'theme_ctrltim'), 
        'section' => 'projets_section', 
        'type' => 'select', 
        'choices' => $projets_choices
    ));

    // Champs du formulaire projet (pour ajouter ou modifier)
    $wp_customize->add_setting('titre_projet', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('titre_projet', array(
        'label' => __('Titre du projet *', 'theme_ctrltim'), 
        'section' => 'projets_section', 
        'type' => 'text'
    ));

    $wp_customize->add_setting('description_projet', array('default' => '', 'sanitize_callback' => 'sanitize_textarea_field'));
    $wp_customize->add_control('description_projet', array(
        'label' => __('Description', 'theme_ctrltim'), 
        'section' => 'projets_section', 
        'type' => 'textarea'
    ));

    $wp_customize->add_setting('video_projet', array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_control('video_projet', array(
        'label' => __('Vidéo (URL)', 'theme_ctrltim'), 
        'section' => 'projets_section', 
        'type' => 'url'
    ));

    $wp_customize->add_setting('image_projet', array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'image_projet', array(
        'label' => __('Image principale', 'theme_ctrltim'), 
        'section' => 'projets_section'
    )));

    $wp_customize->add_setting('cat_exposition', array('default' => 'cat_arcade', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('cat_exposition', array(
        'label' => __('Catégorie', 'theme_ctrltim'), 
        'section' => 'projets_section', 
        'type' => 'select', 
        'choices' => array(
            'cat_arcade' => 'Arcade', 
            'cat_finissants' => 'Finissants', 
            'cat_terre' => 'Terre'
        )
    ));

    // Filtres (checkboxes)
    $wp_customize->add_setting('filtre_jeux', array('default' => false, 'sanitize_callback' => 'wp_validate_boolean'));
    $wp_customize->add_control('filtre_jeux', array(
        'label' => __('Jeux vidéo', 'theme_ctrltim'), 
        'section' => 'projets_section', 
        'type' => 'checkbox'
    ));

    $wp_customize->add_setting('filtre_3d', array('default' => false, 'sanitize_callback' => 'wp_validate_boolean'));
    $wp_customize->add_control('filtre_3d', array(
        'label' => __('3D', 'theme_ctrltim'), 
        'section' => 'projets_section', 
        'type' => 'checkbox'
    ));

    $wp_customize->add_setting('filtre_video', array('default' => false, 'sanitize_callback' => 'wp_validate_boolean'));
    $wp_customize->add_control('filtre_video', array(
        'label' => __('Vidéo', 'theme_ctrltim'), 
        'section' => 'projets_section', 
        'type' => 'checkbox'
    ));

    $wp_customize->add_setting('filtre_web', array('default' => false, 'sanitize_callback' => 'wp_validate_boolean'));
    $wp_customize->add_control('filtre_web', array(
        'label' => __('Web', 'theme_ctrltim'), 
        'section' => 'projets_section', 
        'type' => 'checkbox'
    ));

    // Action à effectuer (pour projets existants seulement)
    $wp_customize->add_setting('action_projet', array('default' => 'modifier', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('action_projet', array(
        'label' => __('Action', 'theme_ctrltim'), 
        'section' => 'projets_section', 
        'type' => 'select', 
        'choices' => array(
            'modifier' => 'Modifier', 
            'supprimer' => 'Supprimer'
        )
    ));

    // =====================
    // ÉTUDIANTS - LISTE ET GESTION
    // =====================
    
    $wp_customize->add_section('etudiants_section', array(
        'title' => __('Étudiants', 'theme_ctrltim'),
        'priority' => 40,
    ));

    // Liste des étudiants existants
    $etudiants = ctrltim_get_all_students();
    $etudiants_list = "";
    if (empty($etudiants)) {
        $etudiants_list = "<p><em>Aucun étudiant pour le moment</em></p>";
    } else {
        $etudiants_list .= "<p><strong>Étudiants existants :</strong></p><ul style='margin-left: 20px;'>";
        foreach ($etudiants as $e) {
            $annee = str_replace(array('premiere', 'deuxieme', 'troisieme'), array('1ère', '2ème', '3ème'), $e->annee);
            $etudiants_list .= "<li style='margin-bottom: 5px;'>" . esc_html($e->nom) . " <span style='color: #666;'>(" . $annee . ")</span></li>";
        }
        $etudiants_list .= "</ul>";
    }

    $wp_customize->add_setting('etudiants_info', array('default' => '', 'sanitize_callback' => 'wp_kses_post'));
    $wp_customize->add_control('etudiants_info', array(
        'label' => __('Étudiants', 'theme_ctrltim'), 
        'section' => 'etudiants_section', 
        'type' => 'textarea',
        'description' => $etudiants_list,
        'input_attrs' => array('readonly' => 'readonly', 'style' => 'display:none;')
    ));

    // Sélection pour modifier/supprimer un étudiant existant
    $etudiants_choices = array('' => '+ Nouvel étudiant');
    if (!empty($etudiants)) {
        foreach ($etudiants as $e) {
            $annee = str_replace(array('premiere', 'deuxieme', 'troisieme'), array('1ère', '2ème', '3ème'), $e->annee);
            $etudiants_choices[$e->id] = $e->nom . " (" . $annee . ")";
        }
    }

    $wp_customize->add_setting('etudiant_a_modifier', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('etudiant_a_modifier', array(
        'label' => __('Étudiant à modifier/supprimer', 'theme_ctrltim'), 
        'section' => 'etudiants_section', 
        'type' => 'select', 
        'choices' => $etudiants_choices
    ));

    // Champs du formulaire étudiant (pour ajouter ou modifier)
    $wp_customize->add_setting('nom_etudiant', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('nom_etudiant', array(
        'label' => __('Nom de l\'étudiant *', 'theme_ctrltim'), 
        'section' => 'etudiants_section', 
        'type' => 'text'
    ));

    $wp_customize->add_setting('image_etudiant', array('default' => '', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'image_etudiant', array(
        'label' => __('Photo de l\'étudiant', 'theme_ctrltim'), 
        'section' => 'etudiants_section'
    )));

    $wp_customize->add_setting('annee_etudiant', array('default' => 'premiere', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('annee_etudiant', array(
        'label' => __('Année', 'theme_ctrltim'), 
        'section' => 'etudiants_section', 
        'type' => 'select', 
        'choices' => array(
            'premiere' => '1ère', 
            'deuxieme' => '2ème', 
            'troisieme' => '3ème'
        )
    ));

    // Action à effectuer (pour étudiants existants seulement)
    $wp_customize->add_setting('action_etudiant', array('default' => 'modifier', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control('action_etudiant', array(
        'label' => __('Action', 'theme_ctrltim'), 
        'section' => 'etudiants_section', 
        'type' => 'select', 
        'choices' => array(
            'modifier' => 'Modifier', 
            'supprimer' => 'Supprimer'
        )
    ));
}

// Enregistrer la fonction de customizer
add_action('customize_register', 'theme_ctrltim_customize_register');

// JavaScript pour améliorer l'expérience utilisateur
function ctrltim_customizer_script() {
    ?>
    <script type="text/javascript">
    (function($) {
        wp.customize.bind('ready', function() {
            // Quand on sélectionne un projet à modifier, pré-remplir les champs
            wp.customize('projet_a_modifier', function(control) {
                control.bind(function(value) {
                    if (value && value !== '') {
                        // Faire un appel AJAX pour récupérer les données du projet
                        $.post(ajaxurl, {
                            action: 'load_project_data',
                            project_id: value,
                            nonce: '<?php echo wp_create_nonce('ctrltim_nonce'); ?>'
                        }, function(response) {
                            if (response.success) {
                                var data = response.data;
                                wp.customize('titre_projet').set(data.titre_projet || '');
                                wp.customize('description_projet').set(data.description_projet || '');
                                wp.customize('video_projet').set(data.video_projet || '');
                                wp.customize('image_projet').set(data.image_projet || '');
                                wp.customize('cat_exposition').set(data.cat_exposition || 'cat_arcade');
                                
                                // Filtres
                                var filtres = data.filtres || [];
                                wp.customize('filtre_jeux').set(filtres.indexOf('filtre_jeux') !== -1);
                                wp.customize('filtre_3d').set(filtres.indexOf('filtre_3d') !== -1);
                                wp.customize('filtre_video').set(filtres.indexOf('filtre_video') !== -1);
                                wp.customize('filtre_web').set(filtres.indexOf('filtre_web') !== -1);
                            }
                        });
                    } else {
                        // Vider les champs pour un nouveau projet
                        wp.customize('titre_projet').set('');
                        wp.customize('description_projet').set('');
                        wp.customize('video_projet').set('');
                        wp.customize('image_projet').set('');
                        wp.customize('cat_exposition').set('cat_arcade');
                        wp.customize('filtre_jeux').set(false);
                        wp.customize('filtre_3d').set(false);
                        wp.customize('filtre_video').set(false);
                        wp.customize('filtre_web').set(false);
                    }
                });
            });

            // Quand on sélectionne un étudiant à modifier, pré-remplir les champs
            wp.customize('etudiant_a_modifier', function(control) {
                control.bind(function(value) {
                    if (value && value !== '') {
                        // Faire un appel AJAX pour récupérer les données de l'étudiant
                        $.post(ajaxurl, {
                            action: 'load_student_data',
                            student_id: value,
                            nonce: '<?php echo wp_create_nonce('ctrltim_nonce'); ?>'
                        }, function(response) {
                            if (response.success) {
                                var data = response.data;
                                wp.customize('nom_etudiant').set(data.nom || '');
                                wp.customize('image_etudiant').set(data.image_etudiant || '');
                                wp.customize('annee_etudiant').set(data.annee || 'premiere');
                            }
                        });
                    } else {
                        // Vider les champs pour un nouvel étudiant
                        wp.customize('nom_etudiant').set('');
                        wp.customize('image_etudiant').set('');
                        wp.customize('annee_etudiant').set('premiere');
                    }
                });
            });
        });
    })(jQuery);
    </script>
    <?php
}
add_action('customize_controls_print_footer_scripts', 'ctrltim_customizer_script');

// AJAX pour charger les données d'un projet
function ctrltim_ajax_load_project_data() {
    if (!wp_verify_nonce($_POST['nonce'], 'ctrltim_nonce')) {
        wp_die('Erreur de sécurité');
    }
    
    global $wpdb;
    $project_id = intval($_POST['project_id']);
    $project = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ctrltim_projets WHERE id = %d", $project_id));
    
    if ($project) {
        $filtres = json_decode($project->filtre_projet, true) ?: array();
        
        wp_send_json_success(array(
            'titre_projet' => $project->titre_projet,
            'description_projet' => $project->description_projet,
            'video_projet' => $project->video_projet,
            'image_projet' => $project->image_projet,
            'cat_exposition' => $project->cat_exposition,
            'filtres' => $filtres
        ));
    } else {
        wp_send_json_error('Projet non trouvé');
    }
}
add_action('wp_ajax_load_project_data', 'ctrltim_ajax_load_project_data');

// AJAX pour charger les données d'un étudiant
function ctrltim_ajax_load_student_data() {
    if (!wp_verify_nonce($_POST['nonce'], 'ctrltim_nonce')) {
        wp_die('Erreur de sécurité');
    }
    
    global $wpdb;
    $student_id = intval($_POST['student_id']);
    $student = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}ctrltim_etudiants WHERE id = %d", $student_id));
    
    if ($student) {
        wp_send_json_success(array(
            'nom' => $student->nom,
            'image_etudiant' => $student->image_etudiant,
            'annee' => $student->annee
        ));
    } else {
        wp_send_json_error('Étudiant non trouvé');
    }
}
add_action('wp_ajax_load_student_data', 'ctrltim_ajax_load_student_data');

?>