document.addEventListener("DOMContentLoaded", () => {
  // Track current filters
  let currentCategory = 'tous';
  let currentDropdownFilter = 'all';

  // Récupérer la catégorie depuis l'URL si présente
  const urlParams = new URLSearchParams(window.location.search);
  const urlCategory = urlParams.get('category');
  if (urlCategory) {
    currentCategory = urlCategory.toLowerCase();
  }

  // Category filter buttons
  const categoryButtons = document.querySelectorAll('.pageGalerie__filter-bar__filter-btn');

  categoryButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      categoryButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      // Get category from button text
      const buttonText = btn.textContent.trim();
      currentCategory = buttonText === 'Tous' ? 'tous' : buttonText.toLowerCase();

      filterProjects();
    });
  });

  // Dropdown filter
  const dropdownSelect = document.querySelector('.pageGalerie__dropdown__select');
  const dropdownMenu = document.querySelector('.pageGalerie__dropdown__menu');
  const dropdownSelected = document.querySelector('.pageGalerie__dropdown__selected');
  const dropdownItems = document.querySelectorAll('.pageGalerie__dropdown__menu li');

  // Toggle dropdown
  if (dropdownSelect) {
    dropdownSelect.addEventListener('click', () => {
      dropdownMenu.classList.toggle('active');
      dropdownSelect.classList.toggle('clicked');
      const caret = dropdownSelect.querySelector('.pageGalerie__dropdown__caret');
      if (caret) caret.classList.toggle('rotate');
    });
  }

  // Handle dropdown selection
  dropdownItems.forEach(item => {
    item.addEventListener('click', () => {
      dropdownItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');

      const filterValue = item.getAttribute('data-filter');
      currentDropdownFilter = filterValue || 'all';
      dropdownSelected.textContent = item.textContent;
      dropdownMenu.classList.remove('active');
      dropdownSelect.classList.remove('clicked');
      const caret = dropdownSelect.querySelector('.pageGalerie__dropdown__caret');
      if (caret) caret.classList.remove('rotate');

      filterProjects();
    });
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.pageGalerie__dropdown')) {
      dropdownMenu?.classList.remove('active');
      dropdownSelect?.classList.remove('clicked');
      const caret = dropdownSelect?.querySelector('.pageGalerie__dropdown__caret');
      if (caret) caret.classList.remove('rotate');
    }
  });

  // Main filter function - stacks both filters
  function filterProjects() {
    const projects = document.querySelectorAll('.pageGalerie__galerieProjets__projets__projet');

    projects.forEach(project => {
      const projectCategory = project.getAttribute('data-category') || '';
      const projectFilters = project.getAttribute('data-filters') || '';

      // Check category filter (insensible à la casse)
      let categoryMatch = currentCategory === 'tous' ||
        (projectCategory.toLowerCase() === currentCategory);

      // Check dropdown filter
      let dropdownMatch = currentDropdownFilter === 'all';

      if (!dropdownMatch && projectFilters) {
        const filtersArray = projectFilters.split(',').map(f => f.trim());
        dropdownMatch = filtersArray.some(f => f.toLowerCase() === currentDropdownFilter.toLowerCase());
      }

      // Show only if BOTH filters match
      if (categoryMatch && dropdownMatch) {
        project.style.display = 'inline-flex';
      } else {
        project.style.display = 'none';
      }
    });
  }

  // Initial filter on page load
  filterProjects();
});