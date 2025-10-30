// Simple bascule de menu hors-canvas
(function () {
  function $sel(sel) { return document.querySelector(sel) }
  function $tous(sel) { return Array.prototype.slice.call(document.querySelectorAll(sel)) }

  var bouton = $sel('.menu-burger');
  var menuHorsCanvas = $sel('#menuHorsCanvas');
  var boutonFermer = menuHorsCanvas && menuHorsCanvas.querySelector('.bouton-fermer-menu');
  var focusableSelectors = 'a[href], button:not([disabled]), input, textarea, select, [tabindex]:not([tabindex="-1"])';
  var dernierFocus = null;
  var fondHorsCanvas = $sel('#fondHorsCanvas');
  var sentinelleHaut = menuHorsCanvas && menuHorsCanvas.querySelector('.sentinelle-haut');
  var sentinelleBas = menuHorsCanvas && menuHorsCanvas.querySelector('.sentinelle-bas');

  function piegeFocus(container) {
    if (!container) return;
    var focusable = $tous(focusableSelectors).filter(function (el) { 
      return container.contains(el) && el !== sentinelleHaut && el !== sentinelleBas && el.offsetParent !== null; 
    });
    if (!focusable.length) return;
    var premier = focusable[0];
    var dernier = focusable[focusable.length - 1];

    function gestionTouche(e) {
      if (e.key !== 'Tab' || !container.classList.contains('ouvert')) return;
      if (e.shiftKey && document.activeElement === premier) {
        e.preventDefault(); dernier.focus();
      } else if (!e.shiftKey && document.activeElement === dernier) {
        e.preventDefault(); premier.focus();
      }
    }

    container._gestionTouche = gestionTouche;
    document.addEventListener('keydown', gestionTouche);
    if (premier && typeof premier.focus === 'function') premier.focus();
  }

  function libereFocus(container) {
    if (container && container._gestionTouche) document.removeEventListener('keydown', container._gestionTouche);
  }

  function ouvrirMenu() {
    if (!menuHorsCanvas) return;
    dernierFocus = document.activeElement;
    menuHorsCanvas.setAttribute('aria-hidden', 'false');
    menuHorsCanvas.classList.add('ouvert');
    if (fondHorsCanvas) { fondHorsCanvas.setAttribute('aria-hidden', 'false'); fondHorsCanvas.classList.add('visible'); }
    document.documentElement.classList.add('pas-defilement');
    // bloquer interactions en dehors du menu
    document.documentElement.classList.add('menu-ouvert');
    if (bouton) bouton.setAttribute('aria-expanded', 'true');
    piegeFocus(menuHorsCanvas);
  }

  function fermerMenu() {
    if (!menuHorsCanvas) return;
    menuHorsCanvas.setAttribute('aria-hidden', 'true');
    menuHorsCanvas.classList.remove('ouvert');
    if (fondHorsCanvas) { fondHorsCanvas.setAttribute('aria-hidden', 'true'); fondHorsCanvas.classList.remove('visible'); }
    document.documentElement.classList.remove('pas-defilement');
    // restaurer interactions
    document.documentElement.classList.remove('menu-ouvert');
    if (bouton) bouton.setAttribute('aria-expanded', 'false');
    libereFocus(menuHorsCanvas);
    if (dernierFocus && typeof dernierFocus.focus === 'function') dernierFocus.focus();
  }

  // liaisons d'événements
  if (bouton) {
    bouton.addEventListener('click', function (e) {
      var expanded = bouton.getAttribute('aria-expanded') === 'true';
      if (expanded) fermerMenu(); else ouvrirMenu();
      e.stopPropagation();
    });
  }
  if (boutonFermer) { boutonFermer.addEventListener('click', function (e) { fermerMenu(); e.stopPropagation(); }); }

  // fermer en cliquant sur le fond ou en dehors du contenu
  if (fondHorsCanvas) { fondHorsCanvas.addEventListener('click', function () { fermerMenu(); }); }
  if (menuHorsCanvas) { menuHorsCanvas.addEventListener('click', function (e) { if (e.target === menuHorsCanvas) fermerMenu(); }); }

  // sentinelles pour trap focus
  if (sentinelleHaut) {
    sentinelleHaut.addEventListener('focus', function () {
      if (!menuHorsCanvas || !menuHorsCanvas.classList.contains('ouvert')) return;
      var focusables = Array.prototype.slice.call(menuHorsCanvas.querySelectorAll(focusableSelectors)).filter(function (el) { return menuHorsCanvas.contains(el) && el !== sentinelleHaut && el !== sentinelleBas; });
      if (focusables.length) focusables[focusables.length - 1].focus();
    });
  }
  if (sentinelleBas) {
    sentinelleBas.addEventListener('focus', function () {
      if (!menuHorsCanvas || !menuHorsCanvas.classList.contains('ouvert')) return;
      var focusables = Array.prototype.slice.call(menuHorsCanvas.querySelectorAll(focusableSelectors)).filter(function (el) { return menuHorsCanvas.contains(el) && el !== sentinelleHaut && el !== sentinelleBas; });
      if (focusables.length) focusables[0].focus();
    });
  }

  // fermer sur Échap
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') fermerMenu(); });

  // Lorsque l'utilisateur clique dans la nav hors-canvas, déplacer la classe 'primaire'
  if (menuHorsCanvas) {
    var navHors = menuHorsCanvas.querySelector('.nav-hors-canvas');
    if (navHors) {
      navHors.addEventListener('click', function (e) {
        var cible = e.target.closest('a.bouton-menu');
        if (!cible || !navHors.contains(cible)) return;
        var actuel = navHors.querySelector('a.bouton-menu.primaire');
        if (actuel === cible) return;
        if (actuel) actuel.classList.remove('primaire');
        cible.classList.add('primaire');
        // animation temporaire
        cible.classList.add('anime-changement');
        cible.addEventListener('animationend', function gestion() {
          cible.classList.remove('anime-changement');
          cible.removeEventListener('animationend', gestion);
        });
      });
    }
  }
})();
