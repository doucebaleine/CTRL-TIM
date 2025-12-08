(function() {
        const spotlight = document.querySelector('.spotlight-layer');
        const cursor = document.querySelector('.cursor');
        const cursorGlow = document.querySelector('.cursor-glow');
        const textBackground = document.getElementById('textBackground');
        
        let mouseX = window.innerWidth / 2;
        let mouseY = window.innerHeight / 2;

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

        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;

            // Update spotlight mask position
            spotlight.style.maskImage = `radial-gradient(circle at ${mouseX}px ${mouseY}px, transparent 0%, transparent 150px, black 250px)`;
            spotlight.style.webkitMaskImage = `radial-gradient(circle at ${mouseX}px ${mouseY}px, transparent 0%, transparent 150px, black 250px)`;

            // Update cursor position
            cursor.style.left = mouseX + 'px';
            cursor.style.top = mouseY + 'px';
            
            // Update cursor glow position
            cursorGlow.style.left = mouseX + 'px';
            cursorGlow.style.top = mouseY + 'px';
        });

        // Initialize spotlight at center
        spotlight.style.maskImage = `radial-gradient(circle at ${mouseX}px ${mouseY}px, transparent 0%, transparent 150px, black 250px)`;
        spotlight.style.webkitMaskImage = `radial-gradient(circle at ${mouseX}px ${mouseY}px, transparent 0%, transparent 150px, black 250px)`;
    })();