<?php
// classes/Activator.php
namespace DemiSelPlugin;

/**
 * Gère les actions à l'activation du plugin.
 */
class Activator {

    /**
     * Méthode appelée lors de l'activation du plugin.
     * Peut être utilisée pour créer des options, des tables de base de données, etc.
     */
    public static function activate() {
        // Exemple : Définir une option par défaut lors de l'activation
        if ( ! get_option( 'demi_sel_plugin_settings' ) ) {
            $default_settings = [
                'message' => 'Hello from Vue.js App!',
                'pagination' => '4',
            ];
            add_option( 'demi_sel_plugin_settings', $default_settings );
        }

        // Vider les règles de réécriture pour s'assurer que les permaliens fonctionnent correctement si le plugin en crée
        flush_rewrite_rules();
    }
}
