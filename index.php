<?php get_header(); ?>
    <!-- header déplacé dans header.php -->
   <main>
      <!-- TITRE DE L'EXPOSITION -->
      <section class="acceuil__TextePrincipal">
         <div class="acceuil__TextePrincipal__Titre">
            <h1>CTRL+TIM</h1>
            <img class="effet-titre" src="<?php echo esc_url( get_template_directory_uri() . '/images/effetTitre.svg' ); ?>" aria-hidden="true" />
            <img class="effet-titre-deux" src="<?php echo esc_url( get_template_directory_uri() . '/images/effetTitre.svg' ); ?>" aria-hidden="true" />
         </div>
         
         <div class="description-exposition-principale">
         <h2>EXPOSITION MULTIMÉDIA</h2>
         <p>« CTRL+TIM » est une vitrine annuelle qui met en lumière la créativité, la diversité et la qualité des projets réalisés par les étudiants en Techniques d’intégration multimédia.
         </p>
         </div>
      </section>

         <!-- Galerie de posters (empilés) placée à droite du main -->
         <div class="galerieBoiteGenerale" aria-hidden="false">
            <div class="pile-affiches" aria-live="polite">
               <?php
               // Récupérer tous les projets et les afficher (pas de filtrage)
               $projects = function_exists('ctrltim_get_all_projets') ? ctrltim_get_all_projets() : array();

               // Classes initiales pour les trois cartes empilées (JS attend ces classes pour l'animation)
               $classes = array('affiche-haut', 'affiche-milieu', 'affiche-arriere');

            if (!empty($projects)) {
               // N'afficher que les projets dont la catégorie (cat_exposition) est l'ID 2 (Finissant)
               $display_index = 0;
               foreach ($projects as $proj) {
                  if (!isset($proj->cat_exposition)) continue;
                  if (intval($proj->cat_exposition) !== 2) continue;
                  $card_class = isset($classes[$display_index]) ? $classes[$display_index] : 'affiche-arriere';
                  $img_src = !empty($proj->image_projet) ? esc_url($proj->image_projet) : esc_url(get_template_directory_uri() . '/images/default.jpg');
                  $alt = !empty($proj->titre_projet) ? esc_attr($proj->titre_projet) : 'Projet';
                  // utiliser l'ID du projet comme data-id pour que le JS puisse s'y référer
                  echo '<div class="carte-affiche ' . $card_class . '" data-id="' . intval($proj->id) . '">';
                  echo '<button class="bouton-fermer-affiche" aria-label="Fermer">✕</button>';
                  echo '<img src="' . $img_src . '" alt="' . $alt . '" />';
                  echo '</div>';
                  $display_index++;
               }
            } else {
                   // Fallback : afficher les cartes statiques si aucun projet trouvé
                   ?>
                   <div class="carte-affiche affiche-haut" data-id="0">
                      <button class="bouton-fermer-affiche" aria-label="Fermer">✕</button>
                      <img src="<?php echo esc_url( get_template_directory_uri() . '/images/hero-logo.svg' ); ?>" alt="Logo" />
                   </div>

                   <div class="carte-affiche affiche-milieu" data-id="1">
                      <button class="bouton-fermer-affiche" aria-label="Fermer">✕</button>
                      <img src="<?php echo esc_url( get_template_directory_uri() . '/images/affiche1.svg' ); ?>" alt="Affiche jeu 1" />
                   </div>

                   <div class="carte-affiche affiche-arriere" data-id="2">
                      <button class="bouton-fermer-affiche" aria-label="Fermer">✕</button>
                      <img src="<?php echo esc_url( get_template_directory_uri() . '/images/affiche2.svg' ); ?>" alt="Affiche jeu 2" />
                   </div>
                   <?php
               }
               ?>
            </div>
         </div>

      </main>

<?php get_footer(); ?>