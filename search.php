<?php get_header(); ?>
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
                    if ($post_type === 'ctrltim_projet' || $post_type === 'ctrltim_etudiant') {
                        echo '<li class="resultat-item custom-type">';
                        echo '<a class="lien-resultat" href="' . esc_url($guid) . '"><span class="titre-resultat">' . esc_html($title) . '</span></a>';
                        echo '<span class="type-resultat">[' . esc_html($post_type === 'ctrltim_projet' ? 'Projet' : 'Étudiant') . ']</span>';
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
