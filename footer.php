    <?php
    ?>
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-col left" aria-hidden="true">
                <!-- Top single icon -->
                <a class="social-icon top-icon" href="#" aria-label="YouTube">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/images/youtube.svg' ); ?>" alt="YouTube" aria-hidden="true" />
                </a>

                <!-- Row of three icons underneath -->
                <div class="icon-row" role="group" aria-label="Réseaux sociaux">
                    <a class="social-icon" href="#" aria-label="Instagram">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/instagram.svg' ); ?>" alt="Instagram" aria-hidden="true" />
                    </a>
                    <a class="social-icon" href="#" aria-label="Facebook">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/facebook.svg' ); ?>" alt="Facebook" aria-hidden="true" />
                    </a>
                    <a class="social-icon" href="#" aria-label="LinkedIn">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/linkedin.svg' ); ?>" alt="LinkedIn" aria-hidden="true" />
                    </a>
                </div>
            </div>

            <div class="footer-center">
                <div class="footer-search" role="search">
                    <img class="iconeRecherche" src="<?php echo esc_url( get_template_directory_uri() . '/images/search-icon.svg' ); ?>" alt="Recherche" aria-hidden="true" />
                    <input type="search" name="s" placeholder="Recherche..." aria-label="Recherche">
                </div>

                <div class="footer-logo-box">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link" aria-label="Accueil">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/images/footer-logo.svg' ); ?>" alt="Logo" />
                    </a>
                </div>
            </div>

            <div class="footer-col right" aria-hidden="true">
                <a class="social-icon" href="#" aria-label="Site web">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/images/site-icon.svg' ); ?>" alt="Site" aria-hidden="true" />
                </a>
                <a class="social-icon" href="#" aria-label="Localisation">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/images/location-icon.svg' ); ?>" alt="Localisation" aria-hidden="true" />
                </a>
            </div>
        </div>
    </footer>
    <?php wp_footer(); ?>
    </body>
    </html>
