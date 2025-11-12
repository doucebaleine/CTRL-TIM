<?php

/*
Template Name: Galerie
*/
//galerie
?>

<?php get_header(); ?>

<body>
  <main class="pageGalerie">
    <h1 class="pageGalerie__titre">Galerie</h1>

    <div class="pageGalerie__filter-bar">
      <button class="pageGalerie__filter-bar__filter-btn active">Tous</button>
      <?php
  $cats = function_exists('ctrltim_get_all_categories') ? ctrltim_get_all_categories() : array();
        if (!empty($cats)) {
            foreach ($cats as $c) {
                // Use the category name as the button label so filter.js (which matches by text) works correctly
                $label = isset($c->nom) ? $c->nom : (isset($c->name) ? $c->name : '');
                if (empty($label)) continue;
                echo '<button class="pageGalerie__filter-bar__filter-btn">' . esc_html($label) . '</button>';
            }
        }
      ?>
    </div>

    <div class="pageGalerie__filter">
      <h3 class="pageGalerie__filter__subtitle">
      Tous
      </h3>
      <p class="pageGalerie__filter__description">
        Explorez l’ensemble des projets réalisés par les étudiants de la Technique d’intégration multimédia. Jeux vidéo, sites web, créations interactives et projets artistiques : découvrez la diversité et le talent qui animent chaque cohorte du TIM.
      </p>
    </div>
    
    <script src="<?php echo get_template_directory_uri(); ?>/js/description.js"></script>

    <section class="pageGalerie__dropdown">
      <div class="pageGalerie__dropdown__select">
        <span class="pageGalerie__dropdown__selected">All</span>
        <div class="pageGalerie__dropdown__caret"></div>
      </div>
      <ul class="pageGalerie__dropdown__menu">
        <li class="active">All</li>
        <li>Jeux Video</li>
        <li>3D</li>
        <li>Animation 3D</li>
        <li>Web</li>
        <li>Video</li>
      </ul>
    </section>

    <section class="pageGalerie__galerieProjets">
      <div class="pageGalerie__galerieProjets__projets">

        <?php
    // Récupérer tous les projets depuis la base de données
    $projets = function_exists('ctrltim_get_all_projets') ? ctrltim_get_all_projets() : array();
    if (!empty($projets)) :
      foreach ($projets as $p) :
                // Image, titre et catégorie
                $img = !empty($p->image_projet) ? $p->image_projet : get_template_directory_uri() . '/images/default.jpg';
                $titre = !empty($p->titre_projet) ? $p->titre_projet : __('Projet sans titre', 'ctrltim');
                $cat_label = '';
                if (function_exists('ctrltim_get_category_label')) {
                    $cat_label = ctrltim_get_category_label($p->cat_exposition);
        } elseif (function_exists('ctrltim_get_nom_categorie')) {
          $cat_label = ctrltim_get_nom_categorie(intval($p->cat_exposition));
                }
        ?>

    <?php
        // Ensure the data-category attribute contains the human-readable category label
        $data_category = '';
        if (!empty($cat_label)) {
          $data_category = $cat_label;
        } elseif (!empty($p->cat_exposition) && !is_numeric($p->cat_exposition)) {
          // fallback to raw string value (legacy)
          $data_category = $p->cat_exposition;
        }
    ?>
     <div class="pageGalerie__galerieProjets__projets__projet"
       data-id="<?php echo intval($p->id); ?>"
       data-category="<?php echo esc_attr($data_category); ?>">
          <a class="pageGalerie__galerieProjets__projets__projet__link" href="<?php echo esc_url( add_query_arg( 'project_id', intval($p->id), home_url('/projet/') ) ); ?>">
            <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($titre); ?>">
            <div class="pageGalerie__galerieProjets__projets__projet__info">
              <span class="pageGalerie__galerieProjets__projets__projet__info__titre"><?php echo esc_html($cat_label); ?></span>
              <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre"><?php echo esc_html($titre); ?></h3>
            </div>
          </a>
        </div>

        <?php
            endforeach;
        else :
        ?>
        <p><?php echo esc_html__('Aucun projet trouvé.', 'ctrltim'); ?></p>
        <?php endif; ?>

      </div>
    </section>

    <script src="<?php echo get_template_directory_uri(); ?>/js/galerie.js"></script>

  </main>
  <script src="<?php echo get_template_directory_uri(); ?>/js/filter.js"></script>
</body>

<?php get_footer(); ?>