<?php
$rest_api_base_url = get_rest_url(null, 'wp/v2/');
/**
 * Affiche la liste de tous les groupes de champs ACF enregistrés.
 * Nécessite que ACF soit actif.
 */
function display_acf_field_groups_list() {
    // Vérifier si ACF est actif
    if ( ! function_exists( 'acf_get_field_groups' ) ) {
        echo '<p>ACF n\'est pas actif ou la fonction acf_get_field_groups n\'est pas disponible.</p>';
        return;
    }

    // Récupérer tous les groupes de champs
    $field_groups = acf_get_field_groups();

    if ( empty( $field_groups ) ) {
        echo '<p>Aucun groupe de champs ACF trouvé.</p>';
        return;
    }

    echo '<h2>Groupes de Champs ACF :</h2>';
    echo '<ul>';
    foreach ( $field_groups as $group ) {
        echo '<li><strong>' . esc_html( $group['title'] ) . '</strong> (ID: ' . esc_html( $group['ID'] ) . ')</li>';
    }
    echo '</ul>';
}

/**
 * Affiche la liste de tous les groupes de champs ACF et les champs qu'ils contiennent.
 * Nécessite que ACF soit actif.
 */
function display_acf_field_groups_and_fields() {
    // Vérifier si ACF est actif
    if ( ! function_exists( 'acf_get_field_groups' ) || ! function_exists( 'acf_get_fields' ) ) {
        echo '<p>ACF n\'est pas actif ou les fonctions nécessaires ne sont pas disponibles.</p>';
        return;
    }

    // Récupérer tous les groupes de champs
    $field_groups = acf_get_field_groups();

    if ( empty( $field_groups ) ) {
        echo '<p>Aucun groupe de champs ACF trouvé.</p>';
        return;
    }

    echo '<h1>Détails des Groupes de Champs ACF :</h1>';

    foreach ( $field_groups as $group ) {
        echo '<div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">';
        echo '<h2>Groupe de Champs : <strong>' . esc_html( $group['title'] ) . '</strong> (ID: ' . esc_html( $group['ID'] ) . ', Clé: ' . esc_html( $group['key'] ) . ')</h2>';

        // Récupérer les champs pour ce groupe de champs
        // Note: acf_get_fields() prend la 'key' du groupe de champs en paramètre
        $fields = acf_get_fields( $group['key'] );

        if ( empty( $fields ) ) {
            echo '<p>Aucun champ trouvé dans ce groupe.</p>';
        } else {
            echo '<h3>Champs :</h3>';
            echo '<ul>';
            foreach ( $fields as $field ) {
                echo '<li>';
                echo '<strong>Nom :</strong> ' . esc_html( $field['label'] ) . ' ';
                echo '(Slug: <code>' . esc_html( $field['name'] ) . '</code>, ';
                echo 'Type: <code>' . esc_html( $field['type'] ) . '</code>, ';
                echo 'Clé: <code>' . esc_html( $field['key'] ) . '</code>)';
                echo '</li>';
            }
            echo '</ul>';
        }
        echo '</div>';
    }
}

/**
 * Fonction pour afficher une liste des Custom Post Types (CPT) enregistrés.
 */
function display_custom_post_types_list()
{
    // Arguments pour get_post_types() :
    // 'public' => true : n'affiche que les types de posts publiquement consultables (visibles dans l'admin et sur le frontend).
    // '_builtin' => false : exclut les types de posts intégrés à WordPress (post, page, attachment, revision, nav_menu_item, custom_css, customize_changeset, oembed_cache, user_request).
    // 'show_ui' => true : n'affiche que les types de posts qui ont une interface utilisateur dans l'administration.
    $args = array(
        'public'   => true,
        '_builtin' => false, // Exclut les types de posts par défaut de WordPress
        'show_ui'  => true,  // N'inclut que les post types affichant une interface utilisateur dans l'admin
    );

    // Récupère la liste des noms des post types qui correspondent aux arguments
    return get_post_types($args, 'objects'); // 'objects' retourne des objets WP_Post_Type pour plus de détails
}


$post_types = display_custom_post_types_list();
// var_dump($post_types);
?>
<div class="wrap">
    <h1><?php esc_html_e('Demi-sel Plugin Settings', 'demi-sel-plugin'); ?></h1>
    <p>Nécessite WordPress 6.7 au minimum pour que les données puissent être transmises à l'app Vue.</p>

    <div>
        <label for="shortcode">Shortcode:
            <input type="text" id="shortcode" readonly value="<?php echo '[' . DEMI_SEL_PLUGIN_SHORTCODE . ']' ?>" />
            <button id="copyShortcode" class="button button-secondary" type="button">Copier le shortcode</button>
        </label>
    </div>
    <form action="options.php" method="post">
        <?php
        // Affiche les champs cachés nécessaires pour les formulaires de réglages
        settings_fields('demi-sel-plugin');
        // Affiche les sections et les champs des réglages
        do_settings_sections('demi-sel-plugin');
        // Affiche le bouton de soumission
        submit_button();
        ?>
    </form>

    <h3 style="color:red;">Ce qui se trouve ci-dessous ne fonctionne pas (pour le moment) !</h3>

    <?php echo $rest_api_base_url; ?>
    <?php display_acf_field_groups_list(); ?>
    <?php display_acf_field_groups_and_fields(); ?>

    <?php
    if ($post_types) {
        echo '<h2>Liste des post types personnalisés :</h2>';
    ?>
        <table class="wp_list_table table-view-list widefat fixed striped">
            <thead>
                <tr>
                    <td class="manage-column check-column"></td>
                    <th class="column-title column-primary">Nom</th>
                    <th class="column-title column-primary">Taxonomies</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($post_types as $post_type) {
                    // $post_type->labels->singular_name contient le nom singulier lisible (ex: "Voiture")
                    // $post_type->name contient le slug du post type (ex: "voiture")
                    if (isset($post_type->taxonomies)) {
                        // Pour chercher à distinguer les post types ACF de ceux produits éventuellement par d'autres plugin (ex Elementor)
                ?>
                        <tr>
                            <th class="check-column" scope="row">
                                <input type="radio" name="post_types" value="<?php echo esc_attr($post_type->name); ?>" />
                            </th>
                            <td class="columun-title column-primary">
                                <strong>
                                    <?php echo esc_html($post_type->labels->singular_name); ?>
                                </strong>
                                <br />
                                <?php echo 'Slug: ' . esc_html($post_type->name) ?>
                            </td>
                            <td class="columun-title column-primary">
                                <table class="wp_list_table table-view-list widefat fixed striped">
                                    <thead>
                                        <tr>
                                            <td class="manage-column check-column"></td>
                                            <th>Nom</th>
                                            <th>Filtre</th>
                                            <th>Tri</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($post_type->taxonomies as $taxonomy) { ?>
                                            <tr>
                                                <th class="check-column" scope="row">
                                                    <input type="checkbox" name="taxonomies" value="<?php echo esc_attr($taxonomy); ?>" />
                                                </th>
                                                <td class="columun-title column-primary">
                                                    <?php echo esc_html($taxonomy); ?>
                                                </td>
                                                <td class="check-column">
                                                    <input type="checkbox" name="taxonomies" value="<?php echo esc_attr($taxonomy); ?>" />
                                                </td>
                                                <td class="check-column">
                                                    <input type="checkbox" name="taxonomies" value="<?php echo esc_attr($taxonomy); ?>" />
                                                </td>
                                            </tr>
                                        <?php }; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- <tr>
                            <td colspan="3">
                                <?php
                                echo '<pre>';
                                var_dump($post_type);
                                echo '</pre>';
                                ?>
                            </td>
                        </tr> -->
            <?php
                    }
                }
                echo '</tbody></table>';
            } else {
                echo '<p>Aucun type de publication personnalisé trouvé.</p>';
            }
            ?>
</div>
<?php

?>