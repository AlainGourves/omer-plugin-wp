<?php

?>
<div class="wrap">
    <h1><?php esc_html_e('Demi-sel Plugin Settings', 'demi-sel-plugin'); ?></h1>
    <p>Nécessite WordPress 6.7 au minimum pour que les données puissent être transmises à l'app Vue.</p>

    <div>
        <label for="shortcode">Shortcode:
            <input type="text" id="shortcode" readonly value="<?php echo '['. DEMI_SEL_PLUGIN_SHORTCODE .']' ?>" />
        </label>
        <button id="copyShortcode" class="button button-secondary" type="button">Copier le shortcode</button>
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
</div>
<?php

?>