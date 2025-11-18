(function () {
    const radios = document.querySelectorAll(".pageProjet__carrousel__boutons__input");
    const slides = document.querySelectorAll(".pageProjet__carrousel__images__conteneur");
    const btnPrev = document.querySelector(".pageProjet__carrousel__nav__btn--prev");
    const btnNext = document.querySelector(".pageProjet__carrousel__nav__btn--next");
    let currentIndex = 0;

    function showSlide(index) {
        // Boucle infinie
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;

        slides.forEach((slide, i) => {
            slide.classList.toggle('pageProjet__carrousel__images__conteneur--active', i === index);
            radios[i].checked = (i === index);
        });

        currentIndex = index;
    }

    // Navigation via boutons radio
    radios.forEach((radio, i) => {
        radio.addEventListener('change', () => {
            currentIndex = i;
            showSlide(i);
        });
    });

    // Navigation via flèches
    btnPrev?.addEventListener('click', () => {
        showSlide(currentIndex - 1);
    });

    btnNext?.addEventListener('click', () => {
        showSlide(currentIndex + 1);
    });

    // Affiche la première image au chargement
    showSlide(currentIndex);
})();
