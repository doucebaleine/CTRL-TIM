document.addEventListener("DOMContentLoaded", () => {
  const normalize = str =>
    str
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .replace(/•/g, "")
      .replace(/\s+/g, " ")
      .trim()
      .toLowerCase();

  const descriptions = {
    "tous":
      "Explorez l’ensemble des projets réalisés par les étudiants de la Technique d’intégration multimédia. Jeux",
    "arcade":
      "L’Arcade de l’expoTIM présente les prototypes de jeux vidéo créés par les étudiants de deuxième année en Technique d’intégration multimédia. Réalisés",
    "graphisme":
      "Dans le cours Conception graphique et imagerie vectorielle, les étudiants de première année ont réalisé une recherche sur un enjeu environnemental. À partir",
    "finissantes":
      "Les finissants de la Technique d’intégration multimédia présentent le projet synthèse de leur parcours. Après"
  };

  const lirePlus = {
    "tous":
      " vidéo, sites web, créations interactives et projets artistiques : découvrez la diversité et le talent qui animent chaque cohorte du TIM.",
    "arcade":
      " dans le cadre du cours Création de jeu en équipe, ces projets sont le fruit d’un processus de production complet : de la conception et la planification à la création des médias, de la programmation aux tests de qualité jusqu’au produit fini.",
    "graphisme":
      " de cette recherche, ils ont imaginé un jeu vidéo ou une application permettant de sensibiliser la population à cet enjeu. Ils en ont conçu l’identité visuelle et l’ont présentée sous forme d’affiche. Le code QR présent sur chaque affiche donne accès à une présentation détaillant le projet proposé.",
    "finissantes":
      " avoir exploré toutes les dimensions du multimédia – Jeu, web, design, programmation, création de médias, interactivité et plus encore – chaque étudiant a choisi le sujet qui le passionne le plus et a développé un projet original qui reflète son expertise et sa créativité."
  };

  const subtitles = {
    "tous": "Tous",
    "arcade": "2e année",
    "graphisme": "1ère année",
    "finissantes": "3e année"
  };

  const buttons = document.querySelectorAll(".pageGalerie__filter-bar__filter-btn");
  const description = document.querySelector(".pageGalerie__filter__debutDescription");
  const texteLirePlus = document.querySelector(".pageGalerie__filter__lirePlus");
  const subtitle = document.querySelector(".pageGalerie__filter__subtitle");
  const btnText = document.getElementById("btnLirePlus");

  if (!buttons.length || !description || !subtitle) return;

  // Fonction pour mettre à jour la description et le subtitle
  function updateDescription(key) {
    description.textContent = descriptions[key] || "Explorez nos projets.";
    texteLirePlus.textContent = lirePlus[key] || "";
    subtitle.textContent = subtitles[key] || "Projets.";
    texteLirePlus.classList.remove("open");
    btnText.textContent = "Lire plus";
  }

  // Récupérer la catégorie depuis l'URL si présente
  const urlParams = new URLSearchParams(window.location.search);
  const urlCategory = urlParams.get('category');
  if (urlCategory) {
    const normalizedCategory = normalize(urlCategory);
    updateDescription(normalizedCategory);
    // Activer le bouton correspondant
    buttons.forEach(btn => {
      if (normalize(btn.textContent) === normalizedCategory) {
        btn.classList.add("active");
      } else {
        btn.classList.remove("active");
      }
    });
  }
  
  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      buttons.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      texteLirePlus.classList.remove("open");
      btnText.textContent = "Lire plus";

      const key = normalize(btn.textContent);
      description.textContent = descriptions[key] || "Explorez nos projets.";
      texteLirePlus.textContent = lirePlus[key] || "";
      subtitle.textContent = subtitles[key] || "Projets.";
    });
  });
  
  btnText.addEventListener("click", () => {
    texteLirePlus.classList.toggle("open");

    if (texteLirePlus.classList.contains("open")) {
      btnText.textContent = "Lire moins";
    } else {
      btnText.textContent = "...Lire plus";
    }
  }); 
});

