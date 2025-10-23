(function($) {
    // Variables globales pour stocker les valeurs sélectionnées
    var selectedProjectId = '';
    var selectedStudentId = '';
    var selectedAction = 'ajouter';

    // Fonction pour mettre à jour l'affichage des étudiants associés
    function mettreAJourListeEtudiants(studentsHtml) {
        var control = wp.customize.control('etudiants_associes_info');
        
        if (control && control.container) {
            var description = control.container.find('.description');
            
            if (studentsHtml && studentsHtml.trim() !== '') {
                description.html(studentsHtml);
            } else {
                description.html('<p><em>Aucun étudiant associé pour le moment</em></p>');
            }
        }
    }

    wp.customize.bind('ready', function() {
        // Suivre les changements d'étudiant sélectionné
        wp.customize('etudiants_selectionnes', function(control) {
            control.bind(function(value) {
                selectedStudentId = value;
            });
        });

        // Suivre les changements d'action
        wp.customize('action_etudiant_projet', function(control) {
            control.bind(function(value) {
                selectedAction = value;
            });
        });

        // Suivre les changements de projet et charger ses données
        wp.customize('projet_a_modifier', function(control) {
            control.bind(function(value) {
                selectedProjectId = value;
                
                if (value && value !== '') {
                    // Charger les données du projet
                    $.post(ctrlTimData.ajaxurl, {
                        action: 'load_project_data',
                        project_id: value,
                        nonce: ctrlTimData.nonce
                    }, function(response) {
                        if (response.success) {
                            var data = response.data;
                            wp.customize('titre_projet').set(data.titre_projet || '');
                            wp.customize('description_projet').set(data.description_projet || '');
                            wp.customize('video_projet').set(data.video_projet || '');
                            wp.customize('image_projet').set(data.image_projet || '');
                            wp.customize('lien_projet').set(data.lien || '');
                            wp.customize('cours_projet').set(data.cours || '');
                            wp.customize('cat_exposition').set(data.cat_exposition || 'cat_premiere_annee');
                            
                            // Filtres
                            var filtres = data.filtres || [];
                            wp.customize('filtre_jeux').set(filtres.indexOf('filtre_jeux') !== -1);
                            wp.customize('filtre_3d').set(filtres.indexOf('filtre_3d') !== -1);
                            wp.customize('filtre_video').set(filtres.indexOf('filtre_video') !== -1);
                            wp.customize('filtre_web').set(filtres.indexOf('filtre_web') !== -1);
                            
                            // Étudiants associés
                            mettreAJourListeEtudiants(data.etudiants_associes || '');
                        }
                    });
                } else {
                    // Vider les champs pour un nouveau projet
                    wp.customize('titre_projet').set('');
                    wp.customize('description_projet').set('');
                    wp.customize('video_projet').set('');
                    wp.customize('image_projet').set('');
                    wp.customize('lien_projet').set('');
                    wp.customize('cours_projet').set('');
                    wp.customize('cat_exposition').set('cat_premiere_annee');
                    wp.customize('filtre_jeux').set(false);
                    wp.customize('filtre_3d').set(false);
                    wp.customize('filtre_video').set(false);
                    wp.customize('filtre_web').set(false);
                    mettreAJourListeEtudiants('');
                }
            });
        });

        // Gérer l'action d'ajout/suppression d'étudiant
        wp.customize('trigger_student_action', function(control) {
            control.bind(function(value) {
                if (value === true) {
                    if (selectedProjectId && selectedStudentId) {
                        $.post(ctrlTimData.ajaxurl, {
                            action: 'manage_project_students',
                            project_id: selectedProjectId,
                            student_id: selectedStudentId,
                            student_action: selectedAction,
                            nonce: ctrlTimData.nonce
                        }, function(response) {
                            if (response.success) {
                                // Recharger les données du projet
                                $.post(ctrlTimData.ajaxurl, {
                                    action: 'load_project_data',
                                    project_id: selectedProjectId,
                                    nonce: ctrlTimData.nonce
                                }, function(projectResponse) {
                                    if (projectResponse.success) {
                                        mettreAJourListeEtudiants(projectResponse.data.etudiants_associes || '');
                                    }
                                });
                                
                                wp.customize('etudiants_selectionnes').set('');
                                selectedStudentId = '';
                                alert('Action effectuée avec succès !');
                            } else {
                                alert('Erreur: ' + response.data);
                            }
                            wp.customize('trigger_student_action').set(false);
                        });
                    } else {
                        alert('Veuillez sélectionner un projet et un étudiant.');
                        wp.customize('trigger_student_action').set(false);
                    }
                }
            });
        });

        // Gestion des étudiants (section séparée)
        wp.customize('etudiant_a_modifier', function(control) {
            control.bind(function(value) {
                if (value && value !== '') {
                    $.post(ctrlTimData.ajaxurl, {
                        action: 'load_student_data',
                        student_id: value,
                        nonce: ctrlTimData.nonce
                    }, function(response) {
                        if (response.success) {
                            var data = response.data;
                            wp.customize('nom_etudiant').set(data.nom || '');
                            wp.customize('image_etudiant').set(data.image_etudiant || '');
                            wp.customize('annee_etudiant').set(data.annee || 'premiere');
                        }
                    });
                } else {
                    wp.customize('nom_etudiant').set('');
                    wp.customize('image_etudiant').set('');
                    wp.customize('annee_etudiant').set('premiere');
                }
            });
        });
    });
})(jQuery);