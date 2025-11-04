<?php

/*
Template Name: Galerie
*/
//galerie
?>

<?php get_header(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jersey+15&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@400;500;600;700&display=swap" rel="stylesheet">
  
</head>
<body>
  <main class="pageGalerie">
    <h1 class="pageGalerie__titre">Galerie</h1>

    <div class="pageGalerie__filter-bar">
      <button class="pageGalerie__filter-bar__filter-btn active">Tous</button>
      <button class="pageGalerie__filter-bar__filter-btn">1re année</button>
      <button class="pageGalerie__filter-bar__filter-btn">Arcade</button>
      <button class="pageGalerie__filter-bar__filter-btn">Finissants</button>
    </div>
    <script src="<?php echo get_template_directory_uri(); ?>/js/filter.js"></script>

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

        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="<?php echo get_template_directory_uri(); ?>/images/BlackOps3.jpg" alt="Call of Duty">
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Call of Duty</h3>
          </div> 
        </div>

        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="<?php echo get_template_directory_uri(); ?>/images/cyberpunk.jpg" alt="Cyberpunk">
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Cyberpunk</h3>
          </div> 
        </div>

        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="<?php echo get_template_directory_uri(); ?>/images/DeathStranding.jpg" alt="Death Stranding">
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Death Stranding</h3>
          </div> 
        </div>

        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="<?php echo get_template_directory_uri(); ?>/images/EldenRing.jpg" alt="Elden Ring">    
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Elden Ring</h3>
          </div>    
        </div>

        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="<?php echo get_template_directory_uri(); ?>/images/Sekiro.jpg" alt="Sekiro">
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Sekiro</h3>
          </div>        
        </div>

        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="<?php echo get_template_directory_uri(); ?>/images/stray.jpg" alt="Stray">
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Stray</h3>
          </div> 
        </div>

        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="<?php echo get_template_directory_uri(); ?>/images/GhostofYotei.jpg" alt="GhostofYotei">
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Ghost of Yotei</h3>
          </div> 
        </div>

      </div>
    </section>

    <script src="<?php echo get_template_directory_uri(); ?>/js/galerie.js"></script>

  </main>
</body>
</html>