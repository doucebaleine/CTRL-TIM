<?php get_header(); ?>
<main>
    <section class="resultats-recherche">
        <h1>Résultats de recherche pour : <span class="mot-cle-recherche"><?php echo esc_html(get_search_query()); ?></span></h1>
        <ul class="liste-resultats">
            <?php
            $search_query = mb_strtolower(trim(get_search_query()));
            $results_found = false;

            if (function_exists('ctrltim_get_all_projets')) {
                $all_projects = ctrltim_get_all_projets();

                foreach ((array) $all_projects as $proj) {

                    // 1) Récupérer titre et description
                    $proj_title = $proj->titre_projet ?? '';
                    $proj_desc  = $proj->description_projet ?? '';
                    $nom_categorie = '';
                    if (!empty($proj->cat_exposition)) {
                        $nom_categorie = ctrltim_get_nom_categorie($proj->cat_exposition);
                    }

                    // 2) Récupérer les étudiants associés VIA ta fonction officielle
                    $etudiants = ctrltim_get_etudiants_for_projet($proj->id);
                    $etudiants_noms = '';

                    if (!empty($etudiants) && is_array($etudiants)) {
                        foreach ($etudiants as $etu) {
                            // S'adapte automatiquement à ta table
                            $nom = ($etu->prenom ?? '') . ' ' . ($etu->nom ?? '');
                            $etudiants_noms .= ' ' . $nom;
                        }
                    }

                    // 3) Appliquer recherche
                    if (!empty($search_query)) {

                        $title_match    = mb_stripos($proj_title, $search_query) !== false;
                        $student_match  = mb_stripos($etudiants_noms, $search_query) !== false;

                        // Si rien ne correspond → ignorer ce projet
                        if (!($title_match || $student_match)) {
                            continue;
                        }
                    }

                    $results_found = true;

                    // ----- Construction du lien vers la page projet -----

                    $page_url = '';
                    $pages_by_template = get_pages(array(
                        'meta_key'   => '_wp_page_template',
                        'meta_value' => 'template-projet.php',
                        'post_type'  => 'page',
                        'number'     => 1,
                    ));

                    if (!empty($pages_by_template)) {
                        $page_url = get_permalink($pages_by_template[0]->ID);
                    }

                    if (empty($page_url)) {
                        $page_obj = get_page_by_path('projet');
                        if ($page_obj) $page_url = get_permalink($page_obj->ID);
                    }

                    if (empty($page_url)) {
                        $page_url = home_url('/projet/');
                    }

                    $project_link = add_query_arg('project_id', intval($proj->id), $page_url);

                    // ------ AFFICHAGE ------
                    echo '<li class="resultat-item custom-type">';
                    echo '<a class="lien-resultat" href="' . esc_url($project_link) . '">
                                <span class="titre-resultat">' . esc_html($proj_title) . '</span>
                            </a>';
                    
                    $slug_categorie = strtolower(str_replace(' ', '-', $nom_categorie));

                    echo '<span class="type-resultat cat-' . esc_attr($slug_categorie) . '">[' . esc_html($nom_categorie) . ']</span>';

                    if (!empty($proj_desc)) {
                        echo '<div class="desc-resultat">' . esc_html(wp_trim_words($proj_desc, 20)) . '</div>';
                    }

                    echo '</li>';
                }
            }

            if (!$results_found) {
                echo '<p class="aucun-resultat">Aucun résultat trouvé.</p>';
            }
            ?>
        </ul>
    </section>
</main>
<?php get_footer(); ?>