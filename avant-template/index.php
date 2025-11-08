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
            </div>
         </div>

      </main>

<?php get_footer(); ?>