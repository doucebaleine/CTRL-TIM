<?php
   /*
   Template Name: À Propos
   */
   //a-propos
?>

   <?php get_header(); ?>
   
   <main>
      <!-- Titre de la page -->
      <section class="titreApropos">
         <h2>À Propos</h2>
      </section>

      <!-- Notre but -->
      <section class="infosaPropos">
         <h3> Notre But</h3>
         <div class="infosaPropos__textBut">
            <p>
               Notre équipe est composée de cinq étudiants en Technique d'intégration multimédia au Collège de
               Maisonneuve. Nous avons créé ce site web dans le cadre d'un projet scolaire visant à promouvoir 
               l'exposition de fin d'année. Notre objectif est de fournir une plateforme en ligne pour présenter
               et archiver les projets des étudiants fait à travers les années.
            </p>
         </div>
         
      </section>

      <!-- Les profs pivots -->
      <section class="profsPivots">
         <h3> Les profs pivots</h3>
         <!-- boite Professeurs -->
         <div class="profsPivots__profs">
            <!-- Professeur 1 -->
            <div class="profsPivots__profs__prof1">
               <!-- Image Professeur 1 -->
               <img src="../images/DavidRoss.png" alt="David Ross" class="profsPivots__profs__prof1__img_prof1">
               <!-- Info Professeur 1 -->
               <div class="profsPivots__profs__prof1__infoProf1">
                  <p>David Ross</p>
                  <p>bureau: F-3004</p>
                  <p>dross@cmaisonneuve.qc.ca</p>
               </div>
            </div>
            <!-- Professeur 2 -->
            <div class="profsPivots__profs__prof2">
               <!-- Image Professeur 2 -->
               <img src="../images/GregoryBony.png" alt="Grégory Bony" class="profsPivots__profs__prof2__img_prof2">
               <!-- Info Professeur 2 -->
               <div class="profsPivots__profs__prof2__infoProf2">
                  <p>Grégory Bony</p>
                  <p> bureau: F-3004</p>
                  <p>gbony@cmaisonneuve.qc.ca</p>
               </div>
            </div>
         </div>
      </section>



      <!-- Liens utiles -->
      <section class="liensApropos">
         <h3>Liens utiles</h3>
         <ul class="liensApropos__listelinks">
            <a href="https://www.cmaisonneuve.qc.ca/programme/integration-multimedia/">Technique d'intégration Multimédia</a>
            <a href="https://sites.google.com/view/centre-aide-tim/accueil">Centre d'aide TIM</a>
         </ul>
      </section>

   </main>
   <footer>
     <?php get_footer(); ?>
   </footer>
</body>

</html>