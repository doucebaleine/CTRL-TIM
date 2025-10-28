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
  <header class="header">
    <div class="logo">
        <img src="images/logo_CTRLTIM.svg" alt="logo_CTRLTIM">
    </div>
    <div class="search-container">
      <input type="text" placeholder="Recherche..." />
    </div>
    <div class="menu-icon">☰</div>
  </header>

  <main class="gallery-page">
    <h1 class="title">Galerie</h1>

    <div class="filter-bar">
      <button class="filter-btn active">Tous</button>
      <button class="filter-btn">Arcade</button>
      <button class="filter-btn">Terre</button>
      <button class="filter-btn">Finissants</button>
    </div>
    <script src="filter.js"></script>

    <p class="subtitle">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin in magna nibh. 
      Praesent luctus, mauris eu rhoncus elementum, tellus nisl malesuada eros.
    </p>

    <section class="dropdown">
      <div class="select">
        <span class="selected">All</span>
        <div class="caret"></div>
      </div>
      <ul class="menu">
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
          <img src="images/BlackOps3.jpg" alt="Call of Duty">
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Call of Duty</h3>
          </div> 
        </div>
        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="images/cyberpunk.jpg" alt="Cyberpunk">
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Cyberpunk</h3>
          </div> 
        </div>
        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="images/DeathStranding.jpg" alt="Death Stranding">
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Death Stranding</h3>
          </div> 
        </div>
        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="images/EldenRing.jpg" alt="Elden Ring">    
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Elden Ring</h3>
          </div>    
        </div>
        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="images/Sekiro.jpg" alt="Sekiro">
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Sekiro</h3>
          </div>        
        </div>
        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="images/stray.jpg" alt="Stray">
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Stray</h3>
          </div> 
        </div>
        <div class="pageGalerie__galerieProjets__projets__projet">
          <img src="images/GhostofYotei.jpg" alt="GhostofYotei">
          <div class="pageGalerie__galerieProjets__projets__projet__info">
            <span class="pageGalerie__galerieProjets__projets__projet__info__titre">Arcade</span>
            <h3 class="pageGalerie__galerieProjets__projets__projet__info__titre">Ghost of Yotei</h3>
          </div> 
        </div>
      </div>
    </section>
    
    <script src="gallery.js"></script>


  </main>
</body>
</html>