document.addEventListener("DOMContentLoaded", () => {
  
  const buttons = document.querySelectorAll('.pageGalerie__filter-bar__filter-btn');
  
  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      buttons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      filterProjects(btn.textContent.toLowerCase());
    });
  });
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


function filterProjects(filter) {
  const projects = document.querySelectorAll('.pageGalerie__galerieProjets__projets__projet');
  
  projects.forEach(project => {
    const category = project.getAttribute('data-category');
    
    if (filter === 'tous' || (category && category.toLowerCase() === filter)) {
      project.style.display = 'inline-flex';
    } else {
      project.style.display = 'none';
    }
  });
}