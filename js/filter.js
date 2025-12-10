document.addEventListener("DOMContentLoaded", () => {
  // Track current filters
  let currentCategory = 'tous';
  let currentDropdownFilter = 'all';
  
  // Category filter buttons
  const categoryButtons = document.querySelectorAll('.pageGalerie__filter-bar__filter-btn');
  
  categoryButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      categoryButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      
      // Get category from button text instead of data-category
      const buttonText = btn.textContent.trim();
      currentCategory = buttonText.toLowerCase();
      
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
    });
  }
  
  // Handle dropdown selection
  dropdownItems.forEach(item => {
    item.addEventListener('click', () => {
      dropdownItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      
      const filterValue = item.getAttribute('data-filter');
      currentDropdownFilter = filterValue;
      dropdownSelected.textContent = item.textContent;
      dropdownMenu.classList.remove('active');
      
      filterProjects();
    });
  });
  
  // Close dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.pageGalerie__dropdown')) {
      dropdownMenu?.classList.remove('active');
    }
  });

  function createNoResultsMessage() {
    const gallery = document.querySelector('.pageGalerie__galerieProjets__projets');
    if (!gallery || document.querySelector('.pageGalerie__galerieProjets__projets__noProjectsMessage')) return;
    
    const message = document.createElement('div');
    message.className = 'pageGalerie__galerieProjets__projets__noProjectsMessage';
    message.style.cssText = `
      display: none;
      width: 100%;
      text-align: center;
      color: #ffffffff;
      font-size: clamp(1.5rem, 1vw + 1rem, 1.75rem);
    `;
    message.textContent = 'Aucun projet trouvé';
    
    gallery.appendChild(message);
  }
  
  // Main filter function - stacks both filters
  function filterProjects() {
    const projects = document.querySelectorAll('.pageGalerie__galerieProjets__projets__projet');
    const noResultsMessage = document.querySelector('.pageGalerie__galerieProjets__projets__noProjectsMessage');
    let visibleCount = 0;
    
    projects.forEach(project => {
      const projectCategory = project.getAttribute('data-category');
      const projectFilters = project.getAttribute('data-filters');
      
      // Check category filter
      let categoryMatch = currentCategory === 'tous' || 
                         (projectCategory && projectCategory.toLowerCase() === currentCategory);
      
      // Check dropdown filter
      let dropdownMatch = currentDropdownFilter === 'all';
      
      if (!dropdownMatch && projectFilters) {
        const filtersArray = projectFilters.split(',');
        dropdownMatch = filtersArray.includes(currentDropdownFilter);
      }
      
      // Show only if BOTH filters match
      if (categoryMatch && dropdownMatch) {
        project.style.display = 'inline-flex';
        visibleCount++;
      } else {
        project.style.display = 'none';
      }

      if (noResultsMessage) {
        noResultsMessage.style.display = visibleCount === 0 ? 'block' : 'none';
      }
    });
  }
  
  createNoResultsMessage();
  filterProjects();
});

document.addEventListener("DOMContentLoaded", () => {
  const dropdowns = document.querySelectorAll(".pageGalerie__dropdown");

  dropdowns.forEach((dropdown) => {
    const select = dropdown.querySelector(".pageGalerie__dropdown__select");
    const caret = dropdown.querySelector(".pageGalerie__dropdown__caret");
    const menu = dropdown.querySelector(".pageGalerie__dropdown__menu");
    const options = dropdown.querySelectorAll(".pageGalerie__dropdown__menu li");
    const selected = dropdown.querySelector(".pageGalerie__dropdown__selected");

    if (!select || !caret || !menu || !selected) return;

    select.addEventListener("click", () => {
      select.classList.toggle("clicked");
      caret.classList.toggle("rotate");
      menu.classList.toggle("open");
    });

    options.forEach((option) => {
      option.addEventListener("click", () => {
        selected.innerText = option.innerText;
        select.classList.remove("clicked");
        caret.classList.remove("rotate");
        menu.classList.remove("open");

        options.forEach((o) => o.classList.remove("active"));
        option.classList.add("active");
      });
    });

    document.addEventListener("click", (e) => {
      if (!dropdown.contains(e.target)) {
        select.classList.remove("clicked");
        caret.classList.remove("rotate");
        menu.classList.remove("open");
      }
    });
  });
});