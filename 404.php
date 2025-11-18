<?php get_header(); ?>
<main class="page-404">
  <section class="contenu-404">
    <div class="erreur-404-emoji" aria-hidden="true">😕</div>
    <h1 class="titre-404">404</h1>
    <h2 class="sous-titre-404">Oups, cette page n'existe pas !</h2>
    <p class="texte-404">La page que vous cherchez est introuvable ou a été déplacée.<br>Essayez de revenir à l'accueil ou d'utiliser la barre de recherche.</p>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="bouton-404">Retour à l'accueil</a>
  </section>
</main>
<?php get_footer(); ?>
