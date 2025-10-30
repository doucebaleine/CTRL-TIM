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
<header class="entete-site">
	<div class="interieur-entete">
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
			<img class="icone-burger" src="<?php echo esc_url( get_template_directory_uri() . '/images/menu-burger.svg' ); ?>" aria-hidden="true" />
		</button>
	</div>
</header>

<!-- Fond hors-canvas -->
<div id="fondHorsCanvas" class="fond-hors-canvas" aria-hidden="true"></div>

<!-- Menu hors-canvas (mobile / burger) -->
<div id="menuHorsCanvas" class="menu-hors-canvas" aria-hidden="true" role="dialog" aria-label="Menu principal">
	<div tabindex="0" class="sentinelle-focus sentinelle-haut" aria-hidden="true"></div>
	<button class="bouton-fermer-menu" aria-label="Fermer le menu">
		<img class="iconeFermerMenu" src="<?php echo esc_url( get_template_directory_uri() . '/images/close-icon.svg' ); ?>" aria-hidden="true" />
	</button>
	<div class="contenu-menu-hors-canvas">
        <div class="recherche-hors-canvas" role="search">
            <img class="iconeRecherche" src="<?php echo esc_url( get_template_directory_uri() . '/images/search-icon.svg' ); ?>" aria-hidden="true" />
            <input type="search" name="s" placeholder="Recherche..." aria-label="Recherche">
        </div>

		<nav class="nav-hors-canvas" aria-label="Navigation principale">
			<?php
			wp_nav_menu(array(
				'theme_location' => 'principal',
				'menu_class' => 'menu-principal',
				'container' => false,
				'fallback_cb' => 'ctrltim_fallback_menu',
			));
			?>
		</nav>
	</div>
	<div tabindex="0" class="sentinelle-focus sentinelle-bas" aria-hidden="true"></div>
</div>
