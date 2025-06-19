<?php
// classes/AdminPage.php
namespace DemiSelPlugin;

/**
 * Gère la création de la page d'administration du plugin.
 */
class AdminPage {

    /**
     * Enregistre les hooks nécessaires pour la page d'administration.
     */
    public function register_hooks() {
        add_action( 'admin_menu', [ $this, 'add_admin_submenu_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'admin_init', [ $this, 'add_admin_js' ] );
        add_filter( 'plugin_action_links_' . DEMI_SEL_PLUGIN_BASENAME, [ $this, 'add_settings_link' ] ); // Ajout du filtre pour le lien "Settings"
    }

    /**
     * Ajoute un élément de sous-menu pour le plugin sous "Tools" dans le tableau de bord WordPress.
     */
    public function add_admin_submenu_page() {
        add_submenu_page(
            'tools.php',                                // Slug de la page parente
            __( 'Demi-sel Settings', 'demi-sel-plugin' ), // Titre de la page
            __( 'Demi-sel', 'demi-sel-plugin' ),         // Titre du sous-menu
            'manage_options',                           // Capacité requise pour accéder
            'demi-sel-plugin',                           // Slug du menu
            [ $this, 'render_admin_page' ],             // Fonction de rappel pour afficher le contenu
        );
    }

    /**
     * Ajoute un lien "Settings" sur la page des plugins.
     *
     * @param array $links Les liens d'action du plugin existants.
     * @return array Les liens d'action mis à jour.
     */
    public function add_settings_link( $links ) {
        // Crée l'URL de la page des réglages (qui est maintenant sous Outils)
        $settings_link = '<a href="' . esc_url( admin_url( 'tools.php?page=demi-sel-plugin' ) ) . '">' . __( 'Settings', 'demi-sel-plugin' ) . '</a>';
        // Ajoute le lien "Settings" au début du tableau des liens existants
        array_push( $links, $settings_link );
        return $links;
    }

    /**
     * Enregistre les réglages du plugin.
     */
    public function register_settings() {
        // Enregistrer la section de réglages
        add_settings_section(
            'demi_sel_plugin_main_section',
            __( 'Réglages du plugin', 'demi-sel-plugin' ),
            function() {
                echo '<p>' . __( 'Configure your Vue.js application settings here.', 'demi-sel-plugin' ) . '</p>';
            },
            'demi-sel-plugin' // Slug de la page
        );

        // Enregistrer le champ 'enabled'
        add_settings_field(
            'demi_sel_plugin_enabled',
            __( 'Enable Demi-sel', 'demi-sel-plugin' ),
            [ $this, 'callback_enabled_field' ],
            'demi-sel-plugin',
            'demi_sel_plugin_main_section'
        );

        // Enregistrer le champ 'message'
        add_settings_field(
            'demi_sel_plugin_message',
            __( 'Titre', 'demi-sel-plugin' ),
            [ $this, 'callback_message_field' ],
            'demi-sel-plugin',
            'demi_sel_plugin_main_section'
        );

        // Enregistrer les réglages dans la base de données
        register_setting(
            'demi-sel-plugin', // Nom du groupe de réglages
            'demi_sel_plugin_settings', // Nom de l'option stockée en DB
            [ $this, 'sanitize_settings' ] // Fonction de nettoyage/validation
        );
    }

    /**
     * Callback pour le champ 'enabled'.
     */
    public function callback_enabled_field() {
        $options = get_option( 'demi_sel_plugin_settings' );
        $enabled = isset( $options['enabled'] ) ? (bool) $options['enabled'] : false;
        ?>
        <label>
            <input type="checkbox" name="demi_sel_plugin_settings[enabled]" value="1" <?php checked( $enabled, true ); ?> />
            <?php _e( 'Check to enable the Demi-sel application on the frontend.', 'demi-sel-plugin' ); ?>
        </label>
        <?php
    }

    /**
     * Callback pour le champ 'message'.
     */
    public function callback_message_field() {
        $options = get_option( 'demi_sel_plugin_settings' );
        $message = isset( $options['message'] ) ? esc_attr( $options['message'] ) : '';
        ?>
        <input type="text" name="demi_sel_plugin_settings[message]" id="plugin-message" value="<?php echo $message; ?>" class="regular-text" />
        <p class="description"><?php _e( 'Titre pour l\'app Demi-sel.', 'demi-sel-plugin' ); ?></p>
        <?php
    }

    /**
     * Nettoie et valide les réglages avant de les sauvegarder.
     *
     * @param array $input Les réglages soumis.
     * @return array Les réglages nettoyés.
     */
    public function sanitize_settings( $input ) {
        $output = get_option( 'demi_sel_plugin_settings' ); // Récupère les réglages actuels
        if ( ! is_array( $output ) ) {
            $output = [];
        }

        // Nettoyage du champ 'enabled'
        $output['enabled'] = isset( $input['enabled'] ) ? (bool) $input['enabled'] : false;

        // Nettoyage du champ 'message'
        $output['message'] = isset( $input['message'] ) ? sanitize_text_field( $input['message'] ) : '';

        return $output;
    }

    /**
     * Ajoute du JS dans la page Admin du plugin
     */
    public function add_admin_js() {
        wp_enqueue_script(
            'demi-sel-plugin-admin-js',
            DEMI_SEL_PLUGIN_URL . 'dist/admin.js',
            [],
            DEMI_SEL_PLUGIN_VERSION,
            true
        );
    }

    /**
     * Rend le contenu HTML de la page d'administration.
     */
    public function render_admin_page() {
        require_once DEMI_SEL_PLUGIN_PATH . 'views/admin-page.php';
    }
}
