<?php get_header(); ?>
<?php get_arrierePlan(); ?>
<main>
    <section class="resultats-recherche">
        <h1>Résultats de recherche pour : <span class="mot-cle-recherche"><?php echo esc_html(get_search_query()); ?></span></h1>
        <?php if (have_posts()) : ?>
            <ul class="liste-resultats">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $post_type = get_post_type();
                    $guid = get_the_guid();
                    $title = get_the_title();
                    $desc = get_the_excerpt();
                    if ($post_type === 'ctrltim_projet') {
                        echo '<li class="resultat-item custom-type">';
                        echo '<a class="lien-resultat" href="' . esc_url($guid) . '"><span class="titre-resultat">' . esc_html($title) . '</span></a>';
                        echo '<span class="type-resultat">[Projet]</span>';
                        if ($desc) echo '<div class="desc-resultat">' . esc_html($desc) . '</div>';
                        echo '</li>';
                    } elseif ($post_type === 'ctrltim_etudiant') {
                        // Lorsqu'on cherche un étudiant, afficher le projet auquel il est assigné
                        $project_link = '';
                        $project_title = '';
                        if (function_exists('ctrltim_get_all_projets')) {
                            $projects = ctrltim_get_all_projets();
                            foreach ($projects as $proj) {
                                // Récupérer la liste des étudiants associés à ce projet (JSON/CSV) via helper si dispo
                                $etudiants = array();
                                if (function_exists('ctrltim_obtenir_etudiants_projet')) {
                                    $etudiants = ctrltim_obtenir_etudiants_projet(intval($proj->id));
                                } elseif (!empty($proj->etudiants_associes)) {
                                    // Fallback: tenter de parser JSON/CSV rudimentairement
                                    $raw = trim($proj->etudiants_associes);
                                    if ($raw !== '' && strtolower($raw) !== 'null') {
                                        $decoded = json_decode($raw, true);
                                        if (is_array($decoded)) {
                                            $etudiants = $decoded;
                                        } else {
                                            $etudiants = array_map('trim', explode(',', $raw));
                                        }
                                    }
                                }
                                // Normaliser pour comparer par nom
                                foreach ((array)$etudiants as $etu) {
                                    $nom = is_array($etu) ? ($etu['nom'] ?? '') : (is_object($etu) ? ($etu->nom ?? '') : (string)$etu);
                                    if ($nom && mb_strtolower($nom) === mb_strtolower($title)) {
                                        $project_title = $proj->titre_projet ?? '';
                                        $project_link = home_url('/?projet_id=' . intval($proj->id));
                                        break 2;
                                    }
                                }
                            }
                        }
                        echo '<li class="resultat-item custom-type">';
                        if ($project_link && $project_title) {
                            echo '<a class="lien-resultat" href="' . esc_url($project_link) . '"><span class="titre-resultat">' . esc_html($project_title) . '</span></a>';
                            echo '<span class="type-resultat">Projet</span>';
                        } else {
                            // Fallback: aucun projet trouvé, afficher l'étudiant comme avant
                            echo '<a class="lien-resultat" href="' . esc_url($guid) . '"><span class="titre-resultat">' . esc_html($title) . '</span></a>';
                            echo '<span class="type-resultat">Étudiant</span>';
                        }
                        if ($desc) echo '<div class="desc-resultat">' . esc_html($desc) . '</div>';
                        echo '</li>';
                    } else {
                        echo '<li class="resultat-item">';
                        echo '<a class="lien-resultat" href="' . get_permalink() . '"><span class="titre-resultat">' . esc_html($title) . '</span></a>';
                        if ($desc) echo '<div class="desc-resultat">' . esc_html($desc) . '</div>';
                        echo '</li>';
                    }
                    ?>
                <?php endwhile; ?>
            </ul>
        <?php else : ?>
            <p class="aucun-resultat">Aucun résultat trouvé.</p>
        <?php endif; ?>
    </section>
</main>
<?php get_footer(); ?>
