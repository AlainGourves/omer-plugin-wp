<?php
// classes/FrontendEnqueue.php
namespace Inc;

/**
 * Gère l'injection des scripts et des styles sur le frontend.
 */
class FrontendEnqueue {

    /**
     * Enregistre les hooks pour l'injection des ressources.
     */
    public function register_hooks() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

        // Enregistre le shortcode pour afficher l'application Vue.js
        add_shortcode( 'demi_sel', [ $this, 'render_demi_sel_shortcode' ] );
    }

    /**
     * Enqueue les scripts et styles de l'application Vue.js.
     */
    public function enqueue_assets() {
        // Récupère les réglages du plugin
        $options = get_option( 'demi_sel_plugin_settings' );
        $enabled = isset( $options['enabled'] ) ? (bool) $options['enabled'] : false;

        // N'injecte les assets que si le plugin est activé dans les réglages
        if ( ! $enabled ) {
            return;
        }

        // Enqueue le CSS de l'application Vue
        wp_enqueue_style(
            'demi-sel-plugin-style',
            DEMI_SEL_PLUGIN_URL . 'public/css/vue-app.css',
            [],
            DEMI_SEL_PLUGIN_VERSION,
            'all'
        );

        // Enqueue la bibliothèque Vue.js depuis un CDN (pour la simplicité de l'exemple)
        wp_enqueue_script(
            'vue-js-cdn',
            'https://unpkg.com/vue@3/dist/vue.global.js',
            [],
            '3.0.0', // Version de Vue
            true    // Charger dans le footer
        );

        // Enqueue le script de votre application Vue.js
        wp_enqueue_script(
            'demi-sel-plugin-script',
            DEMI_SEL_PLUGIN_URL . 'public/js/vue-app.js',
            [ 'vue-js-cdn' ], // Dépend de la librairie Vue.js
            DEMI_SEL_PLUGIN_VERSION,
            true    // Charger dans le footer
        );

        // Passer des données de WordPress à l'application Vue.js via wp_localize_script
        $data_for_vue = [
            'ajax_url' => admin_url( 'admin-ajax.php' ), // Exemple : URL AJAX si vous avez besoin de communiquer avec le backend
            'message'  => isset( $options['message'] ) ? $options['message'] : 'Hello from WordPress!',
            // Ajoutez d'autres données ici si nécessaire
        ];
        wp_localize_script( 'demi-sel-plugin-script', 'vueAppData', $data_for_vue );
    }

    /**
     * Callback pour le shortcode [demi_sel].
     * Retourne l'élément racine où l'application Vue.js sera montée.
     *
     * @param array $atts Attributs du shortcode.
     * @return string Le HTML de l'élément racine si le plugin est activé, sinon une chaîne vide.
     */
    public function render_demi_sel_shortcode( $atts ) {
        // Récupère les réglages du plugin
        $options = get_option( 'demi_sel_plugin_settings' );
        $enabled = isset( $options['enabled'] ) ? (bool) $options['enabled'] : false;

        // N'ajoute la racine que si le plugin est activé dans les réglages
        if ( ! $enabled ) {
            return ''; // Retourne une chaîne vide si le plugin n'est pas activé via les réglages
        }

        // Vous pouvez utiliser les attributs du shortcode ici si besoin
        // $atts = shortcode_atts( array(
        //     'id' => 'demi-sel-root',
        //     'class' => '',
        // ), $atts, 'demi_sel' );

        // Retourne la balise div où l'application Vue.js sera montée
        return '<div id="demi-sel-root"></div>';
    }
}
