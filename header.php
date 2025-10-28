<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title('|', true, 'right'); ?></title>
	<!-- Polices Google -->
	<link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Jersey+15&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
	<div class="header-inner">
		<div class="logo">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link" aria-label="Accueil">
				<div class="logo-icon" aria-hidden="true">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/images/logo.svg' ); ?>" alt="<?php bloginfo('name'); ?>" />
				</div>
			</a>
		</div>

		 <div class="barreRecherche" role="search">
				<img class="iconeRecherche" src="<?php echo esc_url( get_template_directory_uri() . '/images/search-icon.svg' ); ?>" aria-hidden="true" />
				<input type="search" name="s" placeholder="Recherche..." aria-label="Recherche">
			</div>

	<button class="menu-burger" aria-label="Ouvrir le menu" aria-expanded="false">
		<img class="iconeMenuBurger" src="<?php echo esc_url( get_template_directory_uri() . '/images/menu-burger.svg' ); ?>" aria-hidden="true" />
	</button>
	</div>
</header>

<!-- Off-canvas backdrop -->
<div id="offcanvasBackdrop" class="offcanvas-backdrop" aria-hidden="true"></div>

<!-- Off-canvas menu (mobile / burger) -->
<div id="offcanvasMenu" class="offcanvas-menu" aria-hidden="true" role="dialog" aria-label="Menu principal">
	<div tabindex="0" class="focus-sentinel sentinel-top" aria-hidden="true"></div>
	<button class="offcanvas-close" aria-label="Fermer le menu">
		<img class="iconeFermerMenu" src="<?php echo esc_url( get_template_directory_uri() . '/images/close-icon.svg' ); ?>" aria-hidden="true" />
	</button>
	<div class="offcanvas-content">
		<div class="offcanvas-content">
            <div class="offcanvas-search" role="search">
                <img class="iconeRecherche" src="<?php echo esc_url( get_template_directory_uri() . '/images/search-icon.svg' ); ?>" aria-hidden="true" />
                <input type="search" name="s" placeholder="Recherche..." aria-label="Recherche">
            </div>

		<nav class="offcanvas-nav" aria-label="Navigation principale">
			<a class="menu-btn primary" href="#">Galerie</a>
			<a class="menu-btn" href="#">Accueil</a>
			<a class="menu-btn" href="#">À propos</a>
			<a class="menu-btn" href="#">Contact</a>
		</nav>
		</div>
		<div tabindex="0" class="focus-sentinel sentinel-bottom" aria-hidden="true"></div>
    </div>
</div>
