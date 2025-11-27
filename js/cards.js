(function () {
  const pile = document.querySelector('.pile-affiches');
  if (!pile) return;

  const THEME = (window.CTRL_TIM && window.CTRL_TIM.themeUrl) ? window.CTRL_TIM.themeUrl : '';

  // Normaliser cartesProjets (peut être un tableau de strings ou d'objets {src, lien})
  const projetsNorm = Array.isArray(cartesProjets) ? cartesProjets.map(item => {
    if (typeof item === 'string') return { src: item, lien: null };
    // attendre {src: "...", lien: "..."}
    return { src: item.src || '', lien: item.lien || null };
  }) : [];

  // construire affiches comme tableau d'objets {src, lien}
  const affiches = [
    { src: THEME + '/images/hero-logo.svg', lien: null },
    ...projetsNorm
  ];

  // index cyclique pour parcourir affiches dans l'ordre
  let indexAffiche = 0;

  function creerCarte(id, srcObj, estHaut) {
    // srcObj peut être une string (rare, on normalise avant) ou un objet {src,lien}
    const src = (typeof srcObj === 'string') ? srcObj : (srcObj && srcObj.src) ? srcObj.src : '';
    const lien = (typeof srcObj === 'object') ? (srcObj.lien || null) : null;

    const carte = document.createElement('div');
    carte.className = 'carte-affiche';
    if (estHaut) carte.classList.add('affiche-haut');
    else if (id === 1) carte.classList.add('affiche-milieu');
    else carte.classList.add('affiche-arriere');

    // set data-id et data-lien pour référence
    carte.dataset.id = id;
    if (lien) carte.dataset.lien = lien;

    const btn = document.createElement('button');
    btn.className = 'bouton-fermer-affiche';
    btn.setAttribute('aria-label', 'Fermer');
    btn.innerText = '✕';
    btn.addEventListener('click', surFermer);

    const img = document.createElement('img');
    img.src = src;
    img.alt = '';
    img.style.objectFit = 'cover';

    // clic sur l'image -> navigation vers data-lien (si présent)
    img.addEventListener('click', function (e) {
      e.stopPropagation();
      // priorité : lien stocké sur la carte; sinon on cherche un attribut data-lien sur l'image
      const lienCarte = carte.dataset.lien || img.dataset.lien;
      if (lienCarte) {
        window.location.href = lienCarte;
      }
    });

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
    // clic sur la carte entière -> navigation si data-lien présent (ignorer le bouton fermer)
    carte.addEventListener('click', function (e) {
      if (e.target.closest('.bouton-fermer-affiche')) return;
      const lienCarte = carte.dataset.lien || (carte.querySelector('img') && carte.querySelector('img').dataset.lien);
      if (lienCarte) {
        window.location.href = lienCarte;
      }
    });
    return carte;
  }

  // Forcer le logo pour la première carte affichée (si existante)
  const premiereCarteImg = pile.querySelector('.affiche-haut img');
  if (premiereCarteImg) {
    premiereCarteImg.src = THEME + '/images/hero-logo.svg';
    // si la carte php avait un data-lien, on garde; sinon on s'assure qu'elle n'a pas de lien.
  }

  // S'assurer que les images statiques existantes ont le gestionnaire de click
  pile.querySelectorAll('.carte-affiche').forEach(divCarte => {
    const img = divCarte.querySelector('img');
    if (!img) return;
    // si le div a un data-lien (venant du PHP), on veut que le clic sur img navigue
    const lien = divCarte.dataset.lien || null;
    if (lien) {
      // attacher dataset à l'image pour compatibilité future
      img.dataset.lien = lien;
    }
    // attacher le listener (idempotent si rechargé)
    img.addEventListener('click', function (e) {
      e.stopPropagation();
      const logoPath = THEME + '/images/hero-logo.svg';
      if (img.src.includes('hero-logo.svg')) {
        return; // ne rien faire
      }
      const lienImg = divCarte.dataset.lien || img.dataset.lien;
      if (lienImg) window.location.href = lienImg;
    });
    // clic sur la div carte (autour de l'image) -> navigation si data-lien présent
    divCarte.addEventListener('click', function (e) {
      if (e.target.closest('.bouton-fermer-affiche')) return;
      const lienDiv = divCarte.dataset.lien || (divCarte.querySelector('img') && divCarte.querySelector('img').dataset.lien);
      if (lienDiv) {
        window.location.href = lienDiv;
      }
    });
  });

  function ajouterNouvelleArriere() {
    const srcObj = affiches[indexAffiche];
    indexAffiche = (indexAffiche + 1) % affiches.length; // boucle infinie

    const nouvelleCarte = creerCarte(Date.now(), srcObj, false);
    nouvelleCarte.classList.add('nouvelle-arriere');
    pile.appendChild(nouvelleCarte);
    requestAnimationFrame(() => {
      nouvelleCarte.classList.add('apparaitre');
      setTimeout(() => nouvelleCarte.classList.remove('nouvelle-arriere', 'apparaitre'), 450);
    });
  }

  function fermerHaut(haut) {
    if (!haut) return;
    detacherVersFixe(haut);
    haut.classList.add('fermeture');
    haut.addEventListener('transitionend', function gestionnaire() {
      haut.removeEventListener('transitionend', gestionnaire);
      haut.remove();
      decalerPile();
      ajouterNouvelleArriere();
    });
  }

  function detacherVersFixe(el) {
    const rect = el.getBoundingClientRect();
    const prevTransform = window.getComputedStyle(el).transform;
    el.style.position = 'fixed';
    el.style.left = rect.left + 'px';
    el.style.top = rect.top + 'px';
    el.style.width = rect.width + 'px';
    el.style.height = rect.height + 'px';
    el.style.margin = '0';
    el.style.zIndex = 99999;
    el.style.transform = prevTransform === 'none' ? 'none' : prevTransform;
    void el.offsetWidth;
  }

  function surFermer(e) {
    e.stopPropagation();
    const btn = e.currentTarget;
    const carte = btn.closest('.carte-affiche');
    if (!carte) return;
    const haut = pile.querySelector('.affiche-haut');
    if (carte === haut) {
      fermerHaut(haut);
      return;
    }
    const milieu = pile.querySelector('.affiche-milieu');
    const arriere = pile.querySelector('.affiche-arriere');
    if (carte.classList.contains('affiche-milieu')) {
      if (haut) { haut.classList.remove('affiche-haut'); haut.classList.add('affiche-arriere'); }
      if (milieu) { milieu.classList.remove('affiche-milieu'); milieu.classList.add('affiche-haut', 'animer-vers-haut'); }
      if (arriere) { arriere.classList.remove('affiche-arriere'); arriere.classList.add('affiche-milieu'); }
      setTimeout(() => { if (milieu) milieu.classList.remove('animer-vers-haut'); fermerHaut(pile.querySelector('.affiche-haut')); }, 520);
      return;
    }
    if (carte.classList.contains('affiche-arriere')) {
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
      img.src = (THEME || '') + '/images/logo.svg';
      img.classList.add('image-affiche-secours');
    });
  });

  // sécurité : clique sur l'arrière-plan
  pile.addEventListener('click', function (e) {
    if (e.target === pile) return;
  });

})();
