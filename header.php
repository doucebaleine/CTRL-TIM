<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title('|', true, 'right'); ?></title>
	<!-- Polices Google -->
	<link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Jersey+15&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Black+Han+Sans&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="entete-site">
	<div class="interieur-entete">
		<div class="logo">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link" aria-label="Accueil">
				<div class="logo-icon" aria-hidden="true">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/images/logo.svg' ); ?>" alt="logo" />
				</div>
			</a>
		</div>

		  <form class="barreRecherche" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" autocomplete="off">
			  <img class="iconeRecherche" src="<?php echo esc_url( get_template_directory_uri() . '/images/search-icon.svg' ); ?>" aria-hidden="true" />
			<input type="search" name="s" placeholder="Recherche" aria-label="Recherche" autocomplete="off" spellcheck="false" autocorrect="off" autocapitalize="off">
			<button type="button" class="search-clear" aria-label="Effacer l'historique">Effacer</button>
		  </form>

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
		<nav class="nav-hors-canvas" aria-label="Navigation principale">
			<?php
			wp_nav_menu(array(
				'theme_location' => 'principal',
				'menu_class' => 'menu-principal',
				'container' => false,
				'fallback_cb' => 'ctrltim_fallback_menu',
			));
			?>
			<?php
			// Sélecteur d'année pour naviguer vers les anciens sites
			$expo_sites = function_exists('ctrltim_get_expo_sites') ? ctrltim_get_expo_sites() : array();
			$expo_default = 'Expo-TIM';
			if (!empty($expo_sites)) {
				$expo_default = isset($expo_sites[0]['label']) ? $expo_sites[0]['label'] : $expo_default;
			}
			?>
			<div class="menu-annees" data-component="annee-switcher">
				<button type="button" class="annee-bouton bouton-menu" aria-haspopup="true" aria-expanded="false">
					<?php echo esc_html($expo_default); ?>
				</button>
				<ul class="annee-liste" role="menu" aria-label="Choix d'année">
					<?php if (!empty($expo_sites)) : foreach ($expo_sites as $site) :
						$label = isset($site['label']) ? $site['label'] : '';
						$url = isset($site['url']) ? $site['url'] : '#';
					?>
					<li role="none"><a role="menuitem" class="annee-item" href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($label); ?></a></li>
					<?php endforeach; endif; ?>
				</ul>
			</div>
		</nav>
	</div>
	<div tabindex="0" class="sentinelle-focus sentinelle-bas" aria-hidden="true"></div>
</div>