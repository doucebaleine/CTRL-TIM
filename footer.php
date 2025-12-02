<?php
    ?>
    <footer class="pied-page-site">
        <div class="interieur-pied-page">
            <div class="colonne-pied-page gauche" aria-hidden="true">
                <?php
                // récupérer settings customizer
                $media_img  = get_theme_mod( 'image_media', '' );
                $media_url  = get_theme_mod( 'lien_media', '' );
                $media_name = get_theme_mod( 'nom_media', '' );
                $media_sel  = get_theme_mod( 'media_a_modifier', '' ); // id sélectionné

                // récupérer les medias stockés en DB
                $medias = function_exists('ctrltim_get_all_medias') ? ctrltim_get_all_medias() : array();

                // helper pour normaliser image_media (ID ou URL)
                $normalize_img = function($val) {
                    if (empty($val)) return '';
                    if (is_numeric($val)) return wp_get_attachment_url(intval($val)) ?: '';
                    if (filter_var($val, FILTER_VALIDATE_URL)) return esc_url_raw($val);
                    return esc_url_raw($val);
                };

                // Priorité : DB (média sélectionné), sinon thème/customizer, sinon premier DB, sinon fallback
                $main_img = '';
                $main_url = '';
                $main_name = '';

                // 1) Si un id est sélectionné, récupérer la ligne DB correspondante
                if (!empty($media_sel) && is_numeric($media_sel) && !empty($medias)) {
                    foreach ($medias as $m) {
                        if (intval($m->id) === intval($media_sel)) {
                            $main_img = $normalize_img($m->image_media ?? $m->image ?? $m->image_url ?? '');
                            $main_url = esc_url_raw($m->lien ?? $m->url ?? '');
                            $main_name = sanitize_text_field($m->nom ?? '');
                            break;
                        }
                    }
                }

                // 2) Si pas de DB trouvé, tenter le thème/customizer
                if (empty($main_img)) {
                    $main_img = $normalize_img($media_img);
                    $main_url = !empty($media_url) ? esc_url_raw($media_url) : $main_url;
                    $main_name = !empty($media_name) ? sanitize_text_field($media_name) : $main_name;
                }

                // 3) Si toujours vide, prendre le premier en DB
                if (empty($main_img) && !empty($medias)) {
                    $row = $medias[0];
                    $main_img = $normalize_img($row->image_media ?? $row->image ?? $row->image_url ?? '');
                    $main_url = $main_url ?: esc_url_raw($row->lien ?? $row->url ?? '');
                    $main_name = $main_name ?: sanitize_text_field($row->nom ?? '');
                }

                // 4) fallback final
                if (empty($main_img)) $main_img = get_template_directory_uri() . '/images/youtube.svg';
                if (empty($main_url)) $main_url = 'https://www.youtube.com/@TIMaisonneuve';
                if (empty($main_name)) $main_name = 'YouTube';
                ?>

                <a class="icone-sociale icone-haut" href="<?php echo esc_url( $main_url ); ?>" aria-label="<?php echo esc_attr( $main_name ); ?>" target="_blank" rel="noopener noreferrer">
                    <img src="<?php echo esc_url( $main_img ); ?>" alt="<?php echo esc_attr( $main_name ); ?>" />
                </a>

                <div class="rangee-icones" role="group" aria-label="Réseaux sociaux">
                    <?php
                    // afficher jusqu'à 3 icônes issues de la DB (exclure le principal s'il est présent)
                    $shown = 0;
                    foreach ($medias as $m) {
                        if ($shown >= 3) break;
                        // ne pas répéter le principal si même lien ou même image
                        $img_url = $normalize_img($m->image_media ?? $m->image ?? '');
                        $href = esc_url_raw($m->lien ?? $m->url ?? '');
                        $label = sanitize_text_field($m->nom ?? '');
                        if (empty($img_url) || ( $img_url === $main_img && $href === $main_url )) continue;
                        ?>
                        <a class="icone-sociale" href="<?php echo esc_url($href ? $href : '#'); ?>" aria-label="<?php echo esc_attr( $label ? $label : 'Réseau social' ); ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo esc_url( $img_url ? $img_url : get_template_directory_uri() . '/images/instagram.svg' ); ?>" alt="<?php echo esc_attr( $label ? $label : 'Réseau social' ); ?>" aria-hidden="true" />
                        </a>
                        <?php
                        $shown++;
                    }
                    // si pas d'icônes DB, afficher des fallback statiques
                    while ($shown < 3) {
                        $fallbacks = array('instagram.svg','facebook.svg','linkedin.svg');
                        $file = get_template_directory_uri() . '/images/' . $fallbacks[$shown];
                        ?>
                        <a class="icone-sociale" href="#" aria-label="Réseau social" tabindex="-1">
                            <img src="<?php echo esc_url($file); ?>" alt="" aria-hidden="true" />
                        </a>
                        <?php
                        $shown++;
                    }
                    ?>
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
                <?php
                // chercher dans les medias en DB (déjà chargé plus haut)
                $find_media_by_name = function( $name, $medias ) use ( $normalize_img ) {
                    foreach ( $medias as $m ) {
                        if ( isset( $m->nom ) && sanitize_text_field( $m->nom ) === $name ) {
                            $img = $normalize_img( $m->image_media ?? $m->image ?? $m->image_url ?? '' );
                            $href = esc_url_raw( $m->lien ?? $m->url ?? '' );
                            return array( 'img' => $img, 'href' => $href, 'label' => sanitize_text_field( $m->nom ) );
                        }
                    }
                    return null;
                };

                // Site web
                $site = $find_media_by_name( 'Site web', $medias );
                if ( $site && ! empty( $site['img'] ) ) : ?>
                    <a class="icone-sociale" href="<?php echo esc_url( $site['href'] ? $site['href'] : '#' ); ?>" aria-label="<?php echo esc_attr( $site['label'] ); ?>" target="_blank" rel="noopener noreferrer">
                        <img src="<?php echo esc_url( $site['img'] ); ?>" alt="<?php echo esc_attr( $site['label'] ); ?>" aria-hidden="true" />
                    </a>
                    <p><?php echo esc_html( $site['label'] ); ?></p>
                <?php else : ?>
                    <a class="icone-sociale" href="https://www.cmaisonneuve.qc.ca/programme/integration-multimedia/" aria-label="Site web">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/site-icon.svg' ); ?>" alt="Site" aria-hidden="true" />
                    </a>
                    <p>Site web</p>
                <?php endif; ?>

                <?php
                // Localisation
                $loc = $find_media_by_name( 'Localisation', $medias );
                if ( $loc && ! empty( $loc['img'] ) ) : ?>
                    <a class="icone-sociale" href="<?php echo esc_url( $loc['href'] ? $loc['href'] : '#' ); ?>" aria-label="<?php echo esc_attr( $loc['label'] ); ?>" target="_blank" rel="noopener noreferrer">
                        <img src="<?php echo esc_url( $loc['img'] ); ?>" alt="<?php echo esc_attr( $loc['label'] ); ?>" aria-hidden="true" />
                    </a>
                    <p><?php echo esc_html( $loc['label'] ); ?></p>
                <?php else : ?>
                    <a class="icone-sociale" href="https://www.cmaisonneuve.qc.ca/programme/integration-multimedia/" aria-label="Localisation">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/images/location-icon.svg' ); ?>" alt="Localisation" aria-hidden="true" />
                    </a>
                    <p>Localisation</p>
                <?php endif; ?>
             </div>
        </div>
    </footer>
    <?php wp_footer(); ?>
    </body>
    </html>
