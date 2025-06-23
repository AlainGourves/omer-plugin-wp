<?php
/**
 * Affiche la liste de tous les groupes de champs ACF et les champs qu'ils contiennent.
 * Nécessite que ACF soit actif.
 */
function display_acf_field_groups_and_fields()
{
    // Vérifier si ACF est actif
    if (! function_exists('acf_get_field_groups') || ! function_exists('acf_get_fields')) {
        echo '<p>ACF n\'est pas actif ou les fonctions nécessaires ne sont pas disponibles.</p>';
        return;
    }

    // Récupérer tous les groupes de champs
    $field_groups = acf_get_field_groups();

    if (empty($field_groups)) {
        echo '<p>Aucun groupe de champs ACF trouvé.</p>';
        return;
    }

?>
    <h1>Détails des Groupes de Champs ACF :</h1>
    <table class="wp_list_table table-view-list widefat fixed striped">
        <thead>
            <tr>
                <td class="manage-column check-column"></td>
                <th class="column-title column-primary">Nom</th>
                <th class="column-title column-primary">Champs</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($field_groups as $group) {
            ?>
                <tr>
                    <th class="check-column" scope="row">
                        <input type="radio" name="field_group" value="<?php echo esc_attr($group['key']); ?>" />
                    </th>
                    <td class="columun-title column-primary">
                        <strong>
                            <?php echo esc_html($group['title']); ?>
                        </strong>
                    </td>
                    <td>
                        <?php
                        // Récupérer les champs pour ce groupe de champs
                        // Note: acf_get_fields() prend la 'key' du groupe de champs en paramètre
                        $fields = acf_get_fields($group['key']);

                        if (empty($fields)) {
                            echo '<p>Aucun champ trouvé dans ce groupe.</p>';
                        } else {
                        ?>

                            <table class="wp_list_table table-view-list widefat fixed champs">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Filtre</th>
                                        <th>Tri</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($fields as $field) {
                                    ?>
                                        <tr class="field-info">
                                            <td>
                                                <strong><?php echo esc_html($field['label']); ?></strong>
                                            </td>
                                            <td>
                                                <i>
                                                    <?php
                                                    if ($field['name'] === 'titre' || $field['name'] === 'description' || $field['name'] === 'image') {
                                                        echo 'obligatoire';
                                                    } else {
                                                        echo esc_html($field['type']);
                                                    }
                                                    ?>
                                                </i>
                                            </td>
                                            <td>
                                                <?php
                                                if ($field['type'] === 'taxonomy') {
                                                ?>
                                                    <input type="checkbox" name="taxonomies" value="<?php echo esc_attr($field['name']); ?>" />
                                                <?php
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($field['type'] === 'number') {
                                                ?>
                                                    <input type="checkbox" name="tri" value="<?php echo esc_attr($field['name']); ?>" />
                                                <?php
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        if ($field['type'] === 'taxonomy') {
                                        ?>
                                            <tr class="show-field-settings">
                                                <td colspan="4">
                                                    <div>
                                                        <label for="taxonomy_<?php echo esc_attr($field['name']); ?>_label"><span>Etiquette <span class="mandatory">*</span></span>
                                                            <input type="text" id="taxonomy_<?php echo esc_attr($field['name']); ?>_label" placeholder="Filtrer par..." />
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <label for="taxonomy_<?php echo esc_attr($field['name']); ?>_default"><span>Valeur par défaut <span class="mandatory">*</span></span>
                                                            <input type="text" id="taxonomy_<?php echo esc_attr($field['name']); ?>_default" placeholder="Tous les..." />
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        if ($field['type'] === 'number') {
                                        ?>
                                            <tr class="show-field-settings">
                                                <td colspan="4">
                                                    <div>
                                                        <label for="sort_<?php echo esc_attr($field['name']); ?>_label"><span>Etiquette <span class="mandatory">*</span></span>
                                                            <input type="text" id="sort_<?php echo esc_attr($field['name']); ?>_label" placeholder="Trier par..." />
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <label for="sort_<?php echo esc_attr($field['name']); ?>_default"><span>Valeur par défaut <span class="mandatory">*</span></span>
                                                            <input type="text" id="sort_<?php echo esc_attr($field['name']); ?>_default" placeholder="Par défaut..." />
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <p><span class="mandatory">*</span> Champs obligatoire</p>
<?php
}




/**
 * Contenu de la page d'administration du plugin
 */
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
    <div>
        <label for="rest_api_base_url">URL de l'API REST:
            <input type="text" id="rest_api_base_url" readonly value="<?php echo esc_attr(get_rest_url(null, 'wp/v2/')); ?>" />
            <button id="copyRestApiBaseUrl" class="button button-secondary" type="button">Copier l'URL</button>
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

    <?php display_acf_field_groups_and_fields(); ?>

</div>
<?php

?>