<?php
   /*
   Template Name: À Propos
   */
   //a-propos
?>

   <?php get_header(); ?>
   
   <main class="mainArtistes">


   
      <!-- les eleves -->
      <section class="artistes">
         <h2> Les Artistes</h2>
         <!-- boite artiste -->
         <div class="artistes__boiteArtistes">

            <!-- artiste 1 -->
            <div class="artistes__boiteArtistes__artisteSingle">
               <!-- Image artiste 1 -->
                <img src="<?php echo esc_url( get_theme_mod('artist_1_profile_pic') ); ?>" alt="Méduse Mongeau" class="artistes__boiteArtistes__artisteSingle__imgArtiste">

               <!-- Info artiste 1 -->
               <div class="artistes__boiteArtistes__artisteSingle__infoArtiste">
                  <p>Méduse Mongeau</p>
                  <p>Portfolio <a href="https://doucebaleine.me/"></a></p>
               </div>
            </div>
            
            <!-- artiste 2 -->
            <div class="artistes__boiteArtistes__artisteSingle">
               <!-- Image artiste 2 -->
               <img src="<?php echo esc_url( get_theme_mod('artist_2_profile_pic') ); ?>" alt="Samuel César" class="artistes__boiteArtistes__artisteSingle__imgArtiste">
               <!-- Info artiste 2 -->
               <div class="artistes__boiteArtistes__artisteSingle__infoArtiste">
                  <p>Samuel César</p>
                  <p>LinkedIn <a href="https://www.linkedin.com/in/samuel-cesar-2ab762213/"></a></p>
               </div>
            </div>

            <!-- artiste 3 -->
            <div class="artistes__boiteArtistes__artisteSingle">
               <!-- Image artiste 3 -->
               <img src="<?php echo esc_url( get_theme_mod('artist_3_profile_pic') ); ?>" alt="Sébastien Malo" class="artistes__boiteArtistes__artisteSingle__imgArtiste">
               <!-- Info artiste 3 -->
               <div class="artistes__boiteArtistes__artisteSingle__infoArtiste">
                  <p>Sébastien Malo</p>
                  <p>LinkedIn <a href="https://www.linkedin.com/in/s%C3%A9bastien-malo-087904232/"></a></p>
               </div>
            </div>
            
            <!-- artiste 4 -->
            <div class="artistes__boiteArtistes__artisteSingle">
               <!-- Image artiste 4 -->
               <img src="<?php echo esc_url( get_theme_mod('artist_4_profile_pic') ); ?>" alt="Weiqiang Chen" class="artistes__boiteArtistes__artisteSingle__imgArtiste">
               <!-- Info artiste 4 -->
               <div class="artistes__boiteArtistes__artisteSingle__infoArtiste">
                  <p>Weiqiang Chen</p>
                  <p>Behance <a href="https://www.behance.net/weiqiangchen"></a></p>
               </div>

            </div>
            
            <!-- artiste 5 -->
            <div class="artistes__boiteArtistes__artisteSingle">
               <!-- Image artiste 5 -->
               <img src="<?php echo esc_url( get_theme_mod('artist_5_profile_pic') ); ?>" alt="Azpen Sbrizzi" class="artistes__boiteArtistes__artisteSingle__imgArtiste">
               <!-- Info artiste 5 -->
               <div class="artistes__boiteArtistes__artisteSingle__infoArtiste">
                  <p>Azpen Sbrizzi</p>
                  <p>Behance <a href="https://www.behance.net/azpensbrizzi"></a></p>
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