<?php
/**
 * code-PHP.php
 *
 * Référence et snippets rapides pour récupérer et utiliser les variables des bases de données.
 */

if (!defined('ABSPATH')) {
    exit; // Sécurité : accès direct interdit
}

// ------------------------------------------------------------------
// 1) BASE DE DONNÉES PROJETS
// Colonnes : id, titre_projet, description_projet, video_projet, image_projet, images_projet, annee_projet, lien, cours, cat_exposition, filtres, etudiants_associes, date_creation
// ------------------------------------------------------------------

// Récupérer tous les projets (retourne tableau d'objets)
function ctrltim_get_all_projets() {
    global $wpdb;
    $nom_table = ctrltim_table_name('ctrltim_projets');
    // Trier par titre_projet A->Z
  $sql = "SELECT id, titre_projet, description_projet, video_projet, image_projet, images_projet, annee_projet, lien, cours, cat_exposition, filtres, etudiants_associes FROM {$nom_table} ORDER BY titre_projet ASC";
    return $wpdb->get_results($sql);
}

// Récupérer un projet par id (retourne un objet)
function ctrltim_get_projet_by_id($id) {
    global $wpdb;
        $nom_table = ctrltim_table_name('ctrltim_projets');
  $colonnes = 'id, titre_projet, description_projet, video_projet, image_projet, images_projet, annee_projet, lien, cours, cat_exposition, filtres, etudiants_associes';
        return $wpdb->get_row($wpdb->prepare("SELECT {$colonnes} FROM {$nom_table} WHERE id = %d", $id));
}

// Exemple : comment référencer chaque variable d'un projet dans le HTML 
/*
$project = ctrltim_get_projet_by_id(12);
if ($project) {
    $id = $project->id;                     // int
    $titre = $project->titre_projet;        // string
    $description = $project->description_projet; // string
    $video = $project->video_projet;        // string (url ou identifiant)
    $image = $project->image_projet;        // string (url)
    $lien = $project->lien;                 // string (url)
    $cours = $project->cours;               // string
    $cat = $project->cat_exposition;        // string (valeur de catégorie)
    $filtres = json_decode($project->filtres, true) ?: array(); // array
    $etudiants_associes = json_decode($project->etudiants_associes, true) ?: array(); // array d'IDs, voir fonction en dessous pour récupérer les détails
}
*/

// ----- Exemple : utiliser les images pour un carrousel et le filtre d'année -----
/*
$project = ctrltim_get_projet_by_id(12);
if ($project) {
    // Images pour carrousel : stockées en JSON dans images_projet
    $images = json_decode($project->images_projet, true) ?: array();


    // Année / filtre
    $annee = isset($project->annee_projet) ? $project->annee_projet : '2025';

    // Exemple HTML simple (sans JS) pour un carrousel — vous pouvez adapter à votre librairie
    if (!empty($images)) : ?>
      <div class="project-carousel" data-project-year="<?php echo esc_attr($annee); ?>">
        <?php foreach ($images as $image => $img_url) : if ($image >= 5) break; // max 5 images ?>
          <div class="carousel-slide">
            <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($project->titre_projet); ?>">
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif;
}
*/

///Autre exemple plus détaillé et concret (avec boîte HTML)
/*

<?php
$projets = ctrltim_get_all_projets();
if ($projets) :
?>
  <section class="liste-projets">
  <?php foreach ($projets as $projet) : ?>
    <section class="projet">
      <h3><?php echo esc_html($projet->titre_projet ); ?></h3>
        <img class="image-projet" src="<?php echo esc_url( $projet->image_projet ); ?>" alt="<?php echo esc_attr( $projet->titre_projet ); ?>">
      <p><?php echo wp_kses_post( $projet->description_projet ); ?></p>
      <?php if (!empty($projet->lien)) : ?>
        <p><a href="<?php echo esc_url( $projet->lien ); ?>" target="_blank" rel="noopener"><?php echo esc_html__('Voir le projet', 'ctrl-tim'); ?></a></p>
      <?php endif; ?>
    </section>
  <?php endforeach; ?>
  </section>
<?php else: ?>
  <p><?php echo esc_html__('Aucun projet trouvé.', 'ctrl-tim'); ?></p>
<?php endif; ?>


// Exemple pratique : récupérer les étudiants détaillés pour un projet*/
function ctrltim_get_etudiants_for_projet($project_id) {
    global $wpdb;
    // Récupère l'objet projet
    $projet = ctrltim_get_projet_by_id($project_id);
    if (!$projet) return array();

    // IDs d'étudiants (tableau d'entiers)
    $ids_etudiants = json_decode($projet->etudiants_associes, true) ?: array();
    $ids_etudiants = array_map('intval', $ids_etudiants);
    $ids_etudiants = array_filter($ids_etudiants);
    if (empty($ids_etudiants)) return array();

    // Préparer la requête (placeholders selon le nombre d'IDs)
    $placeholders_etudiants = implode(',', array_fill(0, count($ids_etudiants), '%d'));
    $table_etudiants = ctrltim_table_name('ctrltim_etudiants');
    $colonnes_etudiants = 'id, nom, image_etudiant, annee';

    return $wpdb->get_results($wpdb->prepare("SELECT {$colonnes_etudiants} FROM {$table_etudiants} WHERE id IN ({$placeholders_etudiants}) ORDER BY nom ASC", ...$ids_etudiants));
}


// ------------------------------------------------------------------
// 2) BASE DE DONNÉES ÉTUDIANTS (Au cas où, normalement pas utilisé pour l'instant!!)
// Colonnes : id, nom, image_etudiant, annee, date_creation
// ------------------------------------------------------------------

function ctrltim_get_all_etudiants() {
    global $wpdb;
    $nom_table = ctrltim_table_name('ctrltim_etudiants');
    $colonnes = 'id, nom, image_etudiant, annee';
    return $wpdb->get_results("SELECT {$colonnes} FROM {$nom_table} ORDER BY nom ASC");
}

function ctrltim_get_etudiant_by_id($id) {
    global $wpdb;
    $nom_table = ctrltim_table_name('ctrltim_etudiants');
    // Exclure date_creation
    $colonnes = 'id, nom, image_etudiant, annee';
    return $wpdb->get_row($wpdb->prepare("SELECT {$colonnes} FROM {$nom_table} WHERE id = %d", $id));
}

// Exemple d'accès aux variables d'un étudiant (objet)
/*
$student = ctrltim_get_etudiant_by_id(5);
if ($student) {
    $id = $student->id;
    $nom = $student->nom;
    $image = $student->image_etudiant;
    $annee = $student->annee; // 'premiere', 'deuxieme', etc.
}
*/


// ------------------------------------------------------------------
// 3) BASE DE DONNÉES MÉDIAS SOCIAUX
// Colonnes : id, nom, image_media, lien, date_creation
// ------------------------------------------------------------------

function ctrltim_get_all_medias() {
    global $wpdb;
    $nom_table = ctrltim_table_name('ctrltim_medias_sociaux');
    // Exclure date_creation
    $colonnes = 'id, nom, image_media, lien';
    return $wpdb->get_results("SELECT {$colonnes} FROM {$nom_table} ORDER BY nom ASC");
}

function ctrltim_get_media_by_id($id) {
    global $wpdb;
    $nom_table = ctrltim_table_name('ctrltim_medias_sociaux');
    // Exclure date_creation
    $colonnes = 'id, nom, image_media, lien';
    return $wpdb->get_row($wpdb->prepare("SELECT {$colonnes} FROM {$nom_table} WHERE id = %d", $id));
}

// Exemple d'accès à un média (objet)
/*
$media = ctrltim_get_media_by_id(3);
if ($media) {
    $id = $media->id;
    $nom = $media->nom;
    $image = $media->image_media;
    $lien = $media->lien;
}
*/

?>
