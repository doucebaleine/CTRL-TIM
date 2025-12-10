(function() {
    const spotlight = document.querySelector('.spotlight-layer');
    const cursor = document.querySelector('.cursor');
    const cursorGlow = document.querySelector('.cursor-glow');
    const textBackground = document.getElementById('textBackground');
    
    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;

    // Function to get spotlight size based on screen width
    function getSpotlightSize() {
        const width = window.innerWidth;
        if (width <= 599) {
            // Mobile
            return { inner: 75, outer: 150 };
        } else if (width <= 1499) {
            // Tablet
            return { inner: 100, outer: 175 };
        } else {
            // Desktop
            return { inner: 150, outer: 250 };
        }
    }

    // Generate repeating text in brick pattern
    function generateText() {
        textBackground.innerHTML = '';
        const rows = 20;
        const itemsPerRow = 8;
        
        for (let row = 0; row < rows; row++) {
            const rowDiv = document.createElement('div');
            rowDiv.className = 'text-row';
            
            for (let col = 0; col < itemsPerRow; col++) {
                const span = document.createElement('span');
                span.textContent = 'CTRL+TIM';
                rowDiv.appendChild(span);
            }
            
            textBackground.appendChild(rowDiv);
        }
    }

    generateText();

    function updateSpotlight() {
        const size = getSpotlightSize();
        spotlight.style.maskImage = `radial-gradient(circle at ${mouseX}px ${mouseY}px, transparent 0%, transparent ${size.inner}px, black ${size.outer}px)`;
        spotlight.style.webkitMaskImage = `radial-gradient(circle at ${mouseX}px ${mouseY}px, transparent 0%, transparent ${size.inner}px, black ${size.outer}px)`;
    }

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;

        // Update spotlight mask position
        updateSpotlight();

        // Update cursor position
        cursor.style.left = mouseX + 'px';
        cursor.style.top = mouseY + 'px';
        
        // Update cursor glow position
        cursorGlow.style.left = mouseX + 'px';
        cursorGlow.style.top = mouseY + 'px';
    });

    // Add touch support for mobile
    document.addEventListener('touchmove', (e) => {
        const touch = e.touches[0];
        mouseX = touch.clientX;
        mouseY = touch.clientY;

        updateSpotlight();

        cursor.style.left = mouseX + 'px';
        cursor.style.top = mouseY + 'px';
        
        cursorGlow.style.left = mouseX + 'px';
        cursorGlow.style.top = mouseY + 'px';
    });

    // Handle window resize
    window.addEventListener('resize', updateSpotlight);

    // Initialize spotlight at center
    updateSpotlight();
})();