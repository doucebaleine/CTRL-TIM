<?php get_header(); ?>
    <!-- header déplacé dans header.php -->
   <main>
      <!-- TITRE DE L'EXPOSITION -->
      <section class="titre">
         <h1>CTRL+TIM</h1>
         <img class="effet-titre" src="<?php echo esc_url( get_template_directory_uri() . '/images/effetTitre.svg' ); ?>" aria-hidden="true" />
         <img class="effet-titre-deux" src="<?php echo esc_url( get_template_directory_uri() . '/images/effetTitre.svg' ); ?>" aria-hidden="true" />
         <div class="description-exposition-principale">
         <h2>EXPOSITION MULTIMÉDIA</h2>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis harum laboriosam optio ipsam aut eveniet
            at neque error suscipit minima nostrum, necessitatibus rem. Cum sequi praesentium, blanditiis quam corrupti
            eveniet?</p>
         </div>
      </section>

         <!-- Galerie de posters (empilés) placée à droite du main -->
         <div class="galerieBoiteGenerale" aria-hidden="false">
            <div class="pile-affiches" aria-live="polite">
               <?php
               // Récupérer tous les projets et filtrer ceux dont la catégorie (libellé) est 'finissant'
               $projects = function_exists('ctrltim_get_all_projets') ? ctrltim_get_all_projets() : array();
               $finissants = array();
               if (!empty($projects)) {
                   foreach ($projects as $p) {
                       $cat_label = '';
                       if (isset($p->cat_exposition)) {
                           if (is_numeric($p->cat_exposition) && function_exists('ctrltim_get_nom_categorie')) {
                               $cat_label = ctrltim_get_nom_categorie(intval($p->cat_exposition));
                           } else {
                               $cat_label = is_string($p->cat_exposition) ? $p->cat_exposition : '';
                           }
                       }
                       if (strtolower(trim($cat_label)) === 'finissant') {
                           $finissants[] = $p;
                       }
                   }
               }

               // Classes initiales pour les trois cartes empilées (JS attend ces classes pour l'animation)
               $classes = array('affiche-haut', 'affiche-milieu', 'affiche-arriere');

               if (!empty($finissants)) {
                   foreach ($finissants as $index => $proj) {
                       $card_class = isset($classes[$index]) ? $classes[$index] : 'affiche-arriere';
                       $img_src = !empty($proj->image_projet) ? esc_url($proj->image_projet) : esc_url(get_template_directory_uri() . '/images/default.jpg');
                       $alt = !empty($proj->titre_projet) ? esc_attr($proj->titre_projet) : 'Projet';
                       // utiliser l'ID du projet comme data-id pour que le JS puisse s'y référer
                       echo '<div class="carte-affiche ' . $card_class . '" data-id="' . intval($proj->id) . '">';
                       echo '<button class="bouton-fermer-affiche" aria-label="Fermer">✕</button>';
                       echo '<img src="' . $img_src . '" alt="' . $alt . '" />';
                       echo '</div>';
                   }
               } else {
                   // Fallback : afficher les cartes statiques si aucun projet "finissant" trouvé
                   ?>
                   <div class="carte-affiche affiche-haut" data-id="0">
                      <button class="bouton-fermer-affiche" aria-label="Fermer">✕</button>
                      <img src="<?php echo esc_url( get_template_directory_uri() . '/images/logo.svg' ); ?>" alt="Logo" />
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