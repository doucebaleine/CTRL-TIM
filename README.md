# CTRL-TIM WordPress Theme

## Structure des fichiers

### Fichiers principaux
- `functions.php` - Fichier principal des fonctions (fusionné avec functions2.php)
- `header.php` - En-tête du thème
- `footer.php` - Pied de page du thème
- `index.php` - Page d'accueil principale
- `index2.php` - Version alternative de la page d'accueil
- `style.css` - Fichier CSS compilé depuis SCSS
- `style.css.map` - Carte source pour le SCSS

### Templates
- `template-apropos.php` - Template pour la page À propos
- `template-galerie.php` - Template pour la page Galerie

### Dossier functions/
- `customizer.php` - Configuration du customizer WordPress
- `base-de-donnees.php` - Fonctions de base de données

### Dossier js/
- `menu.js` - Fonctionnalité du menu
- `cards.js` - Gestion des cartes/projets
- `filter.js` - Fonctionnalité de filtrage
- `galerie.js` - Fonctionnalité de la galerie
- `customizer.js` - Scripts pour le customizer (chargé uniquement dans l'admin)

### Dossier sass/
- `style.scss` - Fichier SCSS principal
- `normalize.css` - Reset CSS
- `base/autres.scss` - Styles de base
- `components/` - Composants SCSS
  - `_background.scss`
  - `_card.scss`
  - `_footer.scss`
  - `_header.scss`
  - `_hero.scss`
  - `_menu.scss`
  - `_search.scss`
- `layout/` - Layouts SCSS
  - `flexbox.scss`
  - `_apropos.scss`
  - `_galerie.scss`
- `variables/main.scss` - Variables SCSS

### Dossier images/
Dossier créé pour contenir les images du thème (actuellement vide)

## Fichiers chargés par functions.php

### Styles CSS
1. `sass/normalize.css` - Reset CSS
2. `style.css` - Styles principaux (compilé depuis style.scss)

### Scripts JavaScript
1. `js/menu.js` - Scripts du menu
2. `js/cards.js` - Scripts des cartes (avec localisation CTRL_TIM)
3. `js/filter.js` - Scripts de filtrage
4. `js/galerie.js` - Scripts de la galerie

### Fichiers PHP inclus
1. `functions/customizer.php` - Configuration du customizer
2. `functions/base-de-donnees.php` - Fonctions de base de données

## Scripts conditionnels
- `js/customizer.js` - Chargé uniquement dans l'interface d'administration du customizer

## Images référencées dans footer.php
Le footer fait référence aux images suivantes (à ajouter dans le dossier images/):
- `youtube.svg`
- `instagram.svg`
- `facebook.svg`
- `linkedin.svg`
- `search-icon.svg`
- `footer-logo.svg`
- `site-icon.svg`
- `location-icon.svg`

## Compilation SCSS
Le fichier `sass/style.scss` importe tous les composants et layouts disponibles. 
Pour recompiler le SCSS, utilisez votre compilateur préféré (Sass, Live Sass Compiler, etc.)

## Support thème WordPress
- `title-tag` - Génération automatique des titres
- `menus` - Support des menus WordPress
- `post-thumbnails` - Support des images à la une
- `custom-logo` - Support du logo personnalisé
- Menu principal enregistré avec l'ID 'principal'