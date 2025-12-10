<?php

/*
Template Name: Galerie
*/
//galerie
?>

<?php get_header(); ?>
<?php get_arrierePlan(); ?>

<main class="pageGalerie">
  <h1 class="pageGalerie__titre">Galerie</h1>

  <div class="pageGalerie__filter-bar">
    <?php
    // récupérer la catégorie sélectionnée depuis l'URL
    $selected_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
    ?>
    <button class="pageGalerie__filter-bar__filter-btn <?php echo empty($selected_category) ? 'active' : ''; ?>">Tous</button>
    <?php
    $cats = function_exists('ctrltim_get_all_categories') ? ctrltim_get_all_categories() : array();
    if (!empty($cats)) {
            // Définir l'ordre souhaité des catégories
            $ordre_categories = array('Finissant•e•s', 'Arcade', 'Graphisme');
            
            // Trier les catégories selon l'ordre défini
            usort($cats, function($a, $b) use ($ordre_categories) {
                $nom_a = isset($a->nom) ? $a->nom : (isset($a->name) ? $a->name : '');
                $nom_b = isset($b->nom) ? $b->nom : (isset($b->name) ? $b->name : '');
                
                $pos_a = array_search($nom_a, $ordre_categories);
                $pos_b = array_search($nom_b, $ordre_categories);
                
                // Si la catégorie n'est pas dans l'ordre défini, la mettre à la fin
                if ($pos_a === false) $pos_a = 999;
                if ($pos_b === false) $pos_b = 999;
                
                return $pos_a - $pos_b;
            });
            
      foreach ($cats as $c) {
        // Use the category name as the button label so filter.js (which matches by text) works correctly
        $label = isset($c->nom) ? $c->nom : (isset($c->name) ? $c->name : '');
        if (empty($label)) continue;
        $is_active = ($label === $selected_category) ? ' active' : '';
        echo '<button class="pageGalerie__filter-bar__filter-btn' . $is_active . '">' . esc_html($label) . '</button>';
      }
    }
    ?>
  </div>

  <div class="pageGalerie__filter">
    <h3 class="pageGalerie__filter__subtitle">
      Tous
    </h3>
    <p class="pageGalerie__filter__description">
      <span class="pageGalerie__filter__debutDescription">Explorez l’ensemble des projets réalisés par les étudiants de la Technique d’intégration multimédia. Jeux</span>
      <span class="pageGalerie__filter__lirePlus">vidéo, sites web, créations interactives et projets artistiques : découvrez la diversité et le talent qui animent chaque cohorte du TIM.</span>
      <span id="btnLirePlus">...Lire plus</span>
    </p>

  </div>

  <section class="pageGalerie__dropdown">
    <div class="pageGalerie__dropdown__select">
        <span class="pageGalerie__dropdown__selected">Tous</span>
      <div class="pageGalerie__dropdown__caret"></div>
    </div>
    <ul class="pageGalerie__dropdown__menu">
        <li class="active" data-filter="all">Tous</li>
      <li data-filter="jeux">Jeux Video</li>
      <li data-filter="3d">3D</li>
      <li data-filter="web">Web</li>
      <li data-filter="video">Video</li>
    </ul>
  </section>

  <section class="pageGalerie__galerieProjets">
    <div class="pageGalerie__galerieProjets__projets">

      <?php
      $projets = function_exists('ctrltim_get_all_projets') ? ctrltim_get_all_projets() : array();
        
        // Shuffle projects to display them randomly
        if (!empty($projets)) {
            shuffle($projets);
        }
        
      if (!empty($projets)) :
        foreach ($projets as $p) :
          $img = !empty($p->image_projet) ? $p->image_projet : get_template_directory_uri() . '/images/default.jpg';
          $titre = !empty($p->titre_projet) ? $p->titre_projet : __('Projet sans titre', 'ctrltim');

          // Get category label
          $cat_label = '';
          if (function_exists('ctrltim_get_category_label')) {
            $cat_label = ctrltim_get_category_label($p->cat_exposition);
          } elseif (function_exists('ctrltim_get_nom_categorie')) {
            $cat_label = ctrltim_get_nom_categorie(intval($p->cat_exposition));
          }

          // Get filters (from filtres JSON field)
          $filtres_array = array();
          if (!empty($p->filtres)) {
            $decoded = json_decode($p->filtres, true);
            if (is_array($decoded)) {
              $filtres_array = $decoded;
            }
          }

          // Map filter keys to simpler values
          $filter_map = array(
            'filtre_jeux' => 'jeux',
            'filtre_3d' => '3d',
            'filtre_video' => 'video',
            'filtre_web' => 'web'
          );

          $mapped_filters = array();
          foreach ($filtres_array as $f) {
            if (isset($filter_map[$f])) {
              $mapped_filters[] = $filter_map[$f];
            }
          }

          $data_filters = !empty($mapped_filters) ? implode(',', $mapped_filters) : '';

          // Prepare data-category
          $data_category = '';
          if (!empty($cat_label)) {
            $data_category = $cat_label;
          } elseif (!empty($p->cat_exposition) && !is_numeric($p->cat_exposition)) {
            $data_category = $p->cat_exposition;
          }
      ?>

          <div class="pageGalerie__galerieProjets__projets__projet"
            data-id="<?php echo intval($p->id); ?>"
            data-category="<?php echo esc_attr($data_category); ?>"
            data-filters="<?php echo esc_attr($data_filters); ?>">
            <a class="pageGalerie__galerieProjets__projets__projet__link"
              href="<?php echo esc_url(add_query_arg('project_id', intval($p->id), home_url('/index.php/projet/'))); ?>">
              <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($titre); ?>">
              <div class="pageGalerie__galerieProjets__projets__projet__overlay"></div>
              <div class="pageGalerie__galerieProjets__projets__projet__info">
                <span class="pageGalerie__galerieProjets__projets__projet__info__titre"
                  data-category="<?php echo esc_attr($data_category); ?>">
                  <?php echo esc_html($cat_label); ?>
                </span>
                <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">
                  <?php echo esc_html($titre); ?>
                </h3>
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
</main>

<?php get_footer(); ?>