// Historique de recherche pour la barre de recherche WordPress

document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.barreRecherche');
    if (!forms || forms.length === 0) return;

    // Récupère l'historique depuis le localStorage
    function getHistory() {
        return JSON.parse(localStorage.getItem('searchHistory') || '[]');
    }
    function saveHistory(history) {
        localStorage.setItem('searchHistory', JSON.stringify(history));
    }

    // Fonction pour initialiser une instance d'historique sur un formulaire
    function initHistoryForForm(searchForm) {
        const searchInput = searchForm.querySelector('input[name="s"]');
        if (!searchInput) return;

        // Fallback: forcer l'autocomplete off coté JS (certains navigateurs ignorent l'attribut HTML)
        try {
            searchForm.setAttribute('autocomplete', 'off');
            searchInput.setAttribute('autocomplete', 'off');
            searchInput.setAttribute('spellcheck', 'false');
            searchInput.setAttribute('autocorrect', 'off');
            searchInput.setAttribute('autocapitalize', 'off');
        } catch (e) {}

        const historyList = document.createElement('ul');
        historyList.className = 'search-history-list';
        historyList.style.display = 'none';
        historyList.style.position = 'absolute';
        historyList.style.left = 0;
        historyList.style.right = 0;
        historyList.style.top = '100%';
        historyList.style.background = '#141633';
        historyList.style.zIndex = 100;
        historyList.style.borderRadius = '0 0 12px 12px';
        historyList.style.boxShadow = '0 4px 24px rgba(80,60,160,0.12)';
        historyList.style.margin = 0;
        historyList.style.padding = '0.5rem 0';
        historyList.style.listStyle = 'none';
        historyList.style.maxHeight = '220px';
        historyList.style.overflowY = 'auto';
        searchForm.style.position = 'relative';
        searchForm.appendChild(historyList);

        // Fonction pour afficher un toast global
        function showToast(message) {
            // éviter doublons
            const existing = document.querySelector('.search-toast');
            if (existing) existing.remove();
            const t = document.createElement('div');
            t.className = 'search-toast';
            t.textContent = message;
            document.body.appendChild(t);
            // forcer le repaint pour transition
            requestAnimationFrame(() => t.classList.add('visible'));
            setTimeout(() => {
                t.classList.remove('visible');
                setTimeout(() => t.remove(), 300);
            }, 2200);
        }

        function showHistory(filter = '') {
            const history = getHistory().filter(item => item.toLowerCase().includes(filter.toLowerCase()));
            historyList.innerHTML = '';
            if (history.length === 0) {
                historyList.style.display = 'none';
                return;
            }
            history.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item;
                li.className = 'search-history-item';
                li.style.cursor = 'pointer';
                li.style.padding = '8px 18px';
                li.style.color = '#b6c2e0';
                li.style.fontSize = '1rem';
                li.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    searchInput.value = item;
                    searchForm.submit();
                });
                historyList.appendChild(li);
            });
            historyList.style.display = 'block';
        }

        // Ajoute la recherche à l'historique
        searchForm.addEventListener('submit', function () {
            const value = searchInput.value.trim();
            if (!value) return;
            let history = getHistory();
            history = history.filter(item => item.toLowerCase() !== value.toLowerCase());
            history.unshift(value);
            if (history.length > 10) history = history.slice(0, 10);
            saveHistory(history);
        });

        // Clear button (présent dans le header et le footer)
        const clearBtn = searchForm.querySelector('.search-clear');
        if (clearBtn) {
            clearBtn.addEventListener('click', function (e) {
                e.preventDefault();
                // confirmation avant effacement
                const ok = window.confirm("Effacer l'historique de recherche ?\nCette action supprimera toutes les recherches enregistrées.");
                if (!ok) return;
                // vider l'historique partagé
                localStorage.removeItem('searchHistory');
                historyList.innerHTML = '';
                historyList.style.display = 'none';
                // petit feedback visuel: pulse
                clearBtn.classList.add('cleared');
                setTimeout(() => clearBtn.classList.remove('cleared'), 300);
                showToast('Historique effacé');
            });
        }

        // Affiche l'historique au focus
        searchInput.addEventListener('focus', function () {
            showHistory(searchInput.value);
        });
        searchInput.addEventListener('input', function () {
            showHistory(searchInput.value);
        });
        searchInput.addEventListener('blur', function () {
            setTimeout(() => { historyList.style.display = 'none'; }, 120);
        });
    }

    // Initialiser pour chaque formulaire trouvé
    forms.forEach(f => initHistoryForForm(f));
});
