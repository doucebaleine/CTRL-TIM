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
                            wp.customize('cat_exposition').set(data.cat_exposition || '');
                            
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
                    wp.customize('cat_exposition').set('');
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

        // Gestion des médias sociaux (section séparée)
        wp.customize('media_a_modifier', function(control) {
            control.bind(function(value) {
                if (value && value !== '') {
                    $.post(ctrlTimData.ajaxurl, {
                        action: 'load_media_data',
                        media_id: value,
                        nonce: ctrlTimData.nonce
                    }, function(response) {
                        if (response.success) {
                            var data = response.data;
                            wp.customize('nom_media').set(data.nom || '');
                            wp.customize('image_media').set(data.image_media || '');
                            wp.customize('lien_media').set(data.lien || '');
                        }
                    });
                } else {
                    wp.customize('nom_media').set('');
                    wp.customize('image_media').set('');
                    wp.customize('lien_media').set('');
                }
            });
        });

        // Gestion des catégories (section séparée)
        wp.customize('categorie_a_modifier', function(control) {
            control.bind(function(value) {
                if (value && value !== '') {
                    $.post(ctrlTimData.ajaxurl, {
                        action: 'load_category_data',
                        category_id: value,
                        nonce: ctrlTimData.nonce
                    }, function(response) {
                        if (response.success) {
                            var data = response.data;
                            wp.customize('nom_categorie').set(data.nom || '');
                        }
                    });
                } else {
                    wp.customize('nom_categorie').set('');
                }
            });
        });

        // Category immediate-action handler removed; categories are managed via Customizer save or separate AJAX UI

        // (Le comportement d'exécution immédiate via la case 'trigger_media_action' a été retiré)

        // Utility: refresh choices for a given type and control id
        function refreshChoices(type, controlId) {
            $.post(ctrlTimData.ajaxurl, {
                action: 'get_choices',
                type: type,
                nonce: ctrlTimData.nonce
            }, function(response) {
                if (response.success) {
                    var choices = response.data || {};
                    var ctrl = wp.customize.control(controlId);
                    if (!ctrl || !ctrl.container) return;

                    // remember current value
                    var current = '';
                    try { current = wp.customize(controlId)() || ''; } catch(e) { current = ''; }

                    var $select = ctrl.container.find('select');
                    if ($select && $select.length) {
                        $select.empty();

                        // If server returned an ordered array (with {key,label}), respect that order.
                        if (Array.isArray(choices)) {
                            choices.forEach(function(item) {
                                if (!item || typeof item.key === 'undefined') return;
                                var label = item.label;
                                if (typeof label !== 'string') {
                                    if (label && typeof label.label === 'string') {
                                        label = label.label;
                                    } else {
                                        try { label = JSON.stringify(label); } catch(e) { label = String(label); }
                                    }
                                }
                                var $opt = $('<option>').attr('value', item.key).text(label);
                                $select.append($opt);
                            });
                        } else {
                            // Backwards-compatible: object map (may reorder numeric keys)
                            // Only add a default empty option if the server didn't provide one
                            var hasEmpty = Object.prototype.hasOwnProperty.call(choices, '');
                            if (!hasEmpty) {
                                $select.append($('<option>').attr('value','').text('-- Nouveau --'));
                            }
                            for (var id in choices) {
                                if (!choices.hasOwnProperty(id)) continue;
                                var lab = choices[id];
                                if (typeof lab !== 'string') {
                                    if (lab && typeof lab.label === 'string') {
                                        lab = lab.label;
                                    } else {
                                        try { lab = JSON.stringify(lab); } catch(e) { lab = String(lab); }
                                    }
                                }
                                var $opt = $('<option>').attr('value', id).text(lab);
                                $select.append($opt);
                            }
                        }

                        // re-select previous value if still present
                        if (current !== '') {
                            $select.val(current);
                        }
                    }
                }
            });
        }

        // When the Customizer is saved (Publish), refresh selects so new entries appear
        wp.customize.bind('saved', function() {
            // refresh medias, students and projects selects
            refreshChoices('medias', 'media_a_modifier');
            refreshChoices('etudiants', 'etudiant_a_modifier');
            refreshChoices('projets', 'projet_a_modifier');
            refreshChoices('categories', 'categorie_a_modifier');
            // Also refresh the category select used by projects
            refreshChoices('categories', 'cat_exposition');
        });
    });
})(jQuery);