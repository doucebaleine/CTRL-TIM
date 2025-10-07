<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title('|', true, 'right'); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header>
	<!-- LOGO -->
	<section class="logo">
		<p>LOGO</p>
	</section>

	<!-- BARRE DE RECHERCHE -->
	<section class="barreRecherche">
		<!-- Input barre de recherche -->
		<input type="text" placeholder="Recherche..">
		<!-- Icone loupe -->
		<img src="" alt="" class="iconeRecherche">
	</section>

	<!-- BOUTON NAVIGATION -->
	<section class="menuBoiteGlobale">
		<p>MENU</p>
	</section>
</header>
