// bouton FILTRE LOGIQUE DU BOUTON
const buttons = document.querySelectorAll('.pageGalerie__filter-bar__filter-btn');

buttons.forEach(btn => {
  btn.addEventListener('click', () => {
    buttons.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
  });
});


// Bouton DÉROULANT LOGIQUE
document.addEventListener("DOMContentLoaded", () => {
  const dropdowns = document.querySelectorAll('.pageGalerie__dropdown');

  dropdowns.forEach(dropdown => {
    const select = dropdown.querySelector('.pageGalerie__dropdown.select');
    const caret = dropdown.querySelector('.pageGalerie__dropdown__select.caret');
    const menu = dropdown.querySelector('.pageGalerie__dropdown.menu');
    const options = dropdown.querySelectorAll('.pageGalerie__dropdown.menu li');
    const selected = dropdown.querySelector('.pageGalerie__dropdown__select.selected');

    if (!select || !caret || !menu || !selected) return;

    // Lorsque vous cliquez sur la zone déroulante « Sélectionner »
    select.addEventListener('click', () => {
      select.classList.toggle('select-clicked');
      caret.classList.toggle('caret-rotate');
      menu.classList.toggle('menu-open');
    });

    // Lors de la sélection d'un élément
    options.forEach(option => {
      option.addEventListener('click', () => {
        selected.innerText = option.innerText;
        select.classList.remove('select-clicked');
        caret.classList.remove('caret-rotate');
        menu.classList.remove('menu-open');

        options.forEach(o => o.classList.remove('active'));
        option.classList.add('active');
      });
    });

    // Fermer le menu déroulant si vous cliquez à l'extérieur
    document.addEventListener('click', (e) => {
      if (!dropdown.contains(e.target)) {
        select.classList.remove('select-clicked');
        caret.classList.remove('caret-rotate');
        menu.classList.remove('menu-open');
      }
    });
  });
});