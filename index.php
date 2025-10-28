<?php get_header(); ?>
    <!-- header déplacé dans header.php -->
   <main>
      <!-- TITRE DE L'EXPOSITION -->
      <section class="titre">
         <h1>CTRL+TIM</h1>
         <img class="effetTitre" src="<?php echo esc_url( get_template_directory_uri() . '/images/effetTitre.svg' ); ?>" aria-hidden="true" />
         <img class="effetTitre2" src="<?php echo esc_url( get_template_directory_uri() . '/images/effetTitre.svg' ); ?>" aria-hidden="true" />
      </section>

      <!-- DESCRIPTION DE l'EXPOSITION -->
      <section class="descriptionExpoPrincipale">
         <h2>EXPOSITION MULTIMÉDIA</h2>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis harum laboriosam optio ipsam aut eveniet
            at neque error suscipit minima nostrum, necessitatibus rem. Cum sequi praesentium, blanditiis quam corrupti
            eveniet?</p>
      </section>
         <!-- Galerie de posters (empilés) placée à droite du main -->
         <div class="galerieBoiteGenerale" aria-hidden="false">
            <div class="poster-stack" aria-live="polite">
               <div class="poster-card poster-top" data-id="0">
                  <button class="poster-close" aria-label="Fermer">✕</button>
                  <img src="<?php echo esc_url( get_template_directory_uri() . '/images/logo.svg' ); ?>" alt="Logo" />
               </div>

               <div class="poster-card poster-mid" data-id="1">
                  <button class="poster-close" aria-label="Fermer">✕</button>
                  <img src="<?php echo esc_url( get_template_directory_uri() . '/images/effetTitre.svg' ); ?>" alt="Affiche jeu 1" />
               </div>

               <div class="poster-card poster-back" data-id="2">
                  <button class="poster-close" aria-label="Fermer">✕</button>
                  <img src="<?php echo esc_url( get_template_directory_uri() . '/images/effetTitre.svg' ); ?>" alt="Affiche jeu 2" />
               </div>
            </div>
         </div>

      </main>

<?php get_footer(); ?>