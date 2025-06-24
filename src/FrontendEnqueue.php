<?php
// classes/FrontendEnqueue.php
namespace DemiSelPlugin;

/**
 * Gère l'injection des scripts et des styles sur le frontend.
 */
class FrontendEnqueue
{
    private $shortcode_name;
    // Propriété pour indiquer si le shortcode a été détecté
    private $shortcode_detected = false;
    // Handle du module JS de l'app
    private $module_handle = 'demi-sel-plugin-script';

    // Constructeur de la classe
    public function __construct()
    {
        $this->shortcode_name = DEMI_SEL_PLUGIN_SHORTCODE;
    }

    /**
     * Vérifie si le shortcode est présent dans le contenu du post/page actuel.
     * Cette méthode est appelée via le hook 'wp'.
     */
    public function check_shortcode_presence()
    {
        global $post;

        // S'assurer que nous sommes sur une page ou un article valide pour accéder à son contenu
        if (! is_a($post, 'WP_Post')) {
            return;
        }

        // Vérifie si le contenu de la page/post contient le shortcode spécifié.
        if (has_shortcode($post->post_content, $this->shortcode_name)) {
            $this->shortcode_detected = true;
        }
    }

    /**
     * Enregistre les hooks pour l'injection des ressources.
     */
    public function register_hooks()
    {

        // Enregistre le shortcode pour afficher l'application Vue.js
        add_shortcode($this->shortcode_name, [$this, 'render_demi_sel_shortcode']);

        // Hook pour vérifier la présence du shortcode avant le chargement des scripts
        // 'wp' est un hook qui se déclenche après que le post global est disponible et que les requêtes sont analysées.
        add_action('wp', [$this, 'check_shortcode_presence']);
        // Hook pour mettre en file d'attente les assets
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);

        // Filtre pour passer les données au script module de l'app
        add_filter( "script_module_data_{$this->module_handle}", array( $this, 'get_data_for_vue' ) );
    }

    /**
     * Enqueue les scripts et styles de l'application Vue.js.
     */
    public function enqueue_assets()
    {
        // Si le shortcode n'a pas été détecté, ne rien faire
        if (! $this->shortcode_detected) {
            return;
        }
        // Récupère les réglages du plugin
        $options = get_option('demi_sel_plugin_settings');

        // Enqueue le CSS de l'application Vue
        wp_enqueue_style(
            'demi-sel-plugin-style',
            DEMI_SEL_PLUGIN_URL . 'public/vue-app.css',
            [],
            DEMI_SEL_PLUGIN_VERSION,
            'all'
        );

        // Enqueue la bibliothèque Vue.js depuis un CDN (pour la simplicité de l'exemple)
        // Note: 'vue.global.prod.js' en production, 'vue.global.js' sinon
        wp_enqueue_script(
            'vue-js-cdn',
            'https://unpkg.com/vue@3/dist/vue.global.prod.js',
            [],
            '3.0.0', // Version de Vue
            true    // Charger dans le footer
        );

        // Enqueue le module JS de votre application Vue.js
        wp_enqueue_script_module(
            $this->module_handle,
            DEMI_SEL_PLUGIN_URL . 'public/vue-app.js',
            ['vue-js-cdn'], // Dépend de la librairie Vue.js
            DEMI_SEL_PLUGIN_VERSION
        );
    }

    /**
     * Filtre les données passées à l'application Vue.js via le hook script_module_data.
     *
     * @param array $data Les données initiales (un tableau vide par défaut).
     * @return array Les données à transmettre au module JS.
     */
    public function get_data_for_vue( $data ) {
        // Cette méthode sera appelée si le script module est en file d'attente.

        // Passe l'URL de base de l'API REST
        $data['rest_url'] = get_rest_url(null, 'wp/v2/');

        // Exemple : Récupère les réglages du plugin
        $plugin_options = get_option('demi_sel_plugin_settings', array());

        foreach ($plugin_options as $key => $value) {
            $data[$key] = $value;
        }

        return $data; // Il est crucial de retourner le tableau de données
    }

    /**
     * Callback pour le shortcode [demi_sel].
     * Retourne l'élément racine où l'application Vue.js sera montée.
     *
     * @param array $atts Attributs du shortcode.
     * @return string Le HTML de l'élément racine si le plugin est activé, sinon une chaîne vide.
     *
     * !!!! Les données seront insérées dans la page dans un <script type="application/json">
     * avec comme ID 'wp-script-module-data-demi-sel-plugin-script' !!!!
     */
    public function render_demi_sel_shortcode($atts)
    {
        // Récupère les réglages du plugin
        $options = get_option('demi_sel_plugin_settings');

        // Vous pouvez utiliser les attributs du shortcode ici si besoin
        // $atts = shortcode_atts( array(
        //     'id' => 'demi-sel-root',
        //     'class' => '',
        // ), $atts, 'demi_sel' );

        // Retourne la balise div où l'application Vue.js sera montée
        return '<div id="demi-sel-root"><div id="app"></div></div>';
    }
}
