<?php
    ?>
    <footer class="pied-page-site">
        <div class="interieur-pied-page">
            <div class="colonne-pied-page gauche" aria-hidden="true">
                <a class="icone-sociale icone-haut" href="#" aria-label="YouTube">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/images/youtube.svg' ); ?>" alt="YouTube" aria-hidden="true" />
                </a>

                <div class="rangee-icones" role="group" aria-label="Réseaux sociaux">
                    <a class="icone-sociale" href="#" aria-label="Instagram">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/instagram.svg' ); ?>" alt="Instagram" aria-hidden="true" />
                    </a>
                    <a class="icone-sociale" href="#" aria-label="Facebook">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/facebook.svg' ); ?>" alt="Facebook" aria-hidden="true" />
                    </a>
                    <a class="icone-sociale" href="#" aria-label="LinkedIn">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/linkedin.svg' ); ?>" alt="LinkedIn" aria-hidden="true" />
                    </a>
                </div>
            </div>

            <div class="centre-pied-page">
                <form class="recherche-pied-page barreRecherche" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" autocomplete="off">
                    <img class="iconeRecherche" src="<?php echo esc_url( get_template_directory_uri() . '/images/search-icon.svg' ); ?>" alt="Recherche" aria-hidden="true" />
                    <input type="search" name="s" placeholder="Recherche..." aria-label="Recherche" autocomplete="off" spellcheck="false" autocorrect="off" autocapitalize="off">
                </form>

                <div class="boite-logo-pied-page">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link" aria-label="Accueil">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/images/footer-logo.svg' ); ?>" alt="Logo" />
                    </a>
                </div>
            </div>

            <div class="colonne-pied-page droite" aria-hidden="true">
                <a class="icone-sociale" href="#" aria-label="Site web">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/images/site-icon.svg' ); ?>" alt="Site" aria-hidden="true" />
                </a>
                <a class="icone-sociale" href="#" aria-label="Localisation">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/images/location-icon.svg' ); ?>" alt="Localisation" aria-hidden="true" />
                </a>
            </div>
        </div>
    </footer>
    <?php wp_footer(); ?>
    </body>
    </html>
