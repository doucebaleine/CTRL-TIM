# Configuration SASS pour CTRL-TIM

## Structure des fichiers SASS

```
sass/
├── style.scss              # Fichier principal (à compiler vers ../style.css)
├── _normalize.scss         # Reset CSS (intégré dans style.scss)
├── variables/
│   └── main.scss          # Variables SASS
├── base/
│   └── autres.scss        # Styles de base
├── components/
│   ├── _background.scss   # Styles des backgrounds
│   ├── _card.scss         # Styles des cartes
│   ├── _footer.scss       # Styles du footer
│   ├── _header.scss       # Styles du header
│   ├── _hero.scss         # Styles de la section hero
│   ├── _menu.scss         # Styles du menu
│   └── _search.scss       # Styles de la recherche
└── layout/
    ├── flexbox.scss       # Layouts flexbox
    ├── _apropos.scss      # Layout page à propos
    └── _galerie.scss      # Layout page galerie
```

## Compilation

### Option 1: VS Code Live Sass Compiler
1. Installer l'extension "Live Sass Compiler" dans VS Code
2. Ouvrir le fichier `sass/style.scss`
3. Cliquer sur "Watch Sass" dans la barre de statut
4. Le fichier sera automatiquement compilé vers `style.css`

### Option 2: Command Line (Node.js)
```bash
# Installer Sass globalement
npm install -g sass

# Compiler une fois
sass sass/style.scss style.css

# Watch mode (compilation automatique)
sass --watch sass/style.scss:style.css
```

### Option 3: Command Line (Dart Sass)
```bash
# Windows
sass sass/style.scss style.css

# Watch mode
sass --watch sass/style.scss:style.css
```

## Configuration recommandée

Le fichier `sass/style.scss` importe tous les composants nécessaires dans l'ordre correct :

1. **Reset CSS** - Normalize pour une base propre
2. **Variables** - Couleurs, polices, etc.
3. **Base** - Styles de base HTML
4. **Composants** - Éléments réutilisables
5. **Layouts** - Structures de page

## Problèmes courants

### Le CSS n'est pas à jour
- Vérifiez que le fichier `style.css` à la racine du thème existe
- Vérifiez la date de modification du fichier CSS
- Recompilez le SCSS si nécessaire

### Erreurs d'import
- Tous les fichiers partiels doivent commencer par `_`
- Les imports dans `style.scss` ne doivent pas inclure l'extension `.scss`
- Vérifiez les chemins relatifs

### Variables non définies
- Assurez-vous que `variables/main.scss` est importé en premier
- Vérifiez que les variables sont bien définies avant utilisation

## WordPress Integration

Le thème charge automatiquement :
- `style.css` (compilé depuis `sass/style.scss`)
- Tous les styles sont maintenant centralisés dans un seul fichier

Plus besoin de charger `normalize.css` séparément - il est intégré dans le fichier CSS principal.