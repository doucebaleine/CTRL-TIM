<?php get_header(); ?>
    <!-- header déplacé dans header.php -->
   <main>
      <!-- TITRE DE L'EXPOSITION -->
      <section class="titre">
         <h1>CTRL+TIM</h1>
      </section>

      <!-- DESCRIPTION DE l'EXPOSITION -->
      <section class="descriptionExpoPrincipale">
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis harum laboriosam optio ipsam aut eveniet
            at neque error suscipit minima nostrum, necessitatibus rem. Cum sequi praesentium, blanditiis quam corrupti
            eveniet?</p>
      </section>

      <!-- GALERIE -->
      <section class="galerieBoiteGenerale">
         <?php
         // Récupérer tous les projets de la base de données
         $projets = ctrltim_get_all_projects();
         
         if (!empty($projets)) {
             foreach ($projets as $projet) {
                 // Décoder les filtres JSON
                 $filtres = json_decode($projet->filtres, true);
                 $filtres_text = '';
                 if (!empty($filtres)) {
                     $filtres_names = array();
                     foreach ($filtres as $filtre) {
                         switch ($filtre) {
                             case 'filtre_jeux': $filtres_names[] = 'Jeux vidéo'; break;
                             case 'filtre_3d': $filtres_names[] = '3D'; break;
                             case 'filtre_video': $filtres_names[] = 'Vidéo'; break;
                             case 'filtre_web': $filtres_names[] = 'Web'; break;
                         }
                     }
                     $filtres_text = implode(', ', $filtres_names);
                 }
                 
                 // Nettoyer la catégorie pour l'affichage
                 $categorie = str_replace('cat_', '', $projet->cat_exposition);
                 $categorie = ucfirst($categorie);
                 ?>
                 
                 <article class="projet-item">
                     <?php 
                     // Convertir l'ID de l'attachment en URL si c'est un ID
                     $image_url = '';
                     if (!empty($projet->image_projet)) {
                         if (is_numeric($projet->image_projet)) {
                             // C'est un ID d'attachment, récupérer l'URL
                             $image_url = wp_get_attachment_image_url($projet->image_projet, 'large');
                         } else {
                             // C'est déjà une URL
                             $image_url = $projet->image_projet;
                         }
                     }
                     ?>
                     
                     <?php if (!empty($image_url)): ?>
                         <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($projet->titre_projet); ?>" class="projet-image">
                     <?php endif; ?>
                     
                     <h3><?php echo esc_html($projet->titre_projet); ?></h3>
                     
                     <?php if (!empty($projet->description_projet)): ?>
                         <p class="projet-description"><?php echo esc_html($projet->description_projet); ?></p>
                     <?php endif; ?>
                     
                     <div class="projet-details">
                         <span class="projet-categorie">Catégorie: <?php echo esc_html($categorie); ?></span>
                         
                         <?php if (!empty($filtres_text)): ?>
                             <span class="projet-filtres">Filtres: <?php echo esc_html($filtres_text); ?></span>
                         <?php endif; ?>
                     </div>
                     
                     <?php if (!empty($projet->video_projet)): ?>
                         <div class="projet-video">
                             <a href="<?php echo esc_url($projet->video_projet); ?>" target="_blank">Voir la vidéo</a>
                         </div>
                     <?php endif; ?>
                 </article>
                 
                 <?php
             }
         } else {
             echo '<p>Aucun projet disponible pour le moment.</p>';
         }
         ?>
      </section>

      <!-- SECTION ÉTUDIANTS -->
      <section class="etudiants-section">
         <h2>Nos Étudiants</h2>
         
         <?php
         // Récupérer tous les étudiants de la base de données
         $etudiants = ctrltim_get_all_students();
         
         if (!empty($etudiants)) {
             foreach ($etudiants as $etudiant) {
                 // Convertir l'année pour l'affichage
                 $annee_display = '';
                 switch ($etudiant->annee) {
                     case 'premiere': $annee_display = '1ère année'; break;
                     case 'deuxieme': $annee_display = '2ème année'; break;
                     case 'troisieme': $annee_display = '3ème année'; break;
                     default: $annee_display = ucfirst($etudiant->annee);
                 }
                 ?>
                 
                 <article class="etudiant-item">
                     <?php 
                     // Convertir l'ID de l'attachment en URL si c'est un ID
                     $image_url = '';
                     if (!empty($etudiant->image_etudiant)) {
                         if (is_numeric($etudiant->image_etudiant)) {
                             // C'est un ID d'attachment, récupérer l'URL
                             $image_url = wp_get_attachment_image_url($etudiant->image_etudiant, 'medium');
                         } else {
                             // C'est déjà une URL
                             $image_url = $etudiant->image_etudiant;
                         }
                     }
                     ?>
                     
                     <?php if (!empty($image_url)): ?>
                         <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($etudiant->nom); ?>" class="etudiant-photo">
                     <?php endif; ?>
                     
                     <h3 class="etudiant-nom"><?php echo esc_html($etudiant->nom); ?></h3>
                     
                     <p class="etudiant-annee"><?php echo esc_html($annee_display); ?></p>
                    
                 </article>
                 
                 <?php
             }
         } else {
             echo '<p>Aucun étudiant disponible pour le moment.</p>';
         }
         ?>
      </section>

<?php get_footer(); ?>

   </main>
   <footer>
      <!-- MÉDIAS SOCIAUX -->
      <section class="mediasSociauxFooter">
         <p>Média 1</p>
         <p>Média 2</p>
      </section>

      <!-- DESCRIPTION DE L'ÉVÈNEMENT?? OU DE NOUS?? -->
      <section class="creditFooter">
         <p>Ce site a été fait par blablabla</p>
      </section>


      <!-- LOGO -->
      <section class="logoFooter">
         <p>LOGO</p>
      </section>

      <!-- ADRESSE -->
      <section class="adresseFooter">
         <p>ADRESSE</p>
      </section>

      <!-- LIEN VERS LE SITE WEB -->
      <section class="lienFooter">
         <p>LIEN SITE DU COLLÈGE</p>
      </section>
   </footer>
</body>

</html>