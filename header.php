<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title('|', true, 'right'); ?></title>
	<!-- Polices Google -->
	<link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;700&display=swap" rel="stylesheet">
	<!-- Jersey 15 n'est pas une police Google standard; si elle n'est pas disponible, une police de secours sera utilisée via CSS -->
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
	<div class="header-inner">
		<div class="logo">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link" aria-label="Accueil">
				<!-- petit carré avec icône terminal (SVG) -->
				<div class="logo-icon" aria-hidden="true">
					<svg width="36" height="28" viewBox="0 0 36 28" xmlns="http://www.w3.org/2000/svg" role="img" focusable="false">
						<defs>
							<linearGradient id="g1" x1="0" x2="1">
								<stop offset="0%" stop-color="#B47BFF" />
								<stop offset="100%" stop-color="#7CD0FF" />
							</linearGradient>
						</defs>
						<rect rx="6" width="36" height="28" fill="#0f0b18" stroke="url(#g1)" stroke-width="2" />
						<g fill="url(#g1)" transform="translate(6,6)">
							<polygon points="0,6 6,3 0,0" />
							<rect x="10" y="6" width="10" height="3" rx="1.2" />
						</g>
					</svg>
				</div>
			</a>
		</div>

		<div class="barreRecherche" role="search">
			<svg class="iconeRecherche" width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16a6.471 6.471 0 004.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zM10 14a4 4 0 110-8 4 4 0 010 8z"/>
			</svg>
			<input type="search" name="s" placeholder="Recherche..." aria-label="Recherche">
		</div>

		<button class="menu-burger" aria-label="Ouvrir le menu" aria-expanded="false">
			<span class="burger-icon" aria-hidden="true">
				<span></span>
				<span></span>
				<span></span>
			</span>
		</button>
	</div>
</header>

<!-- Off-canvas backdrop -->
<div id="offcanvasBackdrop" class="offcanvas-backdrop" aria-hidden="true"></div>

<!-- Off-canvas menu (mobile / burger) -->
<div id="offcanvasMenu" class="offcanvas-menu" aria-hidden="true" role="dialog" aria-label="Menu principal">
	<div tabindex="0" class="focus-sentinel sentinel-top" aria-hidden="true"></div>
	<button class="offcanvas-close" aria-label="Fermer le menu">✕</button>
	<div class="offcanvas-content">
		<div class="offcanvas-search" role="search">
			<svg class="iconeRecherche" width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
				<path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16a6.471 6.471 0 004.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zM10 14a4 4 0 110-8 4 4 0 010 8z"/>
			</svg>
			<input type="search" name="s" placeholder="Recherche_" aria-label="Recherche">
		</div>

		<nav class="offcanvas-nav" aria-label="Navigation principale">
			<a class="menu-btn primary" href="#">Galerie</a>
			<a class="menu-btn" href="#">Accueil</a>
			<a class="menu-btn" href="#">À propos</a>
		</nav>
		</div>
		<div tabindex="0" class="focus-sentinel sentinel-bottom" aria-hidden="true"></div>
	</div>
