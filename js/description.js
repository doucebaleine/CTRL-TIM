document.addEventListener("DOMContentLoaded", () => {
  const descriptions = {
    "Tous": "Explorez l’ensemble des projets réalisés par les étudiants de la Technique d’intégration multimédia. Jeux vidéo, sites web, créations interactives et projets artistiques : découvrez la diversité et le talent qui animent chaque cohorte du TIM.",
    "Arcade": "L’Arcade de l’expoTIM présente les prototypes de jeux vidéo créés par les étudiants de deuxième année en Technique d’intégration multimédia. Réalisés dans le cadre du cours Création de jeu en équipe, ces projets sont le fruit d’un processus de production complet : de la conception et la planification à la création des médias, de la programmation aux tests de qualité jusqu’au produit fini. ",
    "1re année": "Dans le cours Conception graphique et imagerie vectorielle, les étudiants de première année ont réalisé une recherche sur un enjeu environnemental. À partir de cette recherche, ils ont imaginé un jeu vidéo ou une application permettant de sensibiliser la population à cet enjeu. Ils en ont conçu l’identité visuelle et l’ont présentée sous forme d’affiche. Le code QR présent sur chaque affiche donne accès à une présentation détaillant le projet proposé. ",
    "Finissants": "Les finissants de la Technique d’intégration multimédia présentent le projet synthèse de leur parcours. Après avoir exploré toutes les dimensions du multimédia – Jeu, web, design, programmation, création de médias, interactivité et plus encore – chaque étudiant a choisi le sujet qui le passionne le plus et a développé un projet original qui reflète son expertise et sa créativité. "
  };
  const subtitles = {
    "Tous": "Tous",
    "Arcade": "2e année",
    "1re année": "1ère année",
    "Finissants": "3e année"
  };

  const buttons = document.querySelectorAll(".pageGalerie__filter-bar__filter-btn");
  const description = document.querySelector(".pageGalerie__filter__description");
  const subtitle = document.querySelector(".pageGalerie__filter__subtitle");

  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      buttons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");

      const label = btn.textContent.trim();
      description.textContent = descriptions[label] || "Explorez nos projets.";
      subtitle.textContent = subtitles[label] || "Projets.";
    });
  });
});