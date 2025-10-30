(function () {
  // Comportement simple de pile : fermer la carte du haut -> la supprimer -> décaler les autres vers le haut -> ajouter une nouvelle carte arrière
  const pile = document.querySelector('.pile-affiches');
  if (!pile) return;

  function creerCarte(id, src, estHaut) {
    const carte = document.createElement('div');
    carte.className = 'carte-affiche';
    if (estHaut) carte.classList.add('affiche-haut');
    else if (id === 1) carte.classList.add('affiche-milieu');
    else carte.classList.add('affiche-arriere');
    carte.dataset.id = id;

    const btn = document.createElement('button');
    btn.className = 'bouton-fermer-affiche';
    btn.setAttribute('aria-label', 'Fermer');
    btn.innerText = '✕';
    btn.addEventListener('click', surFermer);

    const img = document.createElement('img');
    img.src = src;
    img.alt = '';
    img.style.objectFit = 'cover';
    // secours si une image échoue à se charger
    img.addEventListener('error', function () {
      console.warn('Image d\'affiche échouée à se charger, utilisation du secours:', src);
      img.src = (THEME || '') + '/images/logo.svg';
      img.classList.add('image-affiche-secours');
    });
    img.addEventListener('load', function () {
      img.classList.add('image-affiche-chargee');
    });

    carte.appendChild(btn);
    carte.appendChild(img);
    return carte;
  }

  // données initiales (utiliser les images du thème comme espaces réservés)
  const THEME = (window.CTRL_TIM && window.CTRL_TIM.themeUrl) ? window.CTRL_TIM.themeUrl : '';
  const affiches = [
    THEME + '/images/logo.svg',
    THEME + '/images/affiche1.svg',
    THEME + '/images/affiche2.svg',
    THEME + '/images/affiche3.svg'
  ];

  // aide pour ajouter une nouvelle carte arrière
  function ajouterNouvelleArriere() {
    const src = affiches[Math.floor(Math.random() * affiches.length)];
    const nouvelleCarte = creerCarte(Date.now(), src, false);
    nouvelleCarte.classList.add('nouvelle-arriere');
    pile.appendChild(nouvelleCarte);
    // forcer la disposition puis animer
    requestAnimationFrame(() => {
      nouvelleCarte.classList.add('apparaitre');
      // après apparaitre supprimer la classe d'aide
      setTimeout(() => nouvelleCarte.classList.remove('nouvelle-arriere', 'apparaitre'), 450);
    });
  }

  function fermerHaut(haut) {
    if (!haut) return;
    // détacher l'élément de fermeture à un positionnement fixe pour que son animation
    // ne change pas la largeur du document et cause une barre de défilement horizontale.
    detacherVersFixe(haut);
    // puis déclencher l'animation de fermeture
    haut.classList.add('fermeture');
    haut.addEventListener('transitionend', function gestionnaire() {
      haut.removeEventListener('transitionend', gestionnaire);
      haut.remove();
      decalerPile();
      ajouterNouvelleArriere();
    });
  }

  // Déplacer l'élément à un positionnement fixe au même emplacement visuel
  // pour que son animation de transformation n'affecte pas la disposition/défilement largeur.
  function detacherVersFixe(el) {
    const rect = el.getBoundingClientRect();
    // préserver les transformations actuelles en supprimant temporairement la transformation
    const prevTransform = window.getComputedStyle(el).transform;
    // définir taille explicite & positionnement fixe
    el.style.position = 'fixed';
    el.style.left = rect.left + 'px';
    el.style.top = rect.top + 'px';
    el.style.width = rect.width + 'px';
    el.style.height = rect.height + 'px';
    el.style.margin = '0';
    el.style.zIndex = 99999;
    // effacer la transformation pour que les transitions CSS s'appliquent depuis l'état visuel actuel
    el.style.transform = prevTransform === 'none' ? 'none' : prevTransform;
    // forcer la disposition
    void el.offsetWidth;
  }

  function surFermer(e) {
    e.stopPropagation();
    const btn = e.currentTarget;
    const carte = btn.closest('.carte-affiche');
    if (!carte) return;
    const haut = pile.querySelector('.affiche-haut');
    // si la carte cliquée est déjà haut, la fermer
    if (carte === haut) {
      fermerHaut(haut);
      return;
    }
    // sinon tourner les classes pour que la carte cliquée devienne haut, animer puis fermer
    const milieu = pile.querySelector('.affiche-milieu');
    const arriere = pile.querySelector('.affiche-arriere');
    if (carte.classList.contains('affiche-milieu')) {
      // haut -> arriere, milieu -> haut, arriere -> milieu
      if (haut) { haut.classList.remove('affiche-haut'); haut.classList.add('affiche-arriere'); }
      if (milieu) { milieu.classList.remove('affiche-milieu'); milieu.classList.add('affiche-haut', 'animer-vers-haut'); }
      if (arriere) { arriere.classList.remove('affiche-arriere'); arriere.classList.add('affiche-milieu'); }
      setTimeout(() => { if (milieu) milieu.classList.remove('animer-vers-haut'); fermerHaut(pile.querySelector('.affiche-haut')); }, 520);
      return;
    }
    if (carte.classList.contains('affiche-arriere')) {
      // tourner : haut->milieu, milieu->arriere, arriere->haut
      if (haut) { haut.classList.remove('affiche-haut'); haut.classList.add('affiche-milieu'); }
      if (milieu) { milieu.classList.remove('affiche-milieu'); milieu.classList.add('affiche-arriere'); }
      carte.classList.remove('affiche-arriere'); carte.classList.add('affiche-haut', 'animer-vers-haut');
      setTimeout(() => { carte.classList.remove('animer-vers-haut'); fermerHaut(pile.querySelector('.affiche-haut')); }, 520);
      return;
    }
  }

  function decalerPile() {
    const milieu = pile.querySelector('.affiche-milieu');
    const arriere = pile.querySelector('.affiche-arriere');
    if (milieu) { milieu.classList.remove('affiche-milieu'); milieu.classList.add('animer-vers-haut', 'affiche-haut'); setTimeout(() => milieu.classList.remove('animer-vers-haut'), 500); }
    if (arriere) { arriere.classList.remove('affiche-arriere'); arriere.classList.add('affiche-milieu'); }
  }

  // attacher le gestionnaire de fermeture pour tous les boutons de fermeture existants
  pile.querySelectorAll('.bouton-fermer-affiche').forEach(btn => btn.addEventListener('click', surFermer));

  // Attacher les gestionnaires d'erreur à tous les nœuds <img> existants (balisage statique)
  pile.querySelectorAll('img').forEach(img => {
    img.style.objectFit = 'cover';
    img.addEventListener('error', function () {
      console.warn('Image d\'affiche statique échouée à se charger, utilisation du secours:', img.src);
      img.src = (THEME || '') + '/images/logo.svg';
      img.classList.add('image-affiche-secours');
    });
  });

  // Sécurité : si l'utilisateur clique sur l'arrière-plan de la pile, fermer le haut aussi
  pile.addEventListener('click', function (e) {
    if (e.target === pile) return; // ignorer l'arrière-plan
  });

})();
