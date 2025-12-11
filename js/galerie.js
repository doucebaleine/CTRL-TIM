document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.pageGalerie__galerieProjets__projets');
    
    if (!slider) return;
    
    let isDown = false;
    let startX;
    let scrollLeft;
    let hasMoved = false;
    const DRAG_THRESHOLD = 5; // pixels avant de considérer comme un drag

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        hasMoved = false;
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });

slider.addEventListener('mouseleave', () => {
  isDown = false;
  slider.classList.remove('active');
});

slider.addEventListener('mouseup', () => {
  isDown = false;
  slider.classList.remove('active');
});

slider.addEventListener('mousemove', (e) => {
  if (!isDown) return;
  
  const x = e.pageX - slider.offsetLeft;
  const walk = x - startX;
  
  // Ajouter la classe active seulement si le mouvement dépasse le seuil
  if (Math.abs(walk) > DRAG_THRESHOLD && !hasMoved) {
    hasMoved = true;
    slider.classList.add('active');
  }
  
  if (hasMoved) {
    e.preventDefault();
    slider.scrollLeft = scrollLeft - walk;
  }
});

slider.addEventListener('touchstart', (e) => {
  isDown = true;
  hasMoved = false;
  startX = e.touches[0].pageX - slider.offsetLeft;
  scrollLeft = slider.scrollLeft;
});

slider.addEventListener('touchend', () => {
  isDown = false;
  slider.classList.remove('active');
});

    slider.addEventListener('touchmove', (e) => {
        if (!isDown) return;
        
        const x = e.touches[0].pageX - slider.offsetLeft;
        const walk = x - startX;
        
        // Ajouter la classe active seulement si le mouvement dépasse le seuil
        if (Math.abs(walk) > DRAG_THRESHOLD && !hasMoved) {
            hasMoved = true;
            slider.classList.add('active');
        }
        
        if (hasMoved) {
            slider.scrollLeft = scrollLeft - walk;
        }
    });
});