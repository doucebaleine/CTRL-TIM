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

      <!-- les eleves -->
      <section class="artistesAPropos">
         <h3> Les artiste</h3>
         <!-- boite artiste -->
         <div class="artistesAPropos__artistes">

            <!-- artiste 1 -->
            <div class="artistesAPropos__artistes__artiste">
               <!-- Image artiste 1 -->
               <img src="../images/GregoryBony.png" alt="Méduse Mongeau" class="artistesAPropos__artistes__artiste__imgArtiste">
               <!-- Info artiste 1 -->
               <div class="artistesAPropos__artistes__artiste__infoArtiste">
                  <p>Méduse Mongeau</p>
                  <p>fact?</p>
                  <p>link1</p>
               </div>
            </div>
            
            <!-- artiste 2 -->
            <div class="artistesAPropos__artistes__artiste">
               <!-- Image artiste 2 -->
               <img src="../images/GregoryBony.png" alt="Samuel César" class="artistesAPropos__artistes__artiste__imgArtiste">
               <!-- Info artiste 2 -->
               <div class="artistesAPropos__artistes__artiste__infoArtiste">
                  <p>Samuel César</p>
                  <p>fact?</p>
                  <p>link1</p>
               </div>
            </div>

            <!-- artiste 3 -->
            <div class="artistesAPropos__artistes__artiste">
               <!-- Image artiste 3 -->
               <img src="../images/GregoryBony.png" alt="Sébastien Malo" class="artistesAPropos__artistes__artiste__imgArtiste">
               <!-- Info artiste 3 -->
               <div class="artistesAPropos__artistes__artiste__infoArtiste">
                  <p>Sébastien Malo</p>
                  <p>fact?</p>
                  <p>link1</p>
               </div>
            </div>
            
            <!-- artiste 4 -->
            <div class="artistesAPropos__artistes__artiste">
               <!-- Image artiste 4 -->
               <img src="../images/GregoryBony.png" alt="Weiqiang Chen" class="artistesAPropos__artistes__artiste__imgArtiste">
               <!-- Info artiste 4 -->
               <div class="artistesAPropos__artistes__artiste__infoArtiste">
                  <p>Weiqiang Chen</p>
                  <p>fact?</p>
                  <p>link1</p>
               </div>

            </div>
            
            <!-- artiste 5 -->
            <div class="artistesAPropos__artistes__artiste">
               <!-- Image artiste 5 -->
               <img src="../images/GregoryBony.png" alt="Azpen Sbrizzi" class="artistesAPropos__artistes__artiste__imgArtiste">
               <!-- Info artiste 5 -->
               <div class="artistesAPropos__artistes__artiste__infoArtiste">
                  <p>Azpen Sbrizzi</p>
                  <p>fact?</p>
                  <p>link1</p>
               </div>
            </div>
         </div>
      </section>


   </main>
   <footer>
     <?php get_footer(); ?>
   </footer>
</body>

</html>